import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import Nav from '../nav/nav.js';

import './language.css';

class Language extends Component {
    getNextPathName() {
        const pathname = this.context.location.pathname;

        if (pathname.indexOf('th/') > -1) {
            return pathname.replace('th/', '');
        } else {
            return pathname.replace('new/', 'new/th/')
        }
    }

    getQuery() {
        return this.context.location.query
    }

    render() {
        const { t, location, i18n } = this.props;
        const language = i18n.language;

        const thaiButton = (
            <span>
                <span className="flag flag-th"></span>
                &nbsp;{t('thai', { lng: 'th' })}
            </span>
        );

        const englishButton = (
            <span>
                <span className="flag flag-us"></span>
                &nbsp;{t('english', { lng: 'en' })}
            </span>
        );

        return (
            <div id="language-switch">
                <Link to={{ pathname: this.getNextPathName(), query: this.getQuery() }}>
                    {language === 'en' ? thaiButton : englishButton}
                </Link>
            </div>
        );
    }
}

Language.contextTypes = {
    location: React.PropTypes.object,
}

const LanguageWithTranslate = translate()(Language);

export default LanguageWithTranslate;
