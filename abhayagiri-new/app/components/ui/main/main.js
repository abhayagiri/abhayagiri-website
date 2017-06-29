import React, { Component } from 'react';
import PropTypes from 'prop-types';
import axios from 'axios';

import Header from '../header/header.js';
import Banner from '../banner/banner.js';

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
        let route = routes[routes.length - 1];
        let page = await axios.get('gegsgs');

        this.setState({
            page: {
                title: 'Audio',
                icon: 'icon-volume-up',
                banner: 'audio.jpg'
            }
        });
    }

    render() {
        return (
            <div className="main">
                <Header />
                <Banner page={this.state.page} />
                {/*<div className="page">
                    {React.cloneElement(this.props.children, { params: this.props.params })}
                </div>*/}
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