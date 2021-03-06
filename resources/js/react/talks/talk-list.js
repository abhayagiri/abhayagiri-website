import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { withTranslation } from 'react-i18next';

import Talk from './talk';
import Pagination from '../shared/pagination';
import CategoryCard from '../shared/category-card';
import Spinner from '../shared/spinner';

class TalkList extends Component {

    static propTypes = {
        talks: PropTypes.object,
        category: PropTypes.object,
        preamble: PropTypes.object
    };

    render() {
        let results = this.props.talks,
            totalPages = results && results.totalPages || 1,
            talks = results && results.result || [],
            category = this.props.category,
            isLoading = this.props.isLoading,
            lng = this.props.i18n.language;

        return (
            <div className={'talk-list' + (isLoading ? ' loading' : '')}>
                <Spinner />
                <div className="row">
                    <div className="col-md-3 hidden-sm-down">
                        {this.props.category && <CategoryCard category={this.props.category} />}
                    </div>
                    <div className="col-md-9 col-sm-12">
                        {this.props.preamble && (<div>
                            {this.props.preamble}
                            <hr />
                        </div>)}
                        <div className="talks">
                            {talks.map((talk, index) => <Talk talk={talk} key={index} />)}
                        </div>
                    </div>
                </div>
                {talks.length > 0 && <Pagination totalPages={totalPages} />}
            </div>
        )
    }
}

export default withTranslation('talks')(TalkList);
