import React, { Component } from 'react';
import PropTypes from 'prop-types';

import PlaylistService from '../../../services/playlist.service';
import PlaylistTalksCard from './card/playlist-talks-card';
import BaseTalks from './base-talks';
import { getCategory, parseId } from './util';

class PlaylistTalks extends Component {

    constructor(props) {
        super(props);
        this.state = {
            playlist: null,
            playlistId: parseId(props.params.playlistId)
        };
    }

    componentDidMount() {
        this.fetchPlaylist(this.state.playlistId);
    }

    componentWillReceiveProps(nextProps) {
        if (this.props.params.playlistId !== nextProps.params.playlistId) {
            const nextPlaylistId = parseId(nextProps.params.playlistId);
            this.setState({
                playlist: null,
                playlistId: nextPlaylistId
            });
            this.fetchPlaylist(nextPlaylistId);
        }
    }

    async fetchPlaylist(playlistId) {
        const playlist = await PlaylistService.getPlaylist(playlistId);
        this.setState({ playlist });
    }

    render() {
        return (
            <BaseTalks
                basePath={`by-collection/${this.state.playlistId}`}
                basePathMatch="by-collection"
                location={this.props.location}
                card={this.state.playlist &&
                    <PlaylistTalksCard playlist={this.state.playlist} />}
                filters={{ playlistId: this.state.playlistId }}
            />
        );
    }
}

export default PlaylistTalks;
