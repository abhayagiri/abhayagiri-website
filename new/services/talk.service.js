import axios from 'axios';

import ErrorService from 'services/error.service';

class TalkService {

    static async getTalk(id) {
        try {
            return await axios.get(`/api/talks/${id}`).data;
        } catch (e) {
            ErrorService.handle(e, `Talk Type ${id} not found`);
        }
    }

    static async getTalks(filters) {
        const
            result = await axios.get('/api/talks', { params: filters }),
            talks = result.data;
        if (talks) {
            return talks;
        } else {
            throw new Error(`Talks with filter ${JSON.stringify(filters)} not found`);
        }
    }

    static async getTalksLatest() {
        const
            result = await axios.get('/api/talks/latest'),
            talks = result.data;
        if (talks) {
            return talks;
        } else {
            throw new Error(`Talks latest not found`);
        }
    }
}

export default TalkService;
