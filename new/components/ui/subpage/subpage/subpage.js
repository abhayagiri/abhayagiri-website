import React, { Component } from 'react';

import { translate } from 'react-i18next';
import { tp } from '../../../../i18n';


import './subpage.css';

class Subpage extends Component {


    render() {
        let subpage = this.context.subpage || null;
        
        return subpage ? (
            <div className="subpage">
                <legend>{tp(subpage, 'title')}</legend>
                <div dangerouslySetInnerHTML={{ __html: tp(subpage, 'bodyHtml') }} />
            </div>

        ) : (<div></div>);
    }
}

Subpage.contextTypes = {
    subpage: React.PropTypes.object
}

const TranslatedSubpage = translate('talks')(Subpage);
export default TranslatedSubpage;

