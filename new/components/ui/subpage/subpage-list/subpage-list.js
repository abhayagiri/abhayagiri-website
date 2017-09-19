import React, { Component } from 'react';
import {Link} from 'react-router';

import { translate } from 'react-i18next';

import { tp } from '../../../../i18n';

import './subpage-list.css';

class SubpageList extends Component {

    render() {
        const { active, i18n } = this.props,
            pathPrefix = i18n.lng === 'th' ? 'th/' : '';

        return (
            <div id='subnav' className="well" >
                <ul className="nav flex-column ">
                    {this.props.subpages.map((subpage, index) => {
                        return (
                            <li key={index} className={"nav-item " + ((subpage.id === active.id) && "active")}  >
                                <Link
                                    className="nav-link"
                                    to={'/new/' + pathPrefix + subpage.page + '/' + subpage.subpath}>
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

const SubpageListWithTranslate = translate()(SubpageList);

export default SubpageListWithTranslate;
