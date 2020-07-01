import React, { Component } from 'react';
import axios from 'axios';

import i18n from 'i18next';

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
        // TODO see above
        const path = i18n.language === 'th' ? '/th/404' : '/404';
        window.location.replace(path);
    }

    static async handle(exception, message)
    {
        let pathname = window.location.pathname;
        if (pathname.startsWith('/new')) {
            pathname = pathname.substring(4);
        }
        let redirect = null;
        try {
            redirect = await axios.get('/api/redirects' + pathname);
        } catch (e) {
        }
        if (redirect && redirect.data) {
            window.location.replace(redirect.data);
        } else {
            console.log('Could not fetch redirect for ' + pathname);
            console.log(message || exception);
            ErrorService.notFound();
        }
    }
}
