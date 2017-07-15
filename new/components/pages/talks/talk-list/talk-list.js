import React, { Component } from 'react';

import Talk from '../talk/talk';

class TalkList extends Component {
    render() {
        return this.props.talks ? (
            <div className='talk-list'>
                {this.props.talks.map(talk => {
                    return <div><Talk talk={talk} /><hr className='border' /></div>
                })}
                <nav>
                    <ul className="pagination justify-content-center">
                        {this.props.currentPage <= 1 && <li className="page-item disabled">
                            <span className="page-link">Previous</span>
                        </li>}
                        {this.props.currentPage > 1 && <li className="page-item">
                            <a className="page-link" href={"/new/talks/" + this.props.currentPage - 1}>Previous</a>
                        </li>}
                        {this.props.currentPage != 1 && <li className="page-item">
                            <a className="page-link" href="#">1</a>
                        </li>}
                        {[...Array(5)].map((x, i) => {
                            let page = this.props.currentPage + i;
                            return (
                                <li className="page-item">
                                    <a className="page-link" href={"/new/talks/" + page}>
                                        {page}
                                    </a>
                                </li>
                            )
                        })
                        }
                        <li className="page-item">
                            <a className="page-link" href={"/new/talks/" + this.props.totalPages}>{this.props.totalPages}</a>
                        </li>
                        {this.props.currentPage >= this.props.totalPages && <li className="page-item disabled">
                            <span className="page-link">Next</span>
                        </li>}
                        {this.props.currentPage < this.props.totalPages && <li className="page-item">
                            <a className="page-link" href={"/new/talks/" + this.props.currentPage + 1}>Next</a>
                        </li>}
                    </ul>
                </nav>
            </div>
        ) : null;
    }
}

export default TalkList;