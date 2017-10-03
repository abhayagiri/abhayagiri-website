import React, { Component } from 'react';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import TalksCard from './talks-card';
import { tp, thp } from '../../../../i18n';

class SubjectTalksCard extends Component {
    render() {
        const
            { t, subject } = this.props;
        return (
            <TalksCard
                title={tp(subject, 'title')}
                imageUrl={subject.imageUrl}
            >
                {thp(subject, 'descriptionHtml')}
            </TalksCard>
        );
    }
}

SubjectTalksCard.propTypes = {
    subject: PropTypes.object.isRequired
};

export default translate('talks')(SubjectTalksCard);
