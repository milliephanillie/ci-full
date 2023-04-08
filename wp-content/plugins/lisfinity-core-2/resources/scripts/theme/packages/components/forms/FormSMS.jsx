/* global React, lc_data */

/**
 * External dependencies.
 */
import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import queryString from 'query-string';
import { isEmpty } from 'lodash';
import Input from '../form-fields/input/Input';
import ReactSVG from 'react-svg';
import homeIcon from '../../../../../images/icons/home.svg';
import { Fragment } from 'react';
import LoaderIcon from '../../../../../images/icons/loader-rings-white.svg';
import axios from 'axios';
import successIcon from '../../../../../images/icons/emoji-happy.svg';
import errorIcon from '../../../../../images/icons/emoji-sad.svg';

const params = queryString.parse(location.search);

const FormSMS = (props) => {
  const { options } = props;
  const [loading, setLoading] = useState(false);
  const [errors, setErrors] = useState(false);
  const [success, setSuccess] = useState(false);
  const [notice, setNotice] = useState(false);

  const submitForm = (e) => {
    e.preventDefault();
    setLoading(true);
    const data = new FormData(e.target);

    const headers = new Headers();
    headers.append('X-WP-Nonce', lc_data.nonce);
    data.append('id', params.id);
    fetch(lc_data.sms_verify, {
      method: 'POST',
      credentials: 'same-origin',
      //headers,
      body: data,
    }).then(json => json.json().then(response => {
      if (response.error) {
        setErrors(response.error_message);
      }
      if (response.success) {
        setSuccess(response.message);
        if (response.redirect) {
          window.location.href = response.redirect;
        }
      }
      setLoading(false);
    }));
  };

  const resend_sms = (contactAdmin = false) => {
    setLoading(true);
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const data = {
      user_id: params.id,
    };
    if (contactAdmin) {
      data.contactAdmin = true;
    }
    axios({
      credentials: 'same-origin',
      //headers,
      method: 'POST',
      url: lc_data.sms_resend,
      data,
    }).then(response => {
      if (response.data.error) {
        setErrors(response.data.error_message);
      }
      if (response.data.success) {
        setNotice(response.data.message);
      }
      setLoading(false);
    });
  };

  return (
    <Fragment>
      {!isEmpty(success) && <div className="relative mt-20 p-20 bg-green-600 rounded font-semibold text-lg text-white">
        <ReactSVG
          src={`${lc_data.dir}dist/${successIcon}`}
          className="absolute fill-white"
          style={{
            top: 0,
            left: -20,
            width: 80,
            height: 80,
            opacity: .15,
          }}
        />
        {success}
      </div>}
      {!success &&
      <form className="form--auth -mb-20" onSubmit={e => submitForm(e)}>
        <Input
          id="sms_code"
          name="sms_code"
          label={lc_data.jst[436]}
          icon={homeIcon}
          error={errors}
          classes="h-44 p-14 border border-grey-300 rounded"
          options={{
            className: 'bg-transparent text-bold w-full',
          }}
        />

        <div className="flex justify-end -mt-10 mb-20">
          {!notice &&
          <button
            type="button"
            className="text-red-700"
            data-animation="ripple"
            onClick={() => resend_sms()}
          >
            {lc_data.jst[574]}
          </button>
          }
          {notice &&
          <div className="flex flex-col items-end text-green-700"
          >
            {notice}
            <button
              type="button"
              className="text-red-700"
              data-animation="ripple"
              onClick={() => resend_sms()}
            >
              {lc_data.jst[574]}
            </button>
          </div>
          }
        </div>

        <button type="submit" className="btn flex-center w-full h-44 bg-blue-700 font-bold hover:bg-blue-900">
          <Fragment>
            {!loading &&
            <Fragment>
              <ReactSVG
                src={`${lc_data.dir}dist/${homeIcon}`}
                className="relative mr-8 w-16 h-16 fill-white"
              />
              {lc_data.jst[434]}
            </Fragment>
            }
            {loading &&
            <ReactSVG
              src={`${lc_data.dir}dist/${LoaderIcon}`}
              className="relative"
              style={{ zoom: 0.8 }}
            />
            }
          </Fragment>
        </button>
        {!isEmpty(errors.global) &&
        <div className="relative mt-20 p-20 bg-red-600 rounded font-semibold text-lg text-white">
          <ReactSVG
            src={`${lc_data.dir}dist/${errorIcon}`}
            className="absolute fill-white"
            style={{
              top: -6,
              left: -20,
              width: 80,
              height: 80,
              opacity: .15,
            }}
          />
          {errors.global}
        </div>}
      </form>}
    </Fragment>
  );
};

export default FormSMS;
