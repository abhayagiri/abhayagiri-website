import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

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
        let subjectGroups = await SubjectService.getSubjectGroups();
        
        subjectGroups = subjectGroups.map((subject) => {
            return {
                imagePath: subject.imageUrl,
                title: tp(subject, 'title'),
                href: location.pathname + '/' + subject.id
            };
        });

        this.setState({
            subjectGroups: subjectGroups,
            isLoading: false
        })
    }

    render() {
        const lng = this.props.i18n.language;
        return !this.state.isLoading ? <CategoryList list={this.state.subjectGroups} /> : <Spinner />;
    }
}

export default translate('talks')(CategorySubjects);
