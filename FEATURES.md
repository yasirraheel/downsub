
---

## 8. Installing yt-dlp on Shared Hosting (No Sudo)

If you are on shared hosting and cannot use `sudo` or `apt-get`, follow these steps to install `yt-dlp` manually in your user directory.

1.  **Connect via SSH** to your server.
2.  **Create a bin directory** (if it doesn't exist):
    ```bash
    mkdir -p ~/bin
    ```
3.  **Download the latest yt-dlp binary**:
    ```bash
    curl -L https://github.com/yt-dlp/yt-dlp/releases/latest/download/yt-dlp -o ~/bin/yt-dlp
    ```
4.  **Make it executable**:
    ```bash
    chmod +x ~/bin/yt-dlp
    ```
5.  **Test the installation**:
    ```bash
    ~/bin/yt-dlp --version
    ```
6.  **Configure Laravel**:
    Since `~/bin` might not be in the web server's environment PATH, you should explicitly tell the application where to find it.
    
    Open your `.env` file and add:
    ```env
    YT_DLP_PATH=/home/yourusername/bin/yt-dlp
    ```
    *(Replace `/home/yourusername` with your actual home directory path. You can find it by running `pwd` in SSH).*
