import React, { Component } from 'react';
import { translate } from 'react-i18next';

import Nav from '../nav/nav.js';
import Language from '../language/language.js';

import './header.css';

class Header extends Component {
    constructor(){
        super();
        this.state = {
            showNav: false
        }
    }

    toggleNav(){
        this.setState({
            showNav: !this.state.showNav
        })
    }

    render() {
        const { t } = this.props;
        const lng = this.props.i18n.language;
        const headerFile = lng === 'en' ? 'header.jpg' : 'thaiheader.jpg';
        return (
            <div id="header" className="clearfix" role="banner">

                <div className="container clearfix">
                    <Language location={this.props.location} />
                    <div id="logo" ><img src={'/media/images/misc/' + headerFile} /></div>
                    <div id="btn-container">
                        <div id={'btn-search-' + lng} className="float-right" />
                        <div id={'btn-menu-' + lng} className="float-right" onClick={this.toggleNav.bind(this)} />
                    </div>
                </div>

                <div id="btn-mobile-container">
                    <div className="btn-group">
                        <button id="btn-mobile-menu" className="btn btn-large btn-inverse" onClick={this.toggleNav.bind(this)}>
                            <i className="fa fa-th"></i> {t('menu')}
                        </button>
                        <button id="btn-mobile-search" className="btn btn-large btn-inverse">
                            <i className="fa fa-search"></i> {t('search')}
                    </button>
                    </div>
                </div>

                {this.state.showNav ? <Nav/> : ''}

            </div>
        );
    }
}

const HeaderWithTranslate = translate('header')(Header);

export default HeaderWithTranslate;
