import React, { Component } from 'react';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import TalksCard from './talks-card';
import { tp } from '../../../../i18n';

class AuthorTalksCard extends Component {
    render() {
        const
            { t, author } = this.props,
            title = tp(author, 'title', false);
        return (
            <TalksCard
                title={t('talks by author', { title })}
                imageUrl={author.imageUrl}
            >
                <p>{t('talks by author', { title })}.</p>
            </TalksCard>
        );
    }
}

AuthorTalksCard.propTypes = {
    author: PropTypes.object.isRequired
};

export default translate('talks')(AuthorTalksCard);
