import React, { Component } from 'react';
import { translate } from 'react-i18next';

import TalksCard from './talks-card';

class AuthorIndexCard extends Component {
    render() {
        const { t } = this.props;
        return (
            <TalksCard
                title={t('talks by teacher')}
                imageUrl="/media/images/themes/Triple%20Gem%20and%20Thai%20Forest%20Legacy-small.jpg"
            >
                Talks and reflections arranged by teacher.
            </TalksCard>
        );
    }
}

export default translate('talks')(AuthorIndexCard);
