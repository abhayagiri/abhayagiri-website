import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import { tp } from '../../../../i18n';
import { subjectGroupPath } from '../util';

class SubjectGroupList extends Component {
    render() {
        const lng = this.props.i18n.language;
        return (
            <div className="subject-group-list">
                <ul>
                    {this.props.subjectGroups.map((subjectGroup, index) => {
                        return (
                            <li key={index}>
                                <Link to={subjectGroupPath(subjectGroup, { lng })}>
                                    {tp(subjectGroup, 'title')}
                                </Link>
                            </li>
                        );
                    })}
                </ul>
            </div>
        );
    }
}

SubjectGroupList.propTypes = {
    subjectGroups: PropTypes.array.isRequired
};

export default translate('talks')(SubjectGroupList);
