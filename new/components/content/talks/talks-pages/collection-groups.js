import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { tp } from 'i18n';
import CategoryList from 'components/shared/categories/category-list/category-list';
import Spinner from 'components/shared/spinner/spinner';
import PlaylistService from 'services/playlist.service';

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
                    title: tp(playlistGroup, 'title'),
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

export default translate('talks')(CategoryCollectionGroups);
