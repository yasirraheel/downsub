# WaSender Deployment Guide

## Prerequisites
- PHP 8.1 or higher
- Node.js 16 or higher
- MySQL Database

## Installation Steps

1. **Upload Files:** Upload the entire project to your shared hosting (e.g., `public_html` or a subdirectory).
2. **Database:** Create a MySQL database and update `.env` file in the root directory with credentials.
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_db_name
   DB_USERNAME=your_db_user
   DB_PASSWORD=your_db_password
   ```
3. **Install Dependencies:**
   - Root directory: Run `composer install`
   - `wa-server` directory: Run `npm install`

4. **Migrations:** Run `php artisan migrate` to set up the database tables.

5. **Start Node Server:**
   - Navigate to `wa-server` directory.
   - Run `node index.js`.
   - For background execution, use `nohup node index.js > output.log 2>&1 &` or use the "Node.js App" feature in cPanel.

6. **Start Laravel Server (if testing locally):**
   - Run `php artisan serve`.

7. **Cron Job:**
   - Set up a cron job to run the campaign processor every minute.
   - Command: `cd /path/to/project && php artisan campaign:run >> /dev/null 2>&1`

## Usage
1. Open the web interface.
2. Scan the QR code to login to WhatsApp.
3. Create Campaigns or Auto Replies.
4. Ensure the Node.js server is running in the background.

## Notes
- The Node.js server runs on port 3000 by default. Ensure this port is not blocked or change it in `wa-server/.env` and `app/Http/Controllers/WhatsAppController.php`.
- Session data is stored in `storage/app/wa_sessions`. Ensure this directory is writable.
