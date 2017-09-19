import React, { Component } from 'react';
import { translate } from 'react-i18next';

import { tp } from '../../../../i18n';

import './subpage-list.css';

class SubpageList extends Component {

    render() {
        return (
            <div id='subnav' className="well" >
                <ul className="nav flex-column ">
                    {this.props.subpages.map((subpage, index) => {
                        return (
                            <li key={index} className="nav-item">
                                <a className="nav-link" href={'/new/' + subpage.page + '/' + subpage.slug}>
                                    {tp(subpage, 'title')}
                                </a>
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
