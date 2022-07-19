import React, { useState } from 'react';
import PropTypes from 'prop-types';
import DOMContextProvider from 'Components/DOMContext';
import { SERP, Analysis } from 'Components/Tabs';
import { Nav, NavItem, NavLink } from 'reactstrap';
import classnames from 'classnames';
import '../i18n';
import { useTranslation } from 'react-i18next';

const SEOAnalysisField = (props) => {
  const { link, rootUrl, keyword } = props;
  const [openTab, setOpenTab] = useState('1');
  const { t } = useTranslation();
  const handleTabClick = (newTab) => setOpenTab(newTab);
  return (
    <div className="bg-white shadow-sm px-3 pb-3 rounded border-secondary" style={{ position: 'relative' }}>
      <DOMContextProvider link={link}>
        <Nav tabs>
          <NavItem>
            <NavLink
              className={classnames({ active: openTab === '1' })}
              onClick={() => handleTabClick('1')}
            >
              {t('tabs.ANALYSIS', 'Analysis')}
            </NavLink>
          </NavItem>
          {/* <NavItem>
            <NavLink
              className={classnames({ active: openTab === '2' })}
              onClick={() => handleTabClick('2')}
            >
              Document Structure
            </NavLink>
          </NavItem> */}
          <NavItem>
            <NavLink
              className={classnames({ active: openTab === '3' })}
              onClick={() => handleTabClick('3')}
            >
              {t('tabs.SERP', 'SERP')}
            </NavLink>
          </NavItem>
        </Nav>
        {openTab === '1' && <Analysis link={link} keyword={keyword} rootUrl={rootUrl} />}
        {openTab === '3' && <SERP link={link} keyword={keyword} rootUrl={rootUrl} />}
      </DOMContextProvider>
    </div>
  );
};

SEOAnalysisField.defaultProps = {
  link: '',
  rootUrl: '',
  keyword: '',
};

SEOAnalysisField.propTypes = {
  link: PropTypes.string,
  rootUrl: PropTypes.string,
  keyword: PropTypes.string,
};

export default SEOAnalysisField;
