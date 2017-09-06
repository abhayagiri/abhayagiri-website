import React, { Component } from 'react';
import PropTypes from 'prop-types';
import TalkList from '../talk-list/talk-list';
import TalkService from '../../../../services/talk.service';
import Spinner from '../../../widgets/spinner/spinner';

class TalksLatest extends Component {

    constructor() {
        super();

        this.state = {
            talks: []
        }
    }

    componentWillMount(){
        this.fetchTalks(this.props);
    }

    async fetchTalks(props) {
        const result = await TalkService.getTalks({
            searchText: props.searchText,
            page: props.page,
            pageSize: props.pageSize
        });

        this.setState({
            talks: result.result,
            totalPages: result.totalPages
        });
    }

    render() {
        let talks = this.state.talks;

        return (
            talks.length ? <TalkList 
                talks={this.state.talks} 
                totalPages={this.state.totalPages} 
                page={this.state.page}/> : <Spinner/>
        )
    }
}

export default TalksLatest;
