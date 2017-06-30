import React, { Component } from 'react';

import './nav.css';

class Nav extends Component {
    render() {
        return (
            <div id="nav-container">
                <div className="container-fluid">
                    <div id="nav">
                        <i className="fa fa-sort-up arrow"></i>

                        <div className="brick">
                            <a href="/home" onclick="nav('home');return false;">
                                <div id="btn-home" className="btn-nav ">
                                    <i className="home fa fa-home"></i><br />
                                    <span className="home title-icon">Home</span>
                                </div>
                            </a>
                        </div>
                        <div className="brick">
                            <a href="/news" onclick="nav('news');return false;">
                                <div id="btn-news" className="btn-nav">
                                    <i className="monastery fa fa-bullhorn"></i><br />
                                    <span className="monastery title-icon">News</span>
                                </div>
                            </a>
                        </div>
                        <div className="brick">
                            <a href="/calendar" onclick="nav('calendar');return false;">
                                <div id="btn-calendar" className="btn-nav ">
                                    <i className="monastery fa fa-calendar"></i><br />
                                    <span className="monastery title-icon">Calendar</span>
                                </div>
                            </a>
                        </div>
                        <div className="brick">
                            <a href="/new/about" onclick="nav('about');return false;">
                                <div id="btn-about" className="btn-nav active">
                                    <i className="monastery fa fa-flag"></i><br />
                                    <span className="monastery title-icon">About</span>
                                </div>
                            </a>
                        </div>
                        <div className="brick">
                            <a href="/community" onclick="nav('community');return false;">
                                <div id="btn-community" className="btn-nav">
                                    <i className="monastery fa fa-group"></i><br />
                                    <span className="monastery title-icon">Community</span>
                                </div>
                            </a>
                        </div>
                        <div className="brick">
                            <a href="/support" onclick="nav('support');return false;">
                                <div id="btn-support" className="btn-nav ">
                                    <i className="monastery fa fa-gift"></i><br />
                                    <span className="monastery title-icon">Support</span>
                                </div>
                            </a>
                        </div>
                        <div className="brick">
                            <a href="/audio" onclick="nav('audio');return false;">
                                <div id="btn-audio" className="btn-nav">
                                    <i className="medianav fa fa-volume-up"></i><br />
                                    <span className="medianav title-icon">Audio</span>
                                </div>
                            </a>
                        </div>
                        <div className="brick">
                            <a href="/books" onclick="nav('books');return false;">
                                <div id="btn-books" className="btn-nav ">
                                    <i className="medianav fa fa-book"></i><br />
                                    <span className="medianav title-icon">Books</span>
                                </div>
                            </a>
                        </div>
                        <div className="brick">
                            <a href="/reflections" onclick="nav('reflections');return false;">
                                <div id="btn-reflections" className="btn-nav ">
                                    <i className="medianav fa fa-leaf"></i><br />
                                    <span className="medianav title-icon">Reflections</span>
                                </div>
                            </a>
                        </div>
                        <div className="brick">
                            <a href="/gallery" onclick="nav('gallery');return false;">
                                <div id="btn-gallery" className="btn-nav ">
                                    <i className="medianav fa fa-picture"></i><br />
                                    <span className="medianav title-icon">Gallery</span>
                                </div>
                            </a>
                        </div>
                        <div className="brick">
                            <a href="/visiting" onclick="nav('visiting');return false;">
                                <div id="btn-visiting" className="btn-nav ">
                                    <i className="monastery fa fa-road"></i><br />
                                    <span className="monastery title-icon">Visiting</span>
                                </div>
                            </a>
                        </div>
                        <div className="brick">
                            <a href="/contact" onclick="nav('contact');return false;">
                                <div id="btn-contact" className="btn-nav ">
                                    <i className="footer fa fa-envelope-alt"></i><br />
                                    <span className="footer title-icon">Contact</span>
                                </div>
                            </a>
                        </div>                    
                    </div>
                </div>
            </div>
        );
    }
}

export default Nav;