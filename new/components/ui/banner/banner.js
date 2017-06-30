import React, { Component } from 'react';

import './banner.css';

class Banner extends Component {
    render() {
        return this.props.page ? (
            <div id="banner">
                <div className="title">{this.props.page.page_title}</div>
                <img src={this.props.page.banner_url}/>
            </div>
        ) : null;
    }
}

export default Banner;