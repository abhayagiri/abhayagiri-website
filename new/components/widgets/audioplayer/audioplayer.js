import React, { Component } from 'react';

import './audioplayer.css';

class AudioPlayer extends Component {
    load(){}
    play(){}
    pause(){}

    render() {
        return (
            <div id="audioplayer" className="navbar navbar-inverse fixed-bottom">
                <div className="container-fluid">
                    <i className="tab tab-audioplayer float-right icon-chevron-down"></i>
                </div>
                <div className="audioplayer-inner">
                    <div className="container">
                        <div className="row">
                            <div id="info-container" className="col-4">
                                <div className="media">
                                    <span className="float-left">
                                        <img id="speaker" className="media-object" src="/media/images/speakers/speakers_ajahn_pasanno.jpg" data-src="$img/50x50" />
                                    </span>
                                    <div id="text" className="media-body">
                                        <span className="title">Ajahn Pasanno</span>
                                        <div className="author">Take the One Seat</div>
                                        <div className="date">June 3, 2017</div>
                                    </div>
                                </div>
                            </div>
                            <div id="time-container" className="col-4">
                                <div className="row-fluid">
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
                                </div>
                            </div>
                            <div id="volume-container" className="col-4">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default AudioPlayer;