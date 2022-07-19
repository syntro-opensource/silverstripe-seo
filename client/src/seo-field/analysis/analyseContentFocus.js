function analyseContentFocus(dom, keyword, t) { // eslint-disable-line no-unused-vars
  if (!keyword) {
    return {
      show: false,
      state: 'secondary',
    };
  }

  const bodyText = dom.querySelector('body').innerText.replace(/^( *)$/gm, '').replace(/^( +)/gm, ' ').replace(/(\r\n|\n|\r)/gm, '');

  const re = new RegExp(keyword, 'gi');
  const matches = bodyText.match(re);

  if (!matches) {
    return {
      show: true,
      state: 'danger',
      message: t('analyseContentFocus.NOTFOUND', 'The focus keyword was not found in the content of this page.'),
    };
  }
  return {
    show: true,
    state: 'success',
    message: t('analyseContentFocus.FOUND', 'The focus keyword was found <strong>{{matches}}</strong> times in the Content of this page.', { matches: matches.length }),
  };
}

export default analyseContentFocus;
