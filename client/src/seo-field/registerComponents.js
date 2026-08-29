import Injector from 'lib/Injector'; // eslint-disable-line import/no-unresolved
import SEOAnalysisField from 'Components/SEOAnalysisField';

// This bundle is loaded via Requirements on the edit form, so it is
// re-evaluated by jQuery.globalEval every time the CMS reloads a DataObject
// edit form through pjax. Since silverstripe/admin 3.2 the JS Injector throws
// "Cannot mutate DI container after it has been initialised" if registerMany is
// called after boot. That thrown error aborts the rest of the fragment's JS
// initialisation (entwine re-binding, menu carets, etc.). Guard registration so
// a second evaluation is a harmless no-op.
let registered = false;

export default () => {
  if (registered) {
    return;
  }

  // If the component is already known to the Injector (e.g. a prior page load
  // in the same browser session already registered it), skip re-registering.
  if (typeof Injector.component.get === 'function') {
    try {
      if (Injector.component.get('SEOAnalysisField')) {
        registered = true;
        return;
      }
    } catch (e) {
      // Injector.component.get throws when the service is not registered yet —
      // that is the expected path on first load, so fall through and register.
    }
  }

  Injector.component.registerMany({
    SEOAnalysisField,
  });

  registered = true;
};
