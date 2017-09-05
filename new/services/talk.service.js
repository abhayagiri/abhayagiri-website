import axios from 'axios';

class TalkService {

    static async getTalk(id) {
        const
            result = await axios.get(`/api/talks/${id}`),
            talk = result.data;
        if (talk) {
            return talk;
        } else {
            throw new Error(`Talk Type ${id} not found`);
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
}

export default TalkService;
