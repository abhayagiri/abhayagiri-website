import React, { Component } from 'react';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import Link from 'components/shared/link/link';
import { tp } from '../../../../i18n';
import CategoryList from '../category-list/category-list';
import AuthorService from '../../../../services/author.service';
import Spinner from '../../../shared/spinner/spinner';

class CategoryTeachers extends Component {

    constructor() {
        super();

        this.state = {
            teachers: [],
            isLoading: true
        }
    }

    componentWillMount() {
        this.fetchTeachers();
    }

    async fetchTeachers() {
        let teachers = await AuthorService.getAuthors({minTalks: 1}),
            location = this.props.location;

        teachers = teachers.map((teacher) => {
            return {
                imageUrl: teacher.imageUrl,
                title: tp(teacher, 'title'),
                href: '/talks/teachers/' + teacher.id + '-' + teacher.slug
            };
        });

        this.setState({
            teachers: teachers,
            isLoading: false
        })
    }

    render() {
        const lng = this.props.i18n.language;
        return !this.state.isLoading ? <CategoryList list={this.state.teachers}/> : <Spinner/>;
    }
}

export default translate('talks')(CategoryTeachers);
