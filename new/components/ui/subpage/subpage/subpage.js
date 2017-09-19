import React, { Component } from 'react';

import { translate } from 'react-i18next';
import { tp } from '../../../../i18n';

import SubpageList from '../subpage-list/subpage-list';

import './subpage.css';

class Subpage extends Component {
    getSubpage(subpages){
        let route = this.props.route;
        return subpages && subpages.filter(subpage => {
            return subpage.slug === route.path;
        })[0];
    }

    render() {
        let subpages = this.context.subpages;
        let subpage = this.getSubpage(subpages);

        return (
            <div className="row">
                <div className="col-2">
                    <SubpageList active={subpage} subpages={subpages} />
                </div>
                <div className='col-10'>
                    <div className="subpage">
                        <legend>{tp(subpage, 'title')}</legend>
                        <div dangerouslySetInnerHTML={{__html: tp(subpage, 'body')}} />
                    </div>
                </div>
            </div>

        );
    }
}

Subpage.contextTypes = {
    subpages: React.PropTypes.Array
}

const TranslatedSubpage = translate('talks')(Subpage);
export default TranslatedSubpage;

