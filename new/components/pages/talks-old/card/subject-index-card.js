import React, { Component } from 'react';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import TalksCard from './talks-card';
import { tp, thp } from '../../../../i18n';

class SubjectIndexCard extends Component {
    render() {
        const
            { t, subjectGroup } = this.props;
        return (
            <TalksCard
                title={tp(subjectGroup, 'title')}
                imageUrl={subjectGroup.imageUrl}
            >
                {thp(subjectGroup, 'descriptionHtml')}
            </TalksCard>
        );
    }
}

SubjectIndexCard.propTypes = {
    subjectGroup: PropTypes.object.isRequired
};

export default translate('talks')(SubjectIndexCard);
