import React, { Component } from 'react';
import PropTypes from 'prop-types';

import BaseTalks from './base-talks';
import TalkTypeTalksCard from './card/talk-type-talks-card';
import TalkTypeService from '../../../services/talk-type.service';
import { parseId } from './util';

class TalkTypeTalks extends Component {

    constructor(props) {
        super(props);
        this.state = {
            talkType: null,
            talkTypeId: parseId(props.params.talkTypeId)
        };
    }

    componentDidMount() {
        this.fetchTalkType(this.state.talkTypeId);
    }

    componentWillReceiveProps(nextProps) {
        if (this.props.params.talkTypeId !== nextProps.params.talkTypeId) {
            const nextTalkTypeid = parseId(nextProps.params.talkTypeId);
            this.setState({
                talkType: null,
                talkTypeId: nextTalkTypeid
            });
            this.fetchTalkType(nextTalkTypeid);
        }
    }

    async fetchTalkType(id) {
        const talkType = await TalkTypeService.getTalkType(id);
        this.setState({ talkType });
    }

    render() {
        const { talkType, talkTypeId } = this.state;
        let basePath = `type/${talkTypeId}`;
        if (talkType) {
            basePath += `-${encodeURIComponent(talkType.slug)}`;
        }
        return (
            <BaseTalks
                basePath={basePath}
                basePathMatch="type"
                location={this.props.location}
                card={talkType &&
                    <TalkTypeTalksCard talkType={talkType} />}
                filters={{ talkTypeId }}
            />
        );
    }
}

export default TalkTypeTalks;
