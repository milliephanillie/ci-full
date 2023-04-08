/* global lc_data, React */
/**
 * External dependencies.
 */
import {Fragment, useState, useEffect, useRef} from '@wordpress/element';
import {isEmpty} from 'lodash';
import ReactSVG from "react-svg";
import successIcon from "../../../../../images/icons/emoji-happy.svg";
import homeIcon from "../../../../../images/icons/home.svg";
import lockIcon from "../../../../../images/icons/lock.svg";
import Checkbox from "../form-fields/checkbox/Checkbox";
import LoaderIcon from "../../../../../images/icons/loader-rings-blue.svg";
import errorIcon from "../../../../../images/icons/emoji-sad.svg";
import {useSelector} from "react-redux";
import InputEl from "./InputEl";
import queryString from "query-string";
import Input from "../form-fields/input/Input";
import personIcon from "../../../../../images/icons/user.svg";
import {NavLink} from "react-router-dom";

const PasswordResetFormEl = (props) => {
  const data = useSelector(state => state);
  const {options} = data;
  const [loading, setLoading] = useState(false);
  const [errors, setErrors] = useState(false);
  const [success, setSuccess] = useState(false);
  const [redirecting, setRedirecting] = useState(false);
  const [settings, setSettings] = useState({});
  const [isReset, setIsReset] = useState(false);
  const [showLogin, setShowLogin] = useState(false);
  const params = queryString.parse(location.search);

  const wrapper = useRef(null);

  let elementorPreview = Object.keys(queryString.parse(location.search)).find(key => key === 'elementor-preview');

  useEffect(() => {
    const el = wrapper.current && wrapper.current.closest('.elementor-password-reset-form');
    if (el) {
      const settingsData = JSON.parse(el.dataset.settings);
      setSettings(settingsData);
    }

  }, []);

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
        setErrors({global: response.message});
      }
      setLoading(false);
    }));
  };
  useEffect(() => {
    if (params && params.reset) {
      setIsReset(true);
    }
  }, []);


  const submitButtonText = settings?.reset_password_button_text ? settings.reset_password_button_text : lc_data.jst[428];
  const successMessage = settings?.success_message_text ? settings.success_message_text : success ? success : lc_data.jst[725];
  const backTologinText = settings?.back_to_login_text ? settings.back_to_login_text : lc_data.jst[427];
  const errorMessage = settings?.error_message_text ? settings.error_message_text :  errors.global  ?  errors.global  : lc_data.jst[726];

  return (
    <div ref={wrapper}>
      {(!isEmpty(success) || elementorPreview) && <div className="success-message-wrapper">
        <div className="relative mt-20 p-20 bg-green-600 success-message rounded font-semibold text-lg text-white">
          {(!settings?.success_message_icon?.value || !settings?.success_message_icon) &&
          <ReactSVG
            src={`${lc_data.dir}dist/${successIcon}`}
            className="absolute fill-white success-message-icon"
          />}
          {settings?.success_message_icon?.value && settings?.success_message_icon?.library !== 'svg' &&
          <i className={`mr-8 ${settings.success_message_icon.value} absolute success-message-icon`}></i>
          }
          {settings?.success_message_icon?.value.url && settings?.success_message_icon?.library === 'svg' &&
          <ReactSVG
            src={`${settings.success_message_icon.value.url}`}
            className={`mr-8 w-18 h-18 absolute success-message-icon`}
          />}
          {successMessage}
        </div>
      </div>}
      {!success && <form className="form--auth -mb-20" onSubmit={e => submitForm(e)}>

        {!isReset &&
        <InputEl
          id="user_login"
          name="user_login"
          label='user_login'
          icon={settings.reset_password_input_submit_icon && settings.reset_password_input_submit_icon.library !== '' ? settings.reset_password_input_submit_icon : personIcon}
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
          <a
            href={`${lc_data.site_url}${lc_data.page_login_endpoint}`}
            className="text-red-700 back-to-login hover:underline"
          >
            {backTologinText}
          </a>
        </div>
        }

        <div className='button-wrapper'>
          <button type="submit"
                  className="btn password-reset-submit-btn text-lg flex-center w-full h-44 bg-blue-700 font-bold hover:bg-blue-900">
            <Fragment>
              {!loading &&
              <Fragment>
                {settings?.reset_password_button_submit_icon?.value && settings?.reset_password_button_submit_icon?.library !== 'svg' &&
                <i className={`mr-8 ${settings.reset_password_button_submit_icon.value} password-reset-btn-icon`}></i>
                }
                {settings?.reset_password_button_submit_icon?.value.url && settings?.reset_password_button_submit_icon?.library === 'svg' &&
                <ReactSVG
                  src={`${settings.reset_password_button_submit_icon.value.url}`}
                  className={`mr-8 w-18 h-18 password-reset-btn-icon`}
                />}
                {submitButtonText}
              </Fragment>
              }
              {loading &&
              <ReactSVG
                src={`${lc_data.dir}dist/${LoaderIcon}`}
                className="relative"
                style={{zoom: 0.8}}
              />
              }
            </Fragment>
          </button>
        </div>
        {(!isEmpty(errors.global) || elementorPreview) && <div className="error-message-wrapper">
          <div className="relative mt-20 p-20 error-message bg-red-600 rounded font-semibold text-lg text-white">
            {(!settings?.error_message_icon?.value || !settings?.error_message_icon) &&
            <ReactSVG
              src={`${lc_data.dir}dist/${errorIcon}`}
              className="absolute fill-white error-message-icon"
            />}
            {settings?.error_message_icon?.value && settings?.error_message_icon?.library !== 'svg' &&
            <i className={`mr-8 ${settings.error_message_icon.value} absolute error-message-icon`}></i>
            }
            {settings?.error_message_icon?.value.url && settings?.error_message_icon?.library === 'svg' &&
            <ReactSVG
              src={`${settings.error_message_icon.value.url}`}
              className={`mr-8 w-18 h-18 absolute error-message-icon`}
            />}
            {errorMessage}

          </div>
        </div>}
      </form>}
    </div>
  );
};

export default PasswordResetFormEl;
