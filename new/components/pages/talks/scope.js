import queryString from 'query-string';

import categories from '../../../data/categories.json';

class TalksScope {

    /**
     * Return the path for /talks resources.
     *
     * The options argument may have the following defined:
     *
     * - lng or i18n.language
     * - page
     * - searchText
     *
     * @param {object} options
     * @return {string} path
     */
    getPath(options) {
        options = options || {}
        let path = '/new';
        const lng = options.i18n ? options.i18n.language : options.lng;
        if (lng === 'th') {
            path += '/th';
        }
        path += '/talks';
        path += this.getMiddlePath();
        let params = {};
        if (options.page && options.page > 1) {
            params.p = options.page;
        }
        if (options.searchText) {
            params.q = options.searchText;
        }
        const qs = queryString.stringify(params);
        if (qs) {
            path += '?' + qs;
        }
        return path;
    }

    equals(scope) {
        return scope.name === this.name;
    }

    getFilters() {
        return {};
    }
}

export class LatestTalksScope extends TalksScope {

    constructor() {
        super();
        this.name = 'latest-talks';
    }

    getMiddlePath() {
        return '/latest';
    }
}

export class TalksByCategoryScope extends TalksScope {

    constructor(category) {
        super();
        this.name = 'category-talks';
        this.category = category;
    }

    equals(scope) {
        return super.equals(scope) && this.category.slug === scope.category.slug;
    }

    getFilters() {
        return {
            categorySlug: this.category.slug
        };
    }

    getMiddlePath() {
        return '/by-category/' + this.category.slug;
    }
}

export class AuthorsScope extends TalksScope {

    constructor(author) {
        super();
        this.name = 'authors';
    }

    getMiddlePath() {
        return '/by-teacher';
    }

}

export class TalksByAuthorScope extends TalksScope {

    constructor(authorId) {
        super();
        this.name = 'author-talks';
        this.authorId = authorId;
    }

    equals(scope) {
        return super.equals(scope) && this.authorId === scope.authorId;
    }

    getFilters() {
        return {
            authorId: this.authorId
        };
    }

    getMiddlePath() {
        // TODO should return with slug.
        let path = '/by-teacher/' + this.authorId;
        if (this.author) {
            path += '-' + this.author.slug;
        }
        return path;
    }
}

function getCategoryBySlug(slug) {
    for (let category of categories) {
        if (category.slug === slug) {
            return category;
        }
    }
    throw new Error('Category not found ' + slug);
}

function parseId(id) {
    const matches = ('' + id).match(/^([0-9]+)(-.*)?$/);
    if (matches) {
        id = matches[1];
    }
    return parseInt(id);
}

export default function getTalksScope(scopeName, params) {
    switch (scopeName) {
        case 'latest-talks':
            return new LatestTalksScope();
        case 'category-talks':
            const category = getCategoryBySlug(params.categorySlug);
            return new TalksByCategoryScope(category);
        case 'authors':
            return new AuthorsScope();
        case 'author-talks':
            return new TalksByAuthorScope(parseId(params.authorId));
        default:
            throw new Error('Not implemented ' + scopeName);
    }
}
