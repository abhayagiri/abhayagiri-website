import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { withBreadcrumbs } from 'components/ui/breadcrumb/breadcrumb';
import { withGlobals } from 'components/shared/globals/globals';
import { tp, thp } from 'i18n';
import TalkList from 'components/content/talks/talk-list/talk-list';
import SubjectService from 'services/subject.service';
import TalkService from 'services/talk.service';

class TalksBySubject extends Component {

    static propTypes = {
        page: PropTypes.number.isRequired,
        params: PropTypes.object.isRequired,
        searchText: PropTypes.string.isRequired,
        setBreadcrumbs: PropTypes.func.isRequired,
        t: PropTypes.func.isRequired
    }

    constructor(props, context) {
        super(props, context);
        this.state = {
            subject: null,
            subjectGroup: null,
            talks: null,
            isLoading: true
        }
    }

    componentDidMount() {
        this.updateBreadcrumbs();
        this.fetchData(this.props);
    }

    componentWillReceiveProps(nextProps) {
        this.fetchData(nextProps)
    }

    async fetchData(props) {
        this.setState({
            isLoading: true
        });
        const subject = await this.fetchSubjectAndSubjectGroup(props);
        this.fetchTalks(subject, props);
    }

    async fetchSubjectAndSubjectGroup(props) {
        const
            subjectGroupId = parseInt(props.params.subjectGroupId),
            subjectId = parseInt(props.params.subjectId),
            subjectGroup = await SubjectService.getSubjectGroup(subjectGroupId),
            subject = subjectGroup.subjects.find((s) => {
                return s.id === subjectId;
            });
        if (!subject) {
            this.setState({
                isLoading: false
            });
            throw new Error('Subject ' + subjectGroupId + ' not found in subjects');
        }
        this.setState({ subjectGroup, subject }, this.updateBreadcrumbs);
        return subject;
    }

    async fetchTalks(subject, props) {
        const talks = await TalkService.getTalks({
            searchText: props.searchText,
            page: props.page,
            pageSize: 10,
            subjectId: subject.id
        });

        this.setState({
            talks: talks,
            isLoading: false
        });
    }

    updateBreadcrumbs = () => {
        this.props.setBreadcrumbs(() => {
        const { subject, subjectGroup } = this.state;
            return [
                {
                    title: this.props.t('subjects'),
                    to: '/talks/subjects'
                },
                subjectGroup ? {
                    title: tp(subjectGroup, 'title'),
                    to: '/talks/subjects/' + subjectGroup.id + '-' + subjectGroup.slug
                } : null,
                subject ? {
                    title: tp(subject, 'title'),
                    to: '/talks/subjects/' + subjectGroup.id + '-' + subjectGroup.slug + '/' + subject.id + '-' + subject.slug
                } : null
            ];
        });
    }

    getCategory() {
        const { subject, subjectGroup } = this.state;
        if (subject && subjectGroup) {
            return {
                title: tp(subjectGroup, 'title'),
                imageUrl: subject.imageUrl || subjectGroup.imageUrl,
                links: subjectGroup.subjects.map((s) => {
                    return {
                        href: '/talks/subjects/' + subjectGroup.id + '-' + subjectGroup.slug + '/' + s.id + '-' + s.slug,
                        title: tp(s, 'title'),
                        active: s.id === subject.id
                    };
                })
            };
        } else {
            return null;
        }
    }

    render() {
        return (
            <TalkList
                isLoading={this.state.isLoading}
                talks={this.state.talks}
                category={this.getCategory()}
                preamble={this.state.subject && thp(this.state.subject, 'descriptionHtml')}
            />
        );
    }
}

export default translate('talks')(
    withBreadcrumbs(
        withGlobals(TalksBySubject, ['page', 'searchText'])
    )
);
