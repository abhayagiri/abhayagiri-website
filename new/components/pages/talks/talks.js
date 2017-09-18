import React, { Component } from 'react';
import PropTypes from 'prop-types';
import CategoryFilter from '../../widgets/categories/category-filter/category-filter';
import SearchFilter from '../../widgets/search-filter/search-filter';

import './talks.css';

const categories = [
    {
        href: '/new/talks/types/2-dhamma-talks',
        title: 'Latest'
    },
    {
        href: '/new/talks/teachers',
        title: 'Teachers'
    },
    {
        href: '/new/talks/subjects',
        title: 'Subjects'
    },
    {
        href: '/new/talks/collections',
        title: 'Collections'
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

    getPageName() {
        let routes = this.props.routes,
            route = routes[routes.length - 1];

        return route && route.name;
    }

    getChildContext() {
        return {
            pageSize: parseInt(this.state.pageSize)
        }
    }

    render() {

        return (
            <div className='talks'>
                <nav className="talks-header navbar navbar-toggleable-sm navbar-light bg-faded">
                    <div className="container">
                        <div className="navbar-collapse" id="navbarNavAltMarkup">
                            <CategoryFilter categories={categories} current={this.getPageName()} />
                            <SearchFilter />
                        </div>
                    </div>
                </nav>
                <div className="talks container">
                    {React.cloneElement(this.props.children, {
                        params: this.props.params
                    })}
                </div>
            </div>
        )
    }
}

TalksPage.childContextTypes = {
    pageSize: React.PropTypes.number
}

export default TalksPage;
