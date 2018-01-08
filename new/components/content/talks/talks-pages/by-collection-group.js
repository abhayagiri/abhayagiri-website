import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { withBreadcrumbs } from 'components/ui/breadcrumb/breadcrumb';
import { withGlobals } from 'components/shared/globals/globals';
import { tp, thp } from 'i18n';
import TalkList from 'components/content/talks/talk-list/talk-list';
import PlaylistService from 'services/playlist.service';
import TalkService from 'services/talk.service';

class TalksByCollectionGroup extends Component {

    static propTypes = {
        page: PropTypes.number.isRequired,
        params: PropTypes.object.isRequired,
        searchText: PropTypes.string.isRequired,
        setBreadcrumbs: PropTypes.func.isRequired,
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
        this.updateBreadcrumbs();
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
        this.setState({ playlistGroup }, this.updateBreadcrumbs);
        return playlistGroup;
    }

    async fetchTalks(playlistGroup, props) {
        const talks = await TalkService.getTalks({
            searchText: props.searchText,
            page: props.page,
            pageSize: 10,
            playlistGroupId: playlistGroup.id
        });

        this.setState({
            talks: talks,
            isLoading: false
        });
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
                } : null,
                playlistGroup ? {
                    title: this.props.t('latest'),
                    to: playlistGroup.latestTalksPath
                } : null
            ];
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

export default translate('talks')(
    withBreadcrumbs(
        withGlobals(TalksByCollectionGroup, ['page', 'searchText'])
    )
);
