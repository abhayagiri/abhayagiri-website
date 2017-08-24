import React, { Component } from 'react';
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
        let talk = this.props.talk;
        return (
            <div className='talk'>
                <div className="row">

                    <div className='col-7'>
                        <div className='media'>
                            <span className='float-left'>
                                <img className='img-speakers media-object' src={talk.img} data-src='$e_img/50x50' />
                            </span>
                            <div className='media-body'>
                                <span className='title' onClick={this.toggleDescription.bind(this)}>
                                    <a>{talk.title}</a>
                                </span>
                                <br />{talk.author.title}
                                <br /><i>{talk.date}</i>
                            </div>
                        </div>
                    </div>

                    <div className='col-5'>
                        <span className='btn-group btn-group-media float-right'>
                            {talk.youtube_id ?
                            <a href="$e_youtube_url" target="_blank" className="btn btn-secondary">
                                <i className="fa fa-youtube-play"></i>&nbsp; Watch
                            </a> : ''}

                            <button className="btn btn-secondary" onClick={this.play.bind(this,talk)}>
                                <i className="fa fa-play"></i>&nbsp;
                                Play
                            </button>

                            <a href="$e_mp3_url" className="btn btn-secondary">
                                <i className="fa fa-cloud-download"></i>&nbsp;
                                Download
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
                {/*<div class='backtotop phone' onclick='backtotop()'>
                    <span class='pull-right'>
                        <i class='icon-caret-up'></i>
                        Back to Top
                </span>
                </div>*/}
            </div >
        );
    }
}

export default Talk;