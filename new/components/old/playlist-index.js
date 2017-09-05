import React, { Component } from 'react';
import PropTypes from 'prop-types';

import PlaylistIndexCard from './card/playlist-index-card';
import PlaylistList from './list/playlist-list';
import PlaylistService from '../../../services/playlist.service';
import TalksLayout from './layout/talks-layout';

class PlaylistIndex extends Component {

    constructor(props) {
        super(props);
        this.state = { playlists: null };
    }

    componentDidMount() {
        this.fetchPlaylists();
    }

    async fetchPlaylists() {
        const playlists = await PlaylistService.getPlaylists();
        this.setState({ playlists });
    }

    render() {
        return (
            <TalksLayout
                basePathMatch="by-collection"
                search={null}
                card={<PlaylistIndexCard />}
                details={this.state.playlists &&
                    <PlaylistList playlists={this.state.playlists} />
                }
            />
        );
    }
}

export default PlaylistIndex;
