import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import { tp } from '../../../../i18n';
import CategoryList from '../category-list/category-list';
import TypeService from '../../../../services/type.service';

class CategoryTypes extends Component {

    constructor() {
        super();

        this.state = {
            types: []
        }
    }

    componentWillMount() {
        this.fetchTeachers();
    }

    async fetchTeachers() {
        let types = await TypeService.getTypes();

        types = types.map((types) => {
            return {
                imagePath: types.imageUrl,
                title: tp(types, 'title')
            };
        });

        this.setState({
            types: types
        })
    }

    render() {
        const lng = this.props.i18n.language;
        return <CategoryList list={this.state.types}/>;
    }
}

export default translate('talks')(CategoryTypes);
