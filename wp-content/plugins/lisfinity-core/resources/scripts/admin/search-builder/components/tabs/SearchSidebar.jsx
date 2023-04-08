/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component, Fragment, createRef } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { map, get, isEmpty } from 'lodash';
import { __ } from '@wordpress/i18n';
import jsonForm from '../../../../forms/utils/build-form-data';
import ReactSVG from 'react-svg';
import SaveIcon from '../../../../../images/icons/save.svg';
import ScrollContainer from 'react-indiana-drag-scroll';
import ArrowDown from '../../../../../images/icons/chevron-down.svg';
import KeyboardIcon from '../../../../../images/icons/keyboard.svg';
import { toast } from 'react-toastify';
import LoaderSearchBuilder from '../../../loaders/LoaderSearchBuilder';
import LoaderIconWhite from '../../../../../images/icons/loader-rings-white.svg';
import Spinner from '../../../../../images/icons/spinner.svg';

let isDown = false;
let startX;
let scrollLeft;

class SearchSidebar extends Component {
  constructor(props) {
    super(props);
    this.state = {
      chosen: [],
      fields: {},
      groups: {},
      chosenGroup: '',
      containerOpen: '',
      detailGroups: {},
      loading: true,
      submitting: false,
    };
  }

  toScroll = createRef();
  wrapper = createRef();

  /**
   * Component will be mounted
   * -------------------------
   */
  componentWillMount() {
    this.getGroups();
    this.getSettings();
    this.fetchSidebarGroups();
  }

  componentDidMount() {
    if (this.wrapper.current) {
      this.wrapper.current.addEventListener('mousedown', this.mouseDown);
      this.wrapper.current.addEventListener('mouseleave', this.mouseLeave);
      this.wrapper.current.addEventListener('mouseup', this.mouseUp);
      this.wrapper.current.addEventListener('mousemove', this.mouseMove);
    }
  }

  componentWillUnmount() {
    if (this.wrapper.current) {
      this.wrapper.current.removeEventListener('mousedown', this.mouseDown);
      this.wrapper.current.removeEventListener('mouseleave', this.mouseLeave);
      this.wrapper.current.removeEventListener('mouseup', this.mouseUp);
      this.wrapper.current.removeEventListener('mousemove', this.mouseMove);
    }
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
  fetchSidebarGroups() {
    this.setState({ loading: true });
    apiFetch({ path: lc_data.search_builder_group_get }).then(groups => {
      this.setState({ detailGroups: groups });
      this.setState({ loading: false });
    });
  }

  /**
   * Get search builder field settings
   * ---------------------------------
   */
  getSettings() {
    const { settings } = this.state;
    this.setState({ loading: true });
    apiFetch({ path: `${lc_data.search_builder_fields}/sidebar` }).then(fields => {
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

    if (chosen && chosen.includes(name)) {
      map(chosen, (item, index) => name === item && chosen.splice(index, 1));
    } else {
      chosen.push(name);
    }

    let group = {};
    if (fields[chosenGroup] === undefined) {
      fields[chosenGroup] = { types: [], fields: [] };
    }
    group = fields[chosenGroup];
    if (group?.types?.includes(name)) {
      map(group.types, (item, index) => name === item && group.types.splice(index, 1));
    } else {
      group?.types?.push(name);
    }

    this.setState({ fields });
    this.setState({ chosen });
  };

  /**
   * Handle local storing of the clicked fields
   * ------------------------------------------
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
    if (group && group?.fields && group?.fields.includes(name)) {
      map(group.fields, (item, index) => name === item && group.fields.splice(index, 1));
    } else {
      group && group.fields && group.fields.push(name);
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
   * Field types variations change handler
   * -------------------------------------
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
   * Field types variations change handler
   * -------------------------------------
   *
   * @param value
   * @param name
   * @param group
   * @param type
   */
  handleMinNumberChange = (value, name, group, type) => {

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
    const { chosen, chosenGroup, containerOpen, detailGroups, fields } = this.state;
    const { options } = this.props;
    const types = get(fields[chosenGroup], 'types');
    const chosenFields = get(fields[chosenGroup], 'fields');

    if (name === 'taxonomy' && types && types?.includes('sb-taxonomy')) {
      return (
        <div key={name} className="search-builder--group px-col">
          <h4 className="mb-10 font-bold text-grey-1000">{lc_data.jst[54]}</h4>

          <div className="fields">
            {map(group, (field, index) => {
              const taxonomyType = get(options['taxonomy'][index], 'type');
              return ('common' === field.field_group || chosenGroup === field.field_group) && 'text' !== field.type &&
                <div key={index}
                     className={`flex flex-col mb-10 py-16 px-20 bg-white rounded cursor-pointer ${'' !== field.parent && 0 !== field.parent ? 'ml-20' : ''}`}
                     onClick={e => this.handleItemClick(e, e.target.value, field.slug)}
                >

                  <div className="flex justify-between items-center">
                    <label htmlFor={field.slug} className="taxonomy--label font-bold">
                      <input
                        type="checkbox"
                        className="grey"
                        id={field.slug}
                        name={field.slug}
                        checked={chosenFields && chosenFields.includes(field.slug) || false}
                        onChange={this.handleFieldClick}
                      />
                      {field.single_name}
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
                      <label htmlFor={`label[${field.slug}][${index}]`}
                             className="mb-4 text-sm text-grey-500">{__('Label', 'lisfinity-core')}</label>
                      <div className="flex items-center px-16 bg-transparent border border-grey-400 rounded">
                        <ReactSVG
                          src={`${lc_data.dir}dist/${KeyboardIcon}`}
                          className="relative top-2 mr-8 w-20 h-20 fill-grey-700 pointer-events-none"
                        />
                        <input
                          className="py-12 no-close w-full bg-transparent border border-grey-300 rounded font-bold text-grey-900"
                          type="text"
                          id={`label[${field.slug}][${index}]`}
                          placeholder={__('No label', 'lisfinity-core')}
                          autoComplete="off"
                          onChange={e => this.handleLabelChange(e.target.value, field.slug, field.field_group)}
                          defaultValue={fields && get(fields[field.field_group]['labels'], field.slug)}
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
                          defaultValue={fields && get(fields[field.field_group]['placeholders'], field.slug)}
                          placeholder={lc_data.jst[35]}
                          autoComplete="off"
                        />
                      </div>
                    </div>
                    <Fragment>
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

                        <div className="flex flex-col mt-10 mb-10">
                          <label htmlFor={`type[${field.slug}][${index}]['minNumber]`}
                                 className="mb-4 text-sm text-grey-500">{lc_data.jst[727]}</label>
                          <div className="flex items-center px-16 bg-transparent border border-grey-400 rounded">
                            <ReactSVG
                              src={`${lc_data.dir}dist/${KeyboardIcon}`}
                              className="relative top-2 mr-8 w-20 h-20 fill-grey-700"
                            />
                            <input
                              className="py-12 w-full bg-transparent border border-grey-300 rounded font-bold text-grey-900 no-close"
                              type="number"
                              id={`type[${field.slug}][${index}]['minNumber]`}
                              onChange={e => this.handleMinNumberChange(e.target.value, field.slug, field.field_group, 'minNumber')}
                              defaultValue={fields && fields[field.field_group] && fields[field.field_group]['options'] && get(fields[field.field_group]['options'][field.slug], 'minNumber') || 0}
                              placeholder="0"
                              autoComplete="off"
                            />
                          </div>
                        </div>
                      </Fragment>
                      }
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
                    </Fragment>
                  </Fragment>
                  }
                </div>;
            })}
          </div>
        </div>
      );
    }
    if (name === 'meta' && types && types?.includes('sb-meta')) {
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
                    <label htmlFor={field.slug} className="taxonomy--label font-bold">
                      <input
                        type="checkbox"
                        id={field.slug}
                        name={field.slug}
                        className="grey"
                        onChange={this.handleFieldClick}
                        checked={chosenFields && chosenFields.includes(field.slug) || false}
                      />
                      {field.single_name}
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
                          placeholder={__('No label', 'lisfinity-core')}
                          onChange={e => this.handleLabelChange(e.target.value, field.slug, chosenGroup)}
                          defaultValue={fields && get(fields[chosenGroup]['labels'], field.slug) || ''}
                          autoComplete="off"
                        />
                      </div>
                    </div>

                    <div className="flex flex-col mt-10">
                      <label htmlFor={`placeholder[${field.slug}]`}
                             className="mb-4 text-sm text-grey-500">{lc_data.jst[708]}</label>
                      <div className="flex items-center px-16 bg-transparent border border-grey-400 rounded">
                        <ReactSVG
                          src={`${lc_data.dir}dist/${KeyboardIcon}`}
                          className="relative top-2 mr-8 w-20 h-20 fill-grey-700 pointer-events-none"
                        />
                        <input
                          className="py-12 no-close w-full bg-transparent border border-grey-300 rounded font-bold text-grey-900"
                          type="text"
                          id={`label[${field.slug}]-min`}
                          placeholder={lc_data.jst[40]}
                          onChange={e => this.handlePlaceholderChange(e.target.value, `${field.slug}-min`, chosenGroup)}
                          defaultValue={fields && get(fields[chosenGroup]['placeholders'], `${field.slug}-min`) || ''}
                          autoComplete="off"
                        />
                      </div>
                    </div>

                    <div className="flex flex-col mt-10">
                      <label htmlFor={`placeholder[${field.slug}]`}
                             className="mb-4 text-sm text-grey-500">{lc_data.jst[709]}</label>
                      <div className="flex items-center px-16 bg-transparent border border-grey-400 rounded">
                        <ReactSVG
                          src={`${lc_data.dir}dist/${KeyboardIcon}`}
                          className="relative top-2 mr-8 w-20 h-20 fill-grey-700 pointer-events-none"
                        />
                        <input
                          className="py-12 no-close w-full bg-transparent border border-grey-300 rounded font-bold text-grey-900"
                          type="text"
                          id={`label[${field.slug}]-max`}
                          placeholder={lc_data.jst[40]}
                          onChange={e => this.handlePlaceholderChange(e.target.value, `${field.slug}-max`, chosenGroup)}
                          defaultValue={fields && get(fields[chosenGroup]['placeholders'], `${field.slug}-max`) || ''}
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

                    <div className="flex flex-col mt-10 mb-10">
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
                          onChange={e => this.handleMinNumberChange(e.target.value, field.slug, chosenGroup, 'suffix')}
                          defaultValue={undefined !== get(fields[chosenGroup]['options'], field.slug) ? get(fields[chosenGroup]['options'][field.slug], 'suffix') : ''}
                          autoComplete="off"
                        />
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
   * Handle search builder form submission
   * -------------------------------------
   *
   * @param e
   */
  handleSubmit = e => {
    e.preventDefault();
    const { fields } = this.state;
    this.setState({ submitting: true });
    const data = fields;
    data['type'] = 'sidebar';
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
   * Handle clicking on a fields group
   * ---------------------------------
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
   * Renders the component.
   *
   * @return {Object}
   */
  render() {
    const { options } = this.props;
    const { chosen, groups, chosenGroup, fields } = this.state;
    const types = fields && get(fields[chosenGroup], 'types');
    return (
      <Fragment>
        {this.state.loading &&
        <div className="relative w-full bg:w-2/4 h-full">
          <LoaderSearchBuilder/>
        </div>
        }
        {!this.state.loading &&
        <div className="search-builder--page py-40 px-20 bg-grey-200 sm:p-40">
          <div>

            <div className="flex flex-col justify-between mb-20 sm:flex-row">
              <div>
                <h2 className="mb-10 font-bold text-4xl">{lc_data.jst[7]}</h2>
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
                         checked={types && types?.includes('sb-taxonomy') || false}/>
                  {lc_data.jst[54]}
                </label>
              </div>
              <div className="field flex items-center">
                <label htmlFor="sb-meta" className="font-bold">
                  <input type="checkbox" id="sb-meta" name="sb-meta" onChange={this.handleChange}
                         className="mr-8"
                         checked={types && types?.includes('sb-meta') || false}/>
                  {lc_data.jst[55]}
                </label>
              </div>
            </div>

            <div className="sb-groups relative flex flex-no-wrap -mx-col overflow-y-auto"
                 ref={this.wrapper}
            >
              {this.state.submitting &&
              <span className="absolute top-0 left-0 w-full h-full bg-grey-200 z-10 opacity-50"></span>
              }
              {map(options, (group, name) => {
                return this.displayFields(group, name);
              })}
            </div>
          </div>
        </div>
        }
      </Fragment>
    );
  }
}

export default SearchSidebar;
