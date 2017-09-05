import React, { Component } from 'react';
import PropTypes from 'prop-types';

import SubjectGroupIndexCard from './card/subject-group-index-card';
import SubjectGroupList from './list/subject-group-list';
import SubjectService from '../../../services/subject.service';
import TalksLayout from './layout/talks-layout';

class SubjectGroupIndex extends Component {

    constructor(props) {
        super(props);
        this.state = { subjectGroups: null };
    }

    componentDidMount() {
        this.fetchSubjectGroups();
    }

    async fetchSubjectGroups() {
        const subjectGroups = await SubjectService.getSubjectGroups();
        this.setState({ subjectGroups });
    }

    render() {
        return (
            <TalksLayout
                basePathMatch="by-subject"
                search={null}
                card={<SubjectGroupIndexCard />}
                details={this.state.subjectGroups &&
                    <SubjectGroupList subjectGroups={this.state.subjectGroups} />
                }
            />
        );
    }
}

export default SubjectGroupIndex;
