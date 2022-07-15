function analyseH1Title(dom, keyword, t) { // eslint-disable-line no-unused-vars
  if (!dom.querySelector('h1')) {
    return {
      show: true,
      state: 'danger',
      message: t('analyseH1Title.NOTITLE', 'This page has no H1-title. Each page should have a unique H1-title.'),
    };
  }

  if (dom.querySelectorAll('h1').length > 1) {
    return {
      show: true,
      state: 'danger',
      message: t('analyseH1Title.MANYTITLES', 'The page contains multiple H1-titles. Each page should contain only one, unique H1-title.'),
    };
  }

  if (keyword) {
    const re = new RegExp(keyword, 'gi');
    if (dom.querySelector('h1').innerText.match(re)) {
      return {
        show: true,
        state: 'success',
        message: t('analyseH1Title.SUCCESS', 'The H1-title is perfect!'),
      };
    }
    return {
      show: true,
      state: 'danger',
      message: t('analyseH1Title.NOKEYWORD', 'The H1-title does not contain the focus-keyword.'),
    };
  }
  return { show: false, state: 'success' };
}

export default analyseH1Title;
