import React, { Component } from 'react';

import './subpage.css';

class Subpage extends Component {
    render() {
        return (
            <div className="subpage">
                <legend>{page.title}</legend>
                {page.body}
                <div class='backtotop' onclick="backtotop()"><span class='pull-right'><i class='icon-caret-up'> Back To Top</i></span></div>
            </div>
        );
    }
}

export default Subpage;