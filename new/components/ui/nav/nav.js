import React, { Component } from 'react';
import { translate } from 'react-i18next';

import PageService from '../../../services/page.service.js';
import './nav.css';

class Nav extends Component {
    render() {
        const pages = PageService.getPages();
        const pagesBlock = pages.map(page => {
            const newPage = page.slug.startsWith('new/');
            const title = this.props.i18n.language === 'en' ? page.titleEn : page.titleTh;
            const buttonId = 'btn-' + (newPage ? page.slug.substring(4) : page.slug);
            const onClick = '';
            return (
                <div className="brick" key={buttonId}>
                    <a href={'/' + page.slug} onClick={onClick}>
                        <div id={buttonId} className="btn-nav">
                            <i className={page.cssClass + ' fa ' + page.icon}></i><br />
                            <span className={page.cssClass + ' title-icon'}>{title}</span>
                        </div>
                    </a>
                </div>
            );
        });

        return (
            <div id="nav-container">
                <div className="container-fluid">
                    <div id="nav">
                        <i className="fa fa-sort-up arrow"></i>
                        {pagesBlock}
                    </div>
                </div>
            </div>
        );
    }
}

const NavWithTranslate = translate()(Nav);

export default NavWithTranslate;
