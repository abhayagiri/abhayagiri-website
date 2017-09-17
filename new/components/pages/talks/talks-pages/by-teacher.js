import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { tp } from '../../../../i18n';
import TalkList from '../talk-list/talk-list';
import TalkService from '../../../../services/talk.service';
import AuthorService from '../../../../services/author.service';
import Spinner from '../../../widgets/spinner/spinner';

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

    componentWillReceiveProps(nextProps){
        this.fetchData(nextProps)
    }

    async fetchData(props) {
        await this.fetchAuthor(props.params.authorId);
        this.fetchTalks(props);
    }

    async fetchAuthor(authorId) {
        let author = await AuthorService.getAuthor(authorId),
            category = {
                imagePath: author.imageUrl,
                title: tp(author, 'title'),
            };

        this.setState({
            category: category
        });
    }

    async fetchTalks(props) {
        const talks = await TalkService.getTalks({
            searchText: this.context.searchText,
            page: this.context.page,
            pageSize: 10,
            authorId: props.params.authorId.split(/-(.+)/)[0]
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

TalksByTeacher.contextTypes = {
    page: React.PropTypes.number,
    searchText: React.PropTypes.string
}

export default TalksByTeacher;
