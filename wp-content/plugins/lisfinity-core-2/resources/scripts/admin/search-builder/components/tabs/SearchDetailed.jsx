/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component, Fragment, createRef } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { map, isEmpty, get } from 'lodash';
import { __, sprintf } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import jsonForm from '../../../../forms/utils/build-form-data';
import Modal from '../../../taxonomies/components/Modal';
import Sortable from '../sortable/sortable';
import ReactSVG from 'react-svg';
import SaveIcon from '../../../../../images/icons/save.svg';
import ScrollContainer from 'react-indiana-drag-scroll';
import ArrowDown from '../../../../../images/icons/chevron-down.svg';
import KeyboardIcon from '../../../../../images/icons/keyboard.svg';
import PlusIcon from '../../../../../images/icons/plus.svg';
import PencilIcon from '../../../../../images/icons/pencil.svg';
import TrashIcon from '../../../../../images/icons/trash.svg';
import { toast } from 'react-toastify';
import LoaderSearchBuilder from '../../../loaders/LoaderSearchBuilder';
import LoaderIconWhite from '../../../../../images/icons/loader-rings-white.svg';
import Spinner from '../../../../../images/icons/spinner.svg';

let isDown = false;
let startX;
let scrollLeft;

class SearchDetailed extends Component {
  constructor(props) {
    super(props);
    this.state = {
      chosen: [],
      fields: {},
      groups: {},
      chosenGroup: '',
      containerOpen: '',
      detailGroups: {},
      modalOpen: false,
      modalEdit: false,
      modalNotice: '',
      themeOptions: {},
      loading: true,
      submitting: false,
    };
  }

  items = createRef();
  wrapper = createRef();

  /**
   * Before component has been mounted
   * ---------------------------------
   */
  componentWillMount() {
    this.fetchDetailGroups();
    this.getGroups();
    this.getSettings();
  }

  /**
   * After a component has been mounted
   * ----------------------------------
   */
  componentDidMount() {
    if (this.wrapper.current) {
      this.wrapper.current.addEventListener('mousedown', this.mouseDown);
      this.wrapper.current.addEventListener('mouseleave', this.mouseLeave);
      this.wrapper.current.addEventListener('mouseup', this.mouseUp);
      this.wrapper.current.addEventListener('mousemove', this.mouseMove);
    }
    document.addEventListener('keydown', this.escFunction, false);

    const id = document.getElementById('lisfinity-search-builder');
    if (id) {
      const options = JSON.parse(id.dataset.options);
      this.setState({ themeOptions: options });
    }
  }

  /**
   * Component will be unmounted
   * ---------------------------
   */
  componentWillUnmount() {
    if (this.wrapper.current) {
      this.wrapper.current.removeEventListener('mousedown', this.mouseDown);
      this.wrapper.current.removeEventListener('mouseleave', this.mouseLeave);
      this.wrapper.current.removeEventListener('mouseup', this.mouseUp);
      this.wrapper.current.removeEventListener('mousemove', this.mouseMove);
    }
    document.removeEventListener('keydown', this.escFunction, false);
  }

  mouseDown = (e) => {
    isDown = true;
    this.wrapper.current.classList.add('active');
    startX = e.pageX - this.wrapper.current.offsetLeft;
    scrollLeft = this.wrapper.current.scrollLeft;
  };

  mouseLeave = () => {
    isDown = false;
    this.wrapper.current.classList.remove('active');
  };

  mouseUp = () => {
    isDown = false;
    this.wrapper.current.classList.remove('active');
  };

  mouseMove = (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - this.wrapper.current.offsetLeft;
    const walk = (x - startX) * 3; //scroll-fast
    this.wrapper.current.scrollLeft = scrollLeft - walk;
  };

  handleClickOutside = e => {
    this.setState({ modalOpen: false });
    const body = document.querySelector('body');
    body.style.overflow = 'auto';
  };

  /**
   * Get search builder field groups
   * -------------------------------
   */
  getGroups() {
    apiFetch({ path: lc_data.groups }).then(groups => {
      const allGroups = [
        {
          single_name: 'Common', plural_name: 'Commons', slug: 'common',
        }
      ];
      map(groups, group => {
        allGroups.push(group);
      });
      this.setState({ chosenGroup: allGroups[0].slug });
      this.setState({ groups: allGroups });
    });
  }

  /**
   * Fetch all available field groups
   * --------------------------------
   */
  fetchDetailGroups() {
    this.setState({ loading: true });
    apiFetch({ path: lc_data.search_builder_group_get }).then(groups => {
      this.setState({ detailGroups: groups });
    });
  }

  /**
   * Get search builder available fields
   * -----------------------------------
   */
  getSettings() {
    this.setState({ loading: true });
    apiFetch({ path: `${lc_data.search_builder_fields}/detailed` }).then(fields => {
      const fieldsLoaded = fields.fields || {};
      this.setState({ fields: fieldsLoaded });
      this.setState({ loading: false });
    });
  }

  /**
   * Add or remove element from the list of chosen
   * ---------------------------------------------
   * @param e
   */
  handleChange = e => {
    let { chosen, fields, chosenGroup } = this.state;
    const { name } = e.target;

    if (chosen.includes(name)) {
      map(chosen, (item, index) => name === item && chosen.splice(index, 1));
    } else {
      chosen.push(name);
    }
    let group = {};
    if (fields[chosenGroup] === undefined) {
      fields[chosenGroup] = { types: [], fields: [] };
    }
    group = fields[chosenGroup];
    if (group.types.includes(name)) {
      map(group.types, (item, index) => name === item && group.types.splice(index, 1));
    } else {
      group.types.push(name);
    }

    this.setState({ fields });
    this.setState({ chosen });
  };

  /**
   * Handle local storing of a field after it has been clicked
   * ---------------------------------------------------------
   *
   * @param e
   */
  handleFieldClick = e => {
    let { chosen, fields, chosenGroup } = this.state;
    const { name } = e.target;

    let group = {};
    if (fields[chosenGroup] === undefined) {
      fields[chosenGroup] = { types: [], fields: [] };
    }
    group = fields[chosenGroup];
    if (group && !group.fields) {
      group.fields = [];
    }
    if (group.fields.includes(name)) {
      map(group.fields, (item, index) => name === item && group.fields.splice(index, 1));
    } else {
      group.fields.push(name);
    }

    this.setState({ fields });
  };

  /**
   * Open container on a click
   * -------------------------
   *
   * @param e
   * @param value
   * @param name
   */
  handleItemClick = (e, value, name) => {
    const { containerOpen } = this.state;
    if (e.target.classList.contains('taxonomy--label') || e.target.classList.contains('grey') || e.target.classList.contains('no-close') || e.target.classList.contains('bg-transparent') || e.target.classList.contains('text-grey-500')) {
      return false;
    }
    if (containerOpen === name) {
      this.setState({ containerOpen: '' });
    } else {
      this.setState({ containerOpen: name });
    }
  };

  /**
   * Change label handler
   * --------------------
   *
   * @param value
   * @param name
   * @param group
   */
  handleLabelChange = (value, name, group) => {
    const { fields } = this.state;
    if (!fields[group]) {
      fields[group] = {};
    }
    if (!get(fields[group], 'labels')) {
      fields[group]['labels'] = {};
    }
    fields[group].labels[name] = value;
    this.setState({ fields });
  };

  /**
   * Change placeholder handler
   * --------------------------
   *
   * @param value
   * @param name
   * @param group
   */
  handlePlaceholderChange = (value, name, group) => {
    const { fields } = this.state;
    if (!fields[group]) {
      fields[group] = {};
    }
    if (!get(fields[group], 'placeholders')) {
      fields[group]['placeholders'] = {};
    }
    fields[group].placeholders[name] = value;
    this.setState({ fields });
  };

  /**
   * Change group handler
   * --------------------
   *
   * @param value
   * @param name
   * @param group
   */
  handleGroupChange = (value, name, group) => {
    const { fields } = this.state;
    if (!get(fields[group], 'groups')) {
      fields[group]['groups'] = {};
    }
    fields[group].groups[name] = value;
    this.setState({ fields });

  };

  /**
   * Change type handler
   * -------------------
   *
   * @param value
   * @param name
   * @param group
   * @param type
   */
  handleTypeChange = (value, name, group, type) => {
    const { fields } = this.state;
    if (!fields[group]) {
      fields[group] = {};
    }
    if (!get(fields[group], 'options')) {
      fields[group]['options'] = {};
    }
    if (!get(fields[group]['options'], name)) {
      fields[group]['options'][name] = {};
    }
    fields[group].options[name][type] = value;
    this.setState({ fields });
  };

  /**
   * Display fields for creating a search
   * ------------------------------------
   *
   * @param group
   * @param name
   * @returns {*}
   */
  displayFields(group, name) {
    const { chosen, chosenGroup, fields, detailGroups, containerOpen } = this.state;
    const { options } = this.props;
    const types = get(fields[chosenGroup], 'types');
    const chosenFields = get(fields[chosenGroup], 'fields');
    if (name === 'taxonomy' && types && types.includes('sb-taxonomy')) {
      return (
        <div key={name} className="search-builder--group px-col">
          <h4 className="mb-10 font-bold text-grey-1000">{__('Taxonomy', 'lisfinity-core')}</h4>

          <div className="fields">
            {map(group, (field, index) => {
              const taxonomyType = get(options['taxonomy'][index], 'type');
              return ('common' === field.field_group || chosenGroup === field.field_group) && 'text' !== field.type &&
                <div key={index}
                     className={`flex flex-col mb-10 py-16 px-20 bg-white rounded cursor-pointer ${'' !== field.parent && 0 !== field.parent ? 'ml-20' : ''}`}
                     onClick={e => this.handleItemClick(e, e.target.value, field.slug)}
                >

                  <div className="flex justify-between items-center">
                    <label htmlFor={field.slug} className="font-bold">
                      <input
                        type="checkbox"
                        className="grey"
                        id={field.slug}
                        name={field.slug}
                        checked={chosenFields && chosenFields.includes(field.slug) || false}
                        onChange={this.handleFieldClick}
                      />
                      {field.single_name}
                      <span
                        className="ml-4 font-normal text-sm text-grey-500">{decodeURIComponent(get(fields[chosenGroup]['groups'], field.slug))}</span>
                    </label>

                    <span>
                      <ReactSVG
                        src={`${lc_data.dir}dist/${ArrowDown}`}
                        className="relative w-12 h-12 fill-grey-700"
                      />
                    </span>
                  </div>

                  {containerOpen === field.slug &&
                  <Fragment>
                    <div className="flex flex-col mt-10">
                      <label htmlFor={`label[${field.slug}]`}
                             className="mb-4 text-sm text-grey-500">{__('Label', 'lisfinity-core')}</label>
                      <div className="flex items-center px-16 bg-transparent border border-grey-400 rounded">
                        <ReactSVG
                          src={`${lc_data.dir}dist/${KeyboardIcon}`}
                          className="relative top-2 mr-8 w-20 h-20 fill-grey-700 pointer-events-none"
                        />
                        <input
                          className="py-12 no-close w-full bg-transparent border border-grey-300 rounded font-bold text-grey-900"
                          type="text"
                          id={`label[${field.slug}]`}
                          placeholder={__('No label', 'lisfinity-core')}
                          autoComplete="off"
                          onChange={e => this.handleLabelChange(e.target.value, field.slug, field.field_group)}
                          defaultValue={fields && fields[field.field_group] && get(fields[field.field_group]['labels'], field.slug)}
                        />
                      </div>
                    </div>
                    <div className="flex flex-col mt-10">
                      <label htmlFor={`placeholder[${field.slug}]`}
                             className="mb-4 text-sm text-grey-500">{__('Placeholder', 'lisfinity-core')}</label>
                      <div className="flex items-center px-16 bg-transparent border border-grey-400 rounded">
                        <ReactSVG
                          src={`${lc_data.dir}dist/${KeyboardIcon}`}
                          className="relative top-2 mr-8 w-20 h-20 fill-grey-700 pointer-events-none"
                        />
                        <input
                          className="py-12 no-close w-full bg-transparent border border-grey-300 rounded font-bold text-grey-900"
                          type="text"
                          id={`placeholder[${field.slug}]`}
                          onChange={e => this.handlePlaceholderChange(e.target.value, field.slug, field.field_group)}
                          defaultValue={fields && fields[field.field_group] && get(fields[field.field_group]['placeholders'], field.slug)}
                          placeholder={lc_data.jst[35]}
                          autoComplete="off"
                        />
                      </div>
                    </div>
                    {taxonomyType === 'input' &&
                    <Fragment>
                      <div className="flex flex-col mt-10">
                        <label htmlFor={`type[${field.slug}][${index}]['step]`}
                               className="mb-4 text-sm text-grey-500">{lc_data.jst[64]}</label>
                        <div className="flex items-center px-16 bg-transparent border border-grey-400 rounded">
                          <ReactSVG
                            src={`${lc_data.dir}dist/${KeyboardIcon}`}
                            className="relative top-2 mr-8 w-20 h-20 fill-grey-700 pointer-events-none"
                          />
                          <input
                            className="py-12 no-close w-full bg-transparent border border-grey-300 rounded font-bold text-grey-900"
                            type="number"
                            id={`type[${field.slug}][${index}][step]`}
                            min={1}
                            onChange={e => this.handleTypeChange(e.target.value, field.slug, field.field_group, 'step')}
                            defaultValue={fields && fields[field.field_group] && fields[field.field_group]['options'] && get(fields[field.field_group]['options'][field.slug], 'step') || 1}
                            autoComplete="off"
                          />
                        </div>
                      </div>
                      <div className="flex flex-col mt-10">
                        <label htmlFor={`type[${field.slug}][${index}][prefix]`}
                               className="mb-4 text-sm text-grey-500">{__('Prefix', 'lisfinity-core')}</label>
                        <div className="flex items-center px-16 bg-transparent border border-grey-400 rounded">
                          <ReactSVG
                            src={`${lc_data.dir}dist/${KeyboardIcon}`}
                            className="relative top-2 mr-8 w-20 h-20 fill-grey-700 pointer-events-none"
                          />
                          <input
                            className="py-12 no-close w-full bg-transparent border border-grey-300 rounded font-bold text-grey-900"
                            type="text"
                            id={`type[${field.slug}][${index}][prefix]`}
                            onChange={e => this.handleTypeChange(e.target.value, field.slug, field.field_group, 'prefix')}
                            defaultValue={fields && fields[field.field_group] && fields[field.field_group]['options'] && get(fields[field.field_group]['options'][field.slug], 'prefix') || ''}
                            autoComplete="off"
                          />
                        </div>
                      </div>
                      <div className="flex flex-col mt-10 mb-10">
                        <label htmlFor={`type[${field.slug}][${index}][suffix]`}
                               className="mb-4 text-sm text-grey-500">{__('Suffix', 'lisfinity-core')}</label>
                        <div className="flex items-center px-16 bg-transparent border border-grey-400 rounded">
                          <ReactSVG
                            src={`${lc_data.dir}dist/${KeyboardIcon}`}
                            className="relative top-2 mr-8 w-20 h-20 fill-grey-700 pointer-events-none"
                          />
                          <input
                            className="py-12 no-close w-full bg-transparent border border-grey-300 rounded font-bold text-grey-900"
                            type="text"
                            id={`type[${field.slug}][${index}][suffix]`}
                            onChange={e => this.handleTypeChange(e.target.value, field.slug, field.field_group, 'suffix')}
                            defaultValue={fields && fields[field.field_group] && fields[field.field_group]['options'] && get(fields[field.field_group]['options'][field.slug], 'suffix') || ''}
                            autoComplete="off"
                          />
                        </div>
                      </div>
                    </Fragment>}
                    <div className="flex flex-col mt-10 mb-10">
                      <label htmlFor={`group[${field.slug}]`}
                             className="mb-4 text-sm text-grey-500">{__('Group', 'lisfinity-core')}</label>
                      <div className="relative flex items-center">
                        <ReactSVG
                          src={`${lc_data.dir}dist/${ArrowDown}`}
                          className="absolute right-16 w-10 h-10 fill-grey-700 pointer-events-none"
                        />
                        <select id={`group[${field.slug}]`}
                                className="appearance-none px-16 py-12 w-full h-auto bg-transparent border border-grey-400 rounded font-bold text-grey-900 no-close"
                                onChange={e => this.handleGroupChange(e.target.value, field.slug, chosenGroup, 'meta')}
                                defaultValue={get(fields[chosenGroup]['groups'], field.slug)}
                        >
                          <option value="any" className="no-close">{__('Not Specified', 'lisfinity-core')}</option>
                          {map(detailGroups[chosenGroup], (name, slug) => {
                            return <option key={slug} value={slug} className="no-close">{name}</option>;
                          })}
                        </select>
                      </div>
                    </div>
                  </Fragment>
                  }
                </div>;
            })}
          </div>
        </div>
      );
    }
    if (name === 'meta' && types && types.includes('sb-meta')) {
      return (
        <div key={name} className="search-builder--group px-col">
          <h4 className="mb-10 font-bold text-grey-1000">{__('Fields Meta', 'lisfinity-core')}</h4>

          <div className="fields">
            {map(group, (field, index) => {
              return (
                <div key={index}
                     className="flex flex-col mb-10 py-16 px-20 bg-white rounded cursor-pointer"
                     onClick={e => this.handleItemClick(e, e.target.value, field.slug)}
                >
                  <div className="flex justify-between items-center">
                    <label htmlFor={field.slug} className="font-bold">
                      <input
                        type="checkbox"
                        id={field.slug}
                        name={field.slug}
                        className="py-16 grey"
                        onChange={this.handleFieldClick}
                        checked={chosenFields && chosenFields.includes(field.slug) || false}
                      />
                      {field.single_name}
                      <span
                        className="ml-4 font-normal text-sm text-grey-500">{decodeURIComponent(get(fields[chosenGroup]['groups'], field.slug))}</span>
                    </label>

                    <span>
                      <ReactSVG
                        src={`${lc_data.dir}dist/${ArrowDown}`}
                        className="relative w-12 h-12 fill-grey-700"
                      />
                    </span>
                  </div>

                  {containerOpen === field.slug &&
                  <Fragment>
                    <div className="flex flex-col mt-10">
                      <label htmlFor={`label[${field.slug}]`}
                             className="mb-4 text-sm text-grey-500">{lc_data.jst[37]}</label>
                      <div className="flex items-center px-16 bg-transparent border border-grey-400 rounded">
                        <ReactSVG
                          src={`${lc_data.dir}dist/${KeyboardIcon}`}
                          className="relative top-2 mr-8 w-20 h-20 fill-grey-700 pointer-events-none"
                        />
                        <input
                          className="py-12 no-close w-full bg-transparent border border-grey-300 rounded font-bold text-grey-900"
                          type="text"
                          id={`label[${field.slug}]`}
                          placeholder={lc_data.jst[38]}
                          onChange={e => this.handleLabelChange(e.target.value, field.slug, chosenGroup)}
                          defaultValue={fields && get(fields[chosenGroup]['labels'], field.slug) || ''}
                          autoComplete="off"
                        />
                      </div>
                    </div>

                    <div className="flex flex-col mt-10">
                      <label htmlFor={`placeholder[${field.slug}]`}
                             className="mb-4 text-sm text-grey-500">{lc_data.jst[39]}</label>
                      <div className="flex items-center px-16 bg-transparent border border-grey-400 rounded">
                        <ReactSVG
                          src={`${lc_data.dir}dist/${KeyboardIcon}`}
                          className="relative top-2 mr-8 w-20 h-20 fill-grey-700 pointer-events-none"
                        />
                        <input
                          className="py-12 no-close w-full bg-transparent border border-grey-300 rounded font-bold text-grey-900"
                          type="text"
                          id={`label[${field.slug}]`}
                          placeholder={lc_data.jst[40]}
                          onChange={e => this.handlePlaceholderChange(e.target.value, field.slug, chosenGroup)}
                          defaultValue={fields && get(fields[chosenGroup]['placeholders'], field.slug) || ''}
                          autoComplete="off"
                        />
                      </div>
                    </div>

                    <div className="flex flex-col mt-10">
                      <label htmlFor={`type[${field.slug}]`}
                             className="mb-4 text-sm text-grey-500">{lc_data.jst[41]}</label>
                      <div className="flex items-center px-16 bg-transparent border border-grey-400 rounded">
                        <ReactSVG
                          src={`${lc_data.dir}dist/${KeyboardIcon}`}
                          className="relative top-2 mr-8 w-20 h-20 fill-grey-700 pointer-events-none"
                        />
                        <input
                          className="py-12 no-close w-full bg-transparent border border-grey-300 rounded font-bold text-grey-900"
                          type="text"
                          id={`label[${field.slug}]`}
                          onChange={e => this.handleTypeChange(e.target.value, field.slug, chosenGroup, 'prefix')}
                          defaultValue={undefined !== get(fields[chosenGroup]['options'], field.slug) ? get(fields[chosenGroup]['options'][field.slug], 'prefix') : ''}
                          autoComplete="off"
                        />
                      </div>
                    </div>

                    <div className="flex flex-col mt-10">
                      <label htmlFor={`type[${field.slug}]`}
                             className="mb-4 text-sm text-grey-500">{lc_data.jst[42]}</label>
                      <div className="flex items-center px-16 bg-transparent border border-grey-400 rounded">
                        <ReactSVG
                          src={`${lc_data.dir}dist/${KeyboardIcon}`}
                          className="relative top-2 mr-8 w-20 h-20 fill-grey-700 pointer-events-none"
                        />
                        <input
                          className="py-12 no-close w-full bg-transparent border border-grey-300 rounded font-bold text-grey-900"
                          type="text"
                          id={`label[${field.slug}]`}
                          onChange={e => this.handleTypeChange(e.target.value, field.slug, chosenGroup, 'suffix')}
                          defaultValue={undefined !== get(fields[chosenGroup]['options'], field.slug) ? get(fields[chosenGroup]['options'][field.slug], 'suffix') : ''}
                          autoComplete="off"
                        />
                      </div>
                    </div>
                    <div className="flex flex-col mt-10 mb-10">
                      <label htmlFor={`group[${field.slug}]`}
                             className="mb-4 text-sm text-grey-500">{lc_data.jst[43]}</label>
                      <div className="relative flex items-center">
                        <select id={`group[${field.slug}]`}
                                className="appearance-none px-16 py-12 w-full h-auto bg-transparent border border-grey-400 rounded font-bold text-grey-900 no-close"
                                onChange={e => this.handleGroupChange(e.target.value, field.slug, chosenGroup, 'meta')}
                                defaultValue={get(fields[chosenGroup]['groups'], field.slug)}
                        >
                          <option value="any" className="no-close">{lc_data.jst[44]}</option>
                          {map(detailGroups[chosenGroup], (name, slug) => {
                            return <option key={slug} value={slug} className="no-close">{name}</option>;
                          })}
                        </select>
                      </div>
                    </div>
                  </Fragment>
                  }
                </div>
              );
            })}
          </div>
        </div>
      );
    }
  }

  /**
   * Handle submission of search builder form
   * ----------------------------------------
   *
   * @param e
   */
  handleSubmit = e => {
    e.preventDefault();

    const { fields } = this.state;
    this.setState({ submitting: true });
    const data = fields;
    data['type'] = 'detailed';
    const headers = new Headers();
    const formData = jsonForm(data);
    let url = lc_data.search_builder_submit;
    headers.append('X-WP-Nonce', lc_data.nonce);
    fetch(url, {
      method: 'POST',
      credentials: 'same-origin',
      headers,
      body: formData,
    }).then(response => response.json().then(json => {
        toast(json.message, {
          position: toast.POSITION.BOTTOM_CENTER,
          autoClose: 3000,
        });
        this.setState({ submitting: false });
      }
    ));
  };

  /**
   * Handle clicking on field group checkbox to switch a niche group
   * ---------------------------------------------------------------
   *
   * @param e
   */
  handleGroupClick = e => {
    const groups = document.getElementsByClassName('group');
    map(groups, group => group.classList.remove('bg-blue-200'));
    e.target.classList.add('bg-blue-200');
    this.setState({ chosenGroup: e.target.dataset.group });
    this.setState({ containerOpen: '' });
  };

  /**
   * Handler for opening a modal
   * ---------------------------
   *
   * @param e
   * @param slug
   */
  handleModalOpen = (e, slug) => {
    const { modalOpen } = this.state;
    this.setState({ modalOpen: true });
    if (!isEmpty(slug)) {
      this.setState({ modalEdit: slug });
    } else {
      this.setState({ modalEdit: '' });
    }
  };

  /**
   * Handler for closing a modal
   * ---------------------------
   *
   * @param e
   */
  handleModalClose = e => {
    const { modalOpen } = this.state;
    this.setState({ modalOpen: false });
  };

  /**
   * On click escape close the modal
   * -------------------------------
   *
   * @param event
   */
  escFunction = (event) => {
    if (event.keyCode === 27) {
      this.setState({ modalOpen: false });
    }
  };

  /**
   * Fields group submission handler
   * -------------------------------
   *
   * @param e
   * @param slug
   */
  handleGroupSubmit = (e, slug = '') => {
    const { chosenGroup, modalEdit } = this.state;
    e.preventDefault();
    const form = e.target;
    const headers = new Headers();
    const formData = new FormData(form);
    formData.append('niche', chosenGroup);
    if (!isEmpty(modalEdit)) {
      formData.append('edit', 'yes');
    }
    let url = lc_data.search_builder_group_submit;
    headers.append('X-WP-Nonce', lc_data.nonce);
    fetch(url, {
      method: 'POST',
      credentials: 'same-origin',
      headers,
      body: formData,
    }).then(response => response.json().then(json => {
        this.fetchDetailGroups();
        this.setState({ modalOpen: false });
        const message = !isEmpty(modalEdit) ? lc_data.jst[45] : json.message;
        toast(message, {
          position: toast.POSITION.BOTTOM_RIGHT,
          autoClose: 3000,
        });
        this.setState({ loading: false });
        this.setState({ submitting: false });
      }
    ));
  };

  /**
   * Fields group deletion handler
   * -----------------------------
   * @param e
   * @param slug
   * @returns {boolean}
   */
  handleGroupDelete = (e, slug) => {
    const { chosenGroup, } = this.state;
    e.preventDefault();
    if (!confirm(lc_data.jst[46])) {
      return false;
    }
    const headers = new Headers();
    const formData = new FormData();
    formData.append('delete', 'yes');
    formData.append('niche', chosenGroup);
    formData.append('old_group', slug);
    let url = lc_data.search_builder_group_submit;
    headers.append('X-WP-Nonce', lc_data.nonce);
    fetch(url, {
      method: 'POST',
      credentials: 'same-origin',
      headers,
      body: formData,
    }).then(response => response.json().then(json => {
        this.setState({ loading: false });
        this.fetchDetailGroups();
        toast(lc_data.jst[47], {
          position: toast.POSITION.BOTTOM_CENTER,
          autoClose: 3000,
        });
        this.setState({ loading: false });
        this.setState({ submitting: false });
      }
    ));
  };

  /**
   * Renders the component.
   *
   * @return {Object}
   */
  render() {
    const { options } = this.props;
    const {
      chosen,
      groups,
      chosenGroup,
      fields,
      detailGroups,
      modalOpen,
      modalEdit,
      modalNotice,
      themeOptions
    } = this.state;
    const types = get(fields[chosenGroup], 'types');
    return (
      <Fragment>
        {this.state.loading &&
        <div className="relative w-full bg:w-2/4 h-full">
          <LoaderSearchBuilder/>
        </div>
        }
        {!this.state.loading &&
        <div className="flex">

          <div className="search-builder--page py-40 px-20 bg-grey-200 sm:p-40">

            {themeOptions.detailed_search === '0' &&
            <div>
              <h2 className="flex items-center mb-10 font-bold text-4xl">
                {lc_data.jst[48]} -
                <span className="ml-2 text-lg text-red-600">{__('Disabled!', 'lisfinity-core')}</span>
              </h2>
              <p
                className="text-xl">
                {lc_data.jst[49]}
                <a
                  href={`${lc_data.admin_url}admin.php?page=lisfinity-theme-options.php`}
                  className="ml-4 text-blue-700 hover:text-blue-800 hover:underline"
                >{lc_data.jst[50]}</a>
              </p>
            </div>
            }

            {!themeOptions || themeOptions.detailed_search !== '0' &&
            <div>

              <div className="flex flex-col justify-between mb-20 sm:flex-row">
                <div>
                  <h2 className="mb-10 font-bold text-4xl">{lc_data.jst[9]}</h2>
                  <p
                    className="text-lg">{lc_data.jst[51]}</p>
                </div>

                <button
                  type="submit"
                  className="relative flex justify-center items-center my-20 sm:my-0 py-12 px-30 h-44 bg-blue-700 hover:bg-blue-800 rounded font-bold text-base text-white"
                  onClick={this.handleSubmit}
                  disabled={this.state.submitting}
                  style={{
                    minWidth: 119,
                  }}
                >
                  {this.state.submitting &&
                  <ReactSVG
                    src={`${lc_data.dir}dist/${Spinner}`}
                    className="absolute"
                    style={{ zoom: .2 }}
                  />
                  }
                  {!this.state.submitting &&
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

              <div className="niche-groups my-30">
                <div
                  className="niche-groups--tabs flex items-center px-20 bg-white rounded shadow__big overflow-x-hidden">
                  <p className="mr-10 font-bold">{lc_data.jst[53]}</p>
                  <ScrollContainer ref={this.toScroll}>
                    <ul className="flex items-center">
                      {map(groups, (group, index) => {
                        return (
                          <li key={index} className="mb-0 whitespace-no-wrap">
                            <button
                              className={`group py-16 px-20 font-light text-grey-1100 focus:outline-none focus:shadow-none ${0 === index ? 'bg-blue-200' : ''}`}
                              data-group={group.slug}
                              onClick={this.handleGroupClick}
                            >
                              {group.single_name}
                            </button>
                          </li>
                        );
                      })}
                    </ul>
                  </ScrollContainer>
                </div>
              </div>

              <div className="field-groups flex mb-30 pb-30 border-b border-grey-300">
                <div className="field flex items-center mr-20">
                  <label htmlFor="sb-taxonomy" className="font-bold">
                    <input type="checkbox" id="sb-taxonomy" name="sb-taxonomy" onChange={this.handleChange}
                           className="mr-8"
                           checked={types && types.includes('sb-taxonomy') || false}/>
                    {lc_data.jst[54]}
                  </label>
                </div>
                <div className="field flex items-center">
                  <label htmlFor="sb-meta" className="font-bold">
                    <input type="checkbox" id="sb-meta" name="sb-meta" onChange={this.handleChange}
                           className="mr-8"
                           checked={types && types.includes('sb-meta') || false}/>
                    {lc_data.jst[55]}
                  </label>
                </div>
              </div>

              <div className="sb-groups flex flex-no-wrap -mx-col overflow-y-auto"
                   ref={this.wrapper}
              >
                {this.state.submitting &&
                <span className="absolute top-0 left-0 w-full h-full bg-grey-200 z-10 opacity-50"></span>
                }
                {map(options, (group, name) => {
                  return this.displayFields(group, name);
                })}

                <div className="search-builder--group px-col">
                  <h4 className="mb-10 font-bold text-grey-1000">{lc_data.jst[27]}</h4>
                  <Sortable
                    forwardedRef={this.items}
                    onUpdate={this.handleSort}
                  >
                    <div className="groups flex flex-col" ref={this.items} data-niche={chosenGroup}>
                      {get(detailGroups, chosenGroup) && map(detailGroups[chosenGroup], (name, slug) => {
                        return (
                          <div key={slug} data-order={slug}
                               className="group group--sort group--handle flex justify-between items-center mb-10 py-16 px-20 bg-white rounded shadow cursor-pointer focus:cursor-grabbing">
                            <span>{name}</span>
                            <div className="group--actions">
                              <button type="button" onClick={e => this.handleModalOpen(e, slug)} className="mr-10">
                                <ReactSVG
                                  src={`${lc_data.dir}dist/${PencilIcon}`}
                                  className="relative w-14 h-14 fill-grey-900 cursor-pointer pointer-events-none"
                                />
                              </button>
                              <button type="button" onClick={e => this.handleGroupDelete(e, slug)}>
                                <ReactSVG
                                  src={`${lc_data.dir}dist/${TrashIcon}`}
                                  className="relative w-14 h-14 fill-grey-900 cursor-pointer pointer-events-none"
                                />
                              </button>
                            </div>
                          </div>
                        );
                      })}
                    </div>
                  </Sortable>
                  <button
                    type="submit"
                    className="flex justify-center items-center py-12 px-30 h-44 bg-blue-700 hover:bg-blue-800 rounded font-bold text-base text-white"
                    onClick={this.handleModalOpen}
                  >
                    <ReactSVG
                      src={`${lc_data.dir}dist/${PlusIcon}`}
                      className="relative mr-8 w-20 h-20 fill-white"
                    />
                    {lc_data.jst[56]}
                  </button>
                </div>
              </div>

            </div>
            }
          </div>

          {modalOpen &&
          <div
            key={3}
            className="modal--wrapper fixed top-0 left-0 flex justify-center w-full h-full overflow-y-auto"
          >
            <Modal classes="form-update-option"
                   closeModal={this.handleModalClose}
                   title={lc_data.jst[57]}
                   open={modalOpen}
                   handleClickOutside={(e) => this.handleClickOutside(e)}
            >
              <form className="form--group form-fetch"
                    onSubmit={this.handleGroupSubmit}>
                <div className="mb-20 w-full bg:w-1/2">
                  <label htmlFor="newGroup"
                         className="lisfinity-label flex mb-10">{lc_data.jst[58]}</label>
                  <div className="flex items-center px-16 bg-grey-100 border border-grey-200 rounded">
                    <ReactSVG
                      src={`${lc_data.dir}dist/${KeyboardIcon}`}
                      className="relative mr-8 w-18 h-18 fill-grey-700"
                    />
                    <input
                      id="newGroup"
                      type="text"
                      name="new_group"
                      defaultValue={!isEmpty(modalEdit) ? decodeURIComponent(modalEdit) : ''}
                      className="py-12 w-full font-semibold bg-transparent border-0 text-grey-700"
                      autoComplete="off"
                      autoFocus
                    />
                  </div>
                  <div className="description mt-6 text-grey-700 leading-snug"
                       style={{ fontSize: '11px' }}>{lc_data.jst[32]}</div>
                </div>
                {!isEmpty(modalEdit) && <input type="hidden" name="old_group" value={modalEdit}/>}
                <button
                  type="submit"
                  className="flex justify-center items-center py-12 px-30 h-44 bg-blue-700 hover:bg-blue-800 rounded font-bold text-base text-white"
                >
                  <ReactSVG
                    src={`${lc_data.dir}dist/${SaveIcon}`}
                    className="relative mr-8 w-20 h-20 fill-white"
                  />
                  {!isEmpty(modalEdit) ?
                    <span className="capitalize">{sprintf(lc_data.jst[33], decodeURIComponent(modalEdit))}</span>
                    :
                    lc_data.jst[34]
                  }
                </button>
              </form>
            </Modal>
          </div>
          }

        </div>
        }
      </Fragment>
    );
  }
}

export default SearchDetailed;
