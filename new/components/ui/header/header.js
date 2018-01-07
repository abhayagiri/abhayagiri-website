import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';
import ClickOutHandler from 'react-onclickout';

import Link from 'components/shared/link/link'
import Nav from 'components/ui/nav/nav';
import Search from 'components/ui/search/search';
import './header.css';

class Header extends Component {

    static propTypes = {
        i18n: PropTypes.object.isRequired,
        t: PropTypes.func.isRequired
    }

    constructor(props, context) {
        super(props, context);
        this.state = {
            navVisible: false,
            searchVisible: false
        }
        this.stayNodes = [ null, null, null ];
    }

    onClickOut = (e) => {
        // Don't close the popup when clicking on these nodes...
        for (const stayNode of this.stayNodes) {
            if (stayNode && stayNode.contains(e.target)) {
                return;
            }
        }
        this.setState({
            navVisible: false,
            searchVisible: false
        });
    }

    onNavLinkClick = (value) => {
        this.setState({ navVisible: false });
    }

    toggleNavVisible = (e) => {
        e.preventDefault();
        this.setState({
            navVisible: !this.state.navVisible,
            searchVisible: false
        });
    }

    toggleSearchVisible = (e) => {
        e.preventDefault();
        this.setState({
            navVisible: false,
            searchVisible: !this.state.searchVisible,
        });
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
                    <div className="btn-container"
                         ref={(n) => { this.stayNodes[0] = n; }}>
                        <div className={'btn-search float-right'}
                                onClick={this.toggleSearchVisible}>
                            <img className="header-nav-button"
                                 src={'/img/ui/' + searchImg} />
                        </div>
                        <div className={'btn-menu float-right'}
                                onClick={this.toggleNavVisible}>
                            <img className="header-nav-button"
                                 src={'/img/ui/' + menuImg} />
                        </div>
                    </div>
                </div>

                <div className="btn-mobile-container"
                     ref={(n) => { this.stayNodes[1] = n; }}>
                    <div className="btn-group">
                        <button className="btn btn-large btn-secondary btn-mobile-menu header-nav-button"
                                onClick={this.toggleNavVisible}>
                            <i className="fa fa-th header-nav-button"></i> {t('menu')}
                        </button>
                        <button className="btn btn-large btn-secondary btn-mobile-search header-nav-button"
                                onClick={this.toggleSearchVisible}>
                            <i className="fa fa-search header-nav-button"></i> {t('search')}
                        </button>
                    </div>
                </div>

                <div ref={(n) => { this.stayNodes[2] = n; }}>
                    <ClickOutHandler onClickOut={this.onClickOut}>
                        <Nav visible={this.state.navVisible}
                            onLinkClick={this.onNavLinkClick} />
                        <Search visible={this.state.searchVisible} />
                    </ClickOutHandler>
                </div>

            </div>
        );
    }
}

const HeaderWithTranslate = translate('header')(Header);

export default HeaderWithTranslate;
