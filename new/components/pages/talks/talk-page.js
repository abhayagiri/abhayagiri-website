import React, { Component } from 'react';
import PropTypes from 'prop-types';

import Talk from './talk/talk';
import TalkPageCard from './card/talk-page-card';
import TalkService from '../../../services/talk.service';
import TalksLayout from './layout/talks-layout';
import { locationEquals } from './util';

class TalkPage extends Component {

    constructor(props) {
        super(props);
        this.state = { talk: null };
    }

    componentWillMount() {
        const talkId = parseInt(this.props.params.talkId);
        this.getTalk(talkId);
    }

    async getTalk(talkId) {
        const talk = await TalkService.getTalk(talkId)
        this.setState({ talk });
    }

    render() {
        console.log(this.state.talk);
        return (
            <TalksLayout
                basePathMatch="nothing"
                search={null}
                card={this.state.talk &&
                    <TalkPageCard talk={this.state.talk} />
                }
                details={this.state.talk &&
                    <Talk talk={this.state.talk} />
                }
            />
        );
    }
}

export default TalkPage;
