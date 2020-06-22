import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { tp, thp } from 'i18n';
import { getPage } from 'components/shared/location';
import TalkList from 'components/content/talks/talk-list/talk-list';
import PlaylistService from 'services/playlist.service';
import TalkService from 'services/talk.service';

class TalksByCollection extends Component {

    static propTypes = {
        params: PropTypes.object.isRequired,
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
        this.setState({ playlistGroup, playlist });
        return playlist;
    }

    async fetchTalks(playlist, props) {
        const talks = await TalkService.getTalks({
            page: getPage(),
            pageSize: 10,
            playlistId: playlist.id
        });

        this.setState({
            talks: talks,
            isLoading: false
        });
    }

    getCategory() {
        const { playlist, playlistGroup } = this.state;
        if (playlist && playlistGroup) {
            return {
                title: tp(playlistGroup, 'title'),
                imageUrl: playlist.imageUrl || playlistGroup.imageUrl,
                links: playlistGroup.playlists.map((p) => {
                    return {
                        href: p.talksPath,
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

export default translate('talks')(TalksByCollection);
