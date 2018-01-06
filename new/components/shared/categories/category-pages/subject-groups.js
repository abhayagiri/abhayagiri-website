import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { withBreadcrumbs } from 'components/ui/breadcrumb/breadcrumb';
import { tp } from 'i18n';
import CategoryList from 'components/shared/categories/category-list/category-list';
import Spinner from 'components/shared/spinner/spinner';
import SubjectService from 'services/subject.service';

export class CategorySubjectGroups extends Component {

    static propTypes = {
        setBreadcrumbs: PropTypes.func.isRequired,
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
        this.updateBreadcrumbs();
        this.fetchSubjectGroups();
    }

    async fetchSubjectGroups() {
        const subjectGroups = await SubjectService.getSubjectGroups();
        this.setState({
            subjectGroups: subjectGroups,
            isLoading: false
        });
    }

    updateBreadcrumbs = () => {
        this.props.setBreadcrumbs(() => {
            return [
                {
                    title: this.props.t('subjects'),
                    to: '/talks/subjects'
                }
            ];
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

export default translate('talks')(
    withBreadcrumbs(CategorySubjectGroups)
);
