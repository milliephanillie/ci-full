/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import { useState } from '@wordpress/element';
import axios from 'axios';
import CloseIcon from '../../../../../../../images/icons/close.svg';
import { isEmpty, map } from 'lodash';
import ModalDemo from '../../../../../../theme/packages/components/modal/ModalDemo';
import ReactSVG from 'react-svg';
import produce from 'immer';
import LoaderDashboardBookmarks from '../../../../../../theme/packages/components/loaders/LoaderDashboardBookmarks';
import { Fragment, useEffect } from 'react';
import LoaderIcon from '../../../../../../../images/icons/loader-rings-white.svg';
import SaveIcon from '../../../../../../../images/icons/save.svg';

const AgentsNew = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { loading, business } = data;
  let { agents } = business;
  const [errors, setErrors] = useState({});
  const [success, setSuccess] = useState(false);
  const [fetching, setFetching] = useState(false);
  const [modalOpen, setModalOpen] = useState(false);
  const [agentData, setAgentData] = useState({
    action: 'new_agent',
    first_name: '',
    last_name: '',
    username: '',
    email: '',
    password: '',
  });

  const changeAgentData = (key, value) => {
    const d = agentData;
    d[key] = value;
    setErrors({});
    setAgentData(d);
  };

  const submitAgent = async (e) => {
    e.preventDefault();
    if (lc_data.is_demo) {
      setModalOpen(true);
      return false;
    }
    setFetching(true);

    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };

    await axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url: `${lc_data.agent_action}/add_new`,
      data: agentData,
    }).then(response => {
      setFetching(false);
      if (response.data.errors) {
        setErrors(response.data.errors);
      }
      if (response.data.success) {
        setSuccess(response.data.message);
      }
    });
  };

  return (
    <section className="dashboard-account flex flex-col p-30 bg-white rounded shadow-theme">
      <div className="agents flex flex-wrap -mx-col -mb-20">

        {success && <div className="mb-20 px-10 text-lg text-green-700">{success}</div>}

        {!success &&
        <form className="w-full"
              onSubmit={(e) => submitAgent(e)}
        >
          <div className="flex flex-wrap w-full">
            <div className="w-full px-10">
              <h3 className="mb-20 font-bold">{lc_data.jst[778]}</h3>
            </div>

            <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 px-10">
              <label htmlFor="first_name" className="flex mb-4">{lc_data.jst[242]}<span
                className="text-sm text-red-600 leading-none">*</span></label>
              <input
                type="text"
                id="first_name"
                name="first_name"
                className="flex mb-10 py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
                placeholder={lc_data.jst[242]}
                onChange={(e) => changeAgentData('first_name', e.target.value)}
              />
              {errors && errors.first_name &&
              <p className="relative left-10 -mt-6 text-red-700" style={{ fontSize: '10px' }}>{errors.first_name}</p>}
            </div>

            <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 px-10">
              <label htmlFor="last_name" className="flex mb-4">{lc_data.jst[243]}<span
                className="text-sm text-red-600 leading-none">*</span></label>
              <input
                type="text"
                id="last_name"
                name="last_name"
                className="flex mb-10 py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
                placeholder={lc_data.jst[243]}
                onChange={(e) => changeAgentData('last_name', e.target.value)}
              />
              {errors && errors.last_name &&
              <p className="relative left-10 -mt-6 text-red-700" style={{ fontSize: '10px' }}>{errors.last_name}</p>}
            </div>

            <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 px-10">
              <label htmlFor="username" className="flex mb-4">{lc_data.jst[422]}<span
                className="text-sm text-red-600 leading-none">*</span></label>
              <input
                type="text"
                id="username"
                name="username"
                className="flex mb-10 py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
                placeholder={lc_data.jst[422]}
                onChange={(e) => changeAgentData('username', e.target.value)}
              />
              {errors && errors.first_name &&
              <p className="relative left-10 -mt-6 text-red-700" style={{ fontSize: '10px' }}>{errors.first_name}</p>}
            </div>

            <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 px-10">
              <label htmlFor="email" className="flex mb-4">{lc_data.jst[253]}<span
                className="text-sm text-red-600 leading-none">*</span></label>
              <input
                type="email"
                id="email"
                name="email"
                className="flex mb-10 py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
                placeholder={lc_data.jst[253]}
                onChange={(e) => changeAgentData('email', e.target.value)}
              />
              {errors && errors.email &&
              <p className="relative left-10 -mt-6 text-red-700" style={{ fontSize: '10px' }}>{errors.email}</p>}
            </div>

            <div className="w-full sm:w-1/3 bg:w-1/4 xxl:w-1/5 px-10">
              <label htmlFor="password" className="flex mb-4">{lc_data.jst[283]}<span
                className="text-sm text-red-600 leading-none">*</span></label>
              <input
                type="text"
                id="password"
                name="password"
                className="flex mb-10 py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
                placeholder={lc_data.jst[283]}
                onChange={(e) => changeAgentData('password', e.target.value)}
              />
              {errors && errors.password &&
              <p className="relative left-10 -mt-6 text-red-700" style={{ fontSize: '10px' }}>{errors.password}</p>}
            </div>
          </div>

          <div className="mb-20 px-10">
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
          </div>
        </form>
        }

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

      </div>
    </section>
  );
};

export default AgentsNew;
