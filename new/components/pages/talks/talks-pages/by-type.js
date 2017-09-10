import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { tp } from '../../../../i18n';
import TalkList from '../talk-list/talk-list';
import TalkService from '../../../../services/talk.service';
import AuthorService from '../../../../services/author.service';
import Spinner from '../../../widgets/spinner/spinner';
import TypeService from '../../../../services/type.service';

class TalksLatest extends Component {

    constructor() {
        super();

        this.state = {
            talks: null,
            category: {},
            isLoading: true
        }
    }

    componentWillMount() {
        this.fetchData(this.props);
    }

    componentWillReceiveProps(nextProps) {
        this.fetchData(nextProps)
    }

    async fetchData(props) {
        await this.fetchTypes(props);
        this.fetchTalks(props);
    }

    async fetchTypes(props) {
        let types = await TypeService.getTypes();
        let currentTypeId = this.props.params.typeId;

        types = types.map((type) => {
            return {
                href: '../' + type.id,
                title: tp(type, 'title'),
                active: type.id === currentTypeId
            };
        });

        let category = {};
        category.title = 'Latest Talks';
        category.imagePath = '/media/images/themes/Spiritual%20Strengths%20and%20Factors%20of%20Awakening-small.JPG';
        category.links = types;

        this.setState({
            category: category
        });
    }

    async fetchTalks(props) {
        const talks = await TalkService.getTalks({
            searchText: props.searchText,
            page: props.page,
            pageSize: props.pageSize,
            typeId: props.params.typeId 
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
