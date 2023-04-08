/* global React, lc_data */

/**
 * External dependencies.
 */
import { NavLink } from 'react-router-dom';
import { useState } from '@wordpress/element';
import { isEmpty } from 'lodash';
import Input from '../form-fields/input/Input';
import ReactSVG from 'react-svg';
import homeIcon from '../../../../../images/icons/home.svg';
import lockIcon from '../../../../../images/icons/lock.svg';
import LoaderIcon from '../../../../../images/icons/loader-rings-blue.svg';
import successIcon from '../../../../../images/icons/emoji-happy.svg';
import { Fragment } from 'react';
import Checkbox from '../form-fields/checkbox/Checkbox';
import errorIcon from '../../../../../images/icons/emoji-sad.svg';
import axios from 'axios';

const FormLogin = (props) => {
  const { options } = props;
  const [loading, setLoading] = useState(false);
  const [errors, setErrors] = useState(false);
  const [success, setSuccess] = useState(false);
  const [redirecting, setRedirecting] = useState(false);

  const loginDemo = async () => {
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const url = lc_data.login_demo;
    await axios({
      credentials: 'same-origin',
      //headers,
      method: 'post',
      url,
      data: {
        username: 'demo',
        password: 'demo',
        redirect: window.location.href,
      }
    }).then(response => {
      if (response.data.success) {
        window.location.href = response.data.redirect;
      }
    });
  };

  const submitForm = (e) => {
    e.preventDefault();
    setLoading(true);
    const data = new FormData(e.target);

    const headers = new Headers();
    fetch(lc_data.login, {
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

  return (
    <Fragment>
      {!success && lc_data.is_demo &&
      <div className="relative flex items-center mb-20 p-20 bg-red-100 rounded font-semibold text-red-1000">
        {lc_data.jst[628]}
        <div className="flex flex-wrap ml-auto">
          {/*<div className="flex flex-col font-normal">
            <div>username: <strong>demo</strong></div>
            <div>password: <strong>demo</strong></div>
          </div>*/}
          <div className="flex w-full">
            <button
              className="btn bg-red-600 rounded text-white leading-none hover:bg-red-700"
              onClick={() => loginDemo()}
            >
              {lc_data.jst[626]}
            </button>
          </div>
        </div>

      </div>
      }

      {!isEmpty(success) &&
      <div className="relative mt-20 p-20 bg-green-600 rounded font-semibold text-lg text-white">
        <ReactSVG
          src={`${lc_data.dir}dist/${successIcon}`}
          className="absolute fill-white"
          style={{
            top: 4,
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
          id="username"
          name="username"
          label={lc_data.jst[422]}
          placeholder={lc_data.jst[423]}
          icon={homeIcon}
          error={errors}
          classes="h-44 p-14 border border-grey-300 rounded"
          options={{
            className: 'bg-transparent text-bold w-full',
          }}
        />
        <Input
          id="password"
          name="password"
          label={lc_data.jst[283]}
          placeholder={lc_data.jst[283]}
          icon={lockIcon}
          error={errors}
          classes="h-44 p-14 border border-grey-300 rounded"
          options={{
            className: 'bg-transparent text-bold w-full',
            type: 'password',
          }}
        />

        <div className="flex justify-between">
          <Checkbox
            id="rememberme"
            name="rememberme"
            label={lc_data.jst[424]}
            error={errors}
            classes="field--checkbox__auth"
          />
          <NavLink
            to={`${lc_data.site_url}${lc_data.page_reset_endpoint}`}
            className="text-red-700 hover:underline"
            data-animation="ripple"
          >
            {lc_data.jst[425]}
          </NavLink>
        </div>

        <button type="submit" className="btn text-lg flex-center w-full h-44 bg-blue-700 font-bold hover:bg-blue-900">
          <Fragment>
            {!loading &&
            <Fragment>
              {lc_data.jst[419]}
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

        <div className="flex justify-center items-center mt-20">
          <span className="text-grey-1100">{lc_data.jst[420]}</span>
          <a href={lc_data.page_register}
             className="relative btn-auth-redirect btn flex-center ml-6 p-0 font-semibold text-blue-700 hover:underline"
             onClick={() => setRedirecting(true)}
          >
            <Fragment>
              {lc_data.jst[653]}
              {redirecting &&
              <ReactSVG
                src={`${lc_data.dir}dist/${LoaderIcon}`}
                className="btn-loader absolute top-2 fill-blue-700"
                style={{ zoom: 0.4 }}
              />
              }
            </Fragment>
          </a>
        </div>

      </form>
      }
      {!isEmpty(options['social-login']) &&
      <div className="mt-40" dangerouslySetInnerHTML={{ __html: options['social-login'] }}/>}
    </Fragment>
  );
};

export default FormLogin;
