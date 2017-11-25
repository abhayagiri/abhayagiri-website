import React, { Component } from 'react';
import PropTypes from 'prop-types';

import { withGlobals } from 'components/shared/globals/globals';
import { tp } from 'i18n';
import Spinner from 'components/shared/spinner/spinner';
import Talk from 'components/content/talks/talk/talk';
import TalkService from 'services/talk.service';

export class TalksById extends Component {

    static propTypes = {
        params: PropTypes.object.isRequired
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
        this.setState({
            talk: talk,
            isLoading: false
        }, () => {
            props.setGlobal('breadcrumbs', this.getBreadcrumbs);
        });
    }

    getBreadcrumbs = () => {
        const { talk } = this.state;
        return [
            {
                title: tp(talk, 'title'),
                to: '/talks/' + talk.id + '-' + talk.slug
            }
        ];
    }

    render() {
        return (
            !this.state.isLoading ? <Talk talk={this.state.talk} /> : <Spinner />
        )
    }
}

export default withGlobals(TalksById);
