import React, { Component } from 'react';

import EventEmitter from '../../../services/emitter.service';

import './audioplayer.css';

class AudioPlayer extends Component {
    constructor() {
        super();

        this.state = {
            audio: {
                "id": 6585,
                "title": "Deliverance from the Mind of Wile E. Coyote",
                "author": {
                    "id": 1,
                    "title": "Ajahn Pasanno",
                    "url_title": "",
                    "user": null,
                    "date": null,
                    "status": "Open"
                },
                "url_title": "deliverance-from-the-mind-of-wile-e.-coyote",
                "date": "2017-03-05",
                "language": "English",
                "category": "Dhamma Talk",
                "mp3": "https://www.abhayagiri.org/media/audio/2017-03-05 AP Deliverance from the Mind of Wile E Coyote.mp3",
                "status": "Open",
                "user": 79,
                "youtube_id": "",
                "description": "<p lang=\"zxx\" style=\"margin-bottom: 0in;\"><span style=\"background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;\">Reflecting\r\non Bhikkhu Bodhi\u2019s talk from the previous evening, Luang Por speaks\r\non establishing the\r\ntwo concordant factors of faith and wisdom, and\r\nalso on nourishing our path with the contemplation of anicca, dukkha,\r\nand anatta \u2013 the doorways to the three deliverances of mind\r\n(vimokkha): signlessness, desirelessness, and emptiness. In opening\r\nourselves up to change, shrinking back from craving, and letting go\r\nof I-making and my-making, we move away\r\nfrom what could be called a\r\n\u201cWile E. Coyote lifestyle\u201d\r\nand towards both worldly happiness and a transcendent peace. This talk was offered on March 5,\r\n2017 at Abhayagiri Buddhist Monastery.<\/span><\/p>", "media_url": "\/media\/audio\/2017-03-05 AP Deliverance from the Mind of Wile E Coyote.mp3"
            }
        }
    }

    componentDidMount() {
        EventEmitter.on('play', this.play.bind(this));
    }

    async play(audio) {
        console.log('received play event', audio);
        await this.setState({
            audio: audio
        });

        let track = 'https://www.abhayagiri.org/media/audio/' + audio.mp3;
        this.audioElement.load(track);
        this.audioElement.play();
    }

    render() {
        let audio = this.state.audio;
        let track = 'https://www.abhayagiri.org/media/audio/' + audio.mp3
        return (
            <div id="audioplayer" className="navbar navbar-inverse fixed-bottom">
                <div className="tab-container container-fluid">
                    <i className="tab tab-audioplayer float-right icon-chevron-down"></i>
                </div>
                <div className="audioplayer-container container">
                    <div className="row">
                        <div id="info-container" className="col-4">
                            <div className="media">
                                <span className="float-left">
                                    <img id="speaker" className="media-object" src="/media/images/speakers/speakers_ajahn_pasanno.jpg" data-src="$img/50x50" />
                                </span>
                                <div id="text" className="media-body">
                                    <span className="title">{audio.author.title}</span>
                                    <div className="author">{audio.title}</div>
                                    <div className="date">{audio.date}</div>
                                </div>
                            </div>
                        </div>
                        <div id="time-container" className="col-4">
                            <audio controls
                                ref={(elm) => { this.audioElement = elm; }}>
                                <source src={track} type="audio/mpeg" />
                                Your browser does not support the audio tag.
                            </audio>
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
                        </div>
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
                    </div>
                </div>
            </div>
        );
    }
}

export default AudioPlayer;