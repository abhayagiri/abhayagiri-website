import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import { tp } from '../../../../i18n';
import './category-filter.css';

class TalksHeader extends Component {

    isActive() {
        return 'nav-link active';
    }

    render() {
        return (
            <div className="navbar-nav mr-auto">
                {this.props.categories.map((category, key) => {
                    return (<Link
                        key={key} to={category.href}
                        className={this.isActive(category.href)}>
                        {category.title}
                    </Link>)
                })}
            </div>
        );
    }
}

const TalksHeaderWithWrapper = translate('talks')(TalksHeader);

export default TalksHeaderWithWrapper;
