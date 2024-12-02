import React from 'react';
import PropTypes from 'prop-types';
import HighlightedText from 'Components/HighlightedText';

/**
 * renders the snippet part of the SERP
 */
function SERPSnippet(props) {
  const { snippet, keyword } = props;
  return (
    <div
      className="serp-preview__snippet"
      style={{
        lineHeight: '1.58',
        color: '#4d5156',
        fontSize: '14px',
      }}
    >
      <HighlightedText content={snippet} keyword={keyword} />
    </div>
  );
}

SERPSnippet.defaultProps = {
  keyword: '',
};

SERPSnippet.propTypes = {
  snippet: PropTypes.string.isRequired,
  keyword: PropTypes.string,
};

export default SERPSnippet;
