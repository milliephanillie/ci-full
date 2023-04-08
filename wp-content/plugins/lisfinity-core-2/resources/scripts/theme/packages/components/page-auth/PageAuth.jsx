/* global React, lc_data */

/**
 * External dependencies.
 */
import { BrowserRouter as Router, Route } from 'react-router-dom';
import { useEffect, useState } from '@wordpress/element';
import queryString from 'query-string';
import Breadcrumb from './partials/Breadcrumb';
import FormRegister from '../forms/FormRegister';
import { Fragment } from 'react';
import FormLogin from '../forms/FormLogin';
import FormPasswordReset from '../forms/FormPasswordReset';
import { isEmpty } from 'lodash';
import LoaderAuth from '../loaders/LoaderAuth';
import he from 'he';

const PageAuth = (props) => {
  const [loading, setLoading] = useState(true);
  const [options, setOptions] = useState(false);
  const [isSMS, setIsSMS] = useState(false);
  const [isReset, setIsReset] = useState(false);
  const [isForgot, setIsForgot] = useState(false);
  const params = queryString.parse(location.search);

  const fetchOptions = () => {
    setLoading(true);
    fetch(`${lc_data.auth_options}/?page=${lc_data.page_id}`).then(json => json.json()).then(options => {
      setOptions(options);
      if (options.page === 'register' && params.sms === 'yes') {
        setIsSMS(true);
      } else if (options.page === 'login' && params.forgot === 'yes') {
        setIsForgot(true);
      } else if (options.page === 'login' && params.reset === 'yes') {
        setIsReset(true);
      }
      setLoading(false);
    });
  };

  useEffect(() => {
    fetchOptions();
  }, []);

  return (
    <div className="auth flex flex-col bg-white md:flex-row">

      <Router>
        <div className="auth--left relative flex-center flex-col w-full md:w-1/2">
          <div className="auth--inner w-full">
            {loading && <LoaderAuth/>}
            {!loading &&
            <Fragment>
              <div className="auth--title mb-30 font-bold">
                {isSMS ?
                  <Fragment>
                    <h1 className="mb-10">{he.decode(lc_data.jst[464])}</h1>
                    <Breadcrumb title={lc_data.jst[465]} options={options}/>
                  </Fragment>
                  :
                  <Fragment>
                    <h1 className="mb-10">{he.decode(options.title)}</h1>
                    <Breadcrumb title={options.title} options={options}/>
                  </Fragment>
                }
              </div>
              <Route path={`${lc_data.site_url}${lc_data.page_register_endpoint}`}
                     render={() => <FormRegister {...props} options={options} isSMS={isSMS}/>}/>
              <Route path={`${lc_data.site_url}${lc_data.page_login_endpoint}`}
                     render={() => <FormLogin {...props} options={options}/>}/>
              <Route path={`${lc_data.site_url}${lc_data.page_reset_endpoint}`}
                     render={() => <FormPasswordReset {...props} options={options}/>}/>
            </Fragment>
            }
          </div>
        </div>
      </Router>

      <div className="auth--promo relative w-full md:w-1/2">
        {!isEmpty(options['page-auth-bg']) &&
        <img
          src={options['page-auth-bg']}
          alt={lc_data.jst[466]}
          className="absolute top-0 left-0 w-full h-full object-cover"
        />}
        {!isEmpty(options['page-auth-overlay']) &&
        <span className="absolute top-0 left-0 w-full h-full" style={{
          backgroundColor: options['page-auth-overlay'],
          opacity: options['page-auth-overlay-opacity'],
        }}></span>
        }
      </div>

    </div>
  );
};

export default PageAuth;
