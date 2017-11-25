import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { withGlobals } from 'components/shared/globals/globals';
import { tp } from 'i18n';
import Link from 'components/shared/link/link';
import SearchFilter from 'components/shared/filters/filter-search/filter-search';
import './filter-bar.css';

export class FilterBar extends Component {

    static propTypes = {
        links: PropTypes.array.isRequired,
        searchTo: PropTypes.oneOfType([
            PropTypes.string,
            PropTypes.func
        ]),
        t: PropTypes.func.isRequired
    }

    constructor(props, context) {
        super(props, context);
        this.state = {
            showCategories: false
        }
    }

    isActiveClass(link) {
        return link.isActive(this.props.location) ?
            'nav-link active' : 'nav-link';
    }

    toggleCategories() {
        this.setState({
            showCategories: !this.state.showCategories
        })
    }

    hideCategories() {
        this.setState({
            showCategories: false
        })
    }

    render() {
        const { t, links } = this.props;

        return (

            <nav className="filter-bar navbar navbar-toggleable-sm navbar-light bg-faded">
                <div className="container">
                    <div className="form-inline my-2 my-lg-0  hidden-md-up float-left">
                        <div className={"dropdown " + (this.state.showCategories && 'show')}>
                            <button
                                onClick={this.toggleCategories.bind(this)}
                                className="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Categories
                            </button>
                            <div className="dropdown-menu"  >
                                {links.map((link, key) => {
                                    return (<Link
                                        key={key} to={link.href}
                                        className={this.isActiveClass(link)}>
                                        {tp(link, 'title')}
                                    </Link>)
                                })}
                            </div>
                        </div>
                    </div>

                    <div className="navbar-nav mr-auto hidden-sm-down">
                        {links.map((link, key) => {
                            return (<Link
                                key={key} to={link.href}
                                className={this.isActiveClass(link)}>
                                {tp(link, 'title')}
                            </Link>)
                        })}
                    </div>

                    {this.props.searchTo &&
                        <SearchFilter searchTo={this.props.searchTo} />}

                </div>

            </nav>

        )
    }
}

export default translate()(withGlobals(FilterBar, 'location'));
