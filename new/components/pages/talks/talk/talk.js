import React, { Component } from 'react';
import renderHTML from 'react-render-html';

class Talk extends Component {
    render() {
        let talk = this.props.talk;
        console.log(talk);
        return (
            <div className='talk row'>
                <div className='col-7'>
                    <div className='media'>
                        <span className='float-left'>
                            <img className='img-speakers media-object' src={talk.img} data-src='$e_img/50x50' />
                        </span>
                        <div className='media-body'>
                            <span className='title'>
                                <a onclick="document.getElementById('audio-description-{$id}').className = 'row-fluid'">{talk.title}</a>
                            </span>
                            <br />{talk.author}
                            <br /><i>{talk.date}</i>
                        </div>
                    </div>
                </div>
                <div className='col-5'>
                    <span className='btn-group btn-group-media'>";


        {talk.youtube_id ? <a href="$e_youtube_url" target="_blank" class="btn">
                            <i class="icon-film"></i>
                            Watch
        </a> : ''}

                        <button href class="btn" onclick="play(this,'$e_title','$e_author','$e_date','$e_img','$e_mp3');">
                            <i class="icon-play"></i>
                            Play
                        </button>

                        <a href="$e_mp3_url" class="btn">
                            <i class="icon-cloud-download"></i>
                            Download
                        </a>

                    </span>
                </div>

                <div id='audio-description-{$id}' class='row-fluid hidden'>
                    <div class='body'>
                        {renderHTML(talk.body)}
                    </div>
                </div>
                <div class='backtotop phone' onclick='backtotop()'>
                    <span class='pull-right'>
                        <i class='icon-caret-up'></i>
                        Back to Top
                </span>
                </div>
            </div >
        );
    }
}

export default Talk;