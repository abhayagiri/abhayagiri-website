import React, { Component } from 'react';
import { Link as RouterLink } from 'react-router';
import { translate } from 'react-i18next';
import { bool, object, string, func, oneOfType } from 'prop-types';
import i18n from 'i18next';

/* These compnents no longer need the /new prefix */
const readyPrefixes = [
    'gallery',
    'talks'
];

/**
 * Updates pathname to use /new, /new/th, etc based on lng or the
 * currently assigned i18next language.
 *
 * If useNew is true, then the path will be prefixed to /new (defaults true).
 *
 * @param string pathname
 * @param string lng (optional)
 * @param boolean useNew (optional)
 * @return string
 */
export function localizePathname(pathname, lng, useNew) {
    if (pathname && pathname[0] === '/') {
        if (!lng) {
            lng = i18n.language;
        }
        let newPrefix = (useNew !== undefined && !useNew) ? '' : '/new';
        pathname = pathname.replace(/^\/((new\/)?(th\/)?)?/, '');
        for (const readyPrefix of readyPrefixes) {
            if (pathname === readyPrefix ||
                pathname.startsWith(readyPrefix + '/')) {
                newPrefix = '';
                break;
            }
        }
        if (lng === 'th') {
            pathname = newPrefix + '/th/' + pathname;
        } else {
            pathname = newPrefix + '/' + pathname;
        }
    }
    return pathname;
}

/**
 * Mimics the spec of React Router 2.x's <Link>, with the exception
 * of not supporting the deprecated props query, hash and state.
 *
 * @see https://github.com/ReactTraining/react-router/blob/v2.8.1/docs/API.md#link
 * @see https://github.com/ReactTraining/react-router/blob/v2.8.1/modules/Link.js
 */
class Link extends Component {

    updateTo(to, lng) {
        if (typeof to === 'object') {
            to = Object.assign({}, to);
            to.pathname = localizePathname(to.pathname, lng);
        } else {
            to = localizePathname(to, lng);
        }
        return to;
    }

    render() {
        let props = Object.assign({}, this.props);
        const { lng } = this.props;
        delete(props.lng);
        props.to = this.updateTo(props.to, lng);
        return <RouterLink {...props} />
    }
}

Link.propTypes = {
    to: oneOfType([ string, object ]),
    activeStyle: object,
    activeClassName: string,
    onlyActiveOnIndex: bool.isRequired,
    onClick: func,
    target: string,
    lng: string
};

Link.defaultProps = {
    onlyActiveOnIndex: false,
    style: {}
}

export default Link;
