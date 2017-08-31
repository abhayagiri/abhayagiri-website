import React, { Component } from 'react';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import TalksCard from './talks-card';
import { tp } from '../../../../i18n';

class TalkTypeTalksCard extends Component {
    render() {
        const { t, talkType } = this.props;
        return (
            <TalksCard
                title={tp(talkType, 'title')}
                imageUrl={talkType.imageUrl}
            >
                <p>{tp(talkType, 'description')}.</p>
            </TalksCard>
        );
    }
}

TalkTypeTalksCard.propTypes = {
    talkType: PropTypes.object.isRequired,
};

export default translate('talks')(TalkTypeTalksCard);
