import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { withGlobals } from 'components/shared/globals/globals';
import { tp, thp } from 'i18n';
import TalkList from 'components/content/talks/talk-list/talk-list';
import PlaylistService from 'services/playlist.service';
import TalkService from 'services/talk.service';

class TalksByCollection extends Component {

    static propTypes = {
        page: PropTypes.number.isRequired,
        params: PropTypes.object.isRequired,
        searchText: PropTypes.string.isRequired,
        t: PropTypes.func.isRequired
    }

    constructor(props, context) {
        super(props, context);
        this.state = {
            playlist: null,
            playlistGroup: null,
            talks: null,
            isLoading: true
        }
    }

    componentDidMount() {
        this.fetchData(this.props);
    }

    componentWillReceiveProps(nextProps) {
        this.fetchData(nextProps)
    }

    async fetchData(props) {
        this.setState({
            isLoading: true
        });
        const playlist = await this.fetchPlaylistAndPlaylistGroup(props);
        this.fetchTalks(playlist, props);
    }

    async fetchPlaylistAndPlaylistGroup(props) {
        const
            playlistGroupId = parseInt(props.params.playlistGroupId),
            playlistId = parseInt(props.params.playlistId),
            playlistGroup = await PlaylistService.getPlaylistGroup(playlistGroupId),
            playlist = playlistGroup.playlists.find((p) => {
                return p.id === playlistId;
            });
        if (!playlist) {
            this.setState({
                isLoading: false
            });
            throw new Error('Playlist ' + playlistId + ' not found in playlists');
        }
        this.setState({ playlistGroup, playlist }, () => {
            props.setGlobal('breadcrumbs', this.getBreadcrumbs);
        });
        return playlist;
    }

    async fetchTalks(playlist, props) {
        const talks = await TalkService.getTalks({
            searchText: props.searchText,
            page: props.page,
            pageSize: 10,
            playlistId: playlist.id
        });

        this.setState({
            talks: talks,
            isLoading: false
        });
    }

    getBreadcrumbs = () => {
        const { playlist, playlistGroup } = this.state;
        return [
            {
                title: this.props.t('collections'),
                to: '/talks/collections'
            },
            {
                title: tp(playlistGroup, 'title'),
                to: '/talks/collections/' + playlistGroup.id + '-' + playlistGroup.slug
            },
            {
                title: tp(playlist, 'title'),
                to: '/talks/collections/' + playlistGroup.id + '-' + playlistGroup.slug + '/' + playlist.id + '-' + playlist.slug
            }
        ];
    }

    getCategory() {
        const { playlist, playlistGroup } = this.state;
        if (playlist && playlistGroup) {
            return {
                title: tp(playlistGroup, 'title'),
                imageUrl: playlist.imageUrl || playlistGroup.imageUrl,
                links: playlistGroup.playlists.map((p) => {
                    return {
                        href: '/talks/collections/' + playlistGroup.id + '-' + playlistGroup.slug + '/' + p.id + '-' + p.slug,
                        title: tp(p, 'title'),
                        active: p.id === playlist.id
                    };
                })
            };
        } else {
            return null;
        }
    }

    render() {
        return (
            <TalkList
                isLoading={this.state.isLoading}
                talks={this.state.talks}
                category={this.getCategory()}
                preamble={this.state.playlist && thp(this.state.playlist, 'descriptionHtml')}
            />
        );
    }
}

export default translate('talks')(
    withGlobals(TalksByCollection, ['page', 'searchText'])
);
