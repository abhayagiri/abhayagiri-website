import React, { Component } from 'react';
import PropTypes from 'prop-types';
import axios from 'axios';
import i18n from '../../../i18n.js';

import Header from '../header/header.js';
import Banner from '../banner/banner.js';
import Breadcrumb from '../breadcrumb/breadcrumb.js';
import Audioplayer from '../audioplayer/audioplayer';
import Language from '../language/language.js';
import Reloader from '../reloader/reloader';

import PageService from '../../../services/page.service';

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
        this.getPageName(props);
    }

    getLanguage(props) {
        const lng = props.route.lng;
        if (lng !== i18n.lng) {
            i18n.changeLanguage(lng);
        }
    }

    getPageName(props) {
        let routes = props.routes,
            route = routes[routes.length - 1],
            pageName = route && route.name;

        this.setState({
            pageName: pageName
        })
    }

    getNavPage(routes) {
        let parts = this.props.location.pathname.split('/');
        parts.shift(); // Remove first /
        if (parts[0] === 'new') {
            parts.shift();
        }
        const slug = (parts[0] === 'th') ? parts[1] : parts[0],
            routesPage = routes.slice(-1)[0].page,
            page = PageService.getPage(routesPage || slug);

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
            searchText: this.state.searchText,
            pageName: this.state.pageName
        }
    }

    render() {
        const { navPage } = this.state;

        if (!navPage) {
            return null;
        } else {
            return (
                <div className="main">
                    <Language />
                    <Header location={this.props.location} />
                    <Banner page={navPage} />
                    <Breadcrumb
                        params={this.props.params}
                        location={this.props.location}
                        routes={this.props.routes}/>
                    <div >
                        {React.cloneElement(this.props.children, { params: this.props.params })}
                    </div>
                    <Audioplayer />
                    <Reloader />
                </div>
            );
        }
    }
}

Main.childContextTypes = {
    location: React.PropTypes.object,
    navPage: React.PropTypes.object,
    page: React.PropTypes.number,
    searchText: React.PropTypes.string,
    pageName: React.PropTypes.string
}

Main.propTypes = {
    children: PropTypes.node,
    routes: PropTypes.array,
    params: PropTypes.object
};

export default Main;
