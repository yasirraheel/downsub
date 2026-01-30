require('dotenv').config();
const express = require('express');
const baileys = require('@whiskeysockets/baileys');
const makeWASocket = baileys.default;
const { useMultiFileAuthState, DisconnectReason } = baileys;
const pino = require('pino');
const cors = require('cors');
const qrcode = require('qrcode');
const fs = require('fs');
const path = require('path');
const axios = require('axios');

const app = express();
app.use(cors());
app.use(express.json());

const PORT = process.env.PORT || 3000;
const LARAVEL_URL = process.env.LARAVEL_URL || 'https://wa-server.shahabtech.com';
const BASE_SESSION_DIR = process.env.SESSION_DIR || path.join(__dirname, '../storage/app/wa_sessions');

// Ensure base session directory exists
if (!fs.existsSync(BASE_SESSION_DIR)) {
    fs.mkdirSync(BASE_SESSION_DIR, { recursive: true });
}

// Store active sessions: Map<sessionId, { sock, qr, status, phoneNumber }>
const sessions = new Map();

async function initSession(sessionId) {
    if (sessions.has(sessionId)) {
        const session = sessions.get(sessionId);
        if (session.status !== 'disconnected') {
            return session;
        }
        // Ensure old socket is closed
        if (session.sock) {
             try { session.sock.end(undefined); } catch (e) {}
        }
        sessions.delete(sessionId);
    }

    const sessionPath = path.join(BASE_SESSION_DIR, sessionId);

    // Ensure session sub-directory exists
    if (!fs.existsSync(sessionPath)) {
        fs.mkdirSync(sessionPath, { recursive: true });
    }

    try {
        const { state, saveCreds } = await useMultiFileAuthState(sessionPath);

        const sock = makeWASocket({
            auth: state,
            logger: pino({ level: 'silent' }),
            browser: ["WaSender", "Chrome", "1.0.0"],
            syncFullHistory: false,
            connectTimeoutMs: 60000,
            printQRInTerminal: false
        });

        const sessionData = {
            sock,
            qr: null,
            status: 'disconnected',
            phoneNumber: null
        };

        sessions.set(sessionId, sessionData);

        sock.ev.on('creds.update', saveCreds);

        sock.ev.on('connection.update', async (update) => {
            const { connection, lastDisconnect, qr } = update;

            if (qr) {
                try {
                    sessionData.qr = await qrcode.toDataURL(qr);
                    sessionData.status = 'scan_qr';
                    console.log(`[${sessionId}] QR Code received`);
                } catch (err) {
                    console.error(`[${sessionId}] QR generation error:`, err);
                }
            }

            if (connection === 'close') {
                const shouldReconnect = (lastDisconnect.error)?.output?.statusCode !== DisconnectReason.loggedOut;

                console.log(`[${sessionId}] Connection closed, reconnecting: ${shouldReconnect}`);

                if (shouldReconnect) {
                    sessionData.status = 'disconnected';
                    initSession(sessionId); // Reconnect immediately
                } else {
                    console.log(`[${sessionId}] Logged out.`);
                    sessionData.status = 'disconnected';
                    sessionData.qr = null;

                    // Close socket to release file locks
                    try { sock.end(undefined); } catch (e) {}

                    // Cleanup session file to allow fresh start
                    sessions.delete(sessionId);
                    try {
                        // Small delay to ensure file handles are released
                        setTimeout(() => {
                            try {
                                fs.rmSync(sessionPath, { recursive: true, force: true });
                                console.log(`[${sessionId}] Session data cleared.`);
                            } catch (e) {
                                console.error(`[${sessionId}] Failed to clear session data:`, e);
                            }
                        }, 1000);
                    } catch (e) {}
                }
            } else if (connection === 'open') {
                console.log(`[${sessionId}] Connection opened`);
                sessionData.status = 'connected';
                sessionData.qr = null;
                sessionData.phoneNumber = sock.user?.id?.split(':')[0];
            }
        });

        sock.ev.on('messages.upsert', async m => {
            if(m.type === 'notify'){
                for(const msg of m.messages) {
                    if(!msg.key.fromMe) {
                        await handleAutoReply(sock, msg, sessionId);
                    }
                }
            }
        });

        return sessionData;
    } catch (error) {
        console.error(`[${sessionId}] Init error:`, error);
        sessions.delete(sessionId);
    }
}

async function handleAutoReply(sock, msg, sessionId) {
    try {
        if (!msg.message) return;

        const remoteJid = msg.key.remoteJid;
        const text = msg.message.conversation ||
                     msg.message.extendedTextMessage?.text ||
                     msg.message.imageMessage?.caption ||
                     msg.message.videoMessage?.caption;

        if(!text || remoteJid.includes('@g.us') || remoteJid === 'status@broadcast') return;

        const number = remoteJid.split('@')[0].split(':')[0];
        console.log(`[${sessionId}] Received message from ${number}: ${text}`);

        // Webhook to Laravel
        try {
            await axios.post(`${LARAVEL_URL}/api/whatsapp/webhook`, {
                session_id: sessionId, // Pass session ID to Laravel
                from: number,
                message: text,
                timestamp: msg.messageTimestamp
            });
        } catch (error) {
            console.error(`[${sessionId}] Webhook error:`, error.message);
        }

        // Auto-reply logic can be handled here or by Laravel via webhook response
        // For now, let's keep the simple "hello" check here as backup,
        // but ideally Laravel should handle logic.
        if (text.toLowerCase() === 'hello') {
            await sock.sendPresenceUpdate('composing', remoteJid);
            await new Promise(r => setTimeout(r, 1000));
            await sock.sendMessage(remoteJid, { text: 'Hello! This is an auto-reply.' });
        }

    } catch (error) {
        console.error(`[${sessionId}] Auto-reply error:`, error);
    }
}

// API Endpoints

// Initialize a session
app.post('/sessions/init', async (req, res) => {
    const { sessionId } = req.body;
    if (!sessionId) return res.status(400).json({ error: 'sessionId required' });

    await initSession(sessionId);
    res.json({ success: true, message: 'Session initialization started' });
});

// Get session status and QR
app.get('/sessions/:sessionId/status', (req, res) => {
    const { sessionId } = req.params;
    const session = sessions.get(sessionId);

    if (!session) {
        return res.status(404).json({ status: 'disconnected', qr: null });
    }

    if (session.status === 'initializing') {
        return res.json({ status: 'initializing', qr: null });
    }

    res.json({
        status: session.status,
        qr: session.qr,
        phoneNumber: session.phoneNumber
    });
});

// Delete a session
app.post('/sessions/delete', async (req, res) => {
    const { sessionId } = req.body;
    const session = sessions.get(sessionId);

    if (session) {
        try {
            await session.sock.logout();
        } catch (e) {}
        session.sock.end(undefined);
        sessions.delete(sessionId);

        // Remove session files
        const sessionPath = path.join(BASE_SESSION_DIR, sessionId);
        fs.rmSync(sessionPath, { recursive: true, force: true });
    }

    res.json({ success: true });
});

// Send Message
app.post('/send-message', async (req, res) => {
    const { sessionId, number, message } = req.body;

    // Fallback for backward compatibility if no sessionId provided (use first active session)
    let session = sessionId ? sessions.get(sessionId) : sessions.values().next().value;

    if (!session || session.status !== 'connected') {
        return res.status(400).json({ error: 'WhatsApp session not connected' });
    }

    try {
        let id = number;
        if (!number.includes('@')) {
            id = `${number}@s.whatsapp.net`;
        }

        console.log(`[${sessionId || 'default'}] Sending to: ${id}, Content: ${message}`);

        await session.sock.sendPresenceUpdate('composing', id);
        await session.sock.sendMessage(id, { text: message });

        console.log(`[${sessionId || 'default'}] Success: ${id}`);
        res.json({ success: true });
    } catch (error) {
        console.error('Send message error:', error);
        res.status(500).json({ error: 'Failed to send message' });
    }
});

// Restore previous sessions on startup
fs.readdir(BASE_SESSION_DIR, (err, files) => {
    if (err) return;
    files.forEach(file => {
        // Check if it's a directory (session)
        const fullPath = path.join(BASE_SESSION_DIR, file);
        if (fs.statSync(fullPath).isDirectory()) {
            console.log(`Restoring session: ${file}`);
            initSession(file);
        }
    });
});

app.listen(PORT, () => {
    console.log(`WhatsApp Server running on port ${PORT}`);
});
