import React, { Component } from 'react';
import PropTypes from 'prop-types';

import SubjectService from '../../../services/subject.service';
import SubjectTalksCard from './card/subject-talks-card';
import BaseTalks from './base-talks';
import { parseId } from './util';

class SubjectTalks extends Component {

    constructor(props) {
        super(props);
        this.state = {
            subject: null,
            subjectGroupId: parseId(props.params.subjectGroupId),
            subjectId: parseId(props.params.subjectId)
        };
    }

    componentDidMount() {
        this.fetchSubject(this.state.subjectId);
    }

    componentWillReceiveProps(nextProps) {
        if (this.props.params.subjectId !== nextProps.params.subjectId) {
            const nextSubjectId = parseId(nextProps.params.subjectId);
            this.setState({
                subject: null,
                subjectId: nextSubjectId
            });
            this.fetchSubject(nextSubjectId);
        }
    }

    async fetchSubject(subjectId) {
        const subject = await SubjectService.getSubject(subjectId);
        this.setState({ subject });
    }

    render() {
        return (
            <BaseTalks
                basePath={`by-subject/${this.state.subjectGroupId}/${this.state.subjectId}`}
                basePathMatch="by-subject"
                location={this.props.location}
                card={this.state.subject &&
                    <SubjectTalksCard subject={this.state.subject} />}
                filters={{ subjectId: this.state.subjectId }}
            />
        );
    }
}

export default SubjectTalks;
