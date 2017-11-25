import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { withGlobals } from 'components/shared/globals/globals';
import { tp } from 'i18n';
import CategoryList from 'components/shared/categories/category-list/category-list';
import Spinner from 'components/shared/spinner/spinner';
import AuthorService from 'services/author.service';

class CategoryTeachers extends Component {

    static propTypes = {
        params: PropTypes.object.isRequired,
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
        this.fetchTeachers();
        this.props.setGlobal('breadcrumbs', this.getBreadcrumbs);
    }

    async fetchTeachers() {
        const teachers = await AuthorService.getAuthors({minTalks: 1});
        this.setState({
            teachers: teachers,
            isLoading: false
        });
    }

    getBreadcrumbs = () => {
        return [
            {
                title: this.props.t('teachers'),
                to: '/talks/teachers'
            }
        ];
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
    withGlobals(CategoryTeachers)
);
