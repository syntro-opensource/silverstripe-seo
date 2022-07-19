import React from 'react';
import PropTypes from 'prop-types';
import useSERP from 'Hooks/useSERP';
import SERPBreadcrumbs from './SERPBreadcrumbs';
import SERPTitle from './SERPTitle';
import SERPSnippet from './SERPSnippet';

/**
 * Renders a SERP preview
 */
const SERP = (props) => {
  const { rootUrl, link, keyword } = props;

  const { breadcrumbs, title, snippet } = useSERP(rootUrl, link, keyword);

  return (
    <div
      className="serp-preview card shadow"
      style={{

      }}
    >
      <div className="serp-preview__body card-body" style={{ fontFamily: 'arial,sans-serif' }}>
        <SERPBreadcrumbs breadcrumbs={breadcrumbs} keyword={keyword} />
        <SERPTitle title={title} link={link} keyword={keyword} />
        <SERPSnippet snippet={snippet} keyword={keyword} />
      </div>
    </div>
  );
};

SERP.defaultProps = {
  rootUrl: 'localhost',
  link: '/',
  keyword: '',
};

SERP.propTypes = {
  rootUrl: PropTypes.string,
  link: PropTypes.string,
  keyword: PropTypes.string,
};

export default SERP;
