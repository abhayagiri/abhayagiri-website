import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import TileList from './tile-list.js';
import { tp } from '../../../../i18n';
import { authorTalksPath } from '../util';

class AuthorList extends Component {
    render() {
        const lng = this.props.i18n.language;
        return <TileList list={this.props.authors.map((author) => {
            return {
                imagePath: author.imageUrl,
                path: authorTalksPath(author, { lng }),
                title: tp(author, 'title')
            };
        })} />;
    }
}

AuthorList.propTypes = {
    authors: PropTypes.array.isRequired
};

export default translate('talks')(AuthorList);
