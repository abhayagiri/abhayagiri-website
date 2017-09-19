import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { tp } from '../../../../i18n';
import TalkList from '../talk-list/talk-list';
import TalkService from '../../../../services/talk.service';
import AuthorService from '../../../../services/author.service';

class TalksByTeacher extends Component {

    constructor() {
        super();

        this.state = {
            talks: null,
            category: null,
            isLoading: true
        }
    }

    componentWillMount() {
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
    }


    async fetchTalks(props) {
        const talks = await TalkService.getTalks({
            searchText: this.props.params.query,
            page: this.context.page,
            pageSize: 10,
        });

        this.setState({
            talks: talks,
            isLoading: false
        });
    }

    render() {
        return (
            <TalkList
                isLoading={this.state.isLoading}
                talks={this.state.talks}
                category={this.state.category} />
        )
    }
}

TalksByTeacher.contextTypes = {
    page: React.PropTypes.number,
    searchText: React.PropTypes.string
}

export default TalksByTeacher;
