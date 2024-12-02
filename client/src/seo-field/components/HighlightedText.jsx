import React from 'react';
import PropTypes from 'prop-types';

function HighlightedText(props) {
  const { content, keyword } = props;
  let highlighted = content;
  if (keyword && keyword !== '') {
    const re = new RegExp(keyword, 'gi');
    highlighted = highlighted.replace(re, '<b><span style="background-color: #fffe79;">$&</span></b>');
  }
  return (<span dangerouslySetInnerHTML={{ __html: highlighted }} />); // eslint-disable-line react/no-danger,max-len
}

HighlightedText.defaultProps = {
  keyword: null,
};

HighlightedText.propTypes = {
  content: PropTypes.string.isRequired,
  keyword: PropTypes.string,
};

export default HighlightedText;
