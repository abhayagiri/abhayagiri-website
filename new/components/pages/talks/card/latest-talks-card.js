import React, { Component } from 'react';
import { Link } from 'react-router';
import { translate } from 'react-i18next';

import TalksCard from './talks-card';
import { talkTypeTalksPath } from '../util';
import { tp } from '../../../../i18n';

class LatestTalksCard extends Component {
    render() {
        const { t } = this.props;
        const lng = this.props.i18n.language;
        return (
            <TalksCard
                title={t('latest talks')}
                imageUrl="/media/images/themes/Spiritual%20Strengths%20and%20Factors%20of%20Awakening-small.JPG"
            >
                <p>
                    Receive dhamma talks automatically by subscribing through&nbsp;
                    <a href="https://itunes.apple.com/us/podcast/abhayagiri-dhamma-talks/id600138148?mt=2">iTunes</a>,
                    <a href="https://www.youtube.com/c/AbhayagiriBuddhistMonastery">YouTube</a>,
                    or <a href="http://feed.abhayagiri.org/abhayagiri-talks">RSS</a>.
                </p>
                <p>
                    <em>Attention</em>: the newest Abhayagiri Dhamma talks are usually
                    available first on our YouTube channel in the&nbsp;
                    <a href="https://www.youtube.com/playlist?list=PLoNO26iBjavYEcHtuODhEvvRizRgk4Dy_">New Dhamma Talks Playlist</a>.
                </p>
                <p>More talks can be found below...</p>
                {this.props.talkTypes &&
                    <div className="list-group">
                        {this.props.talkTypes.map((talkType) => {
                            if (talkType.id == 2) {
                                return null;
                            }
                            return (<Link
                                    to={talkTypeTalksPath(talkType, { lng })}
                                    className="list-group-item">
                                {tp(talkType, 'title')}
                            </Link>);
                        })}
                    </div>
                }
            </TalksCard>
        );
    }
}

export default translate('talks')(LatestTalksCard);
