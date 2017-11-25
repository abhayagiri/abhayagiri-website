import React, { Component } from 'react';
import PropTypes from 'prop-types';

import { tp } from 'i18n';
import Link from 'components/shared/link/link';
import './subpage-list.css';

export default class SubpageList extends Component {

    static propTypes = {
        active: PropTypes.object.isRequired,
        subpages: PropTypes.array.isRequired
    }

    render() {
        const { active, subpages } = this.props;
        return (
            <div id="subnav" className="well">
                <ul className="nav flex-column ">
                    {subpages.map((subpage, index) => {
                        return (
                            <li key={index} className={"nav-item " + ((subpage.id === active.id) && "active")}  >
                                <Link
                                    className="nav-link"
                                    to={`/${subpage.page}/${subpage.subpath}`}>
                                    {tp(subpage, 'title')}
                                </Link>
                            </li>
                        )
                    })}
                </ul>
            </div>
        );
    }
}
