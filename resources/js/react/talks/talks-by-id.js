import React, { Component } from 'react';
import PropTypes from 'prop-types';

import { pageWrapper } from '../shared/util';
import Spinner from '../shared/spinner';
import Talk from './talk';
import TalkService from '../services/talk.service';

export class TalksById extends Component {

    static propTypes = {
        params: PropTypes.object.isRequired,
    }

    constructor(props, context) {
        super(props, context);
        this.state = {
            talk: null,
            category: null,
            isLoading: true
        }
    }

    componentDidMount() {
        this.fetchTalk(this.props);
    }

    async fetchTalk(props) {
        const
            talkId = parseInt(props.params.talkId),
            talk = await TalkService.getTalk(talkId);
        if (talk) {
            this.setState({
                talk: talk,
                isLoading: false
            });
        }
    }

    render() {
        if (this.state.isLoading) {
            return <Spinner />
        } else {
            return <Talk talk={this.state.talk} full={true} />;
        }
    }
}

export default pageWrapper('talks')(TalksById);
