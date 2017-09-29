import React, { Component } from 'react';
import Link from 'components/widgets/link/link';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import './language.css';

class Language extends Component {

    render() {
        const
            { t, i18n } = this.props,
            nextLng = i18n.language === 'th' ? 'en' : 'th',
            buttons = {
            'th': (<span>
                <span className="flag flag-th"></span>
                &nbsp;{t('thai', { lng: 'th' })}
            </span>),
            'en': (<span>
                <span className="flag flag-us"></span>
                &nbsp;{t('english', { lng: 'en' })}
            </span>)
        };

        return (
            <div id="language-switch">
                <Link lng={nextLng} to={this.context.location}>
                    {buttons[nextLng]}
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
