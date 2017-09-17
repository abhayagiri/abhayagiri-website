import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { tp } from '../../../../i18n';
import TalkList from '../talk-list/talk-list';
import TalkService from '../../../../services/talk.service';
import AuthorService from '../../../../services/author.service';
import Spinner from '../../../widgets/spinner/spinner';
import TypeService from '../../../../services/type.service';

class TalksByType extends Component {

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
                href: '../' + type.id + '-' + type.slug,
                title: tp(type, 'title'),
                active: type.id === parseInt(currentTypeId)
            };
        });

        types.unshift({
            href: '../all',
            title: 'All',
            active: 'all' === currentTypeId
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
        let typeId = props.params.typeId;
        typeId = typeId === 'all' ? null : parseInt(typeId.split(/-(.+)/)[0]);

        const talks = await TalkService.getTalks({
            searchText: this.context.searchText,
            page: this.context.page,
            pageSize: 10,
            typeId: typeId
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

TalksByType.contextTypes = {
    page: React.PropTypes.number,
    pageSize: React.PropTypes.number,
    searchText: React.PropTypes.string,
}

export default TalksByType;
