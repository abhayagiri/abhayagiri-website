import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import TileList from './tile-list.js';
import { tp } from '../../../../i18n';
import { playlistTalksPath } from '../util';

class PlaylistList extends Component {
    render() {
        const lng = this.props.i18n.language;
        return <TileList list={this.props.playlists.map((playlist) => {
            return {
                imagePath: playlist.imageUrl,
                path: playlistTalksPath(playlist, { lng }),
                title: tp(playlist, 'title')
            };
        })} />;
    }
}

PlaylistList.propTypes = {
    playlists: PropTypes.array.isRequired
};

export default translate('talks')(PlaylistList);
