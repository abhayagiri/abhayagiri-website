import React, { Component } from 'react';
import { translate } from 'react-i18next';
// 2017-08-01 This seems to create a conflict with UglifyJS and camelcase.
// import renderHTML from 'react-render-html';
import EventEmitter from '../../../../services/emitter.service';

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

    play(talk){
        EventEmitter.emit('play', talk);
        console.log("emitted play event");
    }

    render() {
        const { t, talk } = this.props;
        const youTubeUrl = talk.youtube_id ? ('https://youtu.be/' + talk.youtube_id) : null;
        return (
            <div className='talk'>
                <div className="row">

                    <div className='col-sm-12 col-md-7'>
                        <div className='media'>
                            <span className='float-left'>
                                <img className='img-speakers media-object' src={talk.author.imagePath} data-src='$e_img/50x50' />
                            </span>
                            <div className='media-body'>
                                <span className='title' onClick={this.toggleDescription.bind(this)}>
                                    <a href={'/new/talks/' + talk.url_title}>{talk.title}</a>
                                </span>
                                <br />{talk.author.titleEn}
                                <br /><i>{talk.date}</i>
                            </div>
                        </div>
                    </div>
                    <div className='col-sm-12 col-md-5'>
                         <div className='spacer hidden-md-up'/>
                        <span className='actions btn-group btn-group-media'>
                            {youTubeUrl ?
                            <a href={youTubeUrl} target="_blank" className="btn btn-secondary">
                                <i className="fa fa-youtube-play"></i>&nbsp;
                                {t('watch')}
                            </a> : ''}

                            <button className="btn btn-secondary" onClick={this.play.bind(this,talk)}>
                                <i className="fa fa-play"></i>&nbsp;
                                {t('play')}
                            </button>

                            <a href={talk.media_url} download className="btn btn-secondary">
                                <i className="fa fa-cloud-download"></i>&nbsp;
                                {t('download')}
                            </a>
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
