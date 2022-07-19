import jQuery from 'jquery'; // eslint-disable-line import/no-unresolved
import { loadComponent } from 'lib/Injector'; // eslint-disable-line import/no-unresolved
import React from 'react';
import { createRoot } from 'react-dom/client';

jQuery.entwine('ss', ($) => {
  let root = null;
  // We're matching to the field based on class. We added the last class in the field
  $('.seo-analysis-field .seo-analysis-field').entwine({
    onmatch() {
      // We're using the injector to create an instance of the react component we can use
      const Component = loadComponent('SEOAnalysisField');
      // We've added the schema state to the div in the template above which we'll use as props
      const schemaState = this.data('state');

      // // This is our "polyfill" for `onAutoFill`
      // const setValue = (fieldName, value) => {
      //   // We'll find the input by name, we shouldn't ever have the same input
      //   // with the same name or form state will be messed up
      //   const input = document.querySelector(`input[name="${fieldName}"]`);
      //
      //   // If there's no input field then we'll return early
      //   if (!input) {
      //     return;
      //   }
      //
      //   // Now we can set the field value
      //   input.value = value;
      // };

      // We render the component onto the targeted div
      root = createRoot(this[0]);
      root.render(<Component {...schemaState} />); // eslint-disable-line react/jsx-props-no-spreading, max-len
    },

    // When we change the loaded page we'll remove the component
    onunmatch() {
      root.unmount();
    },
  });
});
