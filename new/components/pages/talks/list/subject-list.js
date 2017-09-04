import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import { tp } from '../../../../i18n';
import { subjectPath } from '../util';

class SubjectList extends Component {
    render() {
        const lng = this.props.i18n.language;
        return (
            <div className="subject-group-list">
                <ul>
                    {this.props.subjects.map((subject, index) => {
                        return (
                            <li key={index}>
                                <Link to={subjectPath(this.props.subjectGroup, subject, { lng })}>
                                    {tp(subject, 'title')}
                                </Link>
                            </li>
                        );
                    })}
                </ul>
            </div>
        );
    }
}

SubjectList.propTypes = {
    subjectGroup: PropTypes.object.isRequired,
    subjects: PropTypes.array.isRequired
};

export default translate('talks')(SubjectList);
