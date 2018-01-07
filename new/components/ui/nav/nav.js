import React, { Component } from 'react';
import PropTypes from 'prop-types';

import { withGlobals } from 'components/shared/globals/globals';
import { tp } from 'i18n';
import Link, { localizePathname } from 'components/shared/link/link';
import PageService from 'services/page.service';
import './nav.css';

class Nav extends Component {

    static propTypes = {
        location: PropTypes.object.isRequired,
        onLinkClick: PropTypes.func.isRequired,
        visible: PropTypes.bool.isRequired
    }

    renderLink(page) {
        const
            path = this.getPath(page),
            inner = this.renderLinkInner(page),
            onClick = this.props.onLinkClick;
        return (
            <div className="brick" key={page.slug}>
                {page.type === 'new' ?
                    <Link to={path} onClick={onClick}>{inner}</Link> :
                    <a href={path} onClick={onClick}>{inner}</a>
                }
            </div>
        );
    }

    getPath(page) {
        const useNew = page.slug === 'new/talks';
        return localizePathname('/' + page.slug, null, useNew);
    }

    renderLinkInner(page) {
        const
            pathname = this.props.location.pathname,
            isActive = pathname.indexOf(page.slug) > -1;
        return (
            <div className={"btn-nav " + (isActive && "active")}>
                <i className={page.cssClass + ' fa ' + page.icon} />
                <br />
                <span className={page.cssClass + ' title-icon'}>
                    {tp(page, 'title')}</span>
            </div>
        );
    };

    render() {
        if (this.props.visible) {
            const pages = PageService.getPages();
            return (
                <div className="nav-container container">
                    <div id="nav">
                        <i className="fa fa-sort-asc arrow"></i>
                        {pages.map(page => this.renderLink(page))}
                    </div>
                </div>
            );
        } else {
            return null;
        }
    }
}

export default withGlobals(Nav, 'location');
