import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { tp } from '../../../../i18n';
import Talk from '../talk/talk';
import TalkService from '../../../../services/talk.service';
import AuthorService from '../../../../services/author.service';
import Spinner from '../../../shared/spinner/spinner';

class TalksByTeacher extends Component {

    constructor() {
        super();

        this.state = {
            talk: null,
            category: null,
            isLoading: true
        }
    }

    componentWillMount() {
        this.fetchTalk(this.props);
    }

    componentWillReceiveProps(nextProps) {
        this.fetchTalk(nextProps)
    }

    async fetchTalk(props) {
        let talkId = props.params.talkId.split(/-(.+)/)[0];
        const talk = await TalkService.getTalk(talkId);

        this.setState({
            talk: talk,
            isLoading: false
        });
    }

    render() {
        return (
            !this.state.isLoading ? <Talk talk={this.state.talk} /> : <Spinner />
        )
    }
}

TalksByTeacher.contextTypes = {
    page: React.PropTypes.number,
    searchText: React.PropTypes.string
}

export default TalksByTeacher;
