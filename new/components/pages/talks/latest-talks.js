import React, { Component } from 'react';
import PropTypes from 'prop-types';

import BaseTalks from './base-talks';
import LatestTalksCard from './card/latest-talks-card';
import TalkTypeService from '../../../services/talk-type.service';

class LatestTalks extends Component {

    constructor(props) {
        super(props);
        this.state = { talkTypes: null };
    }

    componentDidMount() {
        this.fetchTalkTypes();
    }

    async fetchTalkTypes() {
        const talkTypes = await TalkTypeService.getTalkTypes();
        this.setState({ talkTypes });
    }

    render() {
        return (
            <BaseTalks
                basePath="latest"
                basePathMatch="latest"
                location={this.props.location}
                card={<LatestTalksCard talkTypes={this.state.talkTypes} />}
                filters={{ latest: 1, typeId: 2 }}
            />
        );
    }
}

export default LatestTalks;
