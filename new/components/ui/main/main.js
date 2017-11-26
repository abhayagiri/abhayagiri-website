import React, { Component } from 'react';
import PropTypes from 'prop-types';
import axios from 'axios';

import i18n from 'i18n.js';
import Header from 'components/ui/header/header.js';
import Banner from 'components/ui/banner/banner.js';
import Breadcrumb from 'components/ui/breadcrumb/breadcrumb';
import Audioplayer from 'components/ui/audioplayer/audioplayer';
import Language from 'components/ui/language/language.js';
import Reloader from 'components/ui/reloader/reloader';
import PageService from 'services/page.service';
import './main.css';

class Main extends Component {

    static contextTypes = {
        globals: React.PropTypes.object.isRequired
    }

    static propTypes = {
        children: PropTypes.node.isRequired,
        location: PropTypes.object.isRequired,
        params: PropTypes.object.isRequired,
        route: PropTypes.object.isRequired,
        routes: PropTypes.array.isRequired
    }

    constructor(props, context) {
        super(props, context);
        this.state = {
            navPage: null
        };
    }

    componentDidMount() {
        this.getData(this.props);
    }

    componentWillReceiveProps(nextProps) {
        this.getData(nextProps);
    }

    getData(props) {
        this.getNavPage(props);
        this.getLanguage(props);
        this.getPageAndSearchText(props);
        const { globals } = this.context;
        globals.set('location', props.location);
    }

    getLanguage(props) {
        const { lng } = props.route;
        if (lng !== i18n.lng) {
            i18n.changeLanguage(lng);
        }
    }

    getNavPage(props) {
        let parts = props.location.pathname.split('/');
        parts.shift(); // Remove first /
        if (parts[0] === 'new') {
            parts.shift();
        }
        if (parts[0] === 'th') {
            parts.shift();
        }
        const
            routesPage = props.routes.slice(-1)[0].page,
            navPage = PageService.getPage(routesPage || parts[0]);
        this.setState({ navPage });
        this.context.globals.set('navPage', navPage);
    }

    getPageAndSearchText(props) {
        const
            page = this.getPageFromLocation(props),
            searchText = this.getSearchTextFromLocation(props);
        this.context.globals.set('page', page);
        this.context.globals.set('searchText', searchText);
    }

    getPageFromLocation(props) {
        return parseInt(props.location.query.p) || 1;
    }

    getSearchTextFromLocation(props) {
        const matches = props.location.pathname.match(
            /^\/(?:new\/)?(?:th\/)?talks\/search\/(.+)/
        );
        if (matches) { // talks search
            return matches[1];
        } else {
            return props.location.query.q || '';
        }
    }

    render() {
        if (!this.state.navPage) {
            return null;
        } else {
            return (
                <div className="main">
                    <Language />
                    <Header />
                    <Banner />
                    <Breadcrumb />
                    <div>{this.props.children}</div>
                    <Audioplayer />
                    <Reloader />
                </div>
            );
        }
    }
}

export default Main;
