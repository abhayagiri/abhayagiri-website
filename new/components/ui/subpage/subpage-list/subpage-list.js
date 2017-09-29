import React, { Component } from 'react';
import Link from 'components/widgets/link/link';
import { translate } from 'react-i18next';
import { tp } from '../../../../i18n';

import './subpage-list.css';

class SubpageList extends Component {

    render() {
        const { active } = this.props;
        return (
            <div id='subnav' className="well" >
                <ul className="nav flex-column ">
                    {this.props.subpages.map((subpage, index) => {
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

const SubpageListWithTranslate = translate()(SubpageList);

export default SubpageListWithTranslate;
