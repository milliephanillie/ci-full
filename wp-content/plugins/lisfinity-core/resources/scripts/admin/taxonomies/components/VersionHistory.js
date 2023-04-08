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

const VersionHistory = (props) => {
  const [modalOpen, setModalOpen] = useState(false);
  const [loading, setLoading] = useState(false);
  const [saving, setSaving] = useState(false);
  const [versions, setVersions] = useState(false);
  const [savedVersion, setSavedVersion] = useState(false);
  const [template, setTemplate] = useState(0);
  const [page, setPage] = useState(0);
  const [maxPages, setMaxPages] = useState(0);
  const [versionsToDisplay, setVersionsToDisplay] = useState(false);
  const data = useSelector(state => state);
  const dispatch = useDispatch();

  const getVersions = async () => {
    setLoading(true);
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    await axios({
      credentials: 'same-origin',
      headers,
      method: 'get',
      url: `${lc_data.get_cf_versions}`,
    }).then(response => {
      if (response.data.success) {
        setVersions(response.data.versions);
        setSavedVersion(response.data.saved);
        setTemplate(response.data.saved_id);
        setMaxPages(response.data.pages);
        setVersionsToDisplay(response.data.versions.slice(page * 10, (page + 1) * 10));
      }
      setLoading(false);
    });
  };

  const resetVersion = async (id, name) => {
    if (!confirm(lc_data.jst[648])) {
      return false;
    }

    setLoading(true);

    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    await axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url: `${lc_data.reset_cf_version}`,
      data: {
        id,
        name,
      }
    }).then(response => {
      if (response.data.success) {
        setVersions(response.data.versions);
        setTemplate(response.data.saved_id);
        props.updateTaxonomies();
        setModalOpen(false);
        toast(response.data.message, {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
        });
        getVersions();
      }
      if (response.data.error) {
        toast.error(response.data.message, {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
        });
      }
      setLoading(false);
    });
  };

  const saveVersion = async (id, name) => {
    setSaving(true);

    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    await axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url: `${lc_data.save_cf_version}`,
      data: {
        id,
        name,
      }
    }).then(response => {
      if (response.data.success) {
        setTemplate(id);
        toast(response.data.message, {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
        });
        getVersions();
      }
      if (response.data.error) {
        toast.error(response.data.message, {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
        });
      }
      setSaving(false);
    });
  };

  useEffect(() => {
    if (modalOpen) {
      getVersions();
    }
  }, [modalOpen]);

  useEffect(() => {
    if (versions) {
      setVersionsToDisplay(versions.slice(page * 10, (page + 1) * 10));
    }
  }, [page]);

  const pagination = () => {
    let pagination = [];
    if (maxPages > 1) {
      for (let i = 1; i <= maxPages; i += 1) {
        pagination.push(<button key={i} type="button"
                                className={`terms--pagination-item flex justify-center items-center h-20 rounded mt-2 mx-4 text-sm ${i === (page + 1) || ((page + 1) === 0 && i === 1) ? 'bg-blue-700 text-white' : 'bg-grey-100'}`}
                                style={{
                                  width: 36,
                                  height: 30,
                                }}
                                onClick={() => setPage(i - 1)}>{i}</button>);
      }
    }

    return pagination;
  };

  return (
    <div className="cf-version-control relative self-end ml-auto">
      <button type="button"
              onClick={() => setModalOpen(true)}
              className="text-blue-700 text-base hover:underline"
      >{lc_data.jst[645]}
      </button>

      <ModalMain
        open={modalOpen}
        closeModal={() => setModalOpen(false)}
        title={lc_data.jst[645]}
      >
        {loading &&
        <LoaderVersionHistory/>
        }
        {!loading && versionsToDisplay &&
        <div>
          <div className="versions--title flex justify-between items-end mb-20 pb-20 border-b border-grey-100 animate">
            <h5 className="font-semibold text-lg">{sprintf(lc_data.jst[651], versions.length)}</h5>
            {savedVersion &&
            <div className="flex flex-col items-end -mb-4">
              <button className="-mb-2 text-blue-700 underline"
                      onClick={() => resetVersion(savedVersion.id, savedVersion.name)}>{lc_data.jst[652]}</button>
              <span className="text-grey-500" style={{ fontSize: 10 }}>{savedVersion.time_mysql}</span>
            </div>
            }
          </div>
          <div className="versions flex flex-col">
            {versionsToDisplay.map((version, index) => (
              <div key={index} className={`cf-version relative flex ${index !== 0 ? 'mt-10' : ''}`}>
                {template === version.id &&
                <span className="absolute text-grey-500"
                      style={{
                        top: 3,
                        left: -23,
                      }}
                      title={lc_data.jst[650]}
                >
                        <ReactSVG
                          src={`${lc_data.dir}dist/${BoltIcon}`}
                          className="min-w-14 w-14 h-14 fill-blue-700"
                        />
                      </span>
                }
                <span className="mr-4 w-20">{((page * 10) + index + 1)}.</span>
                <div className="flex flex-col">
                  <div className="relative flex items-center -mb-4 font-semibold">
                    {version.id}
                  </div>
                  <span className="text-sm text-grey-500" style={{ fontSize: 10 }}>{version.time_human} ago</span>
                </div>
                <div className="flex flex-col items-end ml-auto">
                  {(index !== 0 || (page !== 0 && index === 0)) &&
                  <button className="-mb-2 text-blue-700 underline"
                          onClick={() => resetVersion(version.id, version.name)}>{lc_data.jst[647]}</button>
                  }
                  {index === 0 && page === 0 &&
                  <div className="-mb-2 text-grey-700">
                    {lc_data.jst[646]} {template !== version.id &&
                  <Fragment>
                    <span className="mr-4">-</span>
                    <button className="-mb-2 text-blue-700 underline"
                            onClick={() => saveVersion(version.id, version.name)}>{lc_data.jst[649]}</button>
                  </Fragment>
                  }
                  </div>
                  }
                  <span className="text-grey-500" style={{ fontSize: 10 }}>{version.time_mysql}</span>
                </div>
              </div>
            ))}
          </div>
        </div>
        }
        {!loading && maxPages > 1 &&
        <div className="terms--pagination flex flex-wrap items-center mt-20 -mx-4 pt-20 border-t border-grey-100">
          {pagination()}
        </div>
        }
      </ModalMain>
      <ToastContainer/>
    </div>
  );
};

export default VersionHistory;
