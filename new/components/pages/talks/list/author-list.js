import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import { tp } from '../../../../i18n';
import { talksPath } from '../util';

import './author-list.css';

class AuthorList extends Component {
    render() {
        const lng = this.props.i18n.language;
        return (
            <div className="authors-list">
                <ul>
                    {this.props.authors.map((author, index) => {
                        const
                            basePath = `by-teacher/${author.id}-${author.slug}`,
                            path = talksPath(basePath, { lng: lng });
                        return (
                            <li key={index}>
                                <Link to={path}>{tp(author, 'title')}</Link>
                            </li>
                        );
                    })}
                </ul>
            </div>
        );
    }
}

AuthorList.propTypes = {
    authors: PropTypes.array.isRequired
};

export default translate('talks')(AuthorList);
