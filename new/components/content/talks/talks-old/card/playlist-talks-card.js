import React, { Component } from 'react';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import TalksCard from './talks-card';
import { tp, thp } from '../../../../i18n';

class PlaylistTalksCard extends Component {
    render() {
        const
            { t, playlist } = this.props;
        return (
            <TalksCard
                title={tp(playlist, 'title')}
                imageUrl={playlist.imageUrl}
            >
                {thp(playlist, 'descriptionHtml')}
            </TalksCard>
        );
    }
}

PlaylistTalksCard.propTypes = {
    playlist: PropTypes.object.isRequired
};

export default translate('talks')(PlaylistTalksCard);
