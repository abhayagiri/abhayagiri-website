import React, { Component } from 'react';
import PropTypes from 'prop-types';
import {Link} from 'react-router';
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
        console.log(base);

        return (

            <nav className="talks-header navbar navbar-toggleable-sm navbar-light bg-faded">
                <div className="container">
                    <div className="navbar-collapse" id="navbarNavAltMarkup">
                        <div className="navbar-nav mr-auto">
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
                </div>
            </nav>

        )
    }
}

const FilterBarTranslated = translate('talks')(FilterBar);

export default FilterBarTranslated;
