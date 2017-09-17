import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { tp } from '../../../../i18n';
import TalkList from '../talk-list/talk-list';
import TalkService from '../../../../services/talk.service';
import AuthorService from '../../../../services/author.service';
import Spinner from '../../../widgets/spinner/spinner';
import PlaylistService from '../../../../services/playlist.service';

class TalksBySubject extends Component {

    constructor() {
        super();

        this.state = {
            talks: null,
            category: {},
            isLoading: true
        }
    }

    async componentWillMount() {
        await this.fetchPlaylistGroup();
        this.fetchData(this.props);
    }

    componentWillReceiveProps(nextProps) {
        this.fetchData(nextProps)
    }

    async fetchData(props) {
        await this.fetchPlaylists(props);
        await this.fetchTalks(props);
    }

    async fetchPlaylistGroup() {
        let playlistGroupId = parseInt(this.props.params.playlistGroupId.split(/-(.+)/)[0]);
        this.playlistGroup = await PlaylistService.getPlaylistGroup(playlistGroupId);
        console.log(this.playlistGroup);
    }

    async fetchPlaylists(props) {
        let currentPlaylistId = props.params.playlistId;
        let playlists = this.playlistGroup.playlists.map((playlist) => {
            return {
                href: '../' + playlist.id + '-' + playlist.slug,
                title: tp(playlist, 'title'),
                active: playlist.id === parseInt(currentPlaylistId)
            };
        });

        let category = {
            title: tp(this.playlistGroup, 'title'),
            imagePath: this.playlistGroup.imageUrl,
            links: playlists
        };

        this.setState({
            category: category
        });
    }

    async fetchTalks(props) {
        let playlistId = parseInt(props.params.playlistId.split(/-(.+)/)[0]);

        const talks = await TalkService.getTalks({
            searchText: this.context.searchText,
            page: this.context.page,
            pageSize: 10,
            playlistId: playlistId
        });

        this.setState({
            talks: talks,
            isLoading: false
        });
    }

    render() {
        return (
            !this.state.isLoading ?
                <TalkList
                    talks={this.state.talks}
                    category={this.state.category} /> :
                <Spinner />
        )
    }
}

TalksBySubject.contextTypes = {
    page: React.PropTypes.number,
    searchText: React.PropTypes.string
}

export default TalksBySubject;
