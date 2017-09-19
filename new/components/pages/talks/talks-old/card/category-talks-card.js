import React, { Component } from 'react';
import PropTypes from 'prop-types';

import TalksCard from './talks-card';
import { tp } from '../../../../i18n';

class CategoryTalksCard extends Component {
    render() {
        const { category } = this.props;
        return (
            <TalksCard
                title={tp(category, 'title')}
                imageUrl={category.image}
            >
                <div dangerouslySetInnerHTML={{ __html: category.descriptionEn }} />
            </TalksCard>
        );
    }
}

CategoryTalksCard.propTypes = {
    category: PropTypes.object.isRequired
};

export default CategoryTalksCard;
