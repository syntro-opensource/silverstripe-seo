import React from 'react';
import PropTypes from 'prop-types';
import HighlightedText from 'Components/HighlightedText';

/**
 * The title part of the SERP preview
 */
const SERPTitle = (props) => {
  const { title, link, keyword } = props;
  return (
    <div
      className="serp-preview__title"
    >
      <a
        className="h3"
        style={{
          marginBottom: '3px',
          paddingTop: '4px',
          fontSize: '20px',
          lineHeight: '1.3',
          fontWeight: 'normal',
          color: 'rgb(26, 13, 171)',
        }}
        href={link}
        target="_blank"
        rel="noreferrer"
      >
        <HighlightedText content={title} keyword={keyword} />
      </a>
    </div>
  );
};

SERPTitle.defaultProps = {
  keyword: '',
};

SERPTitle.propTypes = {
  title: PropTypes.string.isRequired,
  link: PropTypes.string.isRequired,
  keyword: PropTypes.string,
};

export default SERPTitle;
