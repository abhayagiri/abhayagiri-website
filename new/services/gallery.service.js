import axios from 'axios';

class GalleryService {

    static async getGalleries() {
        let response = await axios.get('https://picasaweb.google.com/data/feed/api/user/110976577577357155764?imgmax=320&alt=json');
        let galleries = response.data.feed.entry;
        galleries = galleries.map(gallery => this.parseGallery(gallery));
        return galleries;
    }


    static parseGallery(gallery) {
        let media = gallery.media$group;
        console.log(gallery);
        return {
            title: media.media$title.$t,
            thumbnail: media.media$thumbnail[0].url,
            description: media.media$description.$t,
            id: gallery.gphoto$id.$t
        }
    }
}

export default GalleryService;
