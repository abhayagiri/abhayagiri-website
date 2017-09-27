import React, { Component } from 'react';
import { translate } from 'react-i18next';

import PageService from '../../../services/page.service';
import { tp } from '../../../i18n';
import './nav.css';

class Nav extends Component {
    render() {
        const pages = PageService.getPages();
        const pagesBlock = pages.map(page => {
            const newPage = page.slug.startsWith('new/');
            const buttonId = 'btn-' + (newPage ? page.slug.substring(4) : page.slug);
            const onClick = '';
            return (
                <div className="brick" key={buttonId}>
                    <a href={'/' + page.slug} onClick={onClick}>
                        <div id={buttonId} className="btn-nav">
                            <i className={page.cssClass + ' fa ' + page.icon}></i><br />
                            <span className={page.cssClass + ' title-icon'}>{tp(page, 'title')}</span>
                        </div>
                    </a>
                </div>
            );
        });

        return (
            <div className="nav-container container">
                <div id="nav">
                    <i className="fa fa-sort-asc arrow"></i>
                    {pagesBlock}
                </div>
            </div>
        );
    }
}

Nav.contextTypes = {
    navPage: React.PropTypes.object
}

const NavWithTranslate = translate()(Nav);

export default NavWithTranslate;
