import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';

import Backend from 'i18next-xhr-backend';
import LanguageDetector from 'i18next-browser-languagedetector';

/**
 * Resolve the base URL the bundle was served from so we can locate the exposed
 * `client/lang/{{lng}}/{{ns}}.json` translation files.
 *
 * The bundle lives at `.../client/dist/seo-field/main.js` and the lang files at
 * `.../client/lang/...`, so we strip the last three path segments
 * (`main.js`, `seo-field`, `dist`) and append `/lang/...`.
 *
 * `document.currentScript` is only populated while a script is executing during
 * normal parsing. When the CMS re-evaluates this bundle inside a pjax fragment
 * via jQuery.globalEval, `document.currentScript` is null — so we fall back to
 * scanning the loaded <script> tags for this bundle's URL.
 */
const resolveLangBasePath = () => {
  let src = (document.currentScript && document.currentScript.src) || '';

  if (!src) {
    const scripts = document.querySelectorAll('script[src]');
    for (let i = 0; i < scripts.length; i += 1) {
      const candidate = scripts[i].src || '';
      if (candidate.indexOf('/seo-field/main.js') !== -1) {
        src = candidate;
        break;
      }
    }
  }

  if (!src) {
    // Last-resort fallback: assume the standard exposed vendor path.
    return '/_resources/vendor/syntro/silverstripe-seo/client/lang/{{lng}}/{{ns}}.json';
  }

  return `${src.split('?')[0].split('/').slice(0, -3).join('/')}/lang/{{lng}}/{{ns}}.json`;
};

// not like to use this?
// have a look at the Quick start guide
// for passing in lng and translations on init
//
i18n
// load translation using xhr -> see /public/locales (i.e. https://github.com/i18next/react-i18next/tree/master/example/react/public/locales)
// learn more: https://github.com/i18next/i18next-xhr-backend
  .use(Backend)
// detect user language
// learn more: https://github.com/i18next/i18next-browser-languageDetector
  .use(LanguageDetector)
// pass the i18n instance to react-i18next.
  .use(initReactI18next)
// init i18next
// for all options read: https://www.i18next.com/overview/configuration-options
  .init({
    fallbackLng: 'en-US',
    debug: false,
    whitelist: [
      'en',
      'de',
    ],
    detection: { order: ['htmlTag'] },
    backend: {
      loadPath: resolveLangBasePath(),
    },
    interpolation: {
      escapeValue: false, // not needed for react as it escapes by default
    },

  });

export default i18n;
