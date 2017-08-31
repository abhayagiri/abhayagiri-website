import i18n from '../../../i18n';

/**
 * Return a location object for a /talks/* resource.
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
        lng = options.lng || i18n.language || 'en',
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

/**
 * Return a location object for a talk resource.
 *
 * @param {object} talk
 * @param {object} options
 * @return {object} location
 * @see talksPath()
 */
export function talkPath(talk, options) {
    const basePath = `${talk.id}-${encodeURIComponent(talk.url_title)}`;
    return talksPath(basePath, options);
}

/**
 * Returns a location object for a author talks resource.
 *
 * @param {object} author
 * @param {object} options
 * @return {object} location
 * @see talksPath()
 */
export function authorTalksPath(author, options) {
    const basePath = `by-teacher/${author.id}-${encodeURIComponent(author.slug)}`;
    return talksPath(basePath, options);
}

/**
 * Returns a location object for a talk type talks resource.
 *
 * @param {object} talkType
 * @param {object} options
 * @return {object} location
 * @see talksPath()
 */
export function talkTypeTalksPath(talkType, options) {
    const basePath = `type/${talkType.id}-${encodeURIComponent(talkType.slug)}`;
    return talksPath(basePath, options);
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
