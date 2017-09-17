import React, { Component } from 'react';
import { translate } from 'react-i18next';

import TalksCard from './talks-card';

class SubjectGroupIndexCard extends Component {
    render() {
        const { t } = this.props;
        return (
            <TalksCard
                title={t('talks by subject')}
                imageUrl="/media/images/themes/Triple%20Gem%20and%20Thai%20Forest%20Legacy-small.jpg"
            >
                Talks and reflections arranged by the subject of the talk.
            </TalksCard>
        );
    }
}

export default translate('talks')(SubjectGroupIndexCard);
