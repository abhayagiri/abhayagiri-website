import React, { Component } from 'react';
import { translate } from 'react-i18next';

import './subpage-list.css';

class SubpageList extends Component {

    render() {
        return (
            <div id='subnav' className="well" >
                <ul className="nav flex-column ">
                    {this.props.subpages.map(subpage => {
                        return (
                            <li className="nav-item">
                                <a className="nav-link" href={'/new/' + subpage.page + '/' + subpage.slug}>
                                    {subpage.getTitle(this.props.i18n.language)}
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
