/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import axios from 'axios';
import { toast, ToastContainer } from 'react-toastify';
import * as actions from '../../../../store/actions';
import { isEmpty } from 'lodash';
import MyAccountTabs from './MyAccountTabs';
import ReactSVG from 'react-svg';
import SaveIcon from '../../../../../../../images/icons/save.svg';
import LoaderIcon from '../../../../../../../images/icons/loader-rings-white.svg';
import { Fragment } from 'react';
import ModalDemo from '../../../../../../theme/packages/components/modal/ModalDemo';

const MyAccountPassword = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { loading, business, menuOpen, profile } = data;
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
    }

    const formData = new FormData(e.target);
    formData.append('id', lc_data.current_user_id);
    formData.append('action', 'password');
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
      setFetching(false );
    });
  }

  return (
    !loading &&
    <section className="dashboard-account flex flex-col p-30 bg-white rounded shadow-theme">

      <form onSubmit={e => updateProfile(e)}>

        <div className="flex flex-wrap -mx-10">

          <div className="w-full px-10">
            <h3 className="mb-20 font-bold">{lc_data.jst[282]}</h3>
          </div>

          <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 px-10">
            <label htmlFor="password" className="flex mb-4">{lc_data.jst[283]}</label>
            <input
              type="password"
              id="password"
              name="password"
              className="flex mb-10 py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
            />
            {errors && errors.password &&
            <p className="relative left-10 -mt-6 text-red-700" style={{ fontSize: '10px' }}>{errors.password}</p>}
          </div>

          <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 px-10">
            <label htmlFor="password_2" className="flex mb-4">{lc_data.jst[284]}</label>
            <input
              type="password"
              id="password_2"
              name="password_2"
              className="flex mb-10 py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
            />
            {errors && errors.password_2 &&
            <p className="relative left-10 -mt-6 text-red-700" style={{ fontSize: '10px' }}>{errors.password_2}</p>}
          </div>

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

export default MyAccountPassword;
