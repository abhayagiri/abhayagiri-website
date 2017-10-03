import React, { Component } from 'react';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import Link from 'components/widgets/link/link';
import { tp } from '../../../../i18n';
import CategoryList from '../category-list/category-list';
import Spinner from '../../../widgets/spinner/spinner';
import SubjectService from '../../../../services/subject.service';

class CategorySubjects extends Component {

    constructor() {
        super();

        this.state = {
            subjects: [],
            isLoading: true
        }
    }

    componentWillMount() {
        this.fetchSubjectGroups();
    }

    async fetchSubjectGroups() {
        let groupId = this.props.params.subjectGroupId.split(/-(.+)/)[0];
        let subjectGroup = await SubjectService.getSubjectGroup(groupId);
        let subjects = subjectGroup.subjects.map((subject) => {
              return {
                imageUrl: subject.imageUrl,
                title: tp(subject, 'title'),
                href: this.props.location.pathname + '/' + subject.id + '-' + subject.slug
            };
        });

        this.setState({
            subjects: subjects,
            isLoading: false
        })
    }

    render() {
        return !this.state.isLoading ? <CategoryList list={this.state.subjects} /> : <Spinner />;
    }
}

export default translate('talks')(CategorySubjects);
