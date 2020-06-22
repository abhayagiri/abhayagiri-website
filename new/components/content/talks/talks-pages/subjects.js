import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { tp } from 'i18n';
import CategoryList from 'components/shared/categories/category-list/category-list';
import Spinner from 'components/shared/spinner/spinner';
import SubjectService from 'services/subject.service';

export class CategorySubjects extends Component {

    static propTypes = {
        params: PropTypes.object.isRequired,
        t: PropTypes.func.isRequired
    }

    constructor(props, context) {
        super(props, context);
        this.state = {
            subjectGroup: null,
            isLoading: true
        }
    }

    componentDidMount() {
        this.fetchSubjectGroups(this.props);
    }

    componentWillReceiveProps(nextProps) {
        this.fetchSubjectGroups(nextProps);
    }

    async fetchSubjectGroups(props) {
        const
            groupId = parseInt(props.params.subjectGroupId),
            subjectGroup = await SubjectService.getSubjectGroup(groupId);
        this.setState({
            subjectGroup: subjectGroup,
            isLoading: false
        });
    }

    getCategoryList() {
        const { subjectGroup } = this.state;
        if (subjectGroup) {
            return subjectGroup.subjects.map((subject) => {
                return {
                    imageUrl: subject.imageUrl,
                    title: tp(subject, 'title'),
                    href: subject.talksPath
                };
            });
        } else {
            return [];
        }
    }

    render() {
        return !this.state.isLoading ? <CategoryList list={this.getCategoryList()} /> : <Spinner />;
    }
}

export default translate('talks')(CategorySubjects);
