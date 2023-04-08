/* global lc_data, React */
/**
 * Dependencies.
 */
import {useDispatch, useSelector} from 'react-redux';
import {useEffect, useState} from '@wordpress/element';
import {__} from '@wordpress/i18n';
import {map, isEmpty} from 'lodash';
import ReactSVG from 'react-svg';
import SaveIcon from '../../../../../../../images/icons/save.svg';
import axios from 'axios';
import {toast} from 'react-toastify';
import * as actions from '../../../../store/actions';
import LoaderIcon from '../../../../../../../images/icons/loader-rings-white.svg';
import {Fragment} from 'react';
import ModalDemo from '../../../../../../theme/packages/components/modal/ModalDemo';

const MyAccountBilling = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const {loading, business, menuOpen, profile, options} = data;
  const [errors, setErrors] = useState(null);
  const [country, setCountry] = useState(null);
  const [states, setStates] = useState(null);
  const [fetching, setFetching] = useState(false);
  const [modalOpen, setModalOpen] = useState(false);

  const getStates = () => {
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url: lc_data.get_country_states,
      data: {
        country,
      },
    }).then(result => {
      setStates(result.data);
    });
  };

  useEffect(() => {
    if (country === null && profile.form) {
      setStates(profile.form.country_states);
    }
  });

  useEffect(() => {
    if (country) {
      getStates();
    }
  }, [country]);

  const updateProfile = async (e) => {
    e.preventDefault();
    if (lc_data.is_demo) {
      setModalOpen(true);
      return false;
    }

    setErrors(null);
    setFetching(true);

    const url = lc_data.update_wc_profile;
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };

    const formData = new FormData(e.target);
    formData.append('id', lc_data.current_user_id);
    formData.append('action', 'billing');
    await axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url,
      data: formData,
    }).then(response => {
      if (response.data.success) {
        toast(response.data.message, {
          position: toast.POSITION.BOTTOM_RIGHT,
          autoClose: 3000,
        });
      }
      if (response.data.error) {
        toast.error(response.data.message, {
          position: toast.POSITION.BOTTOM_RIGHT,
          autoClose: 3000,
        });
        if (response.data.error_field) {
          setErrors(response.data.error_field);
        }
      }
      setFetching(false);
    });
  };

  return (
    !loading &&
    <section className="dashboard-account flex flex-col p-30 bg-white rounded shadow-theme">

      <form onSubmit={e => updateProfile(e)}>

        <div className="flex flex-wrap -mx-10">

          <div className="w-full px-10">
            <h3 className="mb-20 font-bold">{lc_data.jst[254]}</h3>
          </div>

          {(options?.checkout_first_name || options?.checkout_first_name === undefined) &&
          <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 mb-10 px-10">
            <label htmlFor="billing_first_name" className="flex mb-4">
              {lc_data.jst[241]}
              <span className="relative text-sm text-red-700" style={{top: '-2px', left: '1px'}}>*</span>
            </label>
            <input
              type="text"
              id="billing_first_name"
              name="billing_first_name"
              className="flex mb-10 py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
              placeholder={lc_data.jst[242]}
              defaultValue={profile.billing && profile.billing.first_name || ''}
            />
            {errors && errors.first_name &&
            <p className="relative left-10 -mt-6 text-red-700" style={{fontSize: '10px'}}>{errors.first_name}</p>}
          </div>
          }

          {(options?.checkout_last_name || options?.checkout_last_name === undefined) &&
          <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 mb-10 px-10">
            <label htmlFor="billing_last_name" className="flex mb-4">
              {lc_data.jst[243]}
              <span className="relative text-sm text-red-700" style={{top: '-2px', left: '1px'}}>*</span>
            </label>
            <input
              type="text"
              id="billing_last_name"
              name="billing_last_name"
              className="flex mb-10 py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
              placeholder={lc_data.jst[244]}
              defaultValue={profile.billing && profile.billing.last_name || ''}
            />
            {errors && errors.last_name &&
            <p className="relative left-10 -mt-6 text-red-700" style={{fontSize: '10px'}}>{errors.last_name}</p>}
          </div>
          }
          {(options?.checkout_company_name || options?.checkout_company_name === undefined) &&
          <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 mb-10 px-10">
            <label htmlFor="billing_company" className="flex mb-4">{lc_data.jst[245]}</label>
            <input
              type="text"
              id="billing_company"
              name="billing_company"
              className="flex mb-10 py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
              placeholder={lc_data.jst[245]}
              defaultValue={profile.billing && profile.billing.company || ''}
            />
            {errors && errors.company &&
            <p className="relative left-10 -mt-6 text-red-700" style={{fontSize: '10px'}}>{errors.company}</p>}
          </div>
          }
          {(profile.form && profile.form.countries && (options?.checkout_country || options?.checkout_country === undefined)) &&
          <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 mb-10 px-10">
            <label htmlFor="billing_country" className="flex mb-4">
              {lc_data.jst[246]}
              <span className="relative text-sm text-red-700" style={{top: '-2px', left: '1px'}}>*</span>
            </label>
            <select
              id="billing_country"
              name="billing_country"
              className="flex mb-10 py-10 px-20 w-full h-44 xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
              defaultValue={profile.billing && profile.billing.country || ''}
              onChange={e => setCountry(e.target.value)}
            >
              {map(profile.form.countries, (label, value) => {
                return <option key={value} value={value} dangerouslySetInnerHTML={{
                  __html: label,
                }}/>;
              })}
            </select>
            {errors && errors.country &&
            <p className="relative left-10 -mt-6 text-red-700" style={{fontSize: '10px'}}>{errors.country}</p>}
          </div>
          }

          {(states && !isEmpty(states) && (options?.checkout_state || options?.checkout_state === undefined)) &&
          <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 mb-10 px-10">
            <label htmlFor="billing_state" className="flex mb-4">
              {lc_data.jst[247]}
              <span className="relative text-sm text-red-700" style={{top: '-2px', left: '1px'}}>*</span>
            </label>
            <select
              id="billing_state"
              name="billing_state"
              className="flex mb-10 py-10 px-20 w-full h-44 xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
              defaultValue={profile.billing && profile.billing.state || ''}
            >
              {states && map(states, (label, value) => {
                return <option key={value} value={value} dangerouslySetInnerHTML={{
                  __html: label,
                }}/>;
              })}
            </select>
            {errors && errors.state &&
            <p className="relative left-10 -mt-6 text-red-700" style={{fontSize: '10px'}}>{errors.state}</p>}
          </div>}

          {(options?.checkout_street_address || options?.checkout_street_address === undefined) &&
          <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 mb-10 px-10">
            <label htmlFor="billing_address" className="flex mb-4">
              {lc_data.jst[248]}
              <span className="relative text-sm text-red-700" style={{top: '-2px', left: '1px'}}>*</span>
            </label>
            <input
              type="text"
              id="billing_address"
              name="billing_address"
              className="flex mb-10 py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
              placeholder={lc_data.jst[248]}
              defaultValue={profile.billing && profile.billing.address_1 || ''}
            />
            {errors && errors.address_1 &&
            <p className="relative left-10 -mt-6 text-red-700" style={{fontSize: '10px'}}>{errors.address_1}</p>}
          </div>
          }
          {(options?.checkout_street_address_two || options?.checkout_street_address_two === undefined) &&
          <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 mb-10 px-10">
            <label htmlFor="billing_address_2" className="flex mb-4">{lc_data.jst[249]}</label>
            <input
              type="text"
              id="billing_address_2"
              name="billing_address_2"
              className="flex mb-10 py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
              placeholder={lc_data.jst[249]}
              defaultValue={profile.billing && profile.billing.address_2 || ''}
            />
            {errors && errors.address_2 &&
            <p className="relative left-10 -mt-6 text-red-700" style={{fontSize: '10px'}}>{errors.address_2}</p>}
          </div>
          }

          {(options?.checkout_zip || options?.checkout_zip === undefined) &&
          <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 mb-10 px-10">
            <label htmlFor="billing_postcode" className="flex mb-4">
              {lc_data.jst[250]}
              <span className="relative text-sm text-red-700" style={{top: '-2px', left: '1px'}}>*</span>
            </label>
            <input
              type="text"
              id="billing_postcode"
              name="billing_postcode"
              className="flex mb-10 py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
              placeholder={lc_data.jst[250]}
              defaultValue={profile.billing && profile.billing.postcode || ''}
            />
            {errors && errors.postcode &&
            <p className="relative left-10 -mt-6 text-red-700" style={{fontSize: '10px'}}>{errors.postcode}</p>}
          </div>
          }

          {(options?.checkout_town || options?.checkout_town === undefined) &&
          <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 mb-10 px-10">
            <label htmlFor="billing_city" className="flex mb-4">
              {lc_data.jst[251]}
              <span className="relative text-sm text-red-700" style={{top: '-2px', left: '1px'}}>*</span>
            </label>
            <input
              type="text"
              id="billing_city"
              name="billing_city"
              className="flex mb-10 py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
              placeholder={lc_data.jst[251]}
              defaultValue={profile.billing && profile.billing.city || ''}
            />
            {errors && errors.city &&
            <p className="relative left-10 -mt-6 text-red-700" style={{fontSize: '10px'}}>{errors.city}</p>}
          </div>
          }

          {(options?.checkout_phone || options?.checkout_phone === undefined) &&
          <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 mb-10 px-10">
            <label htmlFor="billing_phone" className="flex mb-4">
              {lc_data.jst[252]}
              <span className="relative text-sm text-red-700" style={{top: '-2px', left: '1px'}}>*</span>
            </label>
            <input
              type="tel"
              id="billing_phone"
              name="billing_phone"
              className="flex mb-10 py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
              placeholder={lc_data.jst[252]}
              defaultValue={profile.billing && profile.billing.phone || ''}
            />
            {errors && errors.phone &&
            <p className="relative left-10 -mt-6 text-red-700" style={{fontSize: '10px'}}>{errors.phone}</p>}
          </div>
          }

          {(options?.checkout_email_address || options?.checkout_email_address === undefined) &&
          <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 mb-10 px-10">
            <label htmlFor="billing_email" className="flex mb-4">
              {lc_data.jst[253]}
              <span className="relative text-sm text-red-700" style={{top: '-2px', left: '1px'}}>*</span>
            </label>
            <input
              type="email"
              id="billing_email"
              name="billing_email"
              className="flex mb-10 py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
              placeholder={lc_data.jst[253]}
              defaultValue={profile.billing && profile.billing.email || ''}
            />
            {errors && errors.email &&
            <p className="relative left-10 -mt-6 text-red-700" style={{fontSize: '10px'}}>{errors.email}</p>}
          </div>
          }

        </div>

        <button
          type="submit"
          className="flex items-center mt-20 py-12 px-24 bg-blue-700 hover:bg-blue-800 rounded font-bold text-white">
          <Fragment>
            {fetching &&
            <ReactSVG
              src={`${lc_data.dir}dist/${LoaderIcon}`}
              className="relative mr-12 w-14 h-14"
              style={{
                zoom: 0.6,
                left: '-23px',
                top: '-15px',
              }}
            />
            }
            {!fetching &&
            <ReactSVG
              src={`${lc_data.dir}dist/${SaveIcon}`}
              className="relative mr-8 w-14 h-14 fill-white pointer-events-none"
            />
            }
          </Fragment>
          {lc_data.jst[52]}
        </button>
      </form>

      <ModalDemo
        isLogged={lc_data.logged_in}
        open={modalOpen}
        closeModal={() => setModalOpen(false)}
        title={lc_data.jst[606]}
      >
        <div className="font-semibold text-lg text-grey-700" dangerouslySetInnerHTML={{
          __html: lc_data.jst[607],
        }}
        />
      </ModalDemo>
    </section>
  );
};

export default MyAccountBilling;
