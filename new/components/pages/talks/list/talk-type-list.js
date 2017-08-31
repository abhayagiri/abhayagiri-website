import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import { tp } from '../../../../i18n';
import { talkTypeTalksPath } from '../util';

import './talk-type-list.css';

class TalkTypeList extends Component {
    render() {
        const lng = this.props.i18n.language;
        return (
            <div className="talk-type-list">
                <ul>
                    {this.props.talkTypes.map((talkType, index) => {
                        return (
                            <li key={index}>
                                <Link to={talkTypeTalksPath(talkType, { lng })}>
                                    {tp(talkType, 'title')}
                                </Link>
                            </li>
                        );
                    })}
                </ul>
            </div>
        );
    }
}

TalkTypeList.propTypes = {
    talkTypes: PropTypes.array.isRequired
};

export default translate('talks')(TalkTypeList);
