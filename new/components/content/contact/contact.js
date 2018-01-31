import React, { Component } from 'react';
import { translate } from 'react-i18next';

import ContactService from 'services/contact.service';

export class Contact extends Component {
    constructor () {
        super();

        this.state = {
            name: '' ,
            email: '' ,
            message: '',
            loading: false
        };

        this.handleChange = this.handleChange.bind(this);
        this.send = this.send.bind(this);
        this.clear = this.clear.bind(this);
    }

    handleChange (field, event) {
        let newState = {};
        newState[field] = event.target.value;
        this.setState(newState);
    }

    componentDidMount () {
        console.log('here!');
    }

    clear () {
        console.log('clearing form');
    }

    send () {
        this.setState({ loading: true });
        let response = ContactService.send(this.state);
    }

    render () {
        let disabled = this.state.loading;

        return (
            <div className='contact'>
                <div className='container-fluid'>
                    <legend>Contact Form</legend>

                    <div className='control-group'>
                        <label className='control-label' htmlFor="name"><b>Name</b></label>
                        <div className='controls'>
                            <input type="text" id="name" placeholder="Name" className='span3' required disabled={this.state.loading} value={this.state.name} onChange={this.handleChange.bind(this, 'name')} />
                        </div>
                    </div>

                    <div className='control-group'>
                        <label className='control-label' htmlFor="inputIcon"><b>Email address</b></label>
                        <div className='controls'>
                            <div className='input-prepend'>
                                <span className='add-on'><i className='icon-envelope'></i></span>
                                <input className='span3' type="email" placeholder="Email" required disabled={this.state.loading} value={this.state.email} onChange={this.handleChange.bind(this, 'email')} />
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
                            CAPTCHA
                        </div>
                    </div>

                    <hr />

                    <div className='control-group'>
                        <button type="submit" className='btn btn-large btn-primary' onClick={this.send}>Submit</button>
                        <button type="submit" className='btn btn-large' onClick={this.clear}>Cancel</button>
                    </div>

                    <br />
                    <br />
                    <br />
                </div>
            </div>
        );
    }
}

export default Contact;
