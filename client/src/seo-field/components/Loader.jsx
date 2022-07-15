import React from 'react';
import PropTypes from 'prop-types';
import { Spinner } from 'reactstrap';

const Loader = (props) => {
  const { color, cover } = props;
  const loader = (
    <div className="d-flex justify-content-center align-items-center h-100">
      <Spinner color={color} />
    </div>
  );
  if (cover) {
    return (
      <div
        style={{
          position: 'absolute',
          top: 0,
          bottom: 0,
          left: 0,
          right: 0,
          backgroundColor: 'rgba(132, 140, 148, 0.42)',
          zIndex: 1000,
        }}
        className="rounded"
      >
        {loader}
      </div>
    );
  }
  return loader;
};

Loader.defaultProps = {
  color: 'dark',
  cover: false,
};

Loader.propTypes = {
  color: PropTypes.string,
  cover: PropTypes.bool,
};

export default Loader;
