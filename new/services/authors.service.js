import axios from 'axios';

class AuthorsService {

    static async getAuthor(id) {

        let result = await axios.get('/api/authors/' + id),
            author = result.data;

        if (!author) {
            throw new Error('Author ' + id + ' not found.');
        }

        return author;
    }

    static async getAuthors() {

        let result = await axios.get('/api/authors'),
            authors = result.data;

        if (!authors) {
            throw new Error('Authors not found.');
        }

        return authors;
    }
}

export default AuthorsService;
