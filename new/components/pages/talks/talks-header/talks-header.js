import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import { tp } from '../../../../i18n';
import { talksPath } from '../util';

import './talks-header.css';
import categories from '../../../../data/categories.json';

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
            authorIndexPath = talksPath('by-teacher', { lng });

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

                {categories.map((category, index) => {
                    const
                        path = talksPath(`by-category/${category.slug}`, {
                            lng: lng,
                            searchText: '',
                            page: 1
                        });
                    return (
                        <Link
                            key={index} to={path}
                            className={headerClass(path)}>
                            {tp(category, 'title')}
                        </Link>
                    );
                })}
            </div>
        );
    }
}

TalksHeader.propTypes = {
    basePathMatch: PropTypes.string.isRequired
};

const TalksHeaderWithWrapper = translate('talks')(TalksHeader);

export default TalksHeaderWithWrapper;
