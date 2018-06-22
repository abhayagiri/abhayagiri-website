import axios from 'axios';

class ContactOptionsService {

    static async getContactPreambles() {
        const
            result = await axios.get('/api/contact-preambles'),
            preambles = result.data;
        if (preambles) {
            return preambles;
        } else {
            throw new Error(`Contact Preamble not found`);
        }
    }

    static async getContactOptions() {
        const
            result = await axios.get('/api/contact-options'),
            options = result.data;
        if (options) {
            return options;
        } else {
            throw new Error(`Contact options not found`);
        }
    }

    static async getContactOption(slug) {
        const
            result = await axios.get(`/api/contact-options/${slug}`),
            option = result.data;
        if (option) {
            return option;
        } else {
            throw new Error(`Contact option with with ${slug} not found`);
        }
    }

}

export default ContactOptionsService;
