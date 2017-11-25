import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { withGlobals } from 'components/shared/globals/globals';
import { tp } from 'i18n';
import CategoryList from 'components/shared/categories/category-list/category-list';
import Spinner from 'components/shared/spinner/spinner';
import PlaylistService from 'services/playlist.service';

class CategoryCollections extends Component {

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

    componentWillMount() {
        this.fetchPlaylistGroup();
    }

    async fetchPlaylistGroup() {
        const
            playlistGroupId = parseInt(this.props.params.playlistGroupId),
            playlistGroup = await PlaylistService.getPlaylistGroup(playlistGroupId);
        this.setState({
            playlistGroup: playlistGroup,
            isLoading: false
        }, () => {
            this.props.setGlobal('breadcrumbs', this.getBreadcrumbs);
        });
    }

    getBreadcrumbs = () => {
        const { playlistGroup } = this.state;
        return [
            {
                title: this.props.t('collections'),
                to: '/talks/collections'
            },
            {
                title: tp(playlistGroup, 'title'),
                to: '/talks/collections/' + playlistGroup.id + '-' + playlistGroup.slug
            }
        ];
    }

    getCategoryList() {
        const { playlistGroup } = this.state;
        if (playlistGroup) {
            return playlistGroup.playlists.map((playlist) => {
                return {
                    imageUrl: playlist.imageUrl,
                    title: tp(playlist, 'title'),
                    href: '/talks/collections/' + playlistGroup.id + '-' + playlistGroup.slug + '/' + playlist.id + '-' + playlist.slug
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

export default translate('talks')(
    withGlobals(CategoryCollections)
);
