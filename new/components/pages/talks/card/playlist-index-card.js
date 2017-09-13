import React, { Component } from 'react';
import { translate } from 'react-i18next';

import TalksCard from './talks-card';

class PlaylistIndexCard extends Component {
    render() {
        const { t } = this.props;
        return (
            <TalksCard
                title={t('talks by collection')}
                imageUrl="/media/images/themes/Triple%20Gem%20and%20Thai%20Forest%20Legacy-small.jpg"
            >
                Series of talks, reflections, guided meditations, and chanting arranged according to the event or collection with which they were associated.
            </TalksCard>
        );
    }
}

export default translate('talks')(PlaylistIndexCard);
