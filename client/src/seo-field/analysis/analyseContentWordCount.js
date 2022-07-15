const GOOGLE_OPT_CONTENT_LENGTH = 300;

function analyseContentWordCount(dom, keyword, t) { // eslint-disable-line no-unused-vars
  const bodyText = dom.querySelector('body').innerText.replace(/^( *)$/gm, '').replace(/^( +)/gm, ' ').replace(/(\r\n|\n|\r)/gm, '');
  const wordCount = (bodyText.length && bodyText.split(/\s+\b/).length) || 0;

  if (wordCount > GOOGLE_OPT_CONTENT_LENGTH) {
    return {
      show: true,
      state: 'success',
      message: t(
        'analyseContentWordCount.COUNTOK',
        'The content of this page contains <b>{{wordCount}} words</b>',
        { wordCount },
      ),
    };
  }
  return {
    show: true,
    state: 'warning',
    message: t(
      'analyseContentWordCount.COUNTLOW',
      'The content of this page contains <b>{{wordCount}} words</b>, which is less than the recommended {{GOOGLE_OPT_CONTENT_LENGTH}} words.',
      { wordCount, GOOGLE_OPT_CONTENT_LENGTH },
    ),
  };
}

export default analyseContentWordCount;
