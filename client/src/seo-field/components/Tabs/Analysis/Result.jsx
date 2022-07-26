import React from 'react';
import PropTypes from 'prop-types';
/* eslint-disable react/no-danger */

function Result(props) {
  const {
    show, state, message, index,
  } = props;
  if (!show) {
    return null;
  }
  return (
    <>
      {index > 0 && <hr style={{ margin: 0 }} />}
      <div className="d-flex align-items-center py-1" style={{ borderColor: '#dbdee6' }}>

        <div className="p-2">
          <span
            style={{
              height: '.8rem',
              width: '.8rem',
              display: 'block',
              borderRadius: '.8rem',
            }}
            className={`bg-${state}`}
          />
        </div>
        <div className="p-2" dangerouslySetInnerHTML={{ __html: message }} />
      </div>
    </>
  );
}

Result.defaultProps = {
  show: true,
};

Result.propTypes = {
  state: PropTypes.oneOf(['danger', 'warning', 'success', 'secondary']).isRequired,
  message: PropTypes.string.isRequired,
  index: PropTypes.number.isRequired,
  show: PropTypes.bool,
};

export default Result;
