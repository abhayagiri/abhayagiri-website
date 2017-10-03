import React, { Component } from 'react';
import PropTypes from 'prop-types';

import SearchTalks from './search/search-talks';
import TalkList from './list/talk-list';
import TalksLayout from './layout/talks-layout';
import TalkService from '../../../services/talk.service';
import { locationEquals } from './util';

const pageSize = 10;

class BaseTalks extends Component {

    constructor(props) {
        super(props);
        this.state = {
            talks: null,
            totalPages: null
        };
    }

    componentDidMount() {
        this.fetchTalks(this.props);
    }

    componentWillReceiveProps(nextProps) {
        if (!locationEquals(this.props.location, nextProps.location)) {
            this.setState({
                talks: null,
                totalPages: null
            });
            this.fetchTalks(nextProps);
        }
    }

    async fetchTalks(props) {
        const result = await TalkService.getTalks(Object.assign({
            searchText: this.getSearchText(props),
            page: this.getPage(props),
            pageSize: pageSize
        }, props.filters));
        this.setState({
            talks: result.result,
            totalPages: result.totalPages
        });
    }

    getSearchText(props) {
        return props.location.query.q || '';
    }

    getPage(props) {
        return parseInt(props.location.query.p) || 1;
    }

    render() {
        return (
            <TalksLayout
                basePathMatch={this.props.basePathMatch}
                search={
                    <SearchTalks
                        basePath={this.props.basePath}
                    />
                }
                card={this.props.card}
                details={this.state.talks &&
                    <TalkList
                        basePath={this.props.basePath}
                        talks={this.state.talks}
                        searchText={this.getSearchText(this.props)}
                        page={this.getPage(this.props)}
                        totalPages={this.state.totalPages}
                    />
                }
            />
        );
    }
}

BaseTalks.propTypes = {
    basePath: PropTypes.string.isRequired,
    basePathMatch: PropTypes.string.isRequired,
    location: PropTypes.object.isRequired,
    card: PropTypes.object,
    filters: PropTypes.object
};

export default BaseTalks;
