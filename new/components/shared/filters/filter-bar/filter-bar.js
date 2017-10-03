import React, { Component } from 'react';
import PropTypes from 'prop-types';
import Link from 'components/shared/link/link';
import { translate } from 'react-i18next';
import { tp } from '../../../../i18n';
import SearchFilter from '../filter-search/filter-search';

import './filter-bar.css';

class FilterBar extends Component {
    constructor() {
        super();
        this.state = {
            showCategories: false
        }
    }
    isActive(title) {
        return this.context.pageName === title ? 'nav-link active' : 'nav-link';
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
        const { t, i18n } = this.props;

        return (

            <nav className="talks-header navbar navbar-toggleable-sm navbar-light bg-faded">
                <div className="container">
                    <div className="form-inline my-2 my-lg-0  hidden-md-up float-left">
                        <div className={"dropdown " + (this.state.showCategories && 'show')}>
                            <button
                                onClick={this.toggleCategories.bind(this)}

                                className="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Categories
                            </button>
                            <div className="dropdown-menu"  >
                                {this.props.links.map((link, key) => {
                                    return (<Link
                                        key={key} to={link.href}
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
                                key={key} to={link.href}
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

FilterBar.contextTypes = {
    pageName: React.PropTypes.string
}

const FilterBarTranslated = translate('talks')(FilterBar);

export default FilterBarTranslated;
