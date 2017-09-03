import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import { tp } from '../../../../i18n';
import { playlistTalksPath } from '../util';

import './playlist-list.css';

class PlaylistList extends Component {
    render() {
        const lng = this.props.i18n.language;
        return (
            <div className="playlists-list">
                <ul>
                    {this.props.playlists.map((playlist, index) => {
                        return (
                            <li key={index}>
                                <Link to={playlistTalksPath(playlist, { lng })}>
                                    {tp(playlist, 'title')}
                                </Link>
                            </li>
                        );
                    })}
                </ul>
            </div>
        );
    }
}

PlaylistList.propTypes = {
    playlists: PropTypes.array.isRequired
};

export default translate('talks')(PlaylistList);
