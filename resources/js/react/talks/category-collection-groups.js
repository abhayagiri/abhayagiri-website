import React, { Component } from 'react';
import PropTypes from 'prop-types';

import { pageWrapper } from '../shared/util';
import CategoryList from '../shared/category-list';
import Spinner from '../shared/spinner';
import PlaylistService from '../services/playlist.service';

export class CategoryCollectionGroups extends Component {

    static propTypes = {
        t: PropTypes.func.isRequired
    }

    constructor(props, context) {
        super(props, context);
        this.state = {
            playlistGroups: null,
            isLoading: true
        }
    }

    componentDidMount() {
        this.fetchPlaylistGroups();
    }

    async fetchPlaylistGroups() {
        const playlistGroups = await PlaylistService.getPlaylistGroups();
        this.setState({
            playlistGroups: playlistGroups,
            isLoading: false
        });
    }

    getCategoryList() {
        const { playlistGroups } = this.state;
        if (playlistGroups) {
            return playlistGroups.map((playlistGroup) => {
                return {
                    imageUrl: playlistGroup.imageUrl,
                    title: this.props.tp(playlistGroup, 'title'),
                    href: playlistGroup.talksPath
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

export default pageWrapper('talks')(CategoryCollectionGroups);
