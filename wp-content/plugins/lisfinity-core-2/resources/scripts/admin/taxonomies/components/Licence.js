/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState } from '@wordpress/element';
import axios from 'axios';
import he from 'he';
import React, { Fragment } from 'react';
import ReactSVG from 'react-svg';
import Spinner from '../../../../images/icons/spinner.svg';

const Licence = (props) => {
  const [code, setCode] = useState(null);
  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(null);
  const [verified, setVerified] = useState(false);
  const [loading, setLoading] = useState(false);

  const verifyLicence = () => {
    setLoading(true);
    if ('' === code || !code) {
      setError('Purchase code is empty');
      setLoading(false);
    }


    axios({
      url: lc_data.e_verify,
      method: 'POST',
      credentials: 'same-origin',
      data: {
        code,
      }
    }).catch(error => {
      if (error.response.data.data.status === 401) {
        setError(`Error (${he.decode(error.response.data.code)}): ${he.decode(error.response.data.message)}`);
        setLoading(false);
        return false;
      }
    }).then(response => {
      if (response?.data?.success) {
        storeLicence(response.data.e);
      }
    });
  };

  const storeLicence = (e) => {
    const headers = { 'X-WP-Nonce': lc_data.nonce };

    axios({
      url: lc_data.e_store,
      method: 'POST',
      credentials: 'same-origin',
      headers,
      data: {
        e,
      }
    }).then(response => {
      if (response.data.success) {
        setSuccess(he.decode(response.data.data.message));
        setLoading(false);
        setTimeout(() => {
          setVerified(true);
          location.reload();
        }, 3000);
      }
    });
  };

  return (
    !verified &&
    <div className="mt-30">
      <div className="flex flex-wrap items-center p-20 bg-white rounded shadow-theme w-1/3">
        <div className="w-2/3">
          <label htmlFor="code" className="lisfinity-label flex mb-10">Your Purchase Code</label>
          <div className="flex items-center px-16 bg-grey-100 border border-grey-200 rounded">
            <input id="code" type="text" name="code"
                   className="py-12 w-full bg-transparent border-0 font-semibold text-grey-700"
                   onChange={e => {
                     if (error) {
                       setError(null);
                     }
                     setCode(e.target.value);
                   }}
            />
          </div>
        </div>
        <button
          className="relative top-14 ml-10 flex justify-center items-center py-6 px-24 bg-blue-700 hover:bg-blue-800 rounded font-bold text-base text-white"
          onClick={verifyLicence}
          disabled={loading}
        >
          {loading &&
          <Fragment>
            <ReactSVG
              src={`${lc_data.dir}dist/${Spinner}`}
              className="absolute"
              style={{ zoom: .2 }}
            />
            <span className="opacity-0">Verify</span>
          </Fragment>
          }
          {!loading && <span>Verify</span>}
        </button>
        <div className="mt-10 text-sm">Please type your purchase code to enable full theme functionality. <a
          className="text-blue-700 underline"
          href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code" target="_blank">Where
          to find purchase code!</a></div>
        {error && <div className="mt-10 w-full text-sm text-red-700">{error}</div>}
        {success && <div className="mt-10 w-full text-sm text-green-700">{success}</div>}
      </div>
    </div>
  );
};

export default Licence;
