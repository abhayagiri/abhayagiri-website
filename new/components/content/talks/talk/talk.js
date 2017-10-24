import React, { Component } from 'react';
import Link from 'components/shared/link/link';
import { translate } from 'react-i18next';
import ReactGA from 'react-ga';
import moment from 'moment';

// 2017-08-01 This seems to create a conflict with UglifyJS and camelcase.
// import renderHTML from 'react-render-html';

import EventEmitter from 'services/emitter.service';
import { tp, thp } from '../../../../i18n';

import Media from 'components/shared/media/media';

import './talk.css';

class Talk extends Component {
    constructor() {
        super();
        this.state = {
            showDescription: true
        }
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
        window.open(talk.youtubeUrl, '_blank');
        ReactGA.event({
            category: 'talks',
            action: 'watch',
            label: talk.youtubeUrl
        });
    }

    talkLanguage() {
        const { t, talk, i18n } = this.props;
        if (talk.language.code !== i18n.language) {
            return (<span>&nbsp;
                <span className="text-info">{tp(talk.language, 'title')}</span>
            </span>)
        }
    }

    getButtons(talk) {
        let buttons = [];

        if (talk.youtubeUrl) {
            buttons.push({
                href: talk.youtubeUrl,
                onClick: this.watch.bind(this, talk),
                icon: "youtube-play",
                text: this.props.t('watch')
            })
        }

        if (talk.mediaUrl) {
            buttons.push({
                href: talk.mediaUrl,
                onClick: this.play.bind(this, talk),
                icon: "play",
                text: this.props.t('play')
            });
            
            buttons.push({
                href: talk.mediaUrl,
                onClick: this.download.bind(this, talk),
                download: talk.mediaUrl,
                icon: "cloud-download",
                text: this.props.t('download')
            });
        }

        return buttons;
    }

    render() {
        const { t, talk } = this.props;
        let buttons = this.getButtons(talk);
        
        return (
            <div className='talk'>
                <Media
                    author={tp(talk.author, 'title')}
                    thumbnail={talk.author.imageUrl}
                    href={'/talks/' + talk.id + '-' + talk.slug}
                    title={tp(talk, 'title')}
                    date={moment(talk.recordedOn).format('MMMM D, YYYY')}
                    language={this.talkLanguage()}
                    buttons={buttons}
                    description={thp(talk, 'descriptionHtml')} />
            </div>
        );
    }
}

const TalkWithTranslate = translate('talks')(Talk);

export default TalkWithTranslate;
