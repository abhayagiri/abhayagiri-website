import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { tp } from 'i18n';
import CategoryList from 'components/shared/categories/category-list/category-list';
import Spinner from 'components/shared/spinner/spinner';
import SubjectService from 'services/subject.service';

export class CategorySubjectGroups extends Component {

    static propTypes = {
        t: PropTypes.func.isRequired
    }

    constructor(props, context) {
        super(props, context);
        this.state = {
            subjectGroups: null,
            isLoading: true
        }
    }

    componentDidMount() {
        this.fetchSubjectGroups();
    }

    async fetchSubjectGroups() {
        const subjectGroups = await SubjectService.getSubjectGroups();
        this.setState({
            subjectGroups: subjectGroups,
            isLoading: false
        });
    }

    getCategoryList() {
        const { subjectGroups } = this.state;
        if (subjectGroups) {
            return subjectGroups.map((subjectGroup) => {
                return {
                    imageUrl: subjectGroup.imageUrl,
                    title: tp(subjectGroup, 'title'),
                    href: subjectGroup.talksPath
                };
            });
        } else {
            return [];
        }
    }

    render() {
        return !this.state.isLoading ? <CategoryList list={this.getCategoryList()} /> : <Spinner />;
    }
}

export default translate('talks')(CategorySubjectGroups);
