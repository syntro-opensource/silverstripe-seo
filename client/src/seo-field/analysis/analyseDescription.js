// const GOOGLE_OPT_DESCRIPTION_LENGTH = 100;
const GOOGLE_MAX_DESCRIPTION_LENGTH = 160;
const GOOGLE_MIN_DESCRIPTION_LENGTH = 70;

function analyseDescription(dom, keyword, t) {
  const metadesc = dom.querySelector('meta[name="description"]');
  if (!metadesc) {
    return {
      show: true,
      state: 'danger',
      message: t('analyseDescription.NODESC', 'The metadescription was not set. A random and possibly unwanted section of the content might be shown instead.'),
    };
  }
  const { content } = metadesc;
  if (keyword && !content.toLowerCase().match(keyword.toLowerCase())) {
    return {
      show: true,
      state: 'danger',
      message: t('analyseDescription.NOKEYWORD', 'The metadescription does not contain the focus keyword. A random and possibly unwanted section of the content might be shown instead.'),
    };
  }
  if (content.length > GOOGLE_MAX_DESCRIPTION_LENGTH) {
    return {
      show: true,
      state: 'warning',
      message: t('analyseDescription.TOOLONG', 'the metadescription is too long.'),
    };
  }
  if (content.length < GOOGLE_MIN_DESCRIPTION_LENGTH) {
    return {
      show: true,
      state: 'warning',
      message: t('analyseDescription.TOOSHORT', 'the metadescription is too short.'),
    };
  }
  if (keyword) {
    return {
      show: true,
      state: 'success',
      message: t('analyseDescription.SUCCESS', 'the metadescription is perfect!'),
    };
  }
  return {
    show: false,
    state: 'secondary',
  };
}

export default analyseDescription;
