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

const LoginFormEl = (props) => {
  const data = useSelector(state => state);
  const {options} = data;
  const [loading, setLoading] = useState(false);
  const [errors, setErrors] = useState(false);
  const [success, setSuccess] = useState(false);
  const [redirecting, setRedirecting] = useState(false);
  const [settings, setSettings] = useState({});

  const wrapper = useRef(null);

  let elementorPreview = Object.keys(queryString.parse(location.search)).find(key => key === 'elementor-preview');

  useEffect(() => {
    const el = wrapper.current && wrapper.current.closest('.elementor-login-form');
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
    fetch(lc_data.login, {
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
      setLoading(false);
    }));
  };

  const forgotPasswordText = settings?.forgot_password_text ? settings.forgot_password_text : lc_data.jst[425];

  const submitButtonText = settings?.login_form_button_text ? settings.login_form_button_text : lc_data.jst[419];

  const noAccountText = settings?.create_account_text ? settings.create_account_text : lc_data.jst[420];

  const noAccountLinkText = settings?.create_account_link_text ? settings.create_account_link_text : lc_data.jst[653];

  let fieldsArray = [];
  let rememberMe = null;

  {
    settings?.fields?.map(field => {
      if (field.fields !== 'rememberme') {
        fieldsArray.push(
          <InputEl
            id={field.fields}
            key={field._id}
            elementId={field._id}
            name={field.fields}
            label={field.fields}
            placeholder={`Your ${field.fields}`}
            icon={field.selected_icon_field && field.selected_icon_field.library !== '' ? field.selected_icon_field : (!field?.selected_icon_field || field?.selected_icon_field?.library === '') && field.fields === 'username' ? homeIcon : (!field?.selected_icon_field || field?.selected_icon_field?.library === '') && field.fields === 'password' ? lockIcon : ''}
            error={errors}
            classes="h-44 p-14 border border-grey-300 rounded"
            options={{
              className: 'bg-transparent text-bold w-full',
              type: `${field.fields === 'password' ? 'password' : ''}`
            }}
          />)
      } else {
        rememberMe = (
          <Checkbox
            id={field.fields}
            name={field.fields}
            label={lc_data.jst[424]}
            error={errors}
            classes="field--checkbox__auth"
          />
        );
      }
    })
  }

  return (
    <div ref={wrapper}>
      {( elementorPreview || lc_data.current_user_id === '0') &&
      <Fragment>
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
          {fieldsArray}
          <div className="flex justify-between">
            {rememberMe &&
            <Fragment>
              {rememberMe}</Fragment>
            }
            <a
              href={`${lc_data.site_url}${lc_data.page_reset_endpoint}`}
              className="text-red-700 forgot-password hover:underline"
              data-animation="ripple"
            >
              {forgotPasswordText}
            </a>
          </div>
          <div className="button-wrapper">
            <button type="submit"
                    className="btn login-submit-btn text-lg flex-center w-full h-44 bg-blue-700 font-bold hover:bg-blue-900">
              <Fragment>
                {!loading &&
                <Fragment>
                  {settings?.login_form_button_submit_icon?.value && settings?.login_form_button_submit_icon?.library !== 'svg' &&
                  <i className={`mr-8 ${settings.login_form_button_submit_icon.value} login-btn-icon`}></i>
                  }
                  {settings?.login_form_button_submit_icon?.value.url && settings?.login_form_button_submit_icon?.library === 'svg' &&
                  <ReactSVG
                    src={`${settings.login_form_button_submit_icon.value.url}`}
                    className={`mr-8 w-18 h-18 login-btn-icon`}
                  />
                  }
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

          <div className="flex justify-center no-account-wrapper items-center mt-20">
            <span className="text-grey-1100 noaccount-text">{noAccountText}</span>
            <a href={lc_data.page_register}
               className="relative btn-auth-redirect btn flex-center ml-6 noaccount-link p-0 font-semibold text-blue-700 hover:underline"
               onClick={() => setRedirecting(true)}
            >
              <Fragment>
                {noAccountLinkText}
                {redirecting &&
                <ReactSVG
                  src={`${lc_data.dir}dist/${LoaderIcon}`}
                  className="btn-loader absolute top-2 fill-blue-700"
                  style={{zoom: 0.4}}
                />
                }
              </Fragment>
            </a>
          </div>

        </form>
        }
        {!isEmpty(options['social-login']) &&
        <div className="mt-40" dangerouslySetInnerHTML={{__html: options['social-login']}}/>}
      </Fragment>
      }
    </div>
  );
};

export default LoginFormEl;
