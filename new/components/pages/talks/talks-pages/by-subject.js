import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { tp } from '../../../../i18n';
import TalkList from '../talk-list/talk-list';
import TalkService from '../../../../services/talk.service';
import AuthorService from '../../../../services/author.service';
import Spinner from '../../../widgets/spinner/spinner';
import SubjectService from '../../../../services/subject.service';

class TalksBySubject extends Component {

    constructor() {
        super();

        this.state = {
            talks: null,
            category: {},
            isLoading: true
        }
    }

    async componentWillMount() {
        await this.fetchSubjectGroup();
        this.fetchData(this.props);
    }

    componentWillReceiveProps(nextProps) {
        this.fetchData(nextProps)
    }

    async fetchData(props) {
        await this.fetchSubjects(props);
        await this.fetchTalks(props);
    }

    async fetchSubjectGroup() {
        let subjectGroupId = parseInt(this.props.params.subjectGroupId.split(/-(.+)/)[0]);
        this.subjectGroup = await SubjectService.getSubjectGroup(subjectGroupId);
    }

    async fetchSubjects(props) {
        let currentSubjectId = props.params.subjectId;
        let subjects = this.subjectGroup.subjects.map((subject) => {
            return {
                href: '../' + subject.id + '-' + subject.slug,
                title: tp(subject, 'title'),
                active: subject.id === parseInt(currentSubjectId)
            };
        });

        let category = {
            title: tp(this.subjectGroup, 'title'),
            imagePath: this.subjectGroup.imageUrl,
            links: subjects
        };

        this.setState({
            category: category
        });
    }

    async fetchTalks(props) {
        let subjectGroupId = parseInt(props.params.subjectGroupId.split(/-(.+)/)[0]);

        const talks = await TalkService.getTalks({
            searchText: this.context.searchText,
            page: this.context.page,
            pageSize: 10,
            subjectGroupId: subjectGroupId,
            subjectId: subjectId
        });

        this.setState({
            talks: talks,
            isLoading: false
        });
    }

    render() {
        return (
            !this.state.isLoading ?
                <TalkList
                    talks={this.state.talks}
                    category={this.state.category} /> :
                <Spinner />
        )
    }
}

TalksBySubject.contextTypes = {
    page: React.PropTypes.number,
    searchText: React.PropTypes.string
}

export default TalksBySubject;
