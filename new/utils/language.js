
export function setLanguage(link) {
    return (window.location.href.indexOf('/th') === -1) ?
        link.replace('/th', '/') :
        link.replace('/new/', '/new/th/');
}
