import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { translate } from 'react-i18next';
import ReactGA from 'react-ga';
import moment from 'moment';

import Link from 'components/shared/link/link';
import EventEmitter from 'services/emitter.service';
import { tp, thp } from 'i18n';

import './talk.css';

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

    // componentDidMount() {
    //     EventEmitter.on('showTalkFull', this.onShowTalkFull);
    // }

    // componentWillUnmount() {
    //     EventEmitter.off('showTalkFull', this.onShowTalkFull);
    // }

    componentWillReceiveProps(nextProps) {
        this.setState({ 'full': this.getFullFromProps(nextProps) });
    }

    getFullFromProps(props) {
        return props.full !== undefined ? props.full : false;
    }

    showFull = (e) => {
        e.preventDefault();
        this.setState({ 'full': true });
        // EventEmitter.emit('showTalkFull', this);
    }

    onShowTalkFull = (component) => {
        if (component !== this) {
            this.setState({ 'full': false });
        }
    }

    download = (e) => {
        const { talk } = this.props;
        e.stopPropagation();
        ReactGA.event({
            category: 'talks',
            action: 'download',
            label: talk.mediaUrl
        });
    }

    play = (e) => {
        const { talk } = this.props;
        e.preventDefault();
        e.stopPropagation();
        EventEmitter.emit('play', talk);
        ReactGA.event({
            category: 'talks',
            action: 'play',
            label: talk.mediaUrl
        });
    }

    watch = (e) => {
        const { talk } = this.props;
        e.preventDefault();
        e.stopPropagation();
        window.open(talk.youtubeUrl, '_blank');
        ReactGA.event({
            category: 'talks',
            action: 'watch',
            label: talk.youtubeUrl
        });
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

        if (talk.youtubeUrl) {
            buttons.push({
                href: talk.youtubeUrl,
                onClick: this.watch,
                // icon: 'youtube-play',
                text: t('watch')
            })
        };

        if (talk.mediaUrl) {
            buttons.push({
                href: talk.mediaUrl,
                onClick: this.download,
                download: talk.mediaUrl ? talk.downloadFilename : null,
                // icon: 'cloud-download',
                text: t('download')
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
        const { talk } = this.props;
        return <Link to={talk.path}>{tp(talk, 'title')}</Link>;
    }

    renderPlaylists() {
        const { t } = this.props;
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
        const { author } = this.props.talk;
        return <Link to={author.talksPath}>{tp(author, 'title')}</Link>
    }

    renderDate() {
        return moment(this.props.talk.recordedOn).format('MMMM D, YYYY');
    }

    renderLanguage() {
        const { i18n, talk } = this.props;
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
            return thp(this.props.talk, 'descriptionHtml');
        } else {
            let simple = tp(this.props.talk, 'descriptionHtml', false);
            simple = (simple || '').replace(/(<([^>]+)>)/g,' ');
            return <span dangerouslySetInnerHTML={{__html: simple}} />;
        }
    }

    renderSubjects() {
        const { t } = this.props;
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
            <div className={talkClassName} onClick={this.showFull}>
                <div className="leader">
                    <div className="subleader">
                        <div className="image">
                            <img src={this.props.talk.author.imageUrl} />
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
                <div className="show-full">
                    <div className="arrow" />
                </div>
            </div>
        );
    }

}

export default translate('talks')(Talk);
