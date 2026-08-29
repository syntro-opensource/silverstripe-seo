function analyseURL(dom, keyword, t, baseUrl, link) { // eslint-disable-line no-unused-vars
  if (!keyword) {
    return {
      show: false,
      state: 'secondary',
    };
  }

  // Normalize both the link and keyword to lowercase and trim whitespace
  const normalizedLink = (link || '').toLowerCase();
  const normalizedKeyword = (keyword || '').trim().toLowerCase();
  if (normalizedLink.indexOf(normalizedKeyword) < 0) {
    return {
      show: true,
      state: 'warning',
      message: t('analyseURL.NOFOCUS', 'The focus keyword was not found in the URL segment!'),
    };
  }
  return { show: false, state: 'success' };
}

export default analyseURL;
