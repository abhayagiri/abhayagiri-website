import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { withGlobals } from 'components/shared/globals/globals';
import { tp } from 'i18n';
import TalkList from 'components/content/talks/talk-list/talk-list';
import AuthorService from 'services/author.service';
import TalkService from 'services/talk.service';

class TalksByQuery extends Component {

    static propTypes = {
        page: PropTypes.number.isRequired,
        params: PropTypes.object.isRequired,
        searchText: PropTypes.string.isRequired
    }

    constructor(props, context) {
        super(props, context);
        this.state = {
            talks: null,
            category: null,
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
        this.fetchTalks(props);
        props.setGlobal('breadcrumbs', this.getBreadcrumbs);
    }

    async fetchTalks(props) {
        const talks = await TalkService.getTalks({
            searchText: props.searchText,
            page: props.page,
            pageSize: 10,
        });

        this.setState({
            talks: talks,
            isLoading: false
        });
    }

    getBreadcrumbs = () => {
        return [
            {
                title: this.getSearchTitle(),
                to: '/talks/search/' + encodeURIComponent(this.props.params.query)
            }
        ];
    }

    getSearchTitle() {
        return this.props.t('search') + ': "' + this.props.params.query + '"';
    }

    render() {
        console.log(this.props);
        return (
            <TalkList
                isLoading={this.state.isLoading}
                talks={this.state.talks}
                category={{
                    title: this.getSearchTitle(),
                    imageUrl: '/img/ui/search.png',
                    links: []
                }} />
        )
    }
}

export default translate('talks')(
    withGlobals(TalksByQuery, ['page', 'searchText'])
);
