import React, { Component } from 'react';
import TalksService from '../../../services/talks.service';
import Talk from '../talks/talk/talk';

import Spinner from '../../widgets/spinner/spinner';
import './talk.css';

class TalkPage extends Component {

    constructor() {
        super();
        this.state = {
            showDescription: true,
            talk: null
        }
    }

    componentWillMount() {
        const talkId = this.props.params.talkId;
        if (talkId) {
            this.getTalk();
        }
    }

    async getTalk() {
        const talk = await TalksService.getTalk(this.props.params.talkId)
        this.setState({
            talk: talk
        })
    }

    render() {
        const talk = this.state.talk;
        if (talk) {
            return (
                <div className='talk'>
                    <Talk talk={talk} />
                </div>
            )
        } else {
            return <Spinner />
        }
    }
}

export default TalkPage;
