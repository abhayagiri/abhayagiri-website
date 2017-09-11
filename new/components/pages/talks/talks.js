import React, { Component } from 'react';
import PropTypes from 'prop-types';
import CategoryToolbar from '../categories/category-toolbar/category-toolbar';
import './talks.css';

const categories = [
    {
        href: '/new/talks/types/2',
        title: 'Latest'
    },
    {
        href: '/new/talks/teachers',
        title: 'Teachers'
    },
    {
        href: '/new/talks/subjects',
        title: 'Subjects'
    }
];

class TalksPage extends Component {

    constructor() {
        super();

        this.state = {
            category: '',
            searchText: '',
            page: 1,
            pageSize: 10
        };
    }

    componentWillMount() {
        this.getQuery(this.props);
    }

    componentWillReceiveProps(nextProps) {
        this.getQuery(nextProps);
    }

    getQuery(props) {
        let query = props.location.query,
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

    getChildContext() {
        return {
            page: parseInt(this.state.page),
            pageSize: parseInt(this.state.pageSize),
            searchText: this.state.searchText,
        }
    }

    render() {
        return (
            <div>
                <CategoryToolbar categories={categories} />
                <div className="talks container">
                    {React.cloneElement(this.props.children, {
                        params: this.props.params,
                        test: 'test'
                    })}
                </div>
            </div>
        )
    }
}

TalksPage.childContextTypes = {
    page: React.PropTypes.number,
    pageSize: React.PropTypes.number,
    searchText: React.PropTypes.string,
}

export default TalksPage;
