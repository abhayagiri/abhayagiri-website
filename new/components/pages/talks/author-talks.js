import React, { Component } from 'react';
import PropTypes from 'prop-types';

import AuthorService from '../../../services/author.service';
import AuthorTalksCard from './card/author-talks-card';
import BaseTalks from './base-talks';
import { getCategory } from './util';

class AuthorTalks extends Component {

    constructor(props) {
        super(props);
        this.state = {
            author: null,
            authorId: parseInt(props.params.authorId)
        };
    }

    componentDidMount() {
        this.fetchAuthor(this.state.authorId);
    }

    componentWillReceiveProps(nextProps) {
        if (this.props.params.authorId !== nextProps.params.authorId) {
            const nextAuthorId = parseInt(props.params.authorId);
            this.setState({
                author: null,
                authorId: nextAuthorId
            });
            this.fetchAuthor(nextAuthorId);
        }
    }
    async fetchAuthor(authorId) {
        const author = await AuthorService.getAuthor(authorId);
        this.setState({ author });
    }

    render() {
        return (
            <BaseTalks
                basePath={`by-author/${this.state.authorId}`}
                basePathMatch="by-teacher"
                location={this.props.location}
                card={this.state.author &&
                    <AuthorTalksCard author={this.state.author} />}
                filters={{ authorId: this.state.authorId }}
            />
        );
    }
}

export default AuthorTalks;
