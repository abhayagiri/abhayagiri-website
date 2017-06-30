import React, { Component } from 'react';

import './subpage-list.css';

class SubpageList extends Component {

    render() {
        return (
            <div id='subnav' className="well" >
                <ul className="nav flex-column ">
                    {this.props.subpages.map(subpage => {
                        return (
                            <li className="nav-item">
                                <a className="nav-link" href="/about/purpose">{subpage.page_title}</a>
                            </li>
                        )
                    })}
                </ul>
            </div>
        );
    }
}

export default SubpageList;