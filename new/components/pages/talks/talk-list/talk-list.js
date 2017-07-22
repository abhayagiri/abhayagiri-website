import React, { Component } from 'react';

import Talk from '../talk/talk';

class TalkList extends Component {
    render() {

        let currentPage = parseInt(this.props.currentPage);
        let totalPages = parseInt(this.props.totalPages);

        return this.props.talks ? (
            <div className='talk-list'>
                {this.props.talks.map(talk => {
                    return <div><Talk talk={talk} /><hr className='border' /></div>
                })}
                <nav>
                    <ul className="pagination justify-content-center">
                        {/* Previous */}
                        {currentPage <= 1 && <li className="page-item disabled">
                            <span className="page-link">Previous</span>
                        </li>}
                        {currentPage > 1 && <li className="page-item">
                            <a className="page-link" href={"/new/talks/" + (currentPage - 1)}>Previous</a>
                        </li>}

                        {/* Pages */}
                        {currentPage >= 3 && <li className="page-item">
                            <a className="page-link" href={"/new/talks/" + (currentPage - 2)}>{currentPage - 2}</a>
                        </li>}
                        {currentPage >= 2 && <li className="page-item">
                            <a className="page-link" href={"/new/talks/" + (currentPage - 1)}>{currentPage - 1}</a>
                        </li>}
                        <li className="page-item active">
                            <a className="page-link" href={"/new/talks/" + (currentPage)}>{currentPage}</a>
                        </li>
                        {currentPage <= (totalPages - 1) && <li className="page-item">
                            <a className="page-link" href={"/new/talks/" + (currentPage + 1)}>{currentPage + 1}</a>
                        </li>}
                        {currentPage <= (totalPages - 2) && <li className="page-item">
                            <a className="page-link" href={"/new/talks/" + (currentPage + 2)}>{currentPage + 2}</a>
                        </li>}

                        {/* Next */}
                        {currentPage >= totalPages && <li className="page-item disabled">
                            <span className="page-link">Next</span>
                        </li>}
                        {currentPage < totalPages && <li className="page-item">
                            <a className="page-link" href={"/new/talks/" + (currentPage + 1)}>Next</a>
                        </li>}
                    </ul>
                </nav>
            </div>
        ) : null;
    }
}

export default TalkList;