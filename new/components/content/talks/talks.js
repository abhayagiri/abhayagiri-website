import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';
import SubscribeButtons from 'components/shared/button-groups/subscribe-buttons';

import FilterBar from 'components/shared/filters/filter-bar/filter-bar.js';

import './talks.css';

export class TalksPage extends Component {

    static propTypes = {
        searchTo: PropTypes.oneOfType([
            PropTypes.string,
            PropTypes.func
        ]),
        t: PropTypes.func.isRequired
    }

    static links = [
        {
            slug: 'latest',
            href: '/talks/types/2-dhamma-talks',
            isActive: (location) => {
                return /^\/(new\/)?(th\/)?talks\/types/.test(location.pathname);
            }
        },
        {
            slug: 'teachers'
        },
        {
            slug: 'subjects'
        },
        {
            slug: 'collections'
        }
    ];

    getLinks() {
        const { t } = this.props;
        return this.constructor.links.map((link) => {
            link = Object.assign({}, link);
            if (!link.href) {
                link.href = '/talks/' + link.slug;
            }
            if (!link.titleEn) {
                link.titleEn = t(link.slug, { lng: 'en' });
            }
            if (!link.titleTh) {
                link.titleTh = t(link.slug, { lng: 'th' });
            }
            if (!link.isActive) {
                const matcher = RegExp('^/(new/)?(th/)?talks/' + link.slug);
                link.isActive = (location) => {
                    return matcher.test(location.pathname);
                };
            }
            return link;
        });
    }

    render() {
        return (
            <div>
                {/*<FilterBar href='talks/search' links={this.getLinks()} searchTo="/talks/search/" />*/}
                <div className="container" style={{ "marginTop": "10px" }}>
                    <SubscribeButtons></SubscribeButtons>
                </div>
                <div className="talks-container container">
                    {React.cloneElement(this.props.children, {
                        params: this.props.params
                    })}
                </div>
            </div>
        )
    }
}

const TalksWithTranslate = translate('talks')(TalksPage);

export default TalksWithTranslate;
