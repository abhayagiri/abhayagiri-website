import React, { Component } from 'react';
import PropTypes from 'prop-types';

import TalkTypeIndexCard from './card/talk-type-index-card';
import TalkTypeList from './list/talk-type-list';
import TalkTypeService from '../../../services/talk-type.service';
import TalksLayout from './layout/talks-layout';

class TalkTypeIndex extends Component {

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
            <TalksLayout
                basePathMatch="by-type"
                search={null}
                card={<TalkTypeIndexCard />}
                details={this.state.talkTypes &&
                    <TalkTypeList talkTypes={this.state.talkTypes} />
                }
            />
        );
    }
}

export default TalkTypeIndex;
