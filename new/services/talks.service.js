import axios from 'axios';

const api = '/api/talks';

class TalksService {
    static async getTalk(filters) {

        let url = api,
            result = await axios.get(url, { params: filters }),
            talk = result.data;

        if (!talks) {
            throw new Error('Talks not available');
        }

        return talk;
    }

    static async getTalks(filters) {
        let url = api,
            result = await axios.get(url, { params: filters }),
            talks = result.data;

        if (!talks) {
            throw new Error('Talks not available');
        }

        return talks;
    }
}

export default TalksService;