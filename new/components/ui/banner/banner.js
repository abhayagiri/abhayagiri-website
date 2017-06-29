import React, { Component } from 'react';

import './banner.css';

class Banner extends Component {
    render() {
        return this.props.page ? (
            <div id="banner">
                <div className="title">{this.props.page.title}</div>
                <img src={"/media/images/banner/" + this.props.page.banner}/>
            </div>
        ) : null;
    }
}

export default Banner;