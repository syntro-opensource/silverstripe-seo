import React from 'react';
import PropTypes from 'prop-types';
import useAnalysis from 'Hooks/useAnalysis';
import Result from './Result';

function Analysis(props) {
  const { rootUrl, link, keyword } = props;
  const { results } = useAnalysis(rootUrl, link, keyword);
  const order = ['danger', 'warning', 'success', 'secondary'];
  const finalResults = results
    .filter(({ show }) => show)
    .sort((a, b) => order.indexOf(a.state) - order.indexOf(b.state));

  return (
    <div className="analysis my-n3">
      {finalResults.map((item, index) => (
        <Result
          key={`${item.state}_${item.message}`}
          index={index}
          show={item.show}
          state={item.state}
          message={item.message}
        />
      ))}
    </div>
  );
}

Analysis.defaultProps = {
  rootUrl: 'localhost',
  link: '/',
  keyword: '',
};

Analysis.propTypes = {
  rootUrl: PropTypes.string,
  link: PropTypes.string,
  keyword: PropTypes.string,
};

export default Analysis;
