
---

## 7. Channel Downloader Module

*   **Route:** `/admin/channel-downloader`
*   **Dependency:** Requires `yt-dlp` installed on the server and accessible in system PATH.
*   **Architecture:**
    *   **Jobs:** `FetchChannelVideosJob` (lists videos) -> `FetchVideoDetailsJob` (fetches metadata & subtitles).
    *   **Storage:** `storage/app/temp/videos` used for temporary subtitle processing.
    *   **Features:** Fetches Title, Thumbnail, Tags, Subtitles (TXT/VTT), Published Date.
