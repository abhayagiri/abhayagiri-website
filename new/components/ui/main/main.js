import React, { Component } from 'react';
import PropTypes from 'prop-types';
import axios from 'axios';

import i18n from '../../../i18n.js';
import Header from '../header/header.js';
import Banner from '../banner/banner.js';
import Breadcrumb from '../breadcrumb/breadcrumb.js';
import Audioplayer from '../../widgets/audioplayer/audioplayer';
import PageService from '../../../services/page.service';
import Language from '../language/language.js';

import './main.css';

class Main extends Component {
    constructor(props) {
        super(props);

        this.state = {
            page: null,
            routes: []
        };
    }

    componentWillMount() {
        this.getData(this.props);
    }

    componentWillReceiveProps(nextProps) {
        this.getData(nextProps);
    }

    getData(props) {
        this.getNavPage(props.routes);
        this.getLanguage(props);
        this.getQuery(props);
    }

    getLanguage(props) {
        const lng = props.route.lng;
        if (lng !== i18n.lng) {
            i18n.changeLanguage(lng);
        }
    }

    getNavPage(routes) {
        const parts = this.props.location.pathname.split('/'),
            slug = (parts[2] === 'th') ? parts[3] : parts[2],
            routesPage = routes.slice(-1)[0].page,
            page = PageService.getPage(routesPage || slug);
        console.log(page);
        this.setState({
            routes: routes,
            navPage: page
        });
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
        return (query.p && parseInt(query.p)) || 1;
    }

    getChildContext() {
        return {
            location: this.props.location,
            navPage: this.state.navPage,
            page: this.state.page,
            searchText: this.state.searchText
        }
    }

    render() {
        const page = this.state.page;

        if (!page) {
            return null;
        } else {
            return (
                <div className="main">
                    <Language />
                    <Header location={this.props.location} />
                    <Banner page={page} />
                    {/*<Breadcrumb page={page} routes={this.state.routes}/>*/}
                    <div >
                        {React.cloneElement(this.props.children, { params: this.props.params, page: page })}
                    </div>
                    <Audioplayer />
                </div>
            );
        }
    }
}

Main.childContextTypes = {
    location: React.PropTypes.object,
    navPage: React.PropTypes.object,
    page: React.PropTypes.number,
    searchText: React.PropTypes.string
}

Main.propTypes = {
    children: PropTypes.node,
    routes: PropTypes.array,
    params: PropTypes.object
};

export default Main;
