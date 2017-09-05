import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import { tp } from '../../../../i18n';
import './category-toolbar.css';

class TalksHeader extends Component {

    componentWillMount() {
        console.log(this.props.categories);
    }

    isActive() {
        return 'nav-link active';
    }

    render() {
        return (
            <nav className="talks-header navbar navbar-toggleable-sm navbar-light bg-faded">
                <div className="container">
                    <div className="navbar-collapse" id="navbarNavAltMarkup">
                        <div className="navbar-nav mr-auto">
                            {this.props.categories.map((category, key) => {
                                return (<Link
                                    key={key} to={category.href}
                                    className={this.isActive(category.href)}>
                                    {category.title}
                                </Link>)
                            })}
                        </div>
                    </div>
                </div>
            </nav>

        );
    }
}

const TalksHeaderWithWrapper = translate('talks')(TalksHeader);

export default TalksHeaderWithWrapper;
