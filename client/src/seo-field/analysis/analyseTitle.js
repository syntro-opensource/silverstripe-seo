const GOOGLE_MAX_TITLE_LENGTH = 70;
const GOOGLE_OPT_TITLE_LENGTH = 40;
const GOOGLE_MIN_TITLE_LENGTH = 30;
const HOME_WORDS = [
  'home',
  'startseite',
];

function analyseTitle(dom, keyword, t) { // eslint-disable-line no-unused-vars
  const title = dom.title.toLowerCase();

  if (keyword && title.indexOf(keyword.toLowerCase()) < 0) {
    return {
      show: true,
      state: 'danger',
      message: t('analyseTitle.NOKEYWORD', 'The page title does not contain the focus keyword.'),
    };
  }

  // check title length
  if (title.length < GOOGLE_MIN_TITLE_LENGTH) {
    return {
      show: true,
      state: 'danger',
      message: t('analyseTitle.TOOSHORT', 'The page title is too short.'),
    };
  } if (title.length > GOOGLE_MAX_TITLE_LENGTH) {
    return {
      show: true,
      state: 'danger',
      message: t('analyseTitle.TOOLONG', 'The page title is too long.'),
    };
  }

  if (keyword && title.indexOf(keyword.toLowerCase()) > 0) {
    return {
      show: true,
      state: 'warning',
      message: t('analyseTitle.KEYWORDNOTSTART', 'The page title contains the focus keyword, but not at the beginning; Consider moving it to the beginning.'),
    };
  }

  if (title.length < GOOGLE_OPT_TITLE_LENGTH) {
    return {
      show: true,
      state: 'warning',
      message: t('analyseTitle.SHORT', 'The page title is somewhat short, but is above the absolute character minimum of {{GOOGLE_MIN_TITLE_LENGTH}} characters.', { GOOGLE_MIN_TITLE_LENGTH }),
    };
  }

  // check if Title is home
  for (let i = 0; i < HOME_WORDS.length; i += 1) {
    if (title.indexOf(HOME_WORDS[i]) !== -1) {
      return {
        show: true,
        state: 'warning',
        message: t('analyseTitle.CONTAINSHOME', 'The page title should be changed from "{{title}}"; titles containing {{HOME_WORD}} almost always reduces the click-through rate.', { title, HOME_WORD: HOME_WORDS[i] }),
      };
    }
  }

  return {
    show: true,
    state: 'success',
    message: t('analyseTitle.SUCCESS', 'The page title is perfect!'),
  };
}

export default analyseTitle;
