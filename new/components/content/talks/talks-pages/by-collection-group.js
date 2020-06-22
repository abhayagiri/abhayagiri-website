import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { tp, thp } from 'i18n';
import { getPage } from 'components/shared/location';
import TalkList from 'components/content/talks/talk-list/talk-list';
import PlaylistService from 'services/playlist.service';
import TalkService from 'services/talk.service';

class TalksByCollectionGroup extends Component {

    static propTypes = {
        params: PropTypes.object.isRequired,
        t: PropTypes.func.isRequired
    }

    constructor(props, context) {
        super(props, context);
        this.state = {
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
        const playlistGroup = await this.fetchPlaylistGroup(props);
        this.fetchTalks(playlistGroup, props);
    }

    async fetchPlaylistGroup(props) {
        const
            playlistGroupId = parseInt(props.params.playlistGroupId),
            playlistGroup = await PlaylistService.getPlaylistGroup(playlistGroupId);
        if (!playlistGroup) {
            this.setState({
                isLoading: false
            });
            throw new Error('Playlist Group ' + playlistGroupId + ' not found');
        }
        this.setState({ playlistGroup });
        return playlistGroup;
    }

    async fetchTalks(playlistGroup, props) {
        const talks = await TalkService.getTalks({
            page: getPage(),
            pageSize: 10,
            playlistGroupId: playlistGroup.id
        });

        this.setState({
            talks: talks,
            isLoading: false
        });
    }

    getCategory() {
        const { playlistGroup } = this.state;
        if (playlistGroup) {
            return {
                imageUrl: playlistGroup.imageUrl,
                title: tp(playlistGroup, 'title'),
                links: [{
                    href: playlistGroup.talksPath,
                    title: this.props.t('see related talks'),
                    active: false
                }]
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
                preamble={this.state.playlistGroup &&
                    thp(this.state.playlistGroup, 'descriptionHtml')}
            />
        );
    }
}

export default translate('talks')(TalksByCollectionGroup);
