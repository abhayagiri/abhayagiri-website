import React, { Component } from 'react';
import PropTypes from 'prop-types';

import { pageWrapper } from '../shared/util';
import TalkList from './talk-list';
import SubjectService from '../services/subject.service';
import TalkService from '../services/talk.service';

class TalksBySubject extends Component {

    static propTypes = {
        params: PropTypes.object.isRequired,
        t: PropTypes.func.isRequired
    }

    constructor(props, context) {
        super(props, context);
        this.state = {
            subject: null,
            subjectGroup: null,
            talks: null,
            isLoading: true
        }
    }

    componentDidMount() {
        this.fetchData(this.props);
    }

    componentWillReceiveProps(nextProps) {
        this.fetchData(nextProps)
    }

    async fetchData(props) {
        this.setState({
            isLoading: true
        });
        const subject = await this.fetchSubjectAndSubjectGroup(props);
        this.fetchTalks(subject, props);
    }

    async fetchSubjectAndSubjectGroup(props) {
        const
            subjectGroupId = parseInt(props.params.subjectGroupId),
            subjectId = parseInt(props.params.subjectId),
            subjectGroup = await SubjectService.getSubjectGroup(subjectGroupId),
            subject = subjectGroup.subjects.find((s) => {
                return s.id === subjectId;
            });
        if (!subject) {
            this.setState({
                isLoading: false
            });
            throw new Error('Subject ' + subjectGroupId + ' not found in subjects');
        }
        this.setState({ subjectGroup, subject });
        return subject;
    }

    async fetchTalks(subject, props) {
        const talks = await TalkService.getTalks({
            page: props.pageNumber,
            pageSize: 10,
            subjectId: subject.id
        });

        this.setState({
            talks: talks,
            isLoading: false
        });
    }

    getCategory() {
        const { subject, subjectGroup } = this.state;
        if (subject && subjectGroup) {
            return {
                title: this.props.tp(subjectGroup, 'title'),
                imageUrl: subject.imageUrl || subjectGroup.imageUrl,
                links: subjectGroup.subjects.map((s) => {
                    return {
                        href: s.talksPath,
                        title: this.props.tp(s, 'title'),
                        active: s.id === subject.id
                    };
                })
            };
        } else {
            return null;
        }
    }

    render() {
        return (
            <TalkList
                isLoading={this.state.isLoading}
                talks={this.state.talks}
                category={this.getCategory()}
                preamble={this.state.subject && this.props.thp(this.state.subject, 'descriptionHtml')}
            />
        );
    }
}

export default pageWrapper('talks')(TalksBySubject);
