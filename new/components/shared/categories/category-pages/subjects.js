import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { withGlobals } from 'components/shared/globals/globals';
import { tp } from 'i18n';
import CategoryList from 'components/shared/categories/category-list/category-list';
import Spinner from 'components/shared/spinner/spinner';
import SubjectService from 'services/subject.service';

class CategorySubjects extends Component {

    static propTypes = {
        params: PropTypes.object.isRequired,
        t: PropTypes.func.isRequired
    }

    constructor(props, context) {
        super(props, context);
        this.state = {
            subjectGroup: null,
            isLoading: true
        }
    }

    componentDidMount() {
        this.fetchSubjectGroups();
    }

    async fetchSubjectGroups() {
        const
            groupId = parseInt(this.props.params.subjectGroupId),
            subjectGroup = await SubjectService.getSubjectGroup(groupId);
        this.setState({
            subjectGroup: subjectGroup,
            isLoading: false
        }, () => {
            this.props.setGlobal('breadcrumbs', this.getBreadcrumbs);
        });
    }

    getBreadcrumbs = () => {
        const { subjectGroup } = this.state;
        return [
            {
                title: this.props.t('subjects'),
                to: '/talks/subjects'
            },
            {
                title: tp(subjectGroup, 'title'),
                to: '/talks/subjects/' + subjectGroup.id + '-' + subjectGroup.slug
            }
        ];
    }

    getCategoryList() {
        const { subjectGroup } = this.state;
        if (subjectGroup) {
            return subjectGroup.subjects.map((subject) => {
                return {
                    imageUrl: subject.imageUrl,
                    title: tp(subject, 'title'),
                    href: '/talks/subjects/' + subjectGroup.id + '-' + subjectGroup.slug + '/' + subject.id + '-' + subject.slug
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

export default translate('talks')(
    withGlobals(CategorySubjects)
);
