import React, { Component } from 'react';
import PropTypes from 'prop-types';

import './search.css';

function embedScript() {
    var cx = '000972291994809008767:xp7cjel9soe';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
}

class Search extends Component {

    static propTypes = {
        visible: PropTypes.bool.isRequired
    }

    componentDidMount() {
        embedScript();
    }

    render() {
        const className = 'search search-' +
            (this.props.visible ? 'visible' : 'hidden');
        if (this.props.visible) {
            // HACK
            setTimeout(() => document.getElementById('gsc-i-id1').focus(), 1);
        }
        return (
            <div className={className}>
                <div dangerouslySetInnerHTML={
                    {__html: '<gcse:search linktarget="_self"></gcse:search>'}} />
            </div>
        );
    }
}

export default Search;
