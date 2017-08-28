import categories from '../../../data/categories.json';

export function getCategory(slug) {
    for (let category of categories) {
        if (category.slug === slug) {
            return category;
        }
    }
    throw new Error('Category not found ' + slug);
}

/**
 * Returns a location object for a /talks resource.
 *
 * The optional options argument may have the following defined:
 *
 * - lng or i18n.language
 * - page
 * - searchText
 *
 * @param {string} basePath
 * @param {object} options
 * @return {object} location
 */
export function talksPath(basePath, options) {
    options = options || {}
    const
        lng = options.i18n ? options.i18n.language : options.lng,
        lngPrefix = (lng === 'th') ? '/th' : '',
        pathname = `/new${lngPrefix}/talks/${basePath}`;
    let query = {};
    if (options.page && options.page > 1) {
        query.p = options.page;
    }
    if (options.searchText) {
        query.q = options.searchText;
    }
    return { pathname, query };
}

export function locationEquals(a, b) {
    return a.pathname === b.pathname && a.search === b.search;
}

export function parseId(id) {
    const matches = ('' + id).match(/^([0-9]+)(-.*)?$/);
    if (matches) {
        id = matches[1];
    }
    return parseInt(id);
}
