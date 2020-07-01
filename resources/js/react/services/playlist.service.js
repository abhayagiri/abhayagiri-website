import axios from 'axios';

class PlaylistService {

    static async getPlaylist(id) {
        const
            result = await axios.get(`/api/playlists/${id}`),
            playlist = result.data;
        if (playlist) {
            return playlist;
        } else {
            throw new Error(`Playlist ${id} not found`);
        }
    }

    static async getPlaylists() {
        const
            result = await axios.get('/api/playlists'),
            playlists = result.data;
        if (playlists) {
            return playlists;
        } else {
            throw new Error(`Playlists not found`);
        }
    }

    static async getPlaylistGroup(id) {
        const
            result = await axios.get(`/api/playlist-groups/${id}`),
            playlist = result.data;
        if (playlist) {
            return playlist;
        } else {
            throw new Error(`Playlist ${id} not found`);
        }
    }

    static async getPlaylistGroups() {
        const
            result = await axios.get('/api/playlist-groups'),
            playlists = result.data;
        if (playlists) {
            return playlists;
        } else {
            throw new Error(`Playlists not found`);
        }
    }
}

export default PlaylistService;
