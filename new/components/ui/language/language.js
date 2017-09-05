import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import Nav from '../nav/nav.js';

import './language.css';

class Language extends Component {

    render() {
        const { t, location } = this.props;
        const path = location.pathname;
        let content, checkBase, switchBase, switchPath;
        if (this.props.i18n.language === 'en') {
            content = (<span>
                <span className="flag flag-th"></span>
                &nbsp;{t('thai', { lng: 'th' })}
            </span>);
            checkBase = '/new';
            switchBase = '/new/th';
        } else {
            content = (<span>
                <span className="flag flag-us"></span>
                &nbsp;{t('english', { lng: 'en' })}
            </span>);
            checkBase = '/new/th';
            switchBase = '/new';
        }
        if (path === checkBase) {
            switchPath = switchBase;
        } else if (path.startsWith(checkBase + '/')) {
            switchPath = switchBase + path.substring(checkBase.length);
        } else {
            console.warn('Unexpected path: ' + path);
            switchPath = switchBase;
        }
        switchPath += location.search;
        return (
            <div id="language-switch">
                <Link to={switchPath}>{content}</Link>
            </div>
        );
    }
}

Language.propTypes = {
    location: PropTypes.object.isRequired
}

const LanguageWithTranslate = translate()(Language);

export default LanguageWithTranslate;
