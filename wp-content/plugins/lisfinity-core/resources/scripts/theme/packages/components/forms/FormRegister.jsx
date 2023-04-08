/* global React, lc_data */

/**
 * External dependencies.
 */
import { useEffect, useRef, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { isEmpty } from 'lodash';
import ReCaptcha from 'react-google-recaptcha';
import Input from '../form-fields/input/Input';
import ReactSVG from 'react-svg';
import { Fragment } from 'react';
import Checkbox from '../form-fields/checkbox/Checkbox';
import FormSMS from './FormSMS';
import UserIcon from '../../../../../images/icons/user.svg';
import EnvelopeIcon from '../../../../../images/icons/envelope.svg';
import LockIcon from '../../../../../images/icons/lock.svg';
import TabIcon from '../../../../../images/icons/tab.svg';
import LoaderIcon from '../../../../../images/icons/loader-rings-blue.svg';
import successIcon from '../../../../../images/icons/emoji-happy.svg';
import World from '../../../../../images/icons/world.svg';
import errorIcon from '../../../../../images/icons/emoji-sad.svg';
import ModalDemo from '../modal/ModalDemo';
import axios from 'axios';

const FormRegister = (props) => {
  const { options, isSMS } = props;
  const [loading, setLoading] = useState(false);
  const [errors, setErrors] = useState(false);
  const [success, setSuccess] = useState(false);
  const [redirecting, setRedirecting] = useState(false);
  const [modalDemo, setModalDemo] = useState(false);
  const recaptcha = useRef(null);

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
    if (lc_data.is_demo) {
      setModalDemo(true);
      return false;
    }
    setLoading(true);
    const data = new FormData(e.target);

    const headers = new Headers();
    fetch(lc_data.register, {
      method: 'POST',
      credentials: 'same-origin',
      //headers,
      body: data,
    }).then(json => {
    json.json().then(response => {
      if (response.error) {
        setErrors(response.error_message);
        if (recaptcha.current) {
          recaptcha.current.reset();
        }
      }
      if (response.success) {
        setSuccess(response.message);
        if (response.redirect) {
          window.location.href = response.redirect;
        }
      }
      setLoading(false);
    })
    });
  };

  return (
    <Fragment>
      {!success && isSMS &&
      <FormSMS options={options}/>
      }
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
      {!success && !isSMS &&
      <form className="form--auth -mb-20" onSubmit={e => submitForm(e)}>
        {options['choose-profile-register-form'] &&
        <div className="field flex flex-col mb-20">
          <div className="field--top flex justify-between">
            <label htmlFor="profile" className="field--label mb-4 text-sm text-grey-500">{lc_data.jst[743]}</label>
            {errors && errors['profile'] &&
            <div className="field--error text-sm text-red-700 w-2/3 text-right">{errors[name]}</div>}
          </div>
          <select
            id="profile"
            name="profile"
            className="px-14 h-44 w-full bg-grey-100 border border-grey-300 rounded text-grey-700"
            defaultValue="personal"
          >
            <option value="personal">{lc_data.jst[741]}</option>
            <option value="business">{lc_data.jst[742]}</option>
          </select>
        </div>
        }
        {options['vendor-approval'] && <div
          className="mb-20 p-20 bg-blue-100 border border-blue-300 rounded font-semibold text-blue-500">{lc_data.jst[688]}</div>
        }
        <Input
          id="username"
          name="username"
          label={lc_data.jst[422]}
          placeholder={lc_data.jst[423]}
          icon={UserIcon}
          error={errors}
          classes="h-44 p-14 border border-grey-300 rounded"
          options={{
            className: 'w-full bg-transparent text-bold',
          }}
        />
        {options && options['first-name-register-form'] &&
        <Input
          id="first_name"
          name="first_name"
          label={__('First Name', 'lisfinity-core')}
          placeholder={__('First Name', 'lisfinity-core')}
          icon={UserIcon}
          error={errors}
          classes="h-44 p-14 border border-grey-300 rounded"
        />
        }
        {options && options['last-name-register-form'] &&
        <Input
          id="last_name"
          name="last_name"
          label={__('Last Name', 'lisfinity-core')}
          placeholder={__('Last Name', 'lisfinity-core')}
          icon={UserIcon}
          error={errors}
          classes="h-44 p-14 border border-grey-300 rounded"
        />
        }
        <Input
          id="email"
          name="email"
          label={__('Email', 'lisfinity-core')}
          placeholder={lc_data.jst[435]}
          icon={EnvelopeIcon}
          error={errors}
          classes="h-44 p-14 border border-grey-300 rounded"
          options={{
            className: 'w-full bg-transparent text-bold',
            type: 'email',
          }}
        />
        <Input
          id="password"
          name="password"
          label={lc_data.jst[283]}
          placeholder={lc_data.jst[283]}
          icon={LockIcon}
          error={errors}
          classes="h-44 p-14 border border-grey-300 rounded"
          options={{
            className: 'w-full bg-transparent text-bold',
            type: 'password',
          }}
        />

        {options && ((options['auth-verification-sms'] !== 0 && options['auth-verification'] !== 0) || (options['phone-number-register-form'])) &&
        <Input
          id="phone"
          name="phone"
          label={__('Phone', 'lisfinity-core')}
          placeholder={lc_data.jst[431]}
          description={(options['auth-verification-sms'] !== 0 && options['auth-verification'] !== 0) ? lc_data.jst[432] : false}
          icon={TabIcon}
          error={errors}
          classes="h-44 p-14 border border-grey-300 rounded"
          options={{
            className: 'w-full bg-transparent text-bold',
            type: 'tel',
          }}
        />}
        {options && options['website-register-form'] &&
        <Input
          id="website"
          name="website"
          label={__('Website', 'lisfinity-core')}
          placeholder={lc_data.jst[735]}
          icon={World}
          error={errors}
          classes="h-44 p-14 border border-grey-300 rounded"
        />
        }

        {options && options['page-terms'] &&
        <Checkbox
          id="terms"
          name="terms"
          label={options['page-terms-label']}
          error={errors}
          classes="field--checkbox__auth"
        />
        }

        {options && options['auth-enabled'] !== 0 && options['auth-captcha-enabled'] !== 0 &&
        <div className="field mb-20">
          <label htmlFor="g-captcha" className="flex mb-4 text-sm text-grey-500">{options['auth-captcha-label']}</label>
          <div className="field--wrapper">
            <ReCaptcha
              ref={recaptcha}
              sitekey={options['auth-captcha-site-key']}
            />
          </div>
        </div>
        }

        <button type="submit"
                className="btn text-lg flex-center w-full h-48 bg-green-700 font-bold hover:bg-green-900">
          <Fragment>
            {!loading &&
            <Fragment>
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

        <div className="flex justify-center items-center mt-20">
          <span className="text-grey-1100">{lc_data.jst[557]}</span>
          <a href={lc_data.page_login}
             className="relative btn-auth-redirect btn flex-center ml-6 p-0 font-semibold text-blue-700 hover:underline"
             onClick={() => setRedirecting(true)}
          >
            <Fragment>
              {lc_data.jst[558]}
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

      <ModalDemo
        key={41}
        isLogged={lc_data.logged_in}
        open={modalDemo}
        closeModal={() => setModalDemo(false)}
        title={lc_data.jst[606]}
      >
        <div className="font-semibold text-lg text-grey-700" dangerouslySetInnerHTML={{
          __html: lc_data.jst[627],
        }}
        />
        <div>
          <div className="flex w-full mt-10">
            <button
              className="btn bg-blue-700 rounded text-white"
              onClick={() => loginDemo()}
            >
              {lc_data.jst[626]}
            </button>
          </div>
        </div>
      </ModalDemo>
    </Fragment>
  );
};

export default FormRegister;
