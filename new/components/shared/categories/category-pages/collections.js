import React, { Component } from 'react';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import Link from 'components/shared/link/link';
import { tp } from '../../../../i18n';
import CategoryList from '../category-list/category-list';
import Spinner from '../../../shared/spinner/spinner';
import PlaylistService from '../../../../services/playlist.service';

class CategoryCollections extends Component {

    constructor() {
        super();

        this.state = {
            playslists: [],
            isLoading: true
        }
    }

    componentWillMount() {
        this.fetchPlaylistGroups();
    }

    async fetchPlaylistGroups() {
        let playlistGroupId = this.props.params.playlistGroupId;
        let playlistGroup = await PlaylistService.getPlaylistGroup(playlistGroupId);

        let playslists = playlistGroup.playlists.map((playlist) => {
            return {
                imageUrl: playlist.imageUrl,
                title: tp(playlist, 'title'),
                href: this.props.location.pathname + '/' + playlist.id + '-' + playlist.slug
            };
        });

        this.setState({
            playslists: playslists,
            isLoading: false
        });
    }

    render() {
        return !this.state.isLoading ? <CategoryList list={this.state.playslists} /> : <Spinner />;
    }
}

export default translate('talks')(CategoryCollections);
