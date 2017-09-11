import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { tp } from '../../../../i18n';
import TalkList from '../talk-list/talk-list';
import TalkService from '../../../../services/talk.service';
import AuthorService from '../../../../services/author.service';
import Spinner from '../../../widgets/spinner/spinner';
import SubjectService from '../../../../services/subject.service';

class TalksLatest extends Component {

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

    async fetchSubjectGroup(){
        let subjectGroupId = parseInt(this.props.params.subjectGroupId);
        this.subjectGroup = await SubjectService.getSubjectGroup(subjectGroupId);
    }

    async fetchSubjects(props) {
        let currentSubjectId = parseInt(props.params.subjectId);
        let subjects = this.subjectGroup.subjects.map((subject) => {
            return {
                href: '../' + subject.id,
                title: tp(subject, 'title'),
                active: subject.id === currentSubjectId
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
        const talks = await TalkService.getTalks({
            searchText: props.searchText,
            page: props.location.query.p,
            pageSize: 10,
            subjectId: props.params.subjectId
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

export default TalksLatest;
