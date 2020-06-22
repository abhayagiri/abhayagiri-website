export function getHref() {
    return window.location.href;
}

export function getPathname() {
    return window.location.pathname;
}

export function getQuery() {
    let query = {};
    const search = window.location.search;
    if (search[0] === '?') {
        search.substring(1).split('&').map(function (pair) {
            const n = pair.indexOf('=');
            if (n >= 0) {
                query[pair.substring(0, n)] = pair.substring(n + 1);
            } else {
                query[pair] = null;
            }
        });
    }
    return query;
}

export function getPage() {
    return parseInt(getQuery().p) || 1;
}
