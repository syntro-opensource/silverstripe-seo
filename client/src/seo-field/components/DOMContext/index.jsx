import React, { useEffect, useState } from 'react';
import PropTypes from 'prop-types';
import Loader from 'Components/Loader';

export const DOMContext = React.createContext(null);

const DOMContextProvider = (props) => {
  const { link, children } = props;
  const [body, setBody] = useState(null);
  const [isLoading, setIsLoading] = useState(true);
  const [isFetching, setIsFetching] = useState(false);
  const [error, setError] = useState(null);
  useEffect(() => {
    if (!body && !isFetching) {
      setIsFetching(true);
      fetch(`${link}?stage=Stage`)
        .then(async (response) => {
          if (!response.ok) {
            setError(new Error(`HTTP error! Status: ${response.status}`));
          } else {
            const fetchBody = await response.text();
            setBody(fetchBody);
            setIsFetching(false);
            setIsLoading(false);
          }
        })
        .catch((fetchError) => {
          setError(fetchError);
          setIsFetching(false);
        });
    }
  }, [body, isFetching, link]);
  return (
    <DOMContext.Provider value={body}>
      {isLoading && <Loader cover color={error ? 'danger' : 'light'} /> }
      {children}
    </DOMContext.Provider>
  );
};

DOMContextProvider.defaultProps = {
  children: null,
};

DOMContextProvider.propTypes = {
  link: PropTypes.string.isRequired,
  children: PropTypes.node,
};

export default DOMContextProvider;
