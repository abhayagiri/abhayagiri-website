# Website ⇆ YouTube Channel Synchronization

## Definitions

- **website**:     Abhayagiri website
- **talk**:        Abhayagiri website talk
- **aw-playlist**: Abhayagiri website playlist
- **channel**:     YouTube channel for Abhayagiri
- **video**:       YouTube channel video
- **yt-playlist**: YouTube channel playlist
- **no-sync**:     Do not synchronize this item

## Processes

### A. talk ← video

For each **video** in the **channel**, that's public and not **no-sync**, check
for an associated **talk** with matching `youtube_id`. If there's none:

1. Create a **talk** with the data from the **video**.
2. Queue an **video** to **audio** conversion.

### B. talk → video

For each **talk** with `youtube_id` set, find the associated **video** and
apply the following rules:

1. For each field set on the **talk**, but not on the **video**, set it on the
   **video**.
2. For each field set on the **video**, but not on the **talk**, set it on the
   **talk**.
3. For each field set on both, and different, use the **talk** as
   authoritative.
4. Set **video** **tags** from **subjects** associated with **talk**.

### C. aw-playlist ← yt-playlist

For each **yt-playlist** in the **channel**, that's public and not **no-sync**,
check for an associated **aw-playlist** with matching `youtube_id`. If there's
none:

1. Create an **aw-playlist** with data from **yt-playlist**.

### D. aw-playlist → yt-playlist

For each **aw-playlist** with `youtube_id` set, find the associated
`yt-playlist` and apply the following rules:

1. For each field set on the **aw-playlist**, but not on the **yt-playlist**,
   set it on the **yt-playlist**.
2. For each field set on the **yt-playlist**, but not on the **aw-playlist**,
   set it on the **aw-playlist**.
3. For each field set on both, and different, use the **aw-playlist** as
   authoritative.
4. Set **playlist** / **video** membership from **aw-playlist** / **talk**
   membership.

## Field Synchronization

### A. talk ⇆ video

- `talk->title_en` | `talk.author.title_en` ⇆ `video->snippet->title`
- `talk->title_th` ⇆ `video->snippet->localizations->th->title`
- `talk->description_en` `(no-sync)?` ⇆ `video->snippet->description`
- `talk->description_th` ⇆ `video->snippet->localizations->th->description`
- `talk->language->code` `video->snippet->defaultAudioLanguage`
    - `en_th` on the **talk** side should map to `th` on the **video** side
- `talk->recorded_on` ⇆ `video->recordingDetails->recordingDate`
- `talk->subjects` ⇆ `video->snippet->tags`

Always set:

- `video->status->license` = `youtube`
- `video->status->embeddable` `=` `true`
- `video->status->publicStatsViewable` `=` `false`

### B. aw-playlist ⇆ yt-playlist

- `aw-playlist->title_en` | `talk.author.title_en` ⇆ `video->snippet->title`
- `aw-playlist->title_th` ⇆ `video->snippet->localizations->th->title`
- `aw-playlist->description_en` `(no-sync)?` ⇆ `video->snippet->description`
- `aw-playlist->description_th` ⇆ `video->snippet->localizations->th->description`
- `talk->recorded_on` ⇆ `video->recordingDetails->recordingDate`
- `aw-playlist->talks` ⇆ `yt-playlist->playlistItems`

## no-sync

**no-sync** for **videos** and **yt-playlists** are indicated by the phrase
`no-sync` on the last line of the description field.

## References

- https://developers.google.com/youtube/v3/guides/auth/server-side-web-apps
- https://developers.google.com/youtube/v3/guides/implementation/playlists
- https://developers.google.com/youtube/v3/guides/implementation/videos
- https://developers.google.com/youtube/v3/docs/playlistItems
- https://developers.google.com/youtube/v3/docs/videos/list
