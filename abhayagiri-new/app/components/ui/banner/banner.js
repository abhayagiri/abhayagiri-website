import React, { Component } from 'react';

import './banner.css';

class Banner extends Component {
    render() {
        return (
            <div id="banner">
                <div className="title">{this.props.page.title}</div>
                <img src={"/img/banner/" + this.props.page.banner}/>
            </div>
        );
    }
}

export default Banner;