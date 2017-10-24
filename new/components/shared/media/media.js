import React, { Component } from 'react';
import Link from 'components/shared/link/link';

import './media.css';

class Media extends Component {

    constructor() {
        super();
        this.state = {
            showDescription: true
        }
    }

    toggleDescription() {
        // this.setState({
        //     showDescription: !this.state.showDescription
        // })
    }

    render() {
        return (
            <div className='media-item'>
                <div className="row">
                    <div className='col-sm-12 col-md-7'>
                        <div className='media'>
                            <span className='float-left'>
                                <img className='img-speakers media-object' src={this.props.thumbnail} />
                            </span>
                            <div className='media-body'>
                                <span className='title'>
                                    <Link to={this.props.href}>{this.props.title}</Link>
                                </span>
                                <br />{this.props.author}
                                <br /><i>{this.props.date}</i>
                                {this.props.language}
                            </div>
                        </div>
                    </div>
                    <div className='col-sm-12 col-md-5'>
                        <div className='spacer hidden-md-up' />
                        <span className='actions btn-group btn-group-media'>
                            {this.props.buttons.map(button => {
                                return (
                                    <a
                                        onClick={button.onClick}
                                        href={button.href}
                                        download={button.download}
                                        className="btn btn-secondary">
                                            <i className={"fa fa-" + button.icon}></i>&nbsp;
                                            {button.text}
                                    </a>
                                )
                            })}
                        </span>
                    </div>
                </div>
                <br />
                {this.state.showDescription ?
                    <div className="row">
                        <div className='col-12'>
                            {this.props.description}
                        </div>
                    </div> : ''}
            </div >
        );
    }
}

export default Media;
