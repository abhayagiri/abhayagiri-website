import axios from 'axios';

class ContactService {
    static async send (data, callback) {
        try {
            let response = await axios.post('/api/contact', data);
            return { success: true, ...response.data };
        } catch (error) {
            return { success: false, ...error.response.data };
        }
    }
}

export default ContactService;
