import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';
import ReactGA from 'react-ga';
// 2017-08-01 This seems to create a conflict with UglifyJS and camelcase.
// import renderHTML from 'react-render-html';

import EventEmitter from '../../../../services/emitter.service';
import { tp } from '../../../../i18n';


import './talk.css';

class Talk extends Component {
    constructor(){
        super();
        this.state = {
            showDescription: true
        }
    }

    toggleDescription(){
        // this.setState({
        //     showDescription: !this.state.showDescription
        // })
    }

    download(talk, e) {
        ReactGA.event({
            category: 'talks',
            action: 'download',
            label: talk.mediaUrl
        });
    }

    play(talk, e) {
        e.preventDefault();
        EventEmitter.emit('play', talk);
        ReactGA.event({
            category: 'talks',
            action: 'play',
            label: talk.mediaUrl
        });
    }

    watch(talk, e) {
        e.preventDefault();
        window.open(talk.youTubeUrl, '_blank');
        ReactGA.event({
            category: 'talks',
            action: 'watch',
            label: talk.youTubeUrl
        });
    }

    render() {
        const { t, talk } = this.props,
            lng = this.props.i18n.language,
            base = '/new/' + (lng === 'en' ? '' : lng + '/') + 'talks/';
        return (
            <div className='talk'>
                <div className="row">
                    <div className='col-sm-12 col-md-7'>
                        <div className='media'>
                            <span className='float-left'>
                                <img className='img-speakers media-object' src={talk.author.imageUrl} />
                            </span>
                            <div className='media-body'>
                                <span className='title'>
                                    <Link to={base + talk.id + '-' + talk.slug}>{talk.title}</Link>
                                </span>
                                <br />{tp(talk.author, 'title')}
                                <br /><i>{talk.date}</i>
                            </div>
                        </div>
                    </div>
                    <div className='col-sm-12 col-md-5'>
                        <div className='spacer hidden-md-up'/>
                        <span className='actions btn-group btn-group-media'>
                            {talk.youTubeUrl ?
                            <a
                                onClick={this.watch.bind(this, talk)}
                                href={talk.youTubeUrl}
                                className="btn btn-secondary">
                                    <i className="fa fa-youtube-play"></i>&nbsp;
                                    {t('watch')}
                            </a> : ''}
                            {talk.mediaUrl ?
                            <a
                                onClick={this.play.bind(this,talk)}
                                href={talk.mediaUrl}
                                className="btn btn-secondary">
                                    <i className="fa fa-play"></i>&nbsp;
                                    {t('play')}
                            </a> : ''}
                            {talk.mediaUrl ?
                            <a onClick={this.download.bind(this, talk)}
                                href={talk.mediaUrl}
                                download={talk.filename}
                                className="btn btn-secondary">
                                    <i className="fa fa-cloud-download"></i>&nbsp;
                                    {t('download')}
                            </a> : ''}
                        </span>
                    </div>
                </div>
                <br/>
                {this.state.showDescription ?
                <div className="row">
                    <div className='col-12'>
                        <div className='body' dangerouslySetInnerHTML={{__html: talk.description}} />
                    </div>
                </div> : ''}
                {/*<div class='backtotop phone' onClick='backtotop()'>
                    <span class='pull-right'>
                        <i class='icon-caret-up'></i>
                        Back to Top
                </span>
                </div>*/}
            </div >
        );
    }
}

const TalkWithTranslate = translate('talks')(Talk);

export default TalkWithTranslate;
