import analyseDescription from './analyseDescription';
import analyseContentWordCount from './analyseContentWordCount';
import analyseContentFocus from './analyseContentFocus';
import analyseFocusKeyword from './analyseFocusKeyword';
import analyseH1Title from './analyseH1Title';
import analyseTitle from './analyseTitle';
import analyseURL from './analyseURL';

const analyses = [
  analyseDescription,
  analyseContentWordCount,
  analyseContentFocus,
  analyseFocusKeyword,
  analyseH1Title,
  analyseTitle,
  analyseURL,
];

export default analyses;
