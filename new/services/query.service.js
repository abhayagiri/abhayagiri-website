export default class QueryService {
    static updateQuery(param, value) {
        let regex = new RegExp("/([?|&]" + param + "=)[^\&]+/");
        let url = window.location.href;
        return url.replace(regex, '$1' + value);
    }
}