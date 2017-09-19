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
 * Applies to the *Path functions...
 *
 * Return a location object for a talks/* resource.
 *
 * @param {object} object
 * @param {object} options
 * @return {object} location
 * @see talksPath()
 */
export function talkPath(talk, options) {
    const basePath = `${talk.id}-${encodeURIComponent(talk.url_title)}`;
    return talksPath(basePath, options);
}

export function authorTalksPath(author, options) {
    const basePath = `by-teacher/${author.id}-${encodeURIComponent(author.slug)}`;
    return talksPath(basePath, options);
}

export function subjectGroupPath(subjectGroup, options) {
    const basePath = `by-subject/${subjectGroup.id}-${encodeURIComponent(subjectGroup.slug)}`;
    return talksPath(basePath, options);
}

export function subjectPath(subjectGroup, subject, options) {
    const basePath = `by-subject/${subjectGroup.id}-` +
        `${encodeURIComponent(subjectGroup.slug)}/` +
        `${subject.id}-${encodeURIComponent(subject.slug)}`;
    return talksPath(basePath, options);
}

export function playlistTalksPath(playlist, options) {
    const basePath = `by-collection/${playlist.id}-${encodeURIComponent(playlist.slug)}`;
    return talksPath(basePath, options);
}

export function talkTypeTalksPath(talkType, options) {
    const basePath = `by-type/${talkType.id}-${encodeURIComponent(talkType.slug)}`;
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
