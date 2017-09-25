import React, { Component } from 'react';
import { translate } from 'react-i18next';
import axios from 'axios';
import createActivityDetector from 'activity-detector';

import './reloader.css';

const inactivityTimeout = 30; // seconds
const pollTimeout = 60 * 60 * 12; // twice per day

class Reloader extends Component {

    constructor(props) {
        super(props);
        this.axiosError = this.axiosError.bind(this);
        this.axiosResponse = this.axiosResponse.bind(this);
        this.clickReload = this.clickReload.bind(this);
        this.stopIdleTimer = this.stopIdleTimer.bind(this);
        this.startIdleTimer = this.startIdleTimer.bind(this);

        this.revision = null;
        this.activityDetector = null;
        this.idleTimer = null;
        this.pollTimer = null;
        this.state = {
            updateAvailable: false,
            timeLeft: null
        };
    }

    componentDidMount() {
        axios.interceptors.response.use(this.axiosResponse, this.axiosError);
        const pollTimer = () => {
            axios.get('/version');
            this.pollTimer = setTimeout(pollTimer, pollTimeout * 1000);
        };
        this.pollTimer = setTimeout(pollTimer, pollTimeout * 1000);
    }

    axiosError(error) {
        return Promise.reject(error);
    }

    axiosResponse(response) {
        const revision = response.headers['app-chunk-hash'];
        if (revision) {
            if (this.revision) {
                if (this.revision !== revision) {
                    this.setState({ updateAvailable: true });
                    this.startActivityTimer();
                }
            } else {
                this.revision = revision;
            }
        }
        return response;
    }

    startActivityTimer() {
        if (this.activityDetector) {
            this.activityDetector.stop();
            this.activityDetector = null;
        }
        this.activityDetector = createActivityDetector({
            timeToIdle: inactivityTimeout * 1000,
            ignoredEventsWhenIdle: [],
            inactivityEvents: []
        });
        this.activityDetector.on('active', this.stopIdleTimer);
        this.activityDetector.on('idle', this.startIdleTimer);
    }

    stopIdleTimer() {
        console.log('stopIdleTimer')
        if (this.idleTimer) {
            clearTimeout(this.idleTimer);
            this.idleTimer = null;
        }
        this.setState({ timeLeft: null });
    }

    startIdleTimer() {
        console.log('startIdleTimer')
        const secondTimer = () => {
            const timeLeft = this.state.timeLeft;
            if (timeLeft !== null) {
                if (timeLeft >= 1) {
                    this.setState({ timeLeft: timeLeft - 1 });
                    this.secondTimer = setTimeout(secondTimer, 1000);
                } else {
                    this.reload();
                }
            }
        };
        this.secondTimer = setTimeout(secondTimer, 1000);
        this.setState({ timeLeft: inactivityTimeout });
    }

    clickReload(event) {
        event.preventDefault();
        this.reload();
    }

    reload() {
        window.location.reload(true);
    }

    render() {
        const { t } = this.props,
            { timeLeft } = this.state;
        if (this.state.updateAvailable) {
            return (
                <div className="reloader">
                    {t('updates available')}
                    <a href="#" className="reload" onClick={this.clickReload}>
                        {t('reload')}
                        {timeLeft !== null && (<span>
                            &nbsp;{t('time left', { timeLeft })}
                        </span>)}
                    </a>
                </div>
            );
        } else {
            return null;
        }
    }
}

export default translate('reloader')(Reloader);
