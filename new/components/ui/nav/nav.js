import React, { Component } from 'react';
import PropTypes from 'prop-types';

import { withGlobals } from 'components/shared/globals/globals';
import { tp } from 'i18n';
import { localizePathname } from 'components/shared/link/link';
import PageService from 'services/page.service';
import './nav.css';

class Nav extends Component {

    static propTypes = {
        location: PropTypes.object.isRequired
    }

    pageLink(page) {
        const pathname = this.props.location.pathname;
        const isActive = pathname.indexOf(page.slug) > -1;
        return (
            <div className="brick" key={page.slug}>
                <a href={this.getPath(page)}>
                    <div className={"btn-nav " + (isActive && "active")}>
                        <i className={page.cssClass + ' fa ' + page.icon}></i><br />
                        <span className={page.cssClass + ' title-icon'}>{tp(page, 'title')}</span>
                    </div>
                </a>
            </div>
        );
    }

    getPath(page) {
        const useNew = page.slug === 'new/talks';
        return localizePathname('/' + page.slug, null, useNew);
    }

    render() {
        const pages = PageService.getPages();

        return (
            <div className="nav-container container">
                <div id="nav">
                    <i className="fa fa-sort-asc arrow"></i>
                    {pages.map(page => this.pageLink(page))}
                </div>
            </div>
        );
    }
}

export default withGlobals(Nav, 'location');
