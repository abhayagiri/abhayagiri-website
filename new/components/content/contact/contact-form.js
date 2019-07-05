import React, { Component } from 'react';
import { translate } from 'react-i18next';

import ContactService from 'services/contact.service';
import ReCAPTCHA from 'react-google-recaptcha';
import swal from 'sweetalert2';
import './contact.css';

export class ContactForm extends Component {
    constructor () {
        super();

        this.state = {
            'contact-option': '',
            name: '',
            email: '',
            message: '',
            'g-recaptcha-response': '',
            loading: false,
            formIsFullyFilled: false,
            language: '',
        };

        this.handleChange = this.handleChange.bind(this);
        this.handleReCaptchaChange = this.handleReCaptchaChange.bind(this);
        this.buttonDisabled = this.buttonDisabled.bind(this);
        this.send = this.send.bind(this);
        this.clear = this.clear.bind(this);
    }

    componentDidMount() {
        let newState = {};
        newState['contact-option'] = this.props.contactOption;
        newState['language'] = this.props.language;
        this.setState(newState);
    }

    handleChange (event) {
        let newState = {};
        newState[event.target.name] = event.target.value;
        this.setState(newState);
        this.checkIfFormFilled();
    }

    handleReCaptchaChange (value) {
        this.setState({
            'g-recaptcha-response': value,
        });
        this.checkIfFormFilled();
    }

    checkIfFormFilled() {
        let newState = {};
        newState.formIsFullyFilled = this.state.name !== '' && this.state.email !== '' && this.state.message !== '' && this.state['g-recaptcha-response'] !== '';
        this.setState(newState);
    }

    clear () {
        this.resetForm();
     }

    async send (event) {
        event.preventDefault();

        this.setState({
            loading: true,
        });

        let response = await ContactService.send(this.state);

        if (response.success === true) {
            this.props.emailSentHandler(this.state.message);
        } else {
            let errors = Object.keys(response.errors).reduce((content, index) => {
                return content + response.errors[index].join("\n") + "\n";
            }, '');

            swal({
                type: 'error',
                title: this.props.t('whoops'),
                text: errors
            });

            this.setState({ loading: false });
        }
    }

    resetForm () {
        window.grecaptcha.reset();

        this.setState({
            name: '',
            email: '',
            message: '',
            formIsFullyFilled: false,
            loading: false
        });
    }

    buttonDisabled () {
        return this.state.loading || ! this.state.formIsFullyFilled;
    }

    render () {
        const { t } = this.props;

        return (
            <div className='contact container'>
                <form onSubmit={this.send} className="contact-form form-horizontal">
                    <hr className="contact-separator" />

                    <div className='form-group row'>
                        <label className='control-label col-md-2 text-right' htmlFor="name"><b>{t('name')}</b></label>
                        <div className='col-md-6'>
                            <input type="text" id="name" name="name" className='form-control' disabled={this.state.loading} value={this.state.name} onChange={this.handleChange} required />
                        </div>
                    </div>

                    <div className='form-group row'>
                        <label className='control-label col-md-2 text-right' htmlFor="inputIcon"><b>{t('email address')}</b></label>
                        <div className='col-md-6'>
                            <div className='input-prepend'>
                                <span className='add-on'><i className='icon-envelope'></i></span>
                                <input className='form-control' id="email" name="email" type="email" disabled={this.state.loading} value={this.state.email} onChange={this.handleChange} required />
                            </div>
                        </div>
                    </div>

                    <div className='form-group row'>
                        <label className='control-label col-md-2 text-right' htmlFor="email"><b>{t('message')}</b></label>
                        <div className='col-md-6'>
                            <textarea id="message" name="message" rows="12" className="form-control" disabled={this.state.loading} value={this.state.message} onChange={this.handleChange} required></textarea>
                        </div>
                    </div>

                    <div className='form-group row'>
                        <label className='control-label col-md-2 text-right'></label>
                        <div className='col-md-6'>
                            <ReCAPTCHA
                                ref="recaptcha"
                                sitekey={process.env.NOCAPTCHA_SITEKEY}
                                onChange={this.handleReCaptchaChange}
                            />
                        </div>
                    </div>

                    <div className='form-group row'>
                        <div className="col-md-2"></div>
                        <div className="col-md-6">
                            <button type="submit" className='btn btn-large btn-primary' disabled={this.buttonDisabled()}>
                                {this.state.loading ? t('send message loading') : t('send message') }
                            </button>

                            <button className='btn btn-large' onClick={this.clear}>{t('cancel')}</button>
                        </div>
                    </div>
                </form>
            </div>
        );
    }
}

export default translate('contact')(ContactForm);
