import React, { Component } from 'react';

import i18n from 'i18n';

export class NotFound extends Component {
    render() {
        // TODO show a simple static 404 page contents with header/footer
        ErrorService.notFound();
        return null;
    }
}

export default class ErrorService
{

    static notFound()
    {
        const path = i18n.language === 'th' ? '/th/404' : '/404';
        window.location.replace(path);
    }

    static async handle(exception, message)
    {
        let pathname = window.location.pathname;
        if (pathname.startsWith('/new')) {
            pathname = pathname.substring(4);
        }
        try {
            const redirect = await axios.get('/api/redirects' + pathname);
            window.location.replace(redirect.data);
        } catch (e) {
            console.log(message || exception);
            ErrorService.notFound();
        }
    }
}
