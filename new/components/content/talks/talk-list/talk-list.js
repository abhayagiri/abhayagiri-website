import React, { Component } from 'react';
import { translate } from 'react-i18next';
import PropTypes from 'prop-types';
import { scroller, Element } from 'react-scroll';

import Talk from '../talk/talk';
import Pagination from 'components/shared/pagination/pagination';
import CategoryCard from 'components/shared/categories/category-card/category-card';
import Spinner from 'components/shared/spinner/spinner';

import './talk-list.css';

class TalkList extends Component {

    render() {
        let results = this.props.talks,
            totalPages = results && results.totalPages || 1,
            talks = results && results.result || [],
            category = this.props.category,
            isLoading = this.props.isLoading,
            lng = this.props.i18n.language;

        const scrollToTop = () => {
            scroller.scrollTo('talk-list', {
                offset: -50,
                duration: 1000,
                smooth: 'easeInOutQuart'
            });
        }

        return (
            <div className={'talk-list' + (isLoading ? ' loading' : '')}>
                <Element name="talk-list" />
                <Spinner />
                <div className="row">
                    <div className="col-md-3">
                        {this.props.category && <CategoryCard category={this.props.category} />}
                    </div>
                    <div className="col-md-9">
                        {this.props.preamble && (<div>
                            {this.props.preamble}
                            <hr />
                        </div>)}
                        <div className="talks">
                            {talks.map((talk, index) => <Talk talk={talk} key={index} />)}
                        </div>
                    </div>
                </div>
                {talks.length > 0 && <Pagination totalPages={totalPages} onClick={scrollToTop} />}
            </div>
        )
    }
}

TalkList.propTypes = {
    talks: PropTypes.object,
    category: PropTypes.object,
    preamble: PropTypes.object
};

export default translate('talks')(TalkList);
