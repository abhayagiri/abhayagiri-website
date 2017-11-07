import axios from 'axios';

class GalleryService {

    static async getAlbums(filters) {
        let response = await axios.get('/api/albums', { params: filters });
        let albums = response.data;
        return albums;
    }

    static async getAlbum(albumId) {
        console.log(albumId);
        let response = await axios.get('/api/albums/' + albumId);
        let album = response.data;
        return album;
    }
}

export default GalleryService;
