import React, { Component } from 'react';
import PropTypes from 'prop-types';

import AuthorIndexCard from './card/author-index-card';
import AuthorList from './list/author-list';
import AuthorService from '../../../services/author.service';
import TalksLayout from './layout/talks-layout';

class AuthorIndex extends Component {

    constructor(props) {
        super(props);
        this.state = { authors: null };
    }

    componentDidMount() {
        this.fetchAuthors();
    }

    async fetchAuthors() {
        const
            params = { minTalks: 1 },
            authors = await AuthorService.getAuthors(params);
        this.setState({ authors });
    }

    render() {
        return (
            <TalksLayout
                basePathMatch="by-teacher"
                search={null}
                card={<AuthorIndexCard />}
                details={this.state.authors &&
                    <AuthorList authors={this.state.authors} />
                }
            />
        );
    }
}

export default AuthorIndex;
