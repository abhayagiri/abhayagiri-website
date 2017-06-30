import axios from 'axios';

const api = '/api/pages';

class PageService {
    static async getPage(pageName){
        let url = api + '/' + pageName,
            result = await axios.get(url),
            page = result.data;
            console.log(result);
        if(!page){
            throw new Error('Page not found');
        }

        return page;
    }
}

export default PageService;