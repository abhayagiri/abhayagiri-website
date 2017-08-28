import axios from 'axios';

class TalkService {

    static async getTalk(id) {

        let result = await axios.get('/api/talks/' + id),
            talk = result.data;

        if (!talk) {
            throw new Error('Talk ' + id + ' not found.');
        }

        return talk;
    }

    static async getTalks(filters) {

        let result = await axios.get('/api/talks', { params: filters }),
            talks = result.data;

        if (!talks) {
            throw new Error('Talks with filter ' + JSON.stringify(filters) + ' not found.');
        }

        return talks;
    }
}

export default TalkService;
