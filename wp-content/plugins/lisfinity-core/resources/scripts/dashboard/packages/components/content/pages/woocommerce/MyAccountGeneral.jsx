/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import axios from 'axios';
import { toast } from 'react-toastify';
import ReactSVG from 'react-svg';
import SaveIcon from '../../../../../../../images/icons/save.svg';
import LoaderIcon from '../../../../../../../images/icons/loader-rings-white.svg';
import MediaSingleImage from '../../../../../../forms/packages/form-fields/MediaSingleImage';
import { Fragment } from 'react';
import ModalDemo from '../../../../../../theme/packages/components/modal/ModalDemo';
import { isString } from 'lodash';
import * as actions from '../../../../store/actions';
import Input from '../../../../../../theme/packages/components/form-fields/input/Input';
import TabIcon from '../../../../../../../images/icons/tab.svg';
import World from '../../../../../../../images/icons/world.svg';

const MyAccountGeneral = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { loading, business, menuOpen, profile, options } = data;
  const [errors, setErrors] = useState(null);
  const [fetching, setFetching] = useState(false);
  const [modalOpen, setModalOpen] = useState(false);

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
    formData.append('action', 'general');
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

  const handleChange = (e, name, type) => {
    const newData = { ...data };
    if (type === 'single_image') {
      if (e?.id) {
        newData[name] = e.id;
      }
      if (e?.source_url) {
        newData[`${name}_url`] = e.source_url;
        if (name === '_featured_image') {
          setFeaturedImage(newData['_featured_image_url']);
        }
      }
    }
    dispatch(actions.updateFormData(newData));
  };

  return (
    !loading &&
    <section className="dashboard-account flex flex-col p-30 bg-white rounded shadow-theme">

      <form onSubmit={e => updateProfile(e)}>

        <div className="flex flex-wrap -mx-10">

          <div className="w-full px-10">
            <h3 className="mb-20 font-bold">{lc_data.jst[268]}</h3>
          </div>

          <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 px-10">
            <label htmlFor="first_name" className="flex mb-4">{lc_data.jst[241]}</label>
            <input
              type="text"
              id="first_name"
              name="first_name"
              className="flex mb-10 py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
              placeholder={lc_data.jst[242]}
              defaultValue={profile?.first_name || ''}
            />
            {errors && errors.first_name &&
            <p className="relative left-10 -mt-6 text-red-700" style={{ fontSize: '10px' }}>{errors.first_name}</p>}
          </div>

          <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 px-10">
            <label htmlFor="last_name" className="flex mb-4">{lc_data.jst[243]}</label>
            <input
              type="text"
              id="last_name"
              name="last_name"
              className="flex mb-10 py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
              placeholder={lc_data.jst[244]}
              defaultValue={profile?.last_name || ''}
            />
            {errors && errors.last_name &&
            <p className="relative left-10 -mt-6 text-red-700" style={{ fontSize: '10px' }}>{errors.last_name}</p>}
          </div>

          <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 px-10">
            <label htmlFor="display_name" className="flex mb-4">{lc_data.jst[265]}</label>
            <input
              type="text"
              id="display_name"
              name="display_name"
              className="flex mb-10 py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
              placeholder={lc_data.jst[266]}
              defaultValue={profile?.display_name || ''}
            />
            {errors && errors.display_name &&
            <p className="relative left-10 -mt-6 text-red-700" style={{ fontSize: '10px' }}>{errors.display_name}</p>}
          </div>

          <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 px-10">
            <label htmlFor="email" className="flex mb-4">{__('Email', 'lisfinity-core')}</label>
            <input
              type="email"
              id="email"
              name="email"
              className="flex mb-10 py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
              placeholder={__('Email', 'lisfinity-core')}
              defaultValue={profile.user_email || ''}
            />
            {errors && errors.user_email &&
            <p className="relative left-10 -mt-6 text-red-700" style={{ fontSize: '10px' }}>{errors.user_email}</p>}
          </div>
          {options?.vat_number &&
          <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 px-10">
            <label htmlFor="display_name" className="flex mb-4">{lc_data.jst[745]}</label>
            <input
              type="text"
              id="vat_number"
              name="vat_number"
              className="flex mb-10 py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
              placeholder={lc_data.jst[745]}
              defaultValue={profile?.vat_number || ''}
            />
            {errors && errors.vat_number &&
            <p className="relative left-10 -mt-6 text-red-700" style={{ fontSize: '10px' }}>{errors.vat_number}</p>}
          </div>
          }
          {options?.sdi_code &&
          <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 px-10">
            <label htmlFor="display_name" className="flex mb-4">{lc_data.jst[746]}</label>
            <input
              type="text"
              id="sdi_code"
              name="sdi_code"
              className="flex mb-10 py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
              placeholder={lc_data.jst[746]}
              defaultValue={profile?.sdi_code || ''}
            />
            {errors && errors.sdi_code &&
            <p className="relative left-10 -mt-6 text-red-700" style={{ fontSize: '10px' }}>{errors.sdi_code}</p>}
          </div>
          }
          {!options?.is_business_account &&
          <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 px-10">
            <label htmlFor="phone" className="flex mb-4">{__('Phone', 'lisfinity-core')}</label>
            <input
              type="text"
              id="phone"
              name="phone"
              className="flex mb-10 py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
              placeholder={__('Phone', 'lisfinity-core')}
              defaultValue={profile?.phones && profile?.phones[0] && profile?.phones[0]['profile-phone'] || ''}

            />
            {errors && errors.user_phones &&
            <p className="relative left-10 -mt-6 text-red-700" style={{ fontSize: '10px' }}>{errors.user_phone}</p>}
          </div>
          }

          {!options?.is_business_account &&
          <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 px-10">
            <label htmlFor="website" className="flex mb-4">{__('Website', 'lisfinity-core')}</label>
            <input
              id="website"
              name="website"
              placeholder={lc_data.jst[735]}
              defaultValue={profile.website || ''}
              className="flex mb-10 py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
            />
            {errors && errors.user_website &&
            <p className="relative left-10 -mt-6 text-red-700" style={{ fontSize: '10px' }}>{errors.user_website}</p>}
          </div>
          }

          <div
            className={`w-full ${options?.sdi_code && options?.vat_number ? 'sm:w-1/3 mt-0' : (!options?.is_business_account ? 'sm:w-1/2' : 'sm:w-1/3 mt-20')} px-10`}>
            <MediaSingleImage
              display
              dataType="profile"
              name="avatar"
              field={{
                multiple: false,
                defaultValue: profile?.avatar || '',
                field_type: 'no_save',
                size_limit: profile?.media_limit,
              }}
              label={lc_data.jst[267]}
              settings={{
                basic: true,
              }}
              onChange={e => handleChange(e, 'avatar', 'single_image')}
            />
          </div>

        </div>

        <button
          type="submit"
          className="flex items-center mt-20 py-12 px-24 bg-blue-700 hover:bg-blue-800 rounded font-bold text-white e:default"
          disabled={fetching}
        >
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

export default MyAccountGeneral;
