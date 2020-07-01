import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { pageWrapper } from '../shared/util';
import CategoryList from '../shared/category-list';
import Spinner from '../shared/spinner';
import PlaylistService from '../services/playlist.service';

export class CategoryCollections extends Component {

    static propTypes = {
        params: PropTypes.object.isRequired,
        t: PropTypes.func.isRequired
    }

    constructor(props, context) {
        super(props, context);
        this.state = {
            playlistGroup: null,
            isLoading: true
        }
    }

    componentDidMount() {
        this.fetchPlaylistGroup(this.props);
    }

    componentWillReceiveProps(nextProps) {
        this.fetchPlaylistGroup(nextProps);
    }

    async fetchPlaylistGroup(props) {
        const
            playlistGroupId = parseInt(props.params.playlistGroupId),
            playlistGroup = await PlaylistService.getPlaylistGroup(playlistGroupId);
        this.setState({
            playlistGroup: playlistGroup,
            isLoading: false
        });
    }

    getCategoryList() {
        const { playlistGroup } = this.state;
        if (playlistGroup) {
            return playlistGroup.playlists.map((playlist) => {
                return {
                    imageUrl: playlist.imageUrl,
                    title: this.props.tp(playlist, 'title'),
                    href: playlist.talksPath
                };
            });
        } else {
            return [];
        }
    }

    render() {
        return !this.state.isLoading ? <CategoryList list={this.getCategoryList()} /> : <Spinner />;
    }
}

export default pageWrapper('talks')(CategoryCollections);
