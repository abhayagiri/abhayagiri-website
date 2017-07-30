import React, { Component } from 'react';

import ReactDOMServer from 'react';
import menu from './menu.json';
import './nav.css';

class Nav extends Component {
    render() {
        const pages = menu.map(page => {
            const newPage = page.slug.startsWith('new/');
            const title = page.titleEn; // TODO thai
            const buttonId = 'btn-' + (newPage ? page.slug.substring(4) : page.slug);
            const onclick = '';
            return (
                <div className="brick">
                    <a href={'/' + page.slug} onclick={onclick}>
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
                        {pages}
                    </div>
                </div>
            </div>
        );
    }
}

export default Nav;
