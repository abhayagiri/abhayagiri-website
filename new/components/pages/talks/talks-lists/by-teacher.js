import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { tp } from '../../../../i18n';
import TalkList from '../talk-list/talk-list';
import CategoryCard from '../../categories/category-card/category-card';
import TalkService from '../../../../services/talk.service';
import AuthorService from '../../../../services/author.service';
import Spinner from '../../../widgets/spinner/spinner';

class TalksLatest extends Component {

    constructor() {
        super();

        this.state = {
            talks: [],
            author: {}
        }
    }

    componentWillMount() {
        this.fetchData(this.props);
    }

    async fetchData(props) {
        await this.fetchAuthor(props.params.authorId);
        await this.fetchTalks(props);
    }

    async fetchAuthor(authorId) {
        let author = await AuthorService.getAuthor(authorId),
            category = {
                imagePath: author.imageUrl,
                title: tp(author, 'title'),
            };

        this.setState({ author, category });
    }

    async fetchTalks(props) {
        const result = await TalkService.getTalks({
            searchText: props.searchText,
            page: props.page,
            pageSize: props.pageSize,
            authorId: props.authorId
        });

        this.setState({
            talks: result.result,
            totalPages: result.totalPages
        });
    }

    render() {
        let talks = this.state.talks;

        return (
            talks.length ? <div>
                <div className="row">
                    <div className="col-md-3">
                        <CategoryCard category={this.state.category}/>
                    </div>
                    <div className="col-md-9">
                            <TalkList
                                talks={this.state.talks}
                                totalPages={this.state.totalPages}
                                page={this.state.page} />
                        </div>
                    </div>
                </div> : <Spinner />
                )
    }
}

export default TalksLatest;
