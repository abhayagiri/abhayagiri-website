import axios from 'axios';

class AuthorService {

    static async getAuthor(id) {
        const
            result = await axios.get(`/api/authors/${id}`),
            author = result.data;
        if (author) {
            return author;
        } else {
            throw new Error(`Author ${author} not found`);
        }
    }

    static async getAuthors() {
        const
            result = await axios.get('/api/authors'),
            authors = result.data;
        if (authors) {
            return authors;
        } else {
            throw new Error('Authors not found');
        }
    }
}

export default AuthorService;
