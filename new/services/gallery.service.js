import axios from 'axios';

class GalleryService {

    static async getAlbums(filters) {
        let response = await axios.get('/api/albums', { params: filters });
        let albums = response.data;
        return albums;
    }
}

export default GalleryService;
