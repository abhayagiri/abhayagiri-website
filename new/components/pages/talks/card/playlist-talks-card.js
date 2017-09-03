import React, { Component } from 'react';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import TalksCard from './talks-card';
import { tp } from '../../../../i18n';

class PlaylistTalksCard extends Component {
    render() {
        const
            { t, playlist } = this.props,
            title = tp(playlist, 'title', false);
        return (
            <TalksCard
                title={t('talks in collection', { title })}
                imageUrl={playlist.imageUrl}
            >
                <p>{t('talks in collection', { title })}.</p>
            </TalksCard>
        );
    }
}

PlaylistTalksCard.propTypes = {
    playlist: PropTypes.object.isRequired
};

export default translate('talks')(PlaylistTalksCard);
