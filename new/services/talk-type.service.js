import axios from 'axios';

class TalkTypeService {

    static async getTalkType(id) {
        const
            result = await axios.get(`/api/talk-types/${id}`),
            talkType = result.data;
        if (talkType) {
            return talkType;
        } else {
            throw new Error(`Talk Type ${id} not found`);
        }
    }

    static async getTalkTypes() {
        const
            result = await axios.get('/api/talk-types'),
            talkTypes = result.data;
        if (talkTypes) {
            return talkTypes;
        } else {
            throw new Error('Talk Types not found');
        }
    }
}

export default TalkTypeService;