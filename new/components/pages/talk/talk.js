import React, { Component } from 'react';
import TalksService from '../../../services/talks.service';
import Talk from '../talks/talk/talk';

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
        let talk = this.props.params.talk;
        if (talk) {
            this.getTalk();
        }
    }

    async getTalk() {
        let talk = await TalksService.getTalks({
            url_title: talk
        });

        this.setState({
            talk: talk
        })
    }



    render() {
        let talk = this.state.talk;
        return talk && (
            <div className='talk'>
                <Talk talk={talk} />
            </div >
        );
    }
}

export default TalkPage;
