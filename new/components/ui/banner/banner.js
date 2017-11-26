import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { withGlobals } from 'components/shared/globals/globals';
import { tp } from 'i18n';
import './banner.css';

export class Banner extends Component {

    static propTypes = {
        i18n: PropTypes.object.isRequired,
        navPage: PropTypes.object.isRequired,
        t: PropTypes.func.isRequired
    }

    getBannerUrl(slug) {
        slug = slug.replace('new/','');
        return `/img/banner/${slug}.jpg`;
    }

    render() {
        const page = this.props.navPage;

        return (
            <div id="banner">
                <img src={this.getBannerUrl(page.slug)} />
            </div>
        );
    }
}

export default translate()(withGlobals(Banner, 'navPage'));
