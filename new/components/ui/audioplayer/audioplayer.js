import React, { Component } from 'react';

import EventEmitter from '../../../services/emitter.service';
import { tp } from '../../../i18n';

import moment from 'moment';

import './audioplayer.css';

const audioPath = '/media/audio/';

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
        this.setState({
            isHidden: !this.state.isHidden
        });
    }

    async play(audio) {
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

    renderTitle() {
        return (
            tp(this.state.audio, 'title')
        );
    }

    renderPlaylist() {
        const { playlists } = this.state.audio;
        if (playlists && playlists.length) {
            return (
                tp(playlists[0], 'title')
            );
        } else {
            return '';
        }
    }

    renderAuthor() {
        return (
            tp(this.state.audio.author, 'title')
        );
    }

    renderDate() {
        return (
            moment(this.state.audio.recordedOn).format('MMMM D, YYYY')
        );
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
                                    <div className="title">{this.renderTitle()}</div>
                                    <div className="collection">{this.renderPlaylist()}</div>
                                    <div className="author">{this.renderAuthor()}, {this.renderDate()}</div>
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
