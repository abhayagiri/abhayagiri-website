import React, { Component } from 'react';
import { translate } from 'react-i18next';
import { tp, thp } from '../../../i18n';

import ContactOptionsService from 'services/contact-options.service';
import ContactForm from 'components/content/contact/contact-form';
import Spinner from 'components/shared/spinner/spinner';
import Link from 'components/shared/link/link';
import './contact.css';

export class ContactOption extends Component {
    constructor(props) {
        super(props);

        this.state = {
            isLoading: true,
            emailSent: false,
            message: '',
        }

        this.emailSentHandler = this.emailSentHandler.bind(this);
    }

    componentDidMount() {
        this.getContactOption(this.props);
    }

    async getContactOption(props) {
        let contactOptionSlug = props.params.contactOption;

        const contactOption = await ContactOptionsService.getContactOption(contactOptionSlug);

        this.setState({
            isLoading: false,
            contactOption: contactOption,
        });
    }

    emailSentHandler(message) {
        this.setState({
            emailSent: true,
            message: message,
        });
    }

    renderTitle() {
        if (this.state.emailSent) {
            return this.props.t('confirmation title') + ' - ' + tp(this.state.contactOption, 'name');
        }

        return tp(this.state.contactOption, 'name');
    }

    renderBody() {
        if (this.state.emailSent) {
            return thp(this.state.contactOption, 'confirmationHtml');
        }

        return thp(this.state.contactOption, 'bodyHtml');
    }

    renderUserMessage() {
        if (this.state.emailSent) {
            return (
                <div>
                    <legend>{ this.props.t('your message title') }</legend>
                    <blockquote className="blockquote user-message">{ this.state.message }</blockquote>
                </div>
            )
        }

        return ''
    }

    render() {
        let contactOption = this.state && this.state.contactOption;
        const { t, i18n } = this.props;

        return this.state.isLoading ? <Spinner /> : (
            <div className="contact container">
                <legend>{ this.renderTitle() }</legend>
                <div className="row">
                    <div className="col-sm-12">
                        <div className="contact-text">{ this.renderBody() }</div>
                        {
                            contactOption.active && ! this.state.emailSent ?
                                <ContactForm contactOption={contactOption} emailSentHandler={this.emailSentHandler} language={ i18n.language } /> : ''
                        }
                        {
                            this.renderUserMessage()
                        }
                    </div>
                    <div className="col-sm-12">
                        <Link to={'/contact'} className="btn btn-secondary">{t('back')}</Link>
                    </div>
                </div>
            </div>
        )
    }
}

export default translate('contact')(ContactOption);
