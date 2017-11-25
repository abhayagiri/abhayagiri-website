import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { withGlobals } from 'components/shared/globals/globals';
import { tp } from 'i18n';
import TalkList from 'components/content/talks/talk-list/talk-list';
import TalkService from 'services/talk.service';
import TypeService from 'services/type.service';

class TalksByType extends Component {

    static propTypes = {
        page: PropTypes.number.isRequired,
        params: PropTypes.object.isRequired,
        searchText: PropTypes.string.isRequired,
        setGlobal: PropTypes.func.isRequired,
        t: PropTypes.func.isRequired
    }

    constructor(props, context) {
        super(props, context);
        this.state = {
            talks: null,
            type: null,
            types: null,
            isLoading: true
        }
    }

    componentDidMount() {
        this.fetchData(this.props);
    }

    componentWillReceiveProps(nextProps) {
        this.fetchData(nextProps)
    }

    async fetchData(props) {
        this.setState({
            isLoading: true
        });
        const type = await this.fetchTypeAndTypes(props);
        this.fetchTalks(type, props);
    }

    async fetchTypeAndTypes(props) {
        const
            typeId = parseInt(props.params.typeId),
            types = await TypeService.getTypes(),
            type = types.find((t) => {
                return t.id === typeId;
            });
        if (!type) {
            this.setState({
                isLoading: false
            });
            throw new Error('Type ' + typeId + ' not found in types');
        }
        this.setState({ type, types }, () => {
            props.setGlobal('breadcrumbs', this.getBreadcrumbs);
        });
        return type;
    }

    async fetchTalks(type, props) {
        const talks = await TalkService.getTalks({
            searchText: props.searchText,
            page: props.page,
            pageSize: 10,
            typeId: type.id,
            latest: true
        });

        this.setState({
            talks: talks,
            isLoading: false
        });
    }

    getBreadcrumbs = () => {
        const { type } = this.state;
        return [
            {
                title: this.props.t('latest'),
                to: '/talks/types'
            },
            {
                title: tp(type, 'title'),
                to: '/talks/types/' + type.id + '-' + type.slug
            }
        ];
    }

    getCategory() {
        const { type, types } = this.state;
        if (type && types) {
            return {
                imageUrl: type.imageUrl,
                title: tp(type, 'title'),
                links: types.map((t) => {
                    return {
                        href: '/talks/types/' + t.id + '-' + t.slug,
                        title: tp(t, 'title'),
                        active: t.id === type.id
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
                preamble={this.type && thp(this.type, 'descriptionHtml')}
            />
        )
    }
}

export default translate('talks')(
    withGlobals(TalksByType, ['page', 'searchText'])
);
