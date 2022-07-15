import Injector from 'lib/Injector'; // eslint-disable-line import/no-unresolved
import SEOAnalysisField from 'Components/SEOAnalysisField';

export default () => {
  Injector.component.registerMany({
    SEOAnalysisField,
  });
};
