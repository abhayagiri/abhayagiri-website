import React, { Component } from 'react';
import { translate } from 'react-i18next';

import { tp } from '../../../i18n';

import './banner.css';

class Banner extends Component {

    getBannerUrl() {
        // TODO
        // this.props.page.banner_url
        return '/media/images/banner/home.jpg';
    }

    render() {
        const page = this.props.page;
        return (
            <div id="banner">
                <div className="title">{tp(page, 'title')}</div>
                <img src={this.getBannerUrl()}/>
            </div>
        );
    }
}

const BannerWithTranslate = translate()(Banner);

export default BannerWithTranslate;
