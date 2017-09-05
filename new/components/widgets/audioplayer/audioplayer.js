import React, { Component } from 'react';

import EventEmitter from '../../../services/emitter.service';
import { tp } from '../../../i18n';

import './audioplayer.css';

const audioPath = 'https://www.abhayagiri.org/media/audio/';

class AudioPlayer extends Component {
    constructor() {
        super();

        this.state = {
            audio: null,
            isHidden: true
        }
    }

    componentDidMount() {
        EventEmitter.on('play', this.play.bind(this));
    }

    togglePlayer() {
        console.log("toggling player");
        this.setState({
            isHidden: !this.state.isHidden
        });
    }

    async play(audio) {
        console.log(audio);
        if (audio) {
            await this.setState({
                isHidden: false,
                audio: audio
            });

            let track = audioPath + audio.mp3;
            this.audioElement.load(track);
            this.audioElement.play();
        }
    }

    render() {
        let audio = this.state.audio;
        return audio && (
            this.state.audio && <div id="audioplayer" className={"navbar navbar-inverse fixed-bottom " + (this.state.isHidden ? 'hidden' : 'visible')}>
                <div onClick={this.togglePlayer.bind(this)} className="tab-container container-fluid">
                    <i className={"tab tab-audioplayer float-right fa fa-chevron-" + (this.state.isHidden ? "up" : "down")}></i>
                </div>
                <div className="audioplayer-container container">
                    <div className="row">
                        <div id="info-container" className="col-4">
                            <div className="media">
                                <span className="float-left">
                                    <img id="speaker" className="media-object" src={audio.author.imageUrl} />
                                </span>
                                <div id="text" className="media-body">
                                    <div className="title">{audio.title}</div>
                                    <div className="author">{tp(audio.author, 'title')}</div>
                                    <div className="date">{audio.date}</div>
                                </div>
                            </div>
                        </div>
                        <div id="time-container" className="col-4">
                            <audio controls
                                ref={(elm) => { this.audioElement = elm; }}>
                                <source src={audioPath + audio.mp3} type="audio/mpeg" />
                                Your browser does not support the audio tag.
                            </audio>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default AudioPlayer;

{/* <div className="row-fluid">
                                <div className="media">
                                    <span id="buttons" className="float-left">
                                        <button className="btn play" onClick={this.pause.bind(this)}><i className="icon-pause"></i></button>
                                    </span>
                                    <div className="media-body">
                                        <div id="time" className="progress">
                                            <div id="duration" className="bar"></div>
                                            <div id="buffer" className="bar bar-warning"></div>
                                        </div>
                                        <span id="elapsed">00:00:00 / 00:00:00</span>
                                    </div>
                                </div>
                            </div> */}

{/* <div id="volume-container" className="col-4">
                            <div className="media hidden-phone hidden-tablet">
                                <div className="media-body">
                                    <span className="float-right">
                                        <i className="icon-volume-up"></i>
                                    </span>
                                    <div id="volume" className="progress">
                                        <div className="bar"></div>
                                    </div>
                                </div>
                            </div>
                        </div> */}