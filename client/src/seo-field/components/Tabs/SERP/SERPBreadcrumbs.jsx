import React from 'react';
import PropTypes from 'prop-types';
import HighlightedText from 'Components/HighlightedText';
/* eslint-disable react/no-array-index-key */

/**
 * Renders the breadcrumb part of the SERP
 */
function SERPBreadcrumbs(props) {
  const { breadcrumbs, keyword } = props;
  return (
    <div
      className="serp-preview__breadcrumbs"
      style={{
        color: '#202124',
        fontStyle: 'normal',
        fontSize: '14px',
        paddingTop: '1px',
        lineHeight: '1.3',
      }}
    >
      {breadcrumbs.map((item, index) => (
        <span key={`${item}${index}`} style={{ color: index === 0 ? null : '#5f6368' }}>
          {index > 0 && ' › '}
          <HighlightedText content={item} keyword={keyword} />
        </span>
      ))}
    </div>
  );
}

SERPBreadcrumbs.defaultProps = {
  keyword: '',
};

SERPBreadcrumbs.propTypes = {
  breadcrumbs: PropTypes.arrayOf(PropTypes.string).isRequired,
  keyword: PropTypes.string,
};

export default SERPBreadcrumbs;
