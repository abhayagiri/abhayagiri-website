import React, { Component } from 'react';
import { translate } from 'react-i18next';

import TalksCard from './talks-card';

class TalkTypeIndexCard extends Component {
    render() {
        const { t } = this.props;
        return (
            <TalksCard
                title={t('types of talks')}
                imageUrl="/media/images/themes/Triple%20Gem%20and%20Thai%20Forest%20Legacy-small.jpg"
            >
                {t('types of talks')}.
            </TalksCard>
        );
    }
}

export default translate('talks')(TalkTypeIndexCard);
