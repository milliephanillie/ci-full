/* global lc_data, React */

import { BrowserRouter as Router, NavLink, Route } from 'react-router-dom';
import { __ } from '@wordpress/i18n';
import BusinessStore from './BusinessStore';
import BusinessReviews from './BusinessReviews';
import BusinessContact from './BusinessContact';
import BusinessAbout from './BusinessAbout';

const BusinessTabs = (props) => {
  const { business, options } = props;

  const url = `${lc_data['endpoint-business']}/${decodeURI(business.slug)}`;
  return (
    business &&
    <section className="business bg-grey-100">
      <Router>
        <div className="business--tabs mb-20 py-20 bg-white">
          <div className="container">
            <NavLink
              exact
              to={`${lc_data.site_url}${url}/`}
              className="py-8 px-20 font-light"
              activeClassName="bg-blue-200 rounded"
            >
              {lc_data.jst[403]}
            </NavLink>
            {options?.reviews &&
            <NavLink
              to={`${lc_data.site_url}${url}/reviews`}
              className="py-8 px-20 font-light"
              activeClassName="bg-blue-200 rounded"
            >
              {lc_data.jst[404]}
            </NavLink>
            }
            <NavLink
              to={`${lc_data.site_url}${url}/about`}
              className="py-8 px-20 font-light"
              activeClassName="bg-blue-200 rounded"
            >
              {lc_data.jst[629]}
            </NavLink>
            <NavLink
              to={`${lc_data.site_url}${url}/contact`}
              className="py-8 px-20 font-light"
              activeClassName="bg-blue-200 rounded"
            >
              {lc_data.jst[405]}
            </NavLink>
          </div>
        </div>

        <div className="container">

          <Route
            exact
            path={`${lc_data.site_url}${url}/`}
            render={() => <BusinessStore business={business} options={props.options}/>}
          />
          {options?.reviews &&
          <Route
            exact
            path={`${lc_data.site_url}${url}/reviews`}
            render={() => <BusinessReviews business={business}/>}
          />
          }
          <Route
            exact
            path={`${lc_data.site_url}${url}/about`}
            render={() => <BusinessAbout business={business}/>}
          />
          <Route
            exact
            path={`${lc_data.site_url}${url}/contact`}
            render={() => <BusinessContact options={options} business={business}/>}
          />

        </div>
      </Router>
    </section>
  );
};

export default BusinessTabs;
