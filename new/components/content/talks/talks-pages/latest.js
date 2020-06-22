import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';

import { tp } from 'i18n';
import Link from 'components/shared/link/link';
import Talk from 'components/content/talks/talk/talk';
import Spinner from 'components/shared/spinner/spinner';
import TalkService from 'services/talk.service';
import SubscribeButtons from 'components/shared/button-groups/subscribe-buttons';
import './latest.css';

class LatestTalksCard extends Component {

    static propTypes = {
        description: PropTypes.any.isRequired,
        imagePath: PropTypes.string.isRequired,
        title: PropTypes.any.isRequired,
        to: PropTypes.any.isRequired
    }

    render() {
        return (
            <div className="card">
                <div className="card-img-top">
                    <img src={this.props.imagePath} />
                </div>
                <div className="card-img-overlay">
                    <h4 className="card-title">{this.props.title}</h4>
                </div>
                <div className="card-block">
                    <div className="card-text">{this.props.description}</div>
                </div>
                <Link to={this.props.to} />
            </div>
        );
    }
}

class LatestTalks extends Component {

    static propTypes = {
        t: PropTypes.func.isRequired
    }

    constructor(props, context) {
        super(props, context);
        this.state = {
            data: null,
            isLoading: true
        }
    }

    componentDidMount() {
        this.fetchData(this.props);
    }

    async fetchData(props) {
        this.setState({
            isLoading: true
        });
        const data = await TalkService.getTalksLatest();
        this.setState({
            data: data,
            isLoading: false
        });
    }

    render() {
        if (this.state.isLoading) {
            return <Spinner/>
        };
        const { t } = this.props, { data } = this.state;
        return (
            <div className="container latest-talks">
                <div className="row">
                    <div className="col-lg-9">
                        <div className="main">
                            <div className="header">
                                <h2 className="latest">
                                    {t('latest')} {tp(data.main.playlistGroup, 'title')}
                                </h2>
                                <SubscribeButtons></SubscribeButtons>
                            </div>
                            <div className="talks">
                                {data.main.talks.map((talk, index) =>
                                    <Talk talk={talk} full={index == 0} key={index} />)}
                            </div>
                            <div className="see-more">
                                <Link to={data.main.playlistGroup.latestTalksPath}>
                                    {t('see more')} {tp(data.main.playlistGroup, 'title')}…
                                </Link>
                            </div>
                        </div>
                        <div className="alt">
                            <h2 className="latest">
                                {t('latest')} {tp(data.alt.playlistGroup, 'title')}
                            </h2>
                            <div className="talks">
                                {data.alt.talks.map((talk, index) =>
                                    <Talk talk={talk} key={index} />)}
                            </div>
                            <div className="see-more">
                                <Link to={data.alt.playlistGroup.latestTalksPath}>
                                    {t('see more')} {tp(data.alt.playlistGroup, 'title')}…
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div className="col-lg-3">
                        <div className="row">
                            <div className="col-4 col-lg-12">
                                <LatestTalksCard
                                    description={tp(data.playlists, 'description')}
                                    imagePath={data.playlists.imagePath}
                                    title={t('collections')}
                                    to="/talks/collections"
                                />
                            </div>
                            <div className="col-4 col-lg-12">
                                <LatestTalksCard
                                    description={tp(data.subjects, 'description')}
                                    imagePath={data.subjects.imagePath}
                                    title={t('subjects')}
                                    to="/talks/subjects"
                                />
                            </div>
                            <div className="col-4 col-lg-12">
                                <LatestTalksCard
                                    description={tp(data.authors, 'description')}
                                    imagePath={data.authors.imagePath}
                                    title={t('teachers')}
                                    to="/talks/teachers"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default translate('talks')(LatestTalks);
