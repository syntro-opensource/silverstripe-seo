function analyseFocusKeyword(dom, keyword, t) { // eslint-disable-line no-unused-vars
  if (!keyword) {
    return {
      show: true,
      state: 'secondary',
      message: t('analyseFocusKeyword.NOKEYWORD', 'No focus keyword was set. Consider setting one to unlock further checks.'),
    };
  }
  return { show: false, state: 'success' };
}

export default analyseFocusKeyword;
