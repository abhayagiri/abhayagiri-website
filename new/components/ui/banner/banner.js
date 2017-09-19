import React, { Component } from 'react';
import { translate } from 'react-i18next';

import { tp } from '../../../i18n';

import './banner.css';

class Banner extends Component {

    getBannerUrl(slug) {
        slug = slug.replace('new/','');
        return `/img/banner/${slug}.jpg`;
    }

    render() {
        const page = this.props.page;
 console.log(page)
        return (
            <div id="banner">
                <div className="title">{tp(page, 'title')}</div>
               
                <img src={this.getBannerUrl(page.slug)}/>
            </div>
        );
    }
}

const BannerWithTranslate = translate()(Banner);

export default BannerWithTranslate;
