import { useContext } from 'react';
import { DOMContext } from 'Components/DOMContext';
import analyses from 'Analysis/index';
import { useTranslation } from 'react-i18next';

/**
 * useAnalysis - Hook to render the SERP Data
 *
 * @param  {string} baseUrl the base url
 * @param  {string} link    the link of the page to generate the SERP from
 * @param  {string} keyword the keyword to generate the SERP for
 * @return {object}         the data used for rendering the field
 */
function useAnalysis(baseUrl, link, keyword) {
  const page = useContext(DOMContext);
  const { t } = useTranslation();
  const results = [];
  if (page) {
    const parser = new DOMParser();
    const dom = parser.parseFromString(page, 'text/html');
    for (let i = 0; i < analyses.length; i += 1) {
      results.push(analyses[i](dom, keyword, t, baseUrl, link));
    }
  }

  return {
    results,
  };
}

export default useAnalysis;
