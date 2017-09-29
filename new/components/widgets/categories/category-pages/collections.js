import React, { Component } from 'react';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import Link from 'components/widgets/link/link';
import { tp } from '../../../../i18n';
import CategoryList from '../category-list/category-list';
import Spinner from '../../../widgets/spinner/spinner';
import PlaylistService from '../../../../services/playlist.service';

class CategoryCollections extends Component {

    constructor() {
        super();

        this.state = {
            playlistGroups: [],
            isLoading: true
        }
    }

    componentWillMount() {
        this.fetchPlaylistGroups();
    }

    async fetchPlaylistGroups() {
        let playlistGroups = await PlaylistService.getPlaylistGroups();

        playlistGroups = playlistGroups.map((playlistGroup) => {
            const defaultPlaylist = playlistGroup.playlists[0];

            return {
                imageUrl: playlistGroup.imageUrl,
                title: tp(playlistGroup, 'title'),
                href: '/talks/collections/' + playlistGroup.id + '-' + playlistGroup.slug + '/' + defaultPlaylist.id + '-' + defaultPlaylist.slug
            };
        });

        this.setState({
            playlistGroups: playlistGroups,
            isLoading: false
        });
    }

    render() {
        const lng = this.props.i18n.language;
        return !this.state.isLoading ? <CategoryList list={this.state.playlistGroups} /> : <Spinner />;
    }
}

export default translate('talks')(CategoryCollections);
