import axios from 'axios';

import pagesData from '../data/pages.json';

const api = '/api/subpages';

class Subpage {

    constructor(obj) {
        if (obj) {
            Object.assign(this, obj);
        }
    }

}

class Page {

    constructor(obj) {
        if (obj) {
            Object.assign(this, obj);
        }
    }

    async getSubpages() {
        let subpages;
        if (this.subpages) {
            subpages = this.subpages;
        } else {
            let url = api + '/' + this.slug,
                result = await axios.get(url);
            subpages = result.data.map((subData) => {
                return new Subpage(subData);
            });
            this.subpages = subpages;
        }
        if (subpages) {
            return subpages;
        } else {
            throw new Error('Subpages not found');
        }
    }
}

let _pages = null;

class PageService {

    static getPages() {
        if (!_pages) {
            _pages = pagesData.map((pageData) => {
                return new Page(pageData);
            });
        }
        return _pages;
    }

    static getPage(slug) {
        if (slug.startsWith('new/')) {
            slug = slug.substr(4);
        }
        let page = null;
        for (let testPage of PageService.getPages()) {
            let testSlug = testPage.slug;
            if (testSlug.startsWith('new/')) {
                testSlug = testSlug.substr(4);
            }
            if (testSlug === slug) {
                page = testPage;
                break;
            }
        }
        if (page) {
            return page;
        } else {
            return PageService.getPage('home');
            // throw new Error('Page not found: ' + slug);
        }
    }

}

export default PageService;
