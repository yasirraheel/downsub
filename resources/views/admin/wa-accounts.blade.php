@extends('layouts.admin')

@section('title', 'WhatsApp Accounts')

@section('content')
<div class="d-flex justify-between align-center" style="margin-bottom: 2rem;">
    <div>
        <h3 class="text-muted">Manage your connected WhatsApp sessions</h3>
    </div>
    <form action="{{ route('admin.wa-accounts.store') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus" style="margin-right: 0.5rem;"></i> Connect New
        </button>
    </form>
</div>

@if(session('success'))
    <div style="background-color: rgba(0, 200, 83, 0.1); border: 1px solid var(--success); color: var(--success); padding: 1rem; border-radius: var(--radius); margin-bottom: 1.5rem;">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Session ID</th>
                    <th>Phone Number</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($accounts as $account)
                <tr>
                    <td>{{ $account->name }}</td>
                    <td><span style="font-family: monospace; background: var(--bg); padding: 2px 5px; border-radius: 4px;">{{ $account->session_id }}</span></td>
                    <td>{{ $account->phone_number ?? '-' }}</td>
                    <td>
                        @if($account->status == 'connected')
                            <span class="text-success">Connected</span>
                        @elseif($account->status == 'scan_qr')
                            <span class="text-warning">Scan QR</span>
                        @else
                            <span class="text-danger">Disconnected</span>
                        @endif
                    </td>
                    <td>{{ $account->created_at->diffForHumans() }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            @if($account->status == 'connected')
                                <form action="{{ route('admin.wa-accounts.destroy', $account->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to disconnect?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Disconnect</button>
                                </form>
                            @else
                                <button onclick="showQrModal('{{ $account->session_id }}')" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Scan QR</button>
                                <form action="{{ route('admin.wa-accounts.destroy', $account->id) }}" method="POST" onsubmit="return confirm('Delete this account?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;"><i class="fas fa-trash"></i></button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 2rem;">
                        <span class="text-muted">No accounts connected. Click "Connect New" to start.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- QR Modal -->
<div id="qrModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: var(--surface); padding: 2rem; border-radius: var(--radius); text-align: center; max-width: 400px; width: 90%; position: relative;">
        <button onclick="closeQrModal()" style="position: absolute; top: 10px; right: 10px; background: none; border: none; color: var(--text); font-size: 1.5rem; cursor: pointer;">&times;</button>
        <h3>Scan QR Code</h3>
        <p class="text-muted" style="margin-bottom: 1.5rem;">Open WhatsApp > Linked Devices > Link a Device</p>
        <div id="qrContainer" style="background: white; padding: 1rem; display: inline-block; border-radius: 8px;">
            <img id="qrImage" src="" alt="QR Code" style="width: 250px; height: 250px; display: none;">
            <div id="qrLoading" style="width: 250px; height: 250px; display: flex; align-items: center; justify-content: center; color: black;">
                Loading QR...
            </div>
        </div>
        <p id="qrStatus" class="text-warning" style="margin-top: 1rem;">Waiting for code...</p>
    </div>
</div>

<script>
    let currentSessionId = null;
    let qrPollInterval = null;

    function showQrModal(sessionId) {
        currentSessionId = sessionId;
        document.getElementById('qrModal').style.display = 'flex';
        pollQrCode();
        qrPollInterval = setInterval(pollQrCode, 3000);
    }

    function closeQrModal() {
        document.getElementById('qrModal').style.display = 'none';
        if (qrPollInterval) clearInterval(qrPollInterval);
        currentSessionId = null;
    }

    async function pollQrCode() {
        if (!currentSessionId) return;

        try {
            const response = await fetch(`/admin/api/qr/${currentSessionId}`);
            const data = await response.json();

            if (data.error) {
                document.getElementById('qrStatus').innerText = data.error;
                document.getElementById('qrStatus').className = 'text-danger';
                return;
            }

            if (data.message) {
                 document.getElementById('qrStatus').innerText = data.message;
            }

            if (data.qr) {
                document.getElementById('qrImage').src = data.qr;
                document.getElementById('qrImage').style.display = 'block';
                document.getElementById('qrLoading').style.display = 'none';
                document.getElementById('qrStatus').innerText = 'Scan this code with WhatsApp';
                document.getElementById('qrStatus').className = 'text-success';
            } else if (data.status === 'initializing') {
                document.getElementById('qrStatus').innerText = 'Initializing session...';
                document.getElementById('qrStatus').className = 'text-warning';
            } else if (data.status === 'disconnected') {
                 document.getElementById('qrStatus').innerText = 'Waiting for QR code...';
                 document.getElementById('qrStatus').className = 'text-warning';
            }

            if (data.status === 'connected') {
                document.getElementById('qrStatus').innerText = 'Connected! Reloading...';
                document.getElementById('qrStatus').className = 'text-success';
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }
        } catch (error) {
            console.error('Error fetching QR:', error);
            document.getElementById('qrStatus').innerText = 'Connection error';
            document.getElementById('qrStatus').className = 'text-danger';
        }
    }
</script>
@endsection
