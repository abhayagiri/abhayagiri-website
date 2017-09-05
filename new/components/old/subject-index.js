import React, { Component } from 'react';
import PropTypes from 'prop-types';

import SubjectIndexCard from './card/subject-index-card';
import SubjectList from './list/subject-list';
import SubjectService from '../../../services/subject.service';
import TalksLayout from './layout/talks-layout';
import { parseId } from './util';

class SubjectIndex extends Component {

    constructor(props) {
        super(props);
        this.state = {
            subjectGroup: null,
            subjectGroupId: parseId(props.params.subjectGroupId)
        };
    }

    componentDidMount() {
        this.fetchSubjectGroup(this.state.subjectGroupId);
    }

    componentWillReceiveProps(nextProps) {
        if (this.props.params.subjectId !== nextProps.params.subjectId) {
            const nextSubjectGroupId = parseId(nextProps.params.subjectId);
            this.setState({
                subjectGroup: null,
                subjectGroupId: nextSubjectGroupId
            });
            this.fetchSubjectGroup(nextSubjectGroupId);
        }
    }

    async fetchSubjectGroup(subjectGroupId) {
        const subjectGroup = await SubjectService.getSubjectGroup(subjectGroupId);
        this.setState({ subjectGroup });
    }

    render() {
        return (
            <TalksLayout
                basePathMatch="by-subject"
                search={null}
                card={this.state.subjectGroup &&
                    <SubjectIndexCard subjectGroup={this.state.subjectGroup} />}
                details={this.state.subjectGroup &&
                    <SubjectList
                        subjectGroup={this.state.subjectGroup}
                        subjects={this.state.subjectGroup.subjects} />
                }
            />
        );
    }
}

export default SubjectIndex;
