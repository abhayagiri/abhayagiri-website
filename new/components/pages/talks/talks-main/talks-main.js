import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import getTalksCard from '../cards/cards.js';
import AuthorsList from '../authors-list/authors-list';
import TalksList from '../talk-list/talk-list';
import AuthorsService from '../../../../services/authors.service';
import TalksService from '../../../../services/talks.service';
import Spinner from '../../../widgets/spinner/spinner';

const pageSize = 10;

class TalksMain extends Component {

    constructor() {
        super();
        this.state = {
            authors: null,
            talks: null,
            totalPages: null,
            detailsLoading: true
        }
    }

    componentDidMount() {
        this.getDetails(this.props);
    }

    componentWillReceiveProps(nextProps) {
        if (!this.props.scope.equals(nextProps.scope) ||
                this.props.page !== nextProps.page ||
                this.props.searchText !== nextProps.searchText ||
                this.props.i18n.language !== nextProps.i18n.language) {
            this.setState({
                authors: null,
                talks: null,
                detailsLoading: true
            });
            this.getDetails(nextProps);
        }
    }

    async getDetails(props) {
        if (props.scope.name === 'authors') {
            const authors = await AuthorsService.getAuthors();
            this.setState({
                authors: authors,
                detailsLoading: false
            });
        } else {
            const
                filters = this.getFilters(props),
                result = await TalksService.getTalks(filters);
            this.setState({
                talks: result.result,
                totalPages: result.totalPages,
                detailsLoading: false
            });
        }
    }

    getFilters(props) {
        let filters = {
            searchText: props.searchText,
            page: props.page,
            pageSize: pageSize
        }
        Object.assign(filters, props.scope.getFilters());
        return filters;
    }

    renderDetails() {
        if (this.state.authors) {
            return (
                <AuthorsList
                    scope={this.props.scope}
                    authors={this.state.authors}
                />
            );
        } else if (this.state.talks) {
            return (
                <TalksList
                    scope={this.props.scope}
                    talks={this.state.talks}
                    totalPages={this.state.totalPages}
                    page={this.props.page}
                />
            );
        } else {
            return <Spinner />;
        }
    }

    render() {
        return (
            <div className="content container">
                <div className="row">
                    <div className="col-lg-3 hidden-md-down">
                        {getTalksCard(this.props.scope)}
                    </div>
                    <div className="col-lg-9 col-md-12">
                        {this.renderDetails()}
                    </div>
                </div>
            </div>
        );
    }
}

TalksMain.propTypes = {
    scope: PropTypes.object.isRequired,
    searchText: PropTypes.string.isRequired,
    page: PropTypes.number.isRequired
};

const TalksMainWithWrapper = translate('talks')(TalksMain);

export default TalksMainWithWrapper;
