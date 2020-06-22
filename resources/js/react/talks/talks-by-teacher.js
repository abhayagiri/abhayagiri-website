import React, { Component } from 'react';
import PropTypes from 'prop-types';

import { pageWrapper } from '../shared/util';
import TalkList from './talk-list';
import AuthorService from '../services/author.service';
import TalkService from '../services/talk.service';

class TalksByTeacher extends Component {

    static propTypes = {
        params: PropTypes.object.isRequired,
        t: PropTypes.func.isRequired
    }

    constructor(props, context) {
        super(props, context);
        this.state = {
            author: null,
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
        const author = await this.fetchAuthor(props);
        this.fetchTalks(author, props);
    }

    async fetchAuthor(props) {
        const author = await AuthorService.getAuthor(props.params.authorId);
        this.setState({ author });
        return author;
    }

    async fetchTalks(author, props) {
        const talks = await TalkService.getTalks({
            page: props.pageNumber,
            pageSize: 10,
            authorId: author.id
        });

        this.setState({
            talks: talks,
            isLoading: false
        });
    }

    getCategory() {
        const { author } = this.state;
        if (author) {
            return {
                imageUrl: author.imageUrl,
                title: this.props.tp(author, 'title'),
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
                />
        );
    }
}

export default pageWrapper('talks')(TalksByTeacher);
