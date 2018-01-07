import React, { Component } from 'react';
import { translate } from 'react-i18next';

import Link from 'components/shared/link/link'
import Nav from 'components/ui/nav/nav';
import Search from 'components/ui/search/search';
import './header.css';

class Header extends Component {

    constructor(props, context) {
        super(props, context);
        this.state = {
            showNav: false,
            showSearch: false
        }
    }

    toggleNav = (e) => {
        e.preventDefault();
        this.setState({
            showNav: !this.state.showNav
        })
    }

    toggleSearch = (e) => {
        e.preventDefault();
        this.setState({
            showSearch: !this.state.showSearch
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
                <div className="header-container container">
                    <div className="header-logo">
                        <Link to="/">
                            <img src={'/img/ui/' + headerImg} />
                        </Link>
                    </div>
                    <div className="btn-container">
                        <div className={'btn-search float-right'}
                                onClick={this.toggleSearch}>
                            <img src={'/img/ui/' + searchImg} />
                        </div>
                        <div className={'btn-menu float-right'}
                                onClick={this.toggleNav}>
                            <img src={'/img/ui/' + menuImg} />
                        </div>
                    </div>
                </div>

                <div className="btn-mobile-container">
                    <div className="btn-group">
                        <button className="btn btn-large btn-secondary btn-mobile-menu"
                                onClick={this.toggleNav}>
                            <i className="fa fa-th"></i> {t('menu')}
                        </button>
                        <button className="btn btn-large btn-secondary btn-mobile-search"
                                onClick={this.toggleSearch}>
                            <i className="fa fa-search"></i> {t('search')}
                        </button>
                    </div>
                </div>

                {this.state.showNav && <Nav />}
                <Search show={this.state.showSearch}/>

            </div>
        );
    }
}

const HeaderWithTranslate = translate('header')(Header);

export default HeaderWithTranslate;
