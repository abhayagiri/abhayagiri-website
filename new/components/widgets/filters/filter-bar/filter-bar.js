import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import { tp } from '../../../../i18n';
import SearchFilter from '../filter-search/filter-search';

import './filter-bar.css';

class FilterBar extends Component {

    isActive(title) {
        return this.props.current === title ? 'nav-link active' : 'nav-link';
    }

    render() {
        const { t, i18n } = this.props;
        let lng = i18n.language;
        let base = '/new/' + (lng === 'en' ? '' : lng + '/');

        return (

            <nav className="talks-header navbar navbar-toggleable-sm navbar-light bg-faded">
                <div className="container">
                    <div className="form-inline my-2 my-lg-0  hidden-md-up float-left">
                        <div className="dropdown">
                            <button className="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Categories
                        </button>
                            <div className="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                {this.props.links.map((link, key) => {
                                    return (<Link
                                        key={key} to={base + link.href}
                                        className={this.isActive(link.title)}>
                                        {t(link.title.toLowerCase())}
                                    </Link>)
                                })}
                            </div>
                        </div>
                        <div className="dropdown">
                            <button className="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Sub Categories
                        </button>
                            <div className="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                {this.props.links.map((link, key) => {
                                    return (<Link
                                        key={key} to={base + link.href}
                                        className={this.isActive(link.title)}>
                                        {t(link.title.toLowerCase())}
                                    </Link>)
                                })}
                            </div>
                        </div>
                    </div>

                    <div className="navbar-nav mr-auto hidden-sm-down">
                        {this.props.links.map((link, key) => {
                            return (<Link
                                key={key} to={base + link.href}
                                className={this.isActive(link.title)}>
                                {t(link.title.toLowerCase())}
                            </Link>)
                        })}
                    </div>
                    <SearchFilter />
                </div>

            </nav>

        )
    }
}

const FilterBarTranslated = translate('talks')(FilterBar);

export default FilterBarTranslated;