import React, { Component } from 'react';
import PropTypes from 'prop-types';
import axios from 'axios';

import { withBreadcrumbs } from 'components/ui/breadcrumb/breadcrumb';
import { tp } from 'i18n';
import Spinner from 'components/shared/spinner/spinner';
import Talk from 'components/content/talks/talk/talk';
import TalkService from 'services/talk.service';

export class TalksById extends Component {

    static propTypes = {
        params: PropTypes.object.isRequired,
        setBreadcrumbs: PropTypes.func.isRequired
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
        this.updateBreadcrumbs();
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
            }, this.updateBreadcrumbs);
        }
    }

    updateBreadcrumbs = () => {
        this.props.setBreadcrumbs(() => {
        const { talk } = this.state;
            return [
                talk ? {
                    title: tp(talk, 'title'),
                    to: talk.path
                } : null
            ];
        });
    }

    render() {
        if (this.state.isLoading) {
            return <Spinner />
        } else {
            return <Talk talk={this.state.talk} full={true} />;
        }
    }
}

export default withBreadcrumbs(TalksById);
