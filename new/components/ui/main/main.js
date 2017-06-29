import React, { Component } from 'react';
import PropTypes from 'prop-types';
import axios from 'axios';

import Header from '../header/header.js';
import Banner from '../banner/banner.js';
import Breadcrumb from '../breadcrumb/breadcrumb.js';

import PageService from '../../../services/page.service';

import './main.css';

class Main extends Component {
    constructor(props) {
        super(props);
        this.state = {
            page: null
        };
    }

    componentWillMount() {
        try {
            this.getPage(this.props.routes);
        }
        catch (err) {

        }
    }

    componentWillReceiveProps(nextProps) {
        this.getPage(nextProps.routes);
    }

    async getPage(routes) {
        let route = routes[routes.length - 1].path;
        let page;
console.log(route);
        if (route === 'talks') {
            page = {
                title: 'Talks',
                icon: 'icon-volume-up',
                banner: 'audio.jpg'
            }
        } else {
            page = await PageService.getPage(route);
        }

        this.setState({
            page: page
        });
    }

    render() {
        return (
            <div className="main">
                <Header />
                <Banner page={this.state.page} />
                <Breadcrumb />
                <div className="content container">
                    {React.cloneElement(this.props.children, { params: this.props.params })}
                </div>
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