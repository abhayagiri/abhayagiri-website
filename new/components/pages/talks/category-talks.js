import React, { Component } from 'react';
import PropTypes from 'prop-types';

import BaseTalks from './base-talks';
import CategoryTalksCard from './card/category-talks-card';
import CategoryService from '../../../services/category.service';

class CategoryTalks extends Component {

    constructor(props) {
        super(props);
        this.state = {
            category: CategoryService.getCategory(props.params.categorySlug)
        };
    }

    componentWillReceiveProps(nextProps) {
        if (this.props.params.categorySlug !== nextProps.params.categorySlug) {
            this.setState({
                category: CategoryService.getCategory(nextProps.params.categorySlug)
            });
        }
    }

    render() {
        const basePath = `by-category/${this.state.category.slug}`
        return (
            <BaseTalks
                basePath={basePath}
                basePathMatch={basePath}
                location={this.props.location}
                card={<CategoryTalksCard category={this.state.category} />}
                filters={{ categorySlug: this.state.category.slug }}
            />
        );
    }
}

export default CategoryTalks;
