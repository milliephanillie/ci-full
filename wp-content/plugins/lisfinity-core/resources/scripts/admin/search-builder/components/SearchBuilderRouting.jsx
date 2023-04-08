/* global lc_data, React */
/**
 * External dependencies.
 */
import { Link, NavLink } from 'react-router-dom';
import { Component, Fragment, createRef, useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { map, isEmpty } from 'lodash';
import { __, sprintf } from '@wordpress/i18n';
import queryString from 'query-string';
import SearchSidebar from './tabs/SearchSidebar';
import SearchHome from './tabs/SearchHome';
import SearchDetailed from './tabs/SearchDetailed';
import ReactSVG from 'react-svg';
import SearchIcon from '../../../../images/icons/search.svg';
import { ToastContainer } from 'react-toastify';
import Modal from '../../taxonomies/components/Modal';
import GroupExport from '../../taxonomies/components/GroupExport';
import GroupImport from '../../taxonomies/components/GroupImport';
import LoaderSearchBuilder from '../../loaders/LoaderSearchBuilder';
import SearchSingle from './tabs/SearchSingle';
import Licence from '../../taxonomies/components/Licence';

const SearchBuilderRouting = (props) => {
  const [loading, setLoading] = useState(true);
  const [options, setOptions] = useState({});
  const [ieModal, setIeModal] = useState(false);
  const [key, setKey] = useState(false);

  const handleClickOutside = e => {
    setIeModal(false);
    const body = document.querySelector('body');
    body.style.overflow = 'auto';
  };

  const escFunction = (event) => {
    if (event.keyCode === 27) {
      setIeModal(false);
      const body = document.querySelector('body');
      body.style.overflow = 'auto';
    }
  };

  const handleModal = () => {
    setIeModal(!ieModal);
  };

  const closeModal = () => {
    setIeModal(false);
    const body = document.querySelector('body');
    body.style.overflow = 'auto';
  };

  useEffect(() => {
    document.addEventListener('keydown', escFunction, false);

    if (lc_data?.key) {
      if (lc_data.key.is_valid && lc_data.key.license_key === lc_data.l && lc_data.key.domain === lc_data.domain && lc_data.key.next_request) {
        setKey(true);
      }
    }

    getOptions();
    addActiveClassOnLoad();

    return () => document.removeEventListener('keydown', escFunction, false);
  }, []);

  const getOptions = () => {
    if (!loading) {
      setLoading(true);
    }
    apiFetch({ path: lc_data.search_builder_options }).then(data => {
      setOptions(data);
      setLoading(false);
    });
  };

  /**
   * Add active class to the current tab on page load
   * ------------------------------------------------
   */
  const addActiveClassOnLoad = () => {
    const { location } = props;
    const subpage = queryString.parse(location.search);
    const currentPage = subpage.sub || subpage.page;
    const tabs = document.getElementsByClassName('sb-tab');
    map(tabs, tab => tab.dataset.link === currentPage ? tab.classList.add('bg-blue', 'text-white', 'active:text-white') : '');
  };

  /**
   * Render the correct subpage
   * --------------------------
   *
   * @param page
   * @returns {*}
   */
  const renderSubPage = (page) => {
    switch (page) {
      case 'search-sidebar':
        return <SearchSidebar key={0} options={options}/>;
      case 'search-detailed':
        return <SearchDetailed key={0} options={options}/>;
      case 'search-single':
        return <SearchSingle key={0} options={options}/>;
      default:
        return <SearchHome key={0} options={options}/>;
    }
  };

  const handleClick = e => {
    const tabs = document.getElementsByClassName('sb-tab');
    map(tabs, tab => tab.classList.remove('bg-blue', 'text-white', 'active:text-white'));
    e.target.classList.add('bg-blue', 'text-white', 'active:text-white', 'focus:text-white');
  };

  const { location } = props;
  const url = 'wp-admin/admin.php?page=search-builder';
  const subpage = queryString.parse(location.search);

  return (
    <div className="flex flex-wrap py-60 font-sans">

      <div className="dashboard--header inline-flex items-center mb-30 w-full font-sans">

        <ReactSVG
          src={`${lc_data.dir}dist/${SearchIcon}`}
          className="relative w-20 h-20 fill-grey-1100"
        />

        <div className="flex flex-col ml-20 pl-20 border-l-2 border-grey-200">
          <div className="flex flex-wrap">
            <h1 className="font-bold text-4xl">{lc_data.jst[2]}</h1>
            <div
              className="relative"
              style={{
                top: '-16px',
                left: '10px',
              }}
            >
              <button
                type="button"
                className="button button-primary"
                onClick={() => setIeModal(true)}
                style={{
                  fontSize: '10px',
                }}
              >
                <span>{lc_data.jst[3]}</span>
              </button>
            </div>
          </div>
          <p
            className="mt-10 text-lg text-grey-500">{lc_data.jst[4]}</p>
        </div>
      </div>

      {!loading && !key &&
      <div className="w-full mb-60">
        <Licence/>
      </div>
      }

      {key &&
      <nav className="search-builder--navigation">
        <ul className="flex">

          <li
            className={`sb-tab relative flex flex-col items-center mb-20 p-8 bg-white rounded no-underline text-grey-1000 ${isEmpty(subpage.sub) ? 'sb-tab--active' : ''} `}
          >
            <NavLink
              data-link="search-builder"
              className="sb-tab--link relative flex flex-col w-full"
              to={`${lc_data.site_url}${url}`}
              onClick={handleClick}
            >
              <img
                className="p-0 absolute top-0 left-0 w-full border-0 rounded object-cover"
                src={`${lc_data.dir}dist/statics/search-builder/home.jpg`}
                alt={__('Home search fields', 'lisfinity-core')}
                style={{ height: '75%' }}
              />
              <span className="absolute bottom-10 self-center font-bold text-grey-1000 z-1">
                {lc_data.jst[5]}
                </span>
            </NavLink>
          </li>

          <li
            className={`sb-tab relative flex flex-col items-center mb-20 p-8 bg-white rounded no-underline text-grey-1000 ${subpage.sub === 'search-sidebar' ? 'sb-tab--active' : ''} `}
          >
            <NavLink
              data-link="search-sidebar"
              className="sb-tab--link relative flex flex-col w-full"
              to={`${lc_data.site_url}${url}&sub=search-sidebar`}
              onClick={handleClick}
            >
              <img
                className="p-0 absolute top-0 left-0 w-full border-0 rounded object-cover"
                src={`${lc_data.dir}dist/statics/search-builder/search.jpg`}
                alt={lc_data.jst[6]}
                style={{ height: '75%' }}
              />
              <span className="absolute bottom-10 self-center font-bold text-grey-1000 z-1">
                {lc_data.jst[7]}
                </span>
            </NavLink>
          </li>

          <li
            className={`sb-tab relative flex flex-col items-center mb-20 p-8 bg-white rounded no-underline text-grey-1000 ${subpage.sub === 'search-detailed' ? 'sb-tab--active' : ''} `}
          >
            <NavLink
              data-link="search-detailed"
              className="sb-tab--link relative flex flex-col w-full"
              to={`${lc_data.site_url}${url}&sub=search-detailed`}
              onClick={handleClick}
            >
              <img
                className="p-0 absolute top-0 left-0 w-full border-0 rounded object-cover"
                src={`${lc_data.dir}dist/statics/search-builder/detailed.jpg`}
                alt={lc_data.jst[8]}
                style={{ height: '75%' }}
              />
              <span className="absolute bottom-10 self-center font-bold text-grey-1000 z-1">
                {lc_data.jst[9]}
                </span>
            </NavLink>
          </li>

          <li
            className={`sb-tab relative flex flex-col items-center p-8 bg-white rounded no-underline text-grey-1000 ${subpage.sub === 'search-single' ? 'sb-tab--active' : ''} `}
          >
            <NavLink
              data-link="search-detailed"
              className="sb-tab--link relative flex flex-col w-full"
              to={`${lc_data.site_url}${url}&sub=search-single`}
              onClick={handleClick}
            >
              <img
                className="p-0 absolute top-0 left-0 w-full border-0 rounded object-cover"
                src={`${lc_data.dir}dist/statics/search-builder/single.jpg`}
                alt={__('Ad Single Fields', 'lisfinity-core')}
                style={{ height: '75%' }}
              />
              <span className="absolute bottom-10 self-center font-bold text-grey-1000 z-1">
                {lc_data.jst[10]}
                </span>
            </NavLink>
          </li>

        </ul>
      </nav>
      }

      {key &&
      <div className={`search-builder--content relative ${loading ? 'w-full bg:w-2/4' : ''}`}>
        {loading && <LoaderSearchBuilder/>}
        {!loading &&
        <Fragment>
          {renderSubPage(subpage.sub || 'search-home')}
          <div className="flex mt-40 px-30">
            <p dangerouslySetInnerHTML={{
              __html: sprintf(lc_data.jst[11],
                `<a href="https://pebas.gitbook.io/lisfinity-documentation/search-builder/disabling-search-builder" class="text-blue-600 underline" target="_blank">${lc_data.jst[12]}</a>`)
            }}></p>
          </div>
        </Fragment>
        }
        {ieModal &&
        <div
          className="modal--wrapper fixed top-0 left-0 flex justify-center w-full h-full overflow-y-auto"
        >
          <Modal classes="form-import-export"
                 closeModal={closeModal}
                 title={lc_data.jst[13]}
                 open={ieModal}
                 handleClickOutside={(e) => handleClickOutside(e)}
          >
            <div className="flex flex-wrap justify-between">
              <GroupExport url={lc_data.search_export_fields}/>
              <GroupImport url={lc_data.search_import_fields}/>
            </div>
          </Modal>
        </div>
        }
      </div>
      }
      <ToastContainer/>
    </div>
  );
};

export default SearchBuilderRouting;
