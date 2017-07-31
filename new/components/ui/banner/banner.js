import React, { Component } from 'react';
import { translate } from 'react-i18next';

import './banner.css';
import menu from '../nav/menu.json';

class Banner extends Component {

    getTitle() {
        // TODO yuck
        // return this.props.page.page_title
        const slug = this.props.page.slug;
        for (let page of menu) {
            let checkSlug = page.slug;
            if (checkSlug.startsWith('new/')) {
                checkSlug = checkSlug.substring(4);
            }
            console.log([slug, checkSlug]);
            if (slug === checkSlug) {
                // TODO refactor
                const key = this.props.i18n.language === 'en' ? 'titleEn' : 'titleTh';
                return page[key];
            }
        }
        return '?';
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
