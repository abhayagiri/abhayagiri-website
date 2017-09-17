import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import { tp } from '../../../../i18n';
import { talksPath } from '../util';

import './talks-header.css';

class TalksHeader extends Component {

    render() {
        const
            { t, basePathMatch } = this.props,
            lng = this.props.i18n.language,
            basePathMatcher = new RegExp('^/new(/th)?/talks/' + basePathMatch + '$'),
            latestTalksPath = talksPath('latest', {
                lng: lng,
                searchText: '',
                page: 1
            }),
            authorIndexPath = talksPath('by-teacher', { lng }),
            subjectGroupIndexPath = talksPath('by-subject', { lng }),
            playlistIndexPath = talksPath('by-collection', { lng }),
            talkTypeIndexPath = talksPath('by-type', { lng });

        function headerClass(path) {
            let className = "nav-item nav-link category-link";
            if (basePathMatcher.test(path.pathname)) {
                className += ' active';
            }
            return className;
        }

        return (

            <div className="navbar-nav mr-auto">

                <Link
                    key="10" to={latestTalksPath}
                    className={headerClass(latestTalksPath)}>
                    {t('latest')}
                </Link>

                <Link
                    key="11" to={authorIndexPath}
                    className={headerClass(authorIndexPath)}>
                    {t('teachers')}
                </Link>

                <Link
                    key="12" to={subjectGroupIndexPath}
                    className={headerClass(subjectGroupIndexPath)}>
                    {t('subjects')}
                </Link>

                <Link
                    key="13" to={playlistIndexPath}
                    className={headerClass(playlistIndexPath)}>
                    {t('collections')}
                </Link>

                <Link
                    key="14" to={talkTypeIndexPath}
                    className={'types ' + headerClass(talkTypeIndexPath)}>
                    {t('types')}
                </Link>

            </div>
        );
    }
}

TalksHeader.propTypes = {
    basePathMatch: PropTypes.string.isRequired
};

const TalksHeaderWithWrapper = translate('talks')(TalksHeader);

export default TalksHeaderWithWrapper;
