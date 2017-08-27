import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import getTalksScope from '../scope.js';

class AuthorsList extends Component {

    render() {
        const
            lng = this.props.i18n.language,
            getTitle = (author) => {
                // TODO refactor
                const lngKey = lng === 'th' ? 'Th' : 'En';
                return author['title' + lngKey] || author['title' + 'En'];
            }

        return (
            <div className="authors-list">
                <ul>
                    {this.props.authors.map((author, index) => {
                        let scope = getTalksScope('author-talks', { authorId: author.id });
                        scope.author = author;
                        return (
                            <li key={index}>
                                <Link to={scope.getPath({ lng: lng })}>
                                    {getTitle(author)}
                                </Link>
                            </li>
                        );
                    })}
                </ul>
            </div>
        );
    }
}

AuthorsList.propTypes = {
    authors: PropTypes.array.isRequired
};

const AuthorsListWithWrapper = translate('talks')(AuthorsList);

export default AuthorsListWithWrapper;
