import React, { Component } from 'react';
import PropTypes from 'prop-types';

import { tp, thp } from 'i18n';
import './subpage.css';

export default class Subpage extends Component {

    static propTypes = {
        subpage: PropTypes.object.isRequired
    }

    render() {
        const { subpage } = this.props;
        return (
            <div className="subpage">
                <legend>{tp(subpage, 'title')}</legend>
                <div>{thp(subpage, 'bodyHtml')}</div>
            </div>
        );
    }
}
