import React, { Component } from 'react';
import PropTypes from 'prop-types';
import moment from 'moment';

import { pageWrapper } from '../shared/util';
import Link from '../shared/link';

class Talk extends Component {

    static propTypes = {
        full: PropTypes.bool,
        t: PropTypes.func.isRequired,
        talk: PropTypes.object.isRequired
    }

    constructor(props, context) {
        super(props, context);
        this.state = { 'full': this.getFullFromProps(props) };
    }

    componentWillReceiveProps(nextProps) {
        this.setState({ 'full': this.getFullFromProps(nextProps) });
    }

    getFullFromProps(props) {
        return props.full !== undefined ? props.full : false;
    }

    showFull = (e) => {
        e.preventDefault();
        this.setState({ 'full': true });
    }

    onShowTalkFull = (component) => {
        if (component !== this) {
            this.setState({ 'full': false });
        }
    }

    download = (e) => {
        const { talk } = this.props;
        e.stopPropagation();
    }

    play = (e) => {
        const { talk } = this.props;
        e.preventDefault();
        e.stopPropagation();
        window.open(talk.mediaUrl, '_blank');
    }

    watch = (e) => {
        const { talk } = this.props;
        e.preventDefault();
        e.stopPropagation();
        window.open(talk.youtubeVideoUrl, '_blank');
    }

    renderButtons() {
        const { t, talk } = this.props;
        let buttons = [];

        if (talk.mediaUrl) {
            buttons.push({
                href: talk.mediaUrl,
                onClick: this.play,
                icon: 'play',
                text: t('play')
            });
        }

        if (talk.youtubeVideoUrl) {
            buttons.push({
                href: talk.youtubeVideoUrl,
                onClick: this.watch,
                text: t('watch')
            })
        };

        if (talk.mediaUrl) {
            buttons.push({
                href: talk.mediaUrl,
                onClick: this.download,
                download: talk.mediaUrl ? talk.downloadFilename : null,
                icon: 'download',
                text: ''
            });
        }

        return (
            <div className="btn-group" role="group">
                {buttons.map((button, index) => <a
                        className="btn btn-outline-secondary" role="button"
                        onClick={button.onClick} href={button.href}
                        download={button.download} key={index}>
                    {button.icon && <i className={'fa fa-' + button.icon}></i>}
                    {button.text}
                </a>)}
            </div>
        );
    }

    renderTitle() {
        const { talk, tp } = this.props;
        return <Link to={talk.path}>{tp(talk, 'title')}</Link>;
    }

    renderPlaylists() {
        const { t, tp } = this.props;
        const { playlists } = this.props.talk;
        if (playlists && playlists.length) {
            return (
                <span>
                    {playlists.map((playlist, index) => <span key={index}>
                        {!!index && ', '}
                        <Link to={playlist.talksPath}>
                            {tp(playlist, 'title')}
                        </Link>
                    </span>)}
                </span>
            );
        } else {
            return null;
        }
    }

    renderAuthor() {
        const { tp } = this.props;
        const { author } = this.props.talk;
        return <Link to={author.talksPath}>{tp(author, 'title')}</Link>
    }

    renderDate() {
        return moment(this.props.talk.recordedOn).format('MMMM D, YYYY');
    }

    renderLanguage() {
        const { i18n, talk, tp } = this.props;
        if (talk.language.code !== i18n.language) {
            return (
                <span className="badge badge-warning">
                    {tp(talk.language, 'title')}
                </span>
            );
        } else {
            return null;
        }
    }

    renderDescription() {
        if (this.state.full) {
            return this.props.thp(this.props.talk, 'descriptionHtml');
        } else {
            let simple = this.props.tp(this.props.talk, 'descriptionHtml', false);
            simple = (simple || '').replace(/(<([^>]+)>)/g,' ');
            return <span dangerouslySetInnerHTML={{__html: simple}} />;
        }
    }

    renderSubjects() {
        const { t, tp } = this.props;
        const { subjects } = this.props.talk;
        if (subjects && subjects.length) {
            return (
                <span>
                    <span className="heading">
                        {t('subject', { count: subjects.length })}
                    </span>
                    :{' '}
                    {subjects.map((subject, index) => <span key={index}>
                        {!!index && ', '}
                        <Link to={subject.talksPath}>
                            {tp(subject, 'title')}
                        </Link>
                    </span>)}
                </span>
            );
        } else {
            return null;
        }
    }

    render() {
        const
            { t } = this.props,
            talkClassName = 'talk talk-' +
                (this.state.full ? 'full' : 'abbrev');
        return (
            <div className={talkClassName}>
                <div className="leader">
                    <div className="subleader">
                        <div className="image">
                            <img src={this.props.talk.imagePresetUrl} />
                        </div>
                        <div className="meta">
                            <h3 className="title">
                                {this.renderTitle()}
                            </h3>
                            <div className="playlists">
                                {this.renderPlaylists()}
                            </div>
                            <div className="info">
                                <span className="author">
                                    {this.renderAuthor()}
                                </span>
                                ,{' '}
                                <span className="date">
                                    {this.renderDate()}
                                </span>
                                <span className="language">
                                    {this.renderLanguage()}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div className="buttons">
                        {this.renderButtons()}
                    </div>
                </div>
                <div className="description">
                    {this.renderDescription()}
                    <div className="subjects">
                        {this.renderSubjects()}
                    </div>
                </div>
                <div className="show-full" onClick={this.showFull}>
                    <div className="arrow" />
                </div>
            </div>
        );
    }

}

export default pageWrapper('talks')(Talk);
