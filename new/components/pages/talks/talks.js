import React, { Component } from 'react';
import PropTypes from 'prop-types';
import CategoryToolbar from '../categories/category-toolbar/category-toolbar';
import './talks.css';

const categories = [
    {
        href: '/new/talks/latest',
        title: 'Latest'
    },
    {
        href: '/new/talks/category/teachers',
        title: 'Teachers'
    },
    {
        href: '/new/talks/category/subjects',
        title: 'Subjects'
    },
    {
        href: '/new/talks/category/collections',
        title: 'Collections'
    },
    {
        href: '/new/talks/category/types',
        title: 'Types'
    }
];

class TalksPage extends Component {

    constructor() {
        super();

        this.state = {
            category: '',
            searchText: '',
            page: 0,
            pageSize: 10
        };
    }

    componentWillMount() {
        let query = this.props.location.query,
            page = this.getPage(query),
            searchText = this.getSearchText(query);

        this.setState({
            page: page,
            searchText: searchText
        })
    }

    getSearchText(query) {
        return query.q || '';
    }

    getPage(query) {
        return parseInt(query.p) || 1;
    }

    render() {
        return (
            <div>
                <CategoryToolbar categories={categories}/>
                <div className="talks container">
                    {React.cloneElement(this.props.children, {
                        page: this.state.page,
                        pageSize: this.state.pageSize,
                        searchText: this.state.searchText
                    })}
                </div>
            </div>
        )
    }
}

export default TalksPage;
