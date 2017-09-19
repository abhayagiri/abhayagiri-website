import React, { Component } from 'react';
import {Link} from 'react-router';

import { translate } from 'react-i18next';

import { tp } from '../../../../i18n';

import './subpage-list.css';

class SubpageList extends Component {

    render() {
        let active = this.props.active;
        return (
            <div id='subnav' className="well" >
                <ul className="nav flex-column ">
                    {this.props.subpages.map((subpage, index) => {
                        return (
                            <li key={index} className={"nav-item " + ((subpage.slug === active.slug) && "active")}  >
                                <Link 
                                    className="nav-link"  
                                    to={'/new/' + subpage.page + '/' + subpage.slug}>
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
