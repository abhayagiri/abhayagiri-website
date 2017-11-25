import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { withGlobals } from 'components/shared/globals/globals';
import Link from 'components/shared/link/link';
import './language.css';

export class Language extends Component {

    static propTypes = {
        i18n: PropTypes.object.isRequired,
        location: PropTypes.object.isRequired,
        t: PropTypes.func.isRequired
    }

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
                <Link lng={nextLng} to={this.props.location}>
                    {buttons[nextLng]}
                </Link>
            </div>
        );
    }
}

export default translate()(withGlobals(Language, 'location'));
