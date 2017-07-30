import React, { Component } from 'react';
import PropTypes from 'prop-types';
import axios from 'axios';

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
    }

    componentWillMount() {
        console.log(this.props);
        this.getPage(this.props.routes);
    }

    componentWillReceiveProps(nextProps) {
        this.getPage(nextProps.routes);
    }

    async getPage(routes) {
        let route = routes[routes.length - 1].name.toLowerCase();
        console.log(route);
        let page = await PageService.getPage(route);
 
        this.setState({
            routes: routes,
            page: page
        });
    }

    render() {
        let page = this.state.page;

        return (
            <div className="main">
                <Header />
                <Banner page={this.state.page} />
                <Breadcrumb routes={this.state.routes}/>
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