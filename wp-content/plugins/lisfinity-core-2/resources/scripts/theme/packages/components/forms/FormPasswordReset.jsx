/* global React, lc_data */

/**
 * External dependencies.
 */
import { NavLink } from 'react-router-dom';
import { useEffect, useState } from '@wordpress/element';
import queryString from 'query-string';
import { __ } from '@wordpress/i18n';
import { isEmpty } from 'lodash';
import Input from '../form-fields/input/Input';
import ReactSVG from 'react-svg';
import homeIcon from '../../../../../images/icons/home.svg';
import personIcon from '../../../../../images/icons/user.svg';
import { Fragment } from 'react';
import LoaderIcon from '../../../../../images/icons/loader-rings-white.svg';
import successIcon from '../../../../../images/icons/emoji-happy.svg';
import errorIcon from '../../../../../images/icons/emoji-sad.svg';

const FormPasswordReset = (props) => {
  const { options } = props;
  const [loading, setLoading] = useState(false);
  const [isReset, setIsReset] = useState(false);
  const [showLogin, setShowLogin] = useState(false);
  const [errors, setErrors] = useState(false);
  const [success, setSuccess] = useState(false);
  const params = queryString.parse(location.search);

  const submitForm = (e) => {
    e.preventDefault();
    setLoading(true);
    const data = new FormData(e.target);

    const headers = new Headers();
    if (isReset) {
      data.append('cookie', document.cookie);
    }
    const url = isReset ? lc_data.reset : lc_data.forgot;
    fetch(url, {
      method: 'POST',
      credentials: 'same-origin',
      headers,
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
      if (response.show_login) {
        setShowLogin(true);
      }
      if (response.code === 'rest_cookie_invalid_nonce') {
        setErrors({ global: response.message });
      }
      setLoading(false);
    }));
  };

  useEffect(() => {
    if (params && params.reset) {
      setIsReset(true);
    }
  }, []);

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
      {!success && <form className="form--auth -mb-20" onSubmit={e => submitForm(e)}>

        {!isReset &&
        <Input
          id="user_login"
          name="user_login"
          label={lc_data.jst[429]}
          icon={personIcon}
          error={errors}
          classes="h-44 p-14 border border-grey-300 rounded"
          options={{
            className: 'w-full bg-transparent text-bold',
          }}
          placeholder={lc_data.jst[429]}
        />}

        {isReset &&
        <Fragment>
          <Input
            id="password_1"
            name="password_1"
            label={lc_data.jst[430]}
            icon={personIcon}
            error={errors}
            classes="h-44 p-14 border border-grey-300 rounded"
            options={{
              className: 'w-full bg-transparent text-bold',
              type: 'password',
            }}
          />
          <Input
            id="password_2"
            name="password_2"
            label={lc_data.jst[426]}
            icon={personIcon}
            error={errors}
            classes="h-44 p-14 border border-grey-300 rounded"
            options={{
              className: 'w-full bg-transparent text-bold',
              type: 'password',
            }}
          />
        </Fragment>
        }

        {(!isReset || showLogin) &&
        <div className="flex justify-end mb-20">
          <NavLink
            to={`${lc_data.site_url}${lc_data.page_login_endpoint}`}
            className="text-red-700 hover:underline"
          >
            {lc_data.jst[427]}
          </NavLink>
        </div>
        }

        <button type="submit" className="btn text-lg flex-center w-full h-44 bg-blue-700 font-bold hover:bg-blue-900">
          <Fragment>
            {!loading &&
            <Fragment>
              {lc_data.jst[428]}
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

export default FormPasswordReset;
