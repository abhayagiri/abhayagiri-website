import React, { Component } from 'react';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import AuthorsService from '../../../../services/authors.service';
import Spinner from '../../../widgets/spinner/spinner';

export class LatestTalksCard extends Component {

    render() {
        return (
            <div className="card">
                <img className="card-img-top" src="/media/images/themes/Spiritual%20Strengths%20and%20Factors%20of%20Awakening-small.JPG" />
                <div className="card-block">
                    <h4 className="card-title">Latest Talks</h4>
                    <div className="card-text">
                        <p>
                            Receive dhamma talks automatically by subscribing through&nbsp;
                            <a href="https://itunes.apple.com/us/podcast/abhayagiri-dhamma-talks/id600138148?mt=2">iTunes</a>,
                            <a href="https://www.youtube.com/c/AbhayagiriBuddhistMonastery">YouTube</a>,
                            or <a href="http://feed.abhayagiri.org/abhayagiri-talks">RSS</a>.
                        </p>
                        <p>
                            <em>Attention</em>: the newest Abhayagiri Dhamma talks are usually
                            available first on our YouTube channel in the&nbsp;
                            <a href="https://www.youtube.com/playlist?list=PLoNO26iBjavYEcHtuODhEvvRizRgk4Dy_">New Dhamma Talks Playlist</a>.
                        </p>
                    </div>
                </div>
            </div>
        );
    }
}

export class TalksByCategoryCard extends Component {

    render() {
        return (
            <div className="card">
                <img className="card-img-top" src={this.props.scope.category.image} />
                <div className="card-block">
                    <h4 className="card-title">{this.props.scope.category.title}</h4>
                    <div className="card-text" dangerouslySetInnerHTML={{ __html: this.props.scope.category.descriptionEn }} />
                </div>
            </div>
        );
    }
}

export class AuthorsCard extends Component {

    render() {
        return (
            <div className="card">
                <img className="card-img-top" src="/media/images/themes/Triple%20Gem%20and%20Thai%20Forest%20Legacy-small.jpg" />
                <div className="card-block">
                    <h4 className="card-title">Talks by Teachers</h4>
                    <div className="card-text">
                        <p>Talks by specific teachers.</p>
                    </div>
                </div>
            </div>
        );
    }
}

class TalksByAuthorCardWithoutTranslate extends Component {

    constructor() {
        super();
        this.state = {
            author: null,
            isLoading: true
        }
    }

    componentDidMount() {
        this.getAuthor();
    }

    async getAuthor() {
        const author = await AuthorsService.getAuthor(this.props.scope.authorId);
        this.setState({
            author: author,
            isLoading: false
        });
    }

    render() {
        const lngKey = (this.props.i18n.language === 'th' ? 'Th' : 'En');
        if (this.state.isLoading) {
            return <Spinner />
        } else {
            return (
                <div className="card">
                    <img className="card-img-top" src={this.state.author.imageUrl} />
                    <div className="card-block">
                        <h4 className="card-title">{this.state.author['title' + lngKey]}</h4>
                        <div className="card-text">
                            <p>Talks by {this.state.author['title' + lngKey]}.</p>
                        </div>
                    </div>
                </div>
            );
        }
    }
}

export const TalksByAuthorCard = translate('talks')(TalksByAuthorCardWithoutTranslate);

export default function getTalksCard(scope) {
    switch (scope.name) {
        case 'latest-talks':
            return <LatestTalksCard scope={scope} />;
        case 'category-talks':
            return <TalksByCategoryCard scope={scope} />;
        case 'authors':
            return <AuthorsCard scope={scope} />;
        case 'author-talks':
            return <TalksByAuthorCard scope={scope} />;
        default:
            throw new Error('Not implemented ' + scope.name);
    }
}
