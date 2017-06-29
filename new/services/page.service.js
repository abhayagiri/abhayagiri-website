import axios from 'axios';

const api = '/api/pages';

class PageService {
    static async getPage(pageName){
        let url = api + '/' + pageName,
            result = axios.get(url),
            page = result.data;

        if(!page){
            throw new Error('Page not found');
        }

        return page;
    }
}

export default PageService;