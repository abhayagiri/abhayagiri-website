import React, { Component } from 'react';
import { translate } from 'react-i18next';

import Nav from '../nav/nav.js';

import './header.css';

class Header extends Component {
    constructor() {
        super();
        this.state = {
            showNav: false
        }
    }

    toggleNav() {
        this.setState({
            showNav: !this.state.showNav
        })
    }

    render() {
        const { t, i18n } = this.props;
        const lng = i18n.language;
        const headerImg = 'header-' + lng + '.jpg';
        const searchImg = 'search-' + lng + '.png';
        const menuImg = 'menu-' + lng + '.png';

        return (
            <div id="header" className="clearfix" role="banner">

                <div className="header-container container clearfix">
                    <div id="logo" ><img src={'/img/ui/' + headerImg} /></div>
                    <div id="btn-container">
                        <div className={'btn-search float-right'}><img src={'/img/ui/' + searchImg} /></div>
                        <div className={'btn-menu float-right'} onClick={this.toggleNav.bind(this)}>
                            <img src={'/img/ui/' + menuImg} />
                        </div>
                    </div>
                </div>

                <div id="btn-mobile-container">
                    <div className="btn-group">
                        <button className="btn btn-large btn-secondary btn-mobile-menu" onClick={this.toggleNav.bind(this)}>
                            <i className="fa fa-th"></i> {t('menu')}
                        </button>
                        <button className="btn btn-large btn-secondary btn-mobile-search">
                            <i className="fa fa-search"></i> {t('search')}
                        </button>
                    </div>
                </div>

                {this.state.showNav ? <Nav /> : ''}
            </div>
        );
    }
}

const HeaderWithTranslate = translate('header')(Header);

export default HeaderWithTranslate;
