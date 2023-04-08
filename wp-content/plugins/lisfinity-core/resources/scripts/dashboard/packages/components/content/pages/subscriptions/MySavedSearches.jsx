/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import { Fragment, useState } from '@wordpress/element';
import axios from 'axios';
import CloseIcon from '../../../../../../../images/icons/close.svg';
import { isEmpty, map } from 'lodash';
import ModalDemo from '../../../../../../theme/packages/components/modal/ModalDemo';
import ReactSVG from 'react-svg';
import produce from 'immer';
import LoaderDashboardBookmarks from '../../../../../../theme/packages/components/loaders/LoaderDashboardBookmarks';
import * as actions from '../../../../store/actions';
import LoaderIcon from '../../../../../../../images/icons/loader-rings-white.svg';
import SaveIcon from '../../../../../../../images/icons/save.svg';
import { useEffect } from 'react';
import TrashIcon from '../../../../../../../images/icons/trash.svg';

const MySavedSearches = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { loading, business } = data;
  const { saved_searches, } = business;
  const [modalOpen, setModalOpen] = useState(false);
  const [fetching, setFetching] = useState(false);
  const [subs, setSubs] = useState(saved_searches);

  const handleChange = (checked, name) => {
    const saved = subs;
    saved[name]['email'] = checked ? 'yes' : 'no';

    setSubs(saved);
  };

  const saveSearches = async (subs) => {
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
      url: `${lc_data.user_action}/save_search_email`,
      data: {
        subs,
      },
    }).then(response => {
      setFetching(false);
    });
  };

  const deleteSearch = async (subs, name) => {
    if (lc_data.is_demo) {
      setModalOpen(true);
      return false;
    }
    if (!confirm(lc_data.jst[776])) {
      return false;
    }
    const saved = subs;
    delete saved[name];

    setSubs(saved);
    setFetching(true);

    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };

    await axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url: `${lc_data.user_action}/delete_saved_search`,
      data: {
        hash: name,
      },
    }).then(response => {
      setFetching(false);
    });
  };

  return (
    <section className="dashboard-account flex flex-col -mb-10 p-30 bg-white rounded shadow-theme">
      <div className="flex flex-col">

        <div className="flex justify-between font-semibold">
          <div>{lc_data.jst[773]}</div>
          <div>{lc_data.jst[774]}</div>
        </div>
        {!isEmpty(subs) && map(subs, (value, name) => {
          let count = 0;
          return (
            <div key={name} className="flex justify-between mt-10">
              <a href={`${lc_data.page_search}/?${value.args}`}
                 target="_blank">{`${lc_data.jst[775]}${value.index}`}: {name}</a>
              <div key={name}
                   className="saved-search--emails flex justify-end items-center mb-10 w-full sm:w-1/2 xl:w-1/4">
                <div className="text-right">
                  <input
                    type="checkbox"
                    id={name}
                    className="relative top-2"
                    onChange={(e) => handleChange(e.target.checked, name)}
                    defaultChecked={'yes' === value.email}
                  />
                </div>

                <button
                  className="ml-10"
                  onClick={() => deleteSearch(subs, value.hash)}
                >
                  <ReactSVG
                    src={`${lc_data.dir}dist/${TrashIcon}`}
                    className="w-16 h-16 fill-red-700"
                  />
                </button>
              </div>
            </div>
          );
        })}
        <div className="flex w-full">
          <button
            type="submit"
            className="flex items-center mt-20 ml-auto py-12 px-24 bg-blue-700 hover:bg-blue-800 rounded font-bold text-white e:default"
            onClick={() => saveSearches(subs)}
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

export default MySavedSearches;
