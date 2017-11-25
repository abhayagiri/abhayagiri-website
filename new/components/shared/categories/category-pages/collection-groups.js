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
            playlistGroups: null,
            isLoading: true
        }
    }

    componentDidMount() {
        this.fetchPlaylistGroups();
        this.props.setGlobal('breadcrumbs', this.getBreadcrumbs);
    }

    async fetchPlaylistGroups() {
        const playlistGroups = await PlaylistService.getPlaylistGroups();
        this.setState({
            playlistGroups: playlistGroups,
            isLoading: false
        });
    }

    getBreadcrumbs = () => {
        return [
            {
                title: this.props.t('collections'),
                to: '/talks/collections'
            }
        ];
    }

    getCategoryList() {
        const { playlistGroups } = this.state;
        if (playlistGroups) {
            return playlistGroups.map((playlistGroup) => {
                return {
                    imageUrl: playlistGroup.imageUrl,
                    title: tp(playlistGroup, 'title'),
                    href: '/talks/collections/' + playlistGroup.id + '-' + playlistGroup.slug
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
