import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import { LatestTalksScope, TalksByCategoryScope, AuthorsScope } from '../scope.js';

import './talks-header.css';
import categories from '../../../../data/categories.json';

class TalksHeader extends Component {

    render() {

        function headerClassName(itemScope) {
            let className = "nav-item nav-link category-link";
            if (scope.equals(itemScope) ||
                scope.name === 'author-talks' && itemScope.name === 'authors') {
                className += ' active';
            }
            return className;
        }

        const
            { t, scope } = this.props,
            lng = this.props.i18n.language,
            latestScope = new LatestTalksScope(),
            latestPath = latestScope.getPath({
                lng: lng,
                searchText: '',
                page: 1
            }),
            latestClassName = headerClassName(latestScope),
            authorsScope = new AuthorsScope(),
            authorsPath = authorsScope.getPath({ lng: lng }),
            authorsClassName = headerClassName(authorsScope);

        return (

            <div className="navbar-nav mr-auto">

                <Link key="10" to={latestPath} className={latestClassName}>
                    {t('latest')}
                </Link>

                <Link key="11" to={authorsPath} className={authorsClassName}>
                    {t('teachers')}
                </Link>

                {categories.map((category, index) => {

                    const
                        scope = new TalksByCategoryScope(category),
                        path = scope.getPath({
                            lng: lng,
                            searchText: '',
                            page: 1
                        }),
                        className = headerClassName(scope);

                    return (
                        <Link to={path} key={index} className={className}>
                            {category[lng === 'en' ? 'titleEn' : 'titleTh']}
                        </Link>
                    );

                })}

            </div>

        );
    }
}

TalksHeader.propTypes = {
    scope: PropTypes.object.isRequired
};

const TalksHeaderWithWrapper = translate('talks')(TalksHeader);

export default TalksHeaderWithWrapper;
