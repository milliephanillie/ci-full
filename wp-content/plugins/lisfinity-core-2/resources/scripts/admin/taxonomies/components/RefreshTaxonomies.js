/* global lc_data, React */

import { Fragment, useEffect, useState } from '@wordpress/element';
import ModalMain from './ModalMain';
import axios from 'axios';
import ReactSVG from 'react-svg';
import { sprintf } from '@wordpress/i18n';
import BoltIcon from '../../../../images/icons/bolt-alt.svg';
import { toast, ToastContainer } from 'react-toastify';
import { useDispatch, useSelector } from 'react-redux';
import LoaderIcon from '../../../../images/icons/loader-rings.svg';
import LoaderVersionHistory from './LoaderVersionHistory';
import ModalNew from '../../../theme/packages/components/modal/ModalNew';
import React from 'react';
import Spinner from '../../../../images/icons/spinner.svg';

const RefreshTaxonomies = (props) => {
  const [modalOpen, setModalOpen] = useState(false);
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState(null);
  const data = useSelector(state => state);
  const dispatch = useDispatch();

  const refresh = async () => {
    setLoading(true);

    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    await axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url: `${lc_data.taxonomy_refresh}`,
    }).then(response => {
      if (response.data.success) {
        setMessage(response.data.message);
        setTimeout(() => {
          window.location.reload();
        }, 400);
      }
      setLoading(false);
    });
  };

  return (
    <div className="cf-version-control relative self-end ml-auto">
      <button type="button"
              onClick={() => setModalOpen(true)}
              className="text-blue-700 text-base hover:underline"
      >{lc_data.jst[705]}
      </button>

      <ModalNew
        open={modalOpen}
        closeModal={() => setModalOpen(false)}
        title={lc_data.jst[705]}
      >
        <div>
          <div className="versions--title flex flex-col mb-20 pb-20 border-b border-grey-100 animate">
            <h5 className="font-semibold text-lg">{lc_data.jst[706]}</h5>
            <p className="flex mt-10 text-grey-500">{lc_data.jst[707]}</p>
          </div>
          <div className="versions flex flex-col">
            {!message &&
            <button
              type="button"
              className="flex justify-center items-center py-12 px-24 h-44 bg-blue-700 hover:bg-blue-800 rounded font-bold text-base text-white animate"
              onClick={() => refresh()}
              disabled={loading}
            >
              <Fragment>
                {loading &&
                <ReactSVG
                  src={`${lc_data.dir}dist/${Spinner}`}
                  style={{ zoom: .2 }}
                />
                }
                {!loading && <span>{lc_data.jst[705]}</span>}
              </Fragment>
            </button>
            }
            {!loading && message && <p className="text-green-700 animate">{message}</p>}
          </div>
        </div>
      </ModalNew>
    </div>
  );
};

export default RefreshTaxonomies;
