import React, { Component } from 'react';
import { translate } from 'react-i18next';

import ContactService from 'services/contact.service';
import ReCAPTCHA from 'react-google-recaptcha';

export class Contact extends Component {
    constructor () {
        super();

        this.state = {
            name: '' ,
            email: '' ,
            message: '',
            'g-recaptcha-response': '',
            loading: false
        };

        this.handleChange = this.handleChange.bind(this);
        this.handleReCaptchaChange = this.handleReCaptchaChange.bind(this);
        this.send = this.send.bind(this);
        this.clear = this.clear.bind(this);
    }

    handleChange (field, event) {
        let newState = {};
        newState[field] = event.target.value;
        this.setState(newState);
    }

    handleReCaptchaChange(value) {
        this.setState({
            'g-recaptcha-response': value,
        });
    }

    componentDidMount () {
        console.log('here!');
    }

    clear () {
        console.log('clearing form');
     }

    async send (event) {
        event.preventDefault();

        this.setState({ loading: true });

        let response = await ContactService.send(this.state);

        if (response.success === true) {
            alert(response.message);
            this.resetForm();
        } else {
            alert(Object.keys(response.errors).reduce((content, index) => {
                return content + response.errors[index].join("\n") + "\n";
            }, ''));
        }
    }

    resetForm () {
        window.grecaptcha.reset();
        this.setState({
            name: '',
            email: '',
            message: '',
            loading: false
        });
    }

    render () {
        let disabled = this.state.loading;

        return (
            <div className='contact'>
                <div className='container-fluid'>
                    <form onSubmit={this.send}>
                        <legend>Contact Form</legend>

                        <div className='control-group'>
                            <label className='control-label' htmlFor="name"><b>Name</b></label>
                            <div className='controls'>
                                <input type="text" id="name" placeholder="Name" className='span3' disabled={this.state.loading} value={this.state.name} onChange={this.handleChange.bind(this, 'name')} required />
                            </div>
                        </div>

                        <div className='control-group'>
                            <label className='control-label' htmlFor="inputIcon"><b>Email address</b></label>
                            <div className='controls'>
                                <div className='input-prepend'>
                                    <span className='add-on'><i className='icon-envelope'></i></span>
                                    <input className='span3' type="email" placeholder="Email" disabled={this.state.loading} value={this.state.email} onChange={this.handleChange.bind(this, 'email')} required />
                                </div>
                            </div>
                        </div>
                        <div className='control-group'>
                            <label className='control-label' htmlFor="email"><b>Message</b></label>
                            <div className='controls'>
                                <textarea id="message" rows="12" className='span8' disabled={this.state.loading} value={this.state.message} onChange={this.handleChange.bind(this, 'message')} required></textarea>
                            </div>
                        </div>
                        <div className='control-group'>
                            <label className='control-label'></label>
                            <div className='controls'>
                                <ReCAPTCHA
                                    ref="recaptcha"
                                    sitekey={process.env.NOCAPTCHA_SITEKEY}
                                    onChange={this.handleReCaptchaChange}
                                />
                            </div>
                        </div>

                        <hr />

                        <div className='control-group'>
                            <button type="submit" className='btn btn-large btn-primary' disabled={this.state.loading}>
                                {this.state.loading ? 'Sending' : 'Submit' }
                            </button>

                            <button type="submit" className='btn btn-large' onClick={this.clear}>Cancel</button>
                        </div>
                    </form>

                    <br />
                    <br />
                    <br />
                </div>
            </div>
        );
    }
}

export default Contact;
