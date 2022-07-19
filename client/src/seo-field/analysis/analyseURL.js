function analyseURL(dom, keyword, t, baseUrl, link) { // eslint-disable-line no-unused-vars
  if (!keyword) {
    return {
      show: false,
      state: 'secondary',
    };
  }

  if (link.indexOf(keyword) < 0) {
    return {
      show: true,
      state: 'warning',
      message: t('analyseURL.NOFOCUS', 'The focus keyword was not found in the URL segment!'),
    };
  }
  return { show: false, state: 'success' };
}

export default analyseURL;
