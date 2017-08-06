import React, { Component } from 'react';
import PropTypes from 'prop-types';
import axios from 'axios';

import i18n from '../../../i18n.js';
import Header from '../header/header.js';
import Banner from '../banner/banner.js';
import Breadcrumb from '../breadcrumb/breadcrumb.js';
import Audioplayer from '../../widgets/audioplayer/audioplayer';
import PageService from '../../../services/page.service';

import './main.css';

class Main extends Component {
    constructor(props) {
        super(props);
        this.state = {
            page: null,
            routes: []
        };
        const lng = props.route.lng;
        // console.log('main initializing language to ' + lng);
        i18n.changeLanguage(lng);
    }

    componentWillUpdate(nextProps, nextState) {
        // console.log('main componentWillUpdate');
        const nextLng = nextProps.route.lng;
        if (nextLng !== i18n.lng) {
            // console.log('main changing language to ' + nextLng);
            i18n.changeLanguage(nextLng);
        }
    }

    componentWillMount() {
        this.getPage(this.props.routes);
    }

    componentWillReceiveProps(nextProps) {
        this.getPage(nextProps.routes);
    }

    getPage(routes) {
        const parts = this.props.location.pathname.split('/'),
              slug = (parts[2] === 'th') ? parts[3] : parts[2],
              page = PageService.getPage(slug);
        this.setState({
            routes: routes,
            page: page
        });
        console.log(page);
    }

    render() {
        let page = this.state.page;

        return (
            <div className="main">
                <Header location={this.props.location} />
                <Banner page={this.state.page} />
                <Breadcrumb page={this.state.page} routes={this.state.routes}/>
                <div >
                    {React.cloneElement(this.props.children, { params: this.props.params, page: this.state.page })}
                </div>
                <Audioplayer/>
            </div>
        );
    }
}

Main.propTypes = {
    children: PropTypes.node,
    routes: PropTypes.array,
    params: PropTypes.object
};

export default Main;