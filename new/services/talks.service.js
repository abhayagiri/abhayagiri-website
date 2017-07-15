import axios from 'axios';

const api = '/api/talks';

class TalksService {
    static async getTalks(filters){
        console.log(filters);
        let url = api,
            result = await axios.get(url, {params:filters}),
            talks = result.data;

        if(!talks){
            throw new Error('Talks not available');
        }

        return talks;
    }
}

export default TalksService;