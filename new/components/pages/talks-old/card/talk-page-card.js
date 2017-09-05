import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import TalksCard from './talks-card';
import { tp } from '../../../../i18n';
import { authorTalksPath } from '../util';

class TalkPageCard extends Component {
    render() {
        const
            { t, talk } = this.props,
            { author } = talk,
            lng = this.props.i18n.language,
            title = tp(author, 'title', false);
        return (
            <TalksCard
                title={t('talks by author', { title })}
                imageUrl={author.imageUrl}
            >
                <p>
                    <Link to={authorTalksPath(author, { lng })}>
                        {t('more talks by author', { title })}
                    </Link>.
                </p>
            </TalksCard>
        );
    }
}

TalkPageCard.propTypes = {
    talk: PropTypes.object.isRequired
};

export default translate('talks')(TalkPageCard);
