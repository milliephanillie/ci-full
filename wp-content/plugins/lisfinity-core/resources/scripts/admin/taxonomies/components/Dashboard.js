/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect, useRef } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import TextField from '../fields/TextField';
import SelectField from '../fields/SelectField';
import FileField from '../fields/File';
import Taxonomies from './Taxonomies';
import { map, isEmpty } from 'lodash';
import { __, sprintf } from '@wordpress/i18n';
import queryString from 'querystring';
import axios from 'axios';
import ReactSVG from 'react-svg';
import ArrowLeftIcon from '../../../../images/icons/arrow-left.svg';
import PlusIcon from '../../../../images/icons/plus.svg';
import filesIcon from '../../../../images/icons/files.svg';
import React, { Fragment } from 'react';
import SaveIcon from '../../../../images/icons/save.svg';
import LoaderTaxonomies from '../../loaders/LoaderTaxonomies';
import Spinner from '../../../../images/icons/spinner.svg';
import { useDispatch, useSelector } from 'react-redux';
import { setThemeOptions } from '../store/actions';
import ModalNew from '../../../theme/packages/components/modal/ModalNew';
import TaxonomyImport from './TaxonomyImport';
import VersionHistory from './VersionHistory';
import RefreshTaxonomies from './RefreshTaxonomies';
import { isLodash } from '../../../theme/vendor/functions';

const Dashboard = (props) => {
  if ( isLodash() ) {
    _.noConflict();
  }
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [groupName, setGroupName] = useState(document.getElementById('field_group').dataset.name || lc_data.jst[71]);
  const [fields, setFields] = useState([]);
  const [modalOpen, setModalOpen] = useState(false);
  const [elementToUpdate, setElementToUpdate] = useState([]);
  const [updateFields, setUpdateFields] = useState([]);
  const [fieldGroup, setFieldGroup] = useState(document.getElementById('field_group').value || '');
  const [predefined, setPredefined] = useState(false);
  const [notice, setNotice] = useState('');
  const [taxonomies, setTaxonomies] = useState([]);
  const [needsRefresh, setNeedsRefresh] = useState(false);
  const [versionStep, setVersionStep] = useState(0);
  const [emptyTaxonomies, setEmptyTaxonomies] = useState(false);
  const data = useSelector(state => state);
  const dispatch = useDispatch();

  const taxonomyElement = useRef(null);

  const query = queryString.parse(location.search);
  const isDashboard = query['?page'] === 'custom-fields';

  useEffect(() => {
    document.addEventListener('keydown', escFunction, false);

    fetchFields();
    fetchTaxonomies();

    const id = document.getElementById('field_group');
    if (id) {
      const options = JSON.parse(id.dataset.options);
      dispatch(setThemeOptions(options));
    }

    return () => {
      document.removeEventListener('keydown', escFunction, false);
    };
  }, []);

  const fetchTaxonomies = () => {
    const url = fieldGroup !== '' ? `${lc_data.taxonomy_options}` : lc_data.taxonomy_options;
    axios({ url, data: { group: fieldGroup, } }).then((taxonomies) => {
      setTaxonomies(taxonomies.data);
      setLoading(false);
    });
  };

  /**
   * Get taxonomy fields.
   * --------------------
   */
  const fetchFields = () => {
    apiFetch({ path: lc_data.taxonomy_fields }).then((fields) => {
      setFields(fields);
    });
  };

  const escFunction = (event) => {
    if (!submitting && event.keyCode === 27) {
      setModalOpen(false);
    }
  };

  const closeModal = () => {
    setModalOpen(false);
  };

  const handleChange = () => {
  };

  /**
   * Show field by its type
   * ----------------------
   *
   * @param value
   * @param name
   * @param predefined
   * @returns {*}
   */
  const showField = (value, name, predefined = false) => {
    switch (value.type) {
      case 'file':
        return <FileField
          key={value.key}
          value={predefined ? updateFields[name] : ''}
          name={name}
          buttonLabel={value.label || lc_data.jst[69]}
          mediaLibraryButtonLabel={value.label || lc_data.jst[69]}
          mediaLibraryTitle={value.label || lc_data.jst[69]}
          field={value}
          handleChange={(name, value) => handleChange(name, value)}
        />;
      case 'ghost':
        return;
      case 'select':
        let choices = value.choices;
        if ('parent' === name) {
          choices = [{ key: '', value: lc_data.jst[97] }];
          taxonomies[fieldGroup] && map(taxonomies[fieldGroup], tax => {
              choices.push({ key: tax.slug, value: tax.single_name });
            }
          );
        }
        return <SelectField
          display
          key={value.key}
          id={name}
          name={name}
          label={value.label}
          value={predefined ? updateFields[name] : ''}
          description={value.description}
          options={choices}
          field={value}
          handleChange={(name, value) => handleChange(name, value)}
        />;
      default: {
        return <TextField
          key={value.key}
          id={name}
          name={name}
          label={value.label}
          value={predefined ? updateFields[name] : ''}
          description={value.description}
          field={value}
          handleChange={(name, value) => handleChange(name, value)}
        />;
      }
    }
  };

  /**
   * Submit new taxonomy
   * -------------------
   *
   * @param e
   * @param edit
   */
  const handleSubmit = (e, edit = false) => {
    e.preventDefault();

    setSubmitting(true);

    const data = new FormData(e.target);
    const headers = new Headers();
    let url = lc_data.taxonomy_options_store;

    headers.append('X-WP-Nonce', lc_data.nonce);
    data.append('field_group', fieldGroup);

    fetch(url, {
      method: 'POST',
      credentials: 'same-origin',
      headers,
      body: data,
    }).then(response => response.json().then(json => {
        if (json.data.success) {
          //fetchFields();
          fetchTaxonomies();
          setModalOpen(false);
        }
        if (json.data.error) {
          setNotice(json.data.message);
        }
        setEmptyTaxonomies(false);
        setLoading(false);
        setSubmitting(false);
      }
    ));

  };

  /**
   * Open Terms modal
   * ----------------
   *
   * @param e
   * @param order
   * @param options
   * @param isPredefined
   */
  const handleModal = (e, order, options, isPredefined) => {
    setModalOpen(!modalOpen);
    setElementToUpdate(order);
    if (!isEmpty(options)) {
      setUpdateFields(options[fieldGroup][order]);
    }
    setPredefined(isPredefined);
    setNotice('');
  };

  const handleClickOutside = (e, type) => {
    const body = document.querySelector('body');
    if (body.classList.contains('modal-open')) {
      return false;
    }
    setModalOpen(false);
    body.style.overflow = 'auto';
  };

  const handleEmptyTaxonomies = (empty) => {
    setEmptyTaxonomies(empty);
  };

  const closeIeModal = () => {
    setModalOpen(false);
    const body = document.querySelector('body');
    body.style.overflow = 'auto';
  };

  const adminLink = `${lc_data.admin_url}admin.php?page=custom-fields`;
  return [
    !isDashboard && loading &&
    <LoaderTaxonomies key={0}/>,

    !isDashboard &&
    <div key={1} className="relative dashboard py-60">

      {loading &&
      <span
        className="absolute top-0 left-0 w-full h-full bg-grey-100"
        style={{
          'zIndex': 9,
        }}
      ></span>}

      {submitting &&
      <span
        className="absolute top-0 left-0 w-full h-full bg-grey-100 opacity-50"
        style={{
          'zIndex': 9,
        }}
      ></span>}

      <div>

        <a href={adminLink} className="dashboard--header inline-flex items-center font-sans">

          <ReactSVG
            src={`${lc_data.dir}dist/${ArrowLeftIcon}`}
            className="relative w-20 h-20 fill-grey-1100"
          />

          <div className="flex flex-col ml-20 pl-20 border-l-2 border-grey-200">
            <h1 className="font-bold text-4xl">{groupName}</h1>
            <p className="mt-10 text-lg text-grey-500">{lc_data.jst[70]}</p>
          </div>
        </a>

        {(!isEmpty(taxonomies[fieldGroup]) && !emptyTaxonomies) &&
        <div className="flex">
          <button
            type="button"
            onClick={(e) => handleModal(e, [], [], false)}
            className="flex justify-center items-center mt-40 py-12 px-24 bg-blue-700 hover:bg-blue-800 rounded font-bold text-base text-white"
          >
            <ReactSVG
              src={`${lc_data.dir}dist/${PlusIcon}`}
              className="relative mr-8 w-20 h-20 fill-white"
            />
            {lc_data.jst[77]}
          </button>
          {<RefreshTaxonomies/>}
        </div>
        }

        <div
          className={`taxonomies--wrapper relative mt-40 rounded ${(isEmpty(taxonomies[fieldGroup]) || emptyTaxonomies) ? 'py-192 pl-60' : 'py-30'}`}
        >

          {(isEmpty(taxonomies[fieldGroup]) || emptyTaxonomies) &&
          <Fragment>
            <h2 className="font-bold text-4xl">{lc_data.jst[72]}</h2>
            <p
              className="mt-14 text-lg text-grey-900">{lc_data.jst[73]}</p>

            <div className="flex items-center">
              <button
                type="button"
                onClick={(e) => handleModal(e, [], [], false)}
                className="flex justify-center items-center mt-40 py-12 px-24 bg-blue-700 hover:bg-blue-800 rounded font-bold text-base text-white"
              >
                <ReactSVG
                  src={`${lc_data.dir}dist/${PlusIcon}`}
                  className="relative mr-8 w-20 h-20 fill-white"
                />
                {lc_data.jst[77]}
              </button>
            </div>

          </Fragment>
          }

          {(!isEmpty(taxonomies[fieldGroup]) || !emptyTaxonomies) &&
          <Taxonomies
            loading={loading}
            handleModal={handleModal}
            taxonomies={taxonomies}
            needsRefresh={needsRefresh}
            emptyTaxonomies={(empty) => handleEmptyTaxonomies(empty)}
            taxSubmitting={submitting}
          />}

        </div>

        <div className="flex mt-40 px-30">
          <p dangerouslySetInnerHTML={{
            __html: sprintf(lc_data.jst[11],
              `<a href="https://pebas.gitbook.io/lisfinity-documentation/fields-builder/disabling-fields-builder" class="text-blue-600 underline" target="_blank">${lc_data.jst[12]}</a>`)
          }}></p>
        </div>

      </div>

      {modalOpen ?
        <div
          className="modal--wrapper fixed top-0 left-0 flex justify-center w-full h-full overflow-y-auto"
        >
          <ModalNew
            open={modalOpen}
            closeModal={() => !submitting && closeModal()}
            title={lc_data.jst[75]}
          >
            <form className="form-taxonomy form-fetch"
                  onSubmit={(e, edit) => handleSubmit(e, elementToUpdate.length !== 0)}>
              {predefined ?
                <div>
                  <input type="hidden" name="element-to-update" value={elementToUpdate}/>
                  <input type="hidden" name="element-old-slug" value={updateFields.slug}/>
                </div>
                : ''}
              {fields.general &&
              <div className="flex flex-wrap justify-between">
                {map(fields.general, (value, name) => (
                  showField(value, name, predefined)
                ))}
              </div>
              }
              {predefined
                ?
                <div className="flex justify-end">
                  <button
                    className="flex justify-center items-center py-12 px-24 h-44 bg-blue-700 hover:bg-blue-800 rounded font-bold text-base text-white"
                    disabled={submitting}
                  >
                    {submitting &&
                    <Fragment>
                      <ReactSVG
                        src={`${lc_data.dir}dist/${Spinner}`}
                        className="absolute"
                        style={{ zoom: .2 }}
                      />
                      <span className="opacity-0">{lc_data.jst[77]}</span>
                    </Fragment>
                    }
                    {!submitting &&
                    <Fragment>
                      <ReactSVG
                        src={`${lc_data.dir}dist/${SaveIcon}`}
                        className="relative mr-8 w-20 h-20 fill-white"
                      />
                      {lc_data.jst[52]}
                    </Fragment>
                    }
                  </button>
                </div>
                :
                <div className="flex justify-end">
                  <button
                    className="relative flex justify-center items-center py-12 px-24 h-44 bg-blue-700 hover:bg-blue-800 rounded font-bold text-base text-white"
                    disabled={submitting}
                  >
                    {submitting &&
                    <Fragment>
                      <ReactSVG
                        src={`${lc_data.dir}dist/${Spinner}`}
                        className="absolute"
                        style={{ zoom: .2 }}
                      />
                      <span className="opacity-0">{lc_data.jst[77]}</span>
                    </Fragment>
                    }
                    {!submitting &&
                    <Fragment>
                      <ReactSVG
                        src={`${lc_data.dir}dist/${SaveIcon}`}
                        className="relative mr-8 w-20 h-20 fill-white"
                      />
                      {lc_data.jst[77]}
                    </Fragment>
                    }
                  </button>
                </div>
              }
              {notice &&
              <div className="response response--error flex items-center">
                <span className="text-base text-red-700">{notice}</span>
              </div>
              }
            </form>
          </ModalNew>
        </div>
        : ''}
    </div>,
  ];
};

export default Dashboard;
