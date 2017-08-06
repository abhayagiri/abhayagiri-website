import React, { Component } from 'react';
import { translate } from 'react-i18next';

import PageService from '../../../services/page.service';
import './banner.css';

class Banner extends Component {

    getTitle() {
        const slug = this.props.page.slug,
              page = PageService.getPage(slug);
        return page.getTitle(this.props.i18n.language);
    }

    getBannerUrl() {
        // TODO
        // this.props.page.banner_url
        return '/media/images/banner/home.jpg';
    }

    render() {
        return this.props.page ? (
            <div id="banner">
                <div className="title">{this.getTitle()}</div>
                <img src={this.getBannerUrl()}/>
            </div>
        ) : null;
    }
}

const BannerWithTranslate = translate()(Banner);

export default BannerWithTranslate;
