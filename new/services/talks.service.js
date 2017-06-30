import axios from 'axios';

const api = '/api/talks';

class TalksService {
    static async getTalks(){
        let url = api,
            result = await axios.get(url),
            talks = result.data;

        if(!talks){
            throw new Error('Talks not available');
        }

        return talks;
    }
}

export default TalksService;