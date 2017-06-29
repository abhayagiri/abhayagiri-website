import React, { Component } from 'react';

import Talk from '../talk/talk';

class TalkList extends Component {
    render() {
        return this.props.talks ? (
            <div className='talk-list'>
                {this.props.talks.map(talk=>{
                    return <Talk talk={talk}/>
                })}
            </div>
        ) : null;
    }
}

export default TalkList;