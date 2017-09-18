import React, { Component } from 'react';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';

import Talk from '../talk/talk';
import Pagination from '../../../widgets/pagination/pagination';
import CategoryCard from '../../../widgets/categories/category-card/category-card';
import Spinner from '../../../widgets/spinner/spinner';

class TalkList extends Component {

    render() {
        console.log(results);

        let results = this.props.talks,
            totalPages = results && results.totalPages || 1,
            talks = results && results.result || [],
            category = this.props.category,
            isLoading = this.props.isLoading,
            lng = this.props.i18n.language;

            
        return (
            <div>
                <div className="row">
                    {category && <div className="col-md-3">
                        <CategoryCard category={this.props.category} />
                    </div>}
                    <div className={"col-md-" + (category ? '9' : '12')}>
                        {this.props.isLoading ? <Spinner/> : <div className='talk-list'>
                            {talks.map((talk, index) => {
                                return <div key={index}><Talk talk={talk} /><hr className='border' /></div>
                            })}
                            <Pagination totalPages={totalPages} />
                        </div>}
                    </div>
                </div>
            </div>
        )
    }
}

TalkList.propTypes = {
    talks: PropTypes.object,
    category: PropTypes.object
};

export default translate('talks')(TalkList);
