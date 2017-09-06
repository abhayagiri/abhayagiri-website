import React, { Component } from 'react';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import Talk from '../talk/talk';
import Pagination from '../../../widgets/pagination/pagination';
import CategoryCard from '../../categories/category-card/category-card';

class TalkList extends Component {

    render() {
        console.log(this.props);
        let results = this.props.talks,
            page = results.page,
            totalPages = results.totalPages,
            talks = results.result,
            lng = this.props.i18n.language;

        return (
            <div>
                <div className="row">
                    <div className="col-md-3">
                        <CategoryCard category={this.props.category} />
                    </div>
                    <div className="col-md-9">
                        <div className='talk-list'>
                            {talks.map((talk, index) => {
                                return <div key={index}><Talk talk={talk} /><hr className='border' /></div>
                            })}
                            <Pagination page={page} totalPages={totalPages} />
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}

TalkList.propTypes = {
    talks: PropTypes.array.isRequired,
    searchText: PropTypes.string.isRequired,
    page: PropTypes.number.isRequired,
    totalPages: PropTypes.number.isRequired
};

export default translate('talks')(TalkList);
