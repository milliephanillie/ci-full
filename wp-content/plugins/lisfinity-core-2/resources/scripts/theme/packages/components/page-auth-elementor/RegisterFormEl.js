/* global lc_data, React */
/**
 * External dependencies.
 */
import {Fragment, useState, useEffect, useRef} from '@wordpress/element';
import lockIcon from "../../../../../images/icons/lock.svg";
import {useSelector} from "react-redux";
import InputEl from "./InputEl";
import FormSMS from "../forms/FormSMS";
import {isEmpty} from "lodash";
import ReactSVG from "react-svg";
import successIcon from "../../../../../images/icons/emoji-happy.svg";
import UserIcon from "../../../../../images/icons/user.svg";
import {__} from "@wordpress/i18n";
import EnvelopeIcon from "../../../../../images/icons/envelope.svg";
import TabIcon from "../../../../../images/icons/tab.svg";
import Checkbox from "../form-fields/checkbox/Checkbox";
import ReCaptcha from "react-google-recaptcha";
import LoaderIcon from "../../../../../images/icons/loader-rings-blue.svg";
import errorIcon from "../../../../../images/icons/emoji-sad.svg";
import ModalDemo from "../modal/ModalDemo";
import queryString from "query-string";

const RegisterFormEl = (props) => {
  const data = useSelector(state => state);
  const [settings, setSettings] = useState({});
  const [errors, setErrors] = useState(false);
  const [success, setSuccess] = useState(false);
  const [redirecting, setRedirecting] = useState(false);
  const [modalDemo, setModalDemo] = useState(false);
  const [loading, setLoading] = useState(true);
  const [options, setOptions] = useState(false);
  const [isSMS, setIsSMS] = useState(false);
  const [isReset, setIsReset] = useState(false);
  const [isForgot, setIsForgot] = useState(false);
  const params = queryString.parse(location.search);

  const wrapper = useRef(null);
  const recaptcha = useRef(null);

  let elementorPreview = Object.keys(queryString.parse(location.search)).find(key => key === 'elementor-preview');

  useEffect(() => {
    const el = wrapper.current && wrapper.current.closest('.elementor-register-form');
    if (el) {
      const settingsData = JSON.parse(el.dataset.settings);
      setSettings(settingsData);
    }

  }, []);

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
      headers,
      body: data,
    }).then(json => json.json().then(response => {
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
    }));
  };

  const submitButtonText = settings?.register_form_button_text ? settings.register_form_button_text : lc_data.jst[434];

  const haveAccountText = settings?.have_account_text ? settings.have_account_text : lc_data.jst[557];

  const haveAccountLinkText = settings?.have_account_link_text ? settings.have_account_link_text : lc_data.jst[558];

  let fieldsArray = [];

  let terms = null;

  let captcha = null;


  {
    settings?.fields?.map(field => {
      if (field.fields !== 'terms' && field.fields !== 'captcha') {
        fieldsArray.push(
          <InputEl
            id={field.fields}
            key={field._id}
            elementId={field._id}
            name={field.fields}
            label={field.fields}
            placeholder={`Your ${field.fields}`}
            icon={field.selected_icon_field && field.selected_icon_field.library !== '' ? field.selected_icon_field : (!field?.selected_icon_field || field?.selected_icon_field?.library === '') && field.fields === 'phone' ? TabIcon : ((!field?.selected_icon_field || field?.selected_icon_field?.library === '') && (field.fields === 'username' || field.fields === 'last_name' || field.fields === 'first_name')) ? UserIcon : (!field?.selected_icon_field || field?.selected_icon_field?.library === '') && field.fields === 'password' ? lockIcon : (!field?.selected_icon_field || field?.selected_icon_field?.library === '') && field.fields === 'email' ? EnvelopeIcon : ''}
            error={errors}
            classes="h-44 p-14 border border-grey-300 rounded"
            options={{
              className: 'bg-transparent text-bold w-full',
              type: `${field.fields === 'password' ? 'password' : field.fields === 'email' ? 'email' : field.fields === 'phone' ? 'tel' : ''}`
            }}
          />)
      } else if (field.fields === 'terms') {
        terms = (
          <Checkbox
            id={field.fields}
            name={field.fields}
            label={settings['page-terms-label']}
            error={errors}
            classes="field--checkbox__auth"
          />
        )
      } else {
        captcha = (
          <Fragment>
            <label htmlFor="g-captcha"
                   className="flex mb-4 text-sm text-grey-500">{settings['auth-captcha-label']}</label>
            <div className="field--wrapper">
              <ReCaptcha
                ref={recaptcha}
                sitekey={settings['auth-captcha-site-key']}
              />
            </div>
          </Fragment>
        )
      }
    })

  }

  return (
    <div ref={wrapper}>
      {(elementorPreview || lc_data.current_user_id === '0') &&
      <Fragment>
        {!success && isSMS &&
        <FormSMS options={options}/>
        }
        {!isEmpty(success) &&
        <div className="relative mt-20 p-20 bg-green-600 rounded font-semibold text-lg text-white">
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
          {options['vendor-approval'] && <div
            className="mb-20 p-20 bg-blue-100 border border-blue-300 rounded font-semibold text-blue-500">{lc_data.jst[688]}</div>}
          {fieldsArray}
          {settings && settings['page-terms'] &&
          <Fragment>{terms}</Fragment>
          }

          {settings && settings['auth-enabled'] !== 0 && settings['auth-captcha-enabled'] !== 0 &&
          <div className="field mb-20">
            {settings['auth-captcha-site-key'] &&
            <Fragment>
              {captcha}
            </Fragment>
            }
          </div>
          }
          <div className="register-button-wrapper">
            <button type="submit"
                    className="btn text-lg flex-center w-full register-btn h-48 bg-green-700 font-bold hover:bg-green-900">
              <Fragment>
                {!loading &&
                <Fragment>
                  {settings?.register_form_button_submit_icon?.value && settings?.register_form_button_submit_icon?.library !== 'svg' &&
                  <i className={`mr-8 ${settings.register_form_button_submit_icon.value} login-btn-icon`}></i>
                  }
                  {settings?.register_form_button_submit_icon?.value.url && settings?.register_form_button_submit_icon?.library === 'svg' &&
                  <ReactSVG
                    src={`${settings.register_form_button_submit_icon.value.url}`}
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

          <div className="have-account-container">
            <div className="flex justify-center items-center mt-20 have-account-wrapper">
              <span className="text-grey-1100 have-account-text">{haveAccountText}</span>
              <a href={lc_data.page_login}
                 className="relative btn-auth-redirect btn flex-center have-account-link ml-6 p-0 font-semibold text-blue-700 hover:underline"
                 onClick={() => setRedirecting(true)}
              >
                <Fragment>
                  {haveAccountLinkText}
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
          </div>
        </form>
        }
        {!isEmpty(options['social-login']) &&
        <div className="mt-40" dangerouslySetInnerHTML={{__html: options['social-login']}}/>}

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
      }
    </div>
  );
};

export default RegisterFormEl;
