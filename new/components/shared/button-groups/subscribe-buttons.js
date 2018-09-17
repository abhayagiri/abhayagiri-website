import React, { Component } from 'react';
import { translate } from 'react-i18next';
import Link from 'components/shared/link/link';

import './subscribe-buttons.css';

class SubscribeButtons extends Component {

    render() {
        const { t } = this.props;
        return (
            <div className="btn-group subscribe-buttons">
                <a className="btn btn-outline-secondary" href="https://www.youtube.com/channel/UCFAuQ5fmYYVv5_Dim0EQpVA?sub_confirmation=1" target="_blank"><i className="fa fa-youtube"></i> {t('subscribe')}</a>
                <a className="btn btn-outline-secondary" href="https://itunes.apple.com/us/podcast/abhayagiri-dhamma-talks/id600138148?mt=2" target="_blank"><i className="fa fa-apple"></i> {t('subscribe')}</a>
            </div>
        );
    }

}

export default translate('common')(SubscribeButtons);
