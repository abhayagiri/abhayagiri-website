import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';
import { tp, thp } from '../../../../i18n';
import TalkList from '../talk-list/talk-list';
import TalkService from '../../../../services/talk.service';
import AuthorService from '../../../../services/author.service';
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
        this.setState({
            isLoading: true
        });

        await this.fetchTypes(props);
        this.fetchTalks(props);
    }

    async fetchTypes(props) {
        let types = await TypeService.getTypes();
        let currentTypeId = parseInt(props.params.typeId.split(/-(.+)/)[0]);

        types = types.map((type) => {
            if (type.id === currentTypeId) {
                this.type = type;
            }
            return {
                href: '/talks/types/' + type.id + '-' + type.slug,
                title: tp(type, 'title'),
                active: type.id === parseInt(currentTypeId)
            };
        });

        let category = {};
        category.title = this.props.t('latest talks');
        category.imageUrl = '/media/images/themes/Spiritual%20Strengths%20and%20Factors%20of%20Awakening-small.JPG';
        category.links = types;

        this.setState({
            category: category
        });
    }

    async fetchTalks(props) {
        let typeId = parseInt(props.params.typeId.split(/-(.+)/)[0]);

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
            <TalkList
                isLoading={this.state.isLoading}
                talks={this.state.talks}
                category={this.state.category}
                preamble={this.type && thp(this.type, 'descriptionHtml')}
            />
        )
    }
}

TalksByType.contextTypes = {
    page: React.PropTypes.number,
    pageSize: React.PropTypes.number,
    searchText: React.PropTypes.string,
}

export default translate('talks')(TalksByType);