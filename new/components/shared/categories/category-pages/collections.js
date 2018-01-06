import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { withBreadcrumbs } from 'components/ui/breadcrumb/breadcrumb';
import { tp } from 'i18n';
import CategoryList from 'components/shared/categories/category-list/category-list';
import Spinner from 'components/shared/spinner/spinner';
import PlaylistService from 'services/playlist.service';

export class CategoryCollections extends Component {

    static propTypes = {
        params: PropTypes.object.isRequired,
        setBreadcrumbs: PropTypes.func.isRequired,
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
        this.updateBreadcrumbs();
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
        }, this.updateBreadcrumbs);
    }

    updateBreadcrumbs = () => {
        this.props.setBreadcrumbs(() => {
            const { playlistGroup } = this.state;
            return [
                {
                    title: this.props.t('collections'),
                    to: '/talks/collections'
                },
                playlistGroup ? {
                    title: tp(playlistGroup, 'title'),
                    to: playlistGroup.talksPath
                } : null
            ];
        });
    }

    getCategoryList() {
        const { playlistGroup } = this.state;
        if (playlistGroup) {
            return playlistGroup.playlists.map((playlist) => {
                return {
                    imageUrl: playlist.imageUrl,
                    title: tp(playlist, 'title'),
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

export default translate('talks')(
    withBreadcrumbs(CategoryCollections)
);
