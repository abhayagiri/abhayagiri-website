import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { withBreadcrumbs } from 'components/ui/breadcrumb/breadcrumb';
import { tp } from 'i18n';
import CategoryList from 'components/shared/categories/category-list/category-list';
import Spinner from 'components/shared/spinner/spinner';
import AuthorService from 'services/author.service';

export class CategoryTeachers extends Component {

    static propTypes = {
        setBreadcrumbs: PropTypes.func.isRequired,
        t: PropTypes.func.isRequired
    }

    constructor(props, context) {
        super(props, context);
        this.state = {
            teachers: null,
            isLoading: true
        }
    }

    componentDidMount() {
        this.updateBreadcrumbs();
        this.fetchTeachers();
    }

    async fetchTeachers() {
        const teachers = await AuthorService.getAuthors({minTalks: 1});
        this.setState({
            teachers: teachers,
            isLoading: false
        });
    }

    updateBreadcrumbs = () => {
        this.props.setBreadcrumbs(() => {
            return [
                {
                    title: this.props.t('teachers'),
                    to: '/talks/teachers'
                }
            ];
        });
    }

    getCategoryList() {
        if (this.state.teachers) {
            return this.state.teachers.map((teacher) => {
                return {
                    imageUrl: teacher.imageUrl,
                    title: tp(teacher, 'title'),
                    href: '/talks/teachers/' + teacher.id + '-' + teacher.slug
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
    withBreadcrumbs(CategoryTeachers)
);
