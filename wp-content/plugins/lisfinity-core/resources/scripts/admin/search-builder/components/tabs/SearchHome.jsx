/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component, Fragment, createRef } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { map, isEmpty, get } from 'lodash';
import { __, sprintf } from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import SaveIcon from '../../../../../images/icons/save.svg';
import KeyboardIcon from '../../../../../images/icons/keyboard.svg';
import ArrowDown from '../../../../../images/icons/chevron-down.svg';
import ArrowUp from '../../../../../images/icons/chevron-up.svg';
import { toast } from 'react-toastify';
import LoaderSearchBuilder from '../../../loaders/LoaderSearchBuilder';
import LoaderIconWhite from '../../../../../images/icons/loader-rings-white.svg';
import SearchBuilderKeyword from '../partials/SearchBuilderKeyword';
import LoaderIcon from '../../../../../images/icons/loader-rings.svg';
import Spinner from '../../../../../images/icons/spinner.svg';
import jsonForm from '../../../../forms/utils/build-form-data';

let isDown = false;
let startX;
let scrollLeft;

class SearchHome extends Component {
  constructor(props) {
    super(props);
    this.state = {
      chosen: [],
      fields: {},
      labels: {},
      placeholders: {},
      steps: {},
      minNumbers: {},
      options: {},
      keywords: {},
      containerOpen: '',
      loading: false,
      submitting: false,
    };
  }

  wrapper = createRef();

  /**
   * Component is to be mounted
   * --------------------------
   */
  componentWillMount() {
    this.getSettings();
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
   * Get available search builder settings
   * -------------------------------------
   */
  getSettings() {
    const { settings } = this.state;
    const chosen = [];
    const placeholders = {};
    const labels = {};
    const minNumbers = {};
    const maxNumbers = {};
    const steps = {};
    const keywords = {};
    this.setState({ loading: true });
    apiFetch({ path: `${lc_data.search_builder_fields}/home` }).then(fields => {
      const fieldsLoaded = fields || {};
      this.setState({ fields: fieldsLoaded });
      if (!isEmpty(fields)) {
        map(fields.fields.fields, field => {
          if (field === 'sb-keyword' || field === 'sb-taxonomy' || field === 'sb-meta') {
            chosen.push(field);
          }
        });
        map(fields.fields.placeholder, (field, name) => {
          placeholders[name] = field;
        });
        map(fields.fields.label, (field, name) => {
          labels[name] = field;
        });
        map(fields.fields?.steps, (field, name) => {
          steps[name] = field;
        });
        map(fields.fields?.minNumbers, (field, name) => {
          minNumbers[name] = field;
        });
        map(fields.fields?.maxNumbers, (field, name) => {
          maxNumbers[name] = field;
        });
        map(fields.fields.keywordOptions, (field, name) => {
          if (field === 'true' || field === 'false') {
            keywords[name] = field === 'true';
          } else {
            keywords[name] = field;
          }
        });
      }
      this.setState({ chosen });
      this.setState({ labels });
      this.setState({ keywords });
      this.setState({ placeholders });
      this.setState({ steps });
      this.setState({ minNumbers });
      this.setState({ maxNumbers });
      this.setState({ loading: false });
    });
  }

  /**
   * Add or remove element from the list of chosen
   * ---------------------------------------------
   * @param e
   */
  handleChange = e => {
    let { chosen } = this.state;
    const { name } = e.target;

    if (chosen.includes(name)) {
      map(chosen, (item, index) => name === item && chosen.splice(index, 1));
    } else {
      chosen.push(name);
    }

    this.setState({ chosen });
  };

  handleItemClick = (e, value, name) => {
    const { containerOpen } = this.state;
    if (e.target.classList.contains('grey') || e.target.classList.contains('no-close') || e.target.classList.contains('bg-transparent') || e.target.classList.contains('text-grey-500')) {
      return false;
    }
    if (containerOpen === name) {
      this.setState({ containerOpen: '' });
    } else {
      this.setState({ containerOpen: name });
    }
  };

  handleLabelChange = (value, name) => {
    const { labels } = this.state;
    labels[name] = value;
    this.setState({ labels });
  };

  handlePlaceholderChange = (value, name) => {
    const { placeholders } = this.state;
    placeholders[name] = value;
    this.setState({ placeholders });
  };

  handleStepChange = (value, name) => {
    const { steps } = this.state;
    steps[name] = value;
    this.setState({ steps });
  };

  handleKeywordOptions = (keywords) => {
    this.setState({ keywords });
  };

  handleMinNumberChange = (value, name, group, type) => {
    const { minNumbers } = this.state;
    minNumbers[name] = value;
    this.setState({ minNumbers });
  };

  handleMaxNumberChange = (value, name, group, type) => {
    const { maxNumbers } = this.state;
    maxNumbers[name] = value;
    this.setState({ maxNumbers });
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
    const { chosen, fields, labels, placeholders, containerOpen, steps, minNumbers, maxNumbers } = this.state;
    const placeholder = get(fields.fields, 'placeholder');
    const label = get(fields.fields, 'label');

    if (name === 'keyword' && chosen.includes('sb-keyword')) {
      return (
        <div key={name} className="search-builder--group search-builder--group__keyword px-col">
          <h4 className="mb-10 font-bold text-grey-1000">{__('Keyword', 'lisfinity-core')}</h4>

          <div className="p-20 bg-white rounded">

            <div className="field-container flex flex-col">

              <div className="flex flex-col mb-10">
                <label htmlFor={`label[keyword]`}
                       className="mb-4 text-sm text-grey-500">{__('Label', 'lisfinity-core')}</label>
                <div className="flex items-center px-16 bg-transparent border border-grey-400 rounded">
                  <ReactSVG
                    src={`${lc_data.dir}dist/${KeyboardIcon}`}
                    className="relative top-2 mr-8 w-20 h-20 fill-grey-700"
                  />
                  <input
                    className="py-12 w-full bg-transparent border border-grey-300 rounded font-bold text-grey-900 no-close"
                    type="text" id={`label[keyword]`}
                    onChange={e => {
                      this.handleLabelChange(e.target.value, 'keyword');
                    }}
                    defaultValue={labels && labels['keyword'] || lc_data.jst[59]}
                  />
                </div>
              </div>
              <div className="flex flex-col mb-10 ">
                <label htmlFor={`placeholder[keyword]`}
                       className="mb-4 text-sm text-grey-500">{lc_data.jst[39]}</label>
                <div className="flex items-center px-16 bg-transparent border border-grey-400 rounded">
                  <ReactSVG
                    src={`${lc_data.dir}dist/${KeyboardIcon}`}
                    className="relative top-2 mr-8 w-20 h-20 fill-grey-700"
                  />
                  <input
                    className="py-12 w-full bg-transparent border border-grey-300 rounded font-bold text-grey-900 no-close"
                    type="text"
                    id={`placeholder[keyword]`}
                    onChange={e => this.handlePlaceholderChange(e.target.value, 'keyword')}
                    defaultValue={placeholders && placeholders['keyword'] || lc_data.jst[60]}
                  />
                </div>
              </div>
            </div>
          </div>

          <div className="fields mt-20">
            <SearchBuilderKeyword fields={this.state.fields} options={this.state.keywords || {}}
                                  handleKeyword={this.handleKeywordOptions}/>
          </div>
        </div>
      );
    }
    if (name === 'taxonomy' && chosen.includes('sb-taxonomy')) {
      return (

        <div key="category-type" className="search-builder--group px-col">
          <h4 className="mb-10 font-bold text-grey-1000">{lc_data.jst[54]}</h4>

          <div className="fields">
            <div key={9999999}
                 className={`flex flex-col mb-10 py-16 px-20 bg-white rounded cursor-pointer`}
                 onClick={e => this.handleItemClick(e, e.target.value, 'category-type')}
            >

              <div className="flex justify-between items-center">
                <label htmlFor="category-type" className="font-bold">
                  <input
                    type="checkbox"
                    id="category-type"
                    name="category-type"
                    defaultChecked={fields && fields.fields && fields.fields.fields.includes('category-type')}
                    className="grey"
                  />
                  {lc_data.jst[473]}
                </label>

                <span>
                      <ReactSVG
                        src={`${lc_data.dir}dist/${ArrowDown}`}
                        className="relative w-12 h-12 fill-grey-700"
                      />
                    </span>
              </div>
              {containerOpen === 'category-type' &&
              <Fragment>
                <div className="flex flex-col mt-10">
                  <label htmlFor={`label["category-type"]`}
                         className="mb-4 text-sm text-grey-500">{lc_data.jst[37]}</label>
                  <div className="flex items-center px-16 bg-transparent border border-grey-400 rounded">
                    <ReactSVG
                      src={`${lc_data.dir}dist/${KeyboardIcon}`}
                      className="relative top-2 mr-8 w-20 h-20 fill-grey-700"
                    />
                    <input
                      className="py-12 w-full bg-transparent border border-grey-300 rounded font-bold text-grey-900 no-close"
                      type="text"
                      id={`label["category-type"]`}
                      onChange={e => this.handleLabelChange(e.target.value, 'category-type')}
                      defaultValue={labels ? labels['category-type'] : ''}
                      placeholder={lc_data.jst[38]}
                      autoComplete="off"
                    />
                  </div>
                </div>
                <div className="flex flex-col mt-10 mb-10">
                  <label htmlFor={`placeholder["category-type"]`}
                         className="mb-4 text-sm text-grey-500">{lc_data.jst[39]}</label>
                  <div className="flex items-center px-16 bg-transparent border border-grey-400 rounded">
                    <ReactSVG
                      src={`${lc_data.dir}dist/${KeyboardIcon}`}
                      className="relative top-2 mr-8 w-20 h-20 fill-grey-700"
                    />
                    <input
                      className="py-12 w-full bg-transparent border border-grey-300 rounded font-bold text-grey-900 no-close"
                      type="text"
                      id={`placeholder["category-type"]`}
                      onChange={e => this.handlePlaceholderChange(e.target.value, 'category-type')}
                      defaultValue={placeholders ? placeholders['category-type'] : ''}
                      placeholder={lc_data.jst[40]}
                      autoComplete="off"
                    />
                  </div>
                </div>
              </Fragment>
              }
            </div>
            {map(group, (field, index) => {

              const taxonomyType = get(this.props.options['taxonomy'][index], 'type');
              return 'common' === field.field_group && 'text' !== field.type &&
                <div key={index}
                     className={`flex flex-col mb-10 py-16 px-20 bg-white rounded cursor-pointer ${'' !== field.parent ? 'ml-20' : ''}`}
                     onClick={e => this.handleItemClick(e, e.target.value, field.slug)}
                >

                  <div className="flex justify-between items-center">
                    <label htmlFor={field.slug} className="font-bold">
                      <input
                        type="checkbox"
                        id={field.slug}
                        name={field.slug}
                        defaultChecked={fields && fields.fields && fields.fields.fields.includes(field.slug)}
                        className="grey"
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
                          className="relative top-2 mr-8 w-20 h-20 fill-grey-700"
                        />
                        <input
                          className="py-12 w-full bg-transparent border border-grey-300 rounded font-bold text-grey-900 no-close"
                          type="text"
                          id={`label[${field.slug}]`}
                          onChange={e => this.handleLabelChange(e.target.value, field.slug)}
                          defaultValue={labels ? labels[field.slug] : ''}
                          placeholder={lc_data.jst[38]}
                          autoComplete="off"
                        />
                      </div>
                    </div>
                    <div className="flex flex-col mt-10 mb-10">
                      <label htmlFor={`placeholder[${field.slug}]`}
                             className="mb-4 text-sm text-grey-500">{lc_data.jst[39]}</label>
                      <div className="flex items-center px-16 bg-transparent border border-grey-400 rounded">
                        <ReactSVG
                          src={`${lc_data.dir}dist/${KeyboardIcon}`}
                          className="relative top-2 mr-8 w-20 h-20 fill-grey-700"
                        />
                        <input
                          className="py-12 w-full bg-transparent border border-grey-300 rounded font-bold text-grey-900 no-close"
                          type="text"
                          id={`placeholder[${field.slug}]`}
                          onChange={e => this.handlePlaceholderChange(e.target.value, field.slug)}
                          defaultValue={placeholders ? placeholders[field.slug] : ''}
                          placeholder={lc_data.jst[40]}
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
                            onChange={e => this.handleStepChange(e.target.value, field.slug, field.field_group, 'step')}
                            defaultValue={steps?.[field.slug] || 1}
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
                            defaultValue={minNumbers?.[field.slug] || 0}
                            placeholder='0'
                            autoComplete="off"
                          />
                        </div>
                      </div>
                    </Fragment>
                    }
                  </Fragment>
                  }
                </div>;
            })}
          </div>
        </div>
      );
    }
    if (name === 'meta' && chosen.includes('sb-meta')) {
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
                        defaultChecked={fields && fields.fields && fields.fields.fields.includes(field.slug)}
                        className="grey"
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
                             className="mb-4 text-sm text-grey-500">{lc_data.jst[712]}</label>
                      <div className="flex items-center px-16 bg-transparent border border-grey-400 rounded">
                        <ReactSVG
                          src={`${lc_data.dir}dist/${KeyboardIcon}`}
                          className="relative top-2 mr-8 w-20 h-20 fill-grey-700"
                        />
                        <input
                          className="py-12 w-full bg-transparent border border-grey-300 rounded font-bold text-grey-900 no-close"
                          type="text"
                          id={`label[${field.slug}]-min`}
                          onChange={e => this.handleLabelChange(e.target.value, `${field.slug}-label-min`)}
                          defaultValue={labels ? labels[field.slug] : ''}
                          placeholder={lc_data.jst[38]}
                          autoComplete="off"
                        />
                      </div>
                    </div>
                    <div className="flex flex-col mt-10">
                      <label htmlFor={`label[${field.slug}]`}
                             className="mb-4 text-sm text-grey-500">{lc_data.jst[713]}</label>
                      <div className="flex items-center px-16 bg-transparent border border-grey-400 rounded">
                        <ReactSVG
                          src={`${lc_data.dir}dist/${KeyboardIcon}`}
                          className="relative top-2 mr-8 w-20 h-20 fill-grey-700"
                        />
                        <input
                          className="py-12 w-full bg-transparent border border-grey-300 rounded font-bold text-grey-900 no-close"
                          type="text"
                          id={`label[${field.slug}]-max`}
                          onChange={e => this.handleLabelChange(e.target.value, `${field.slug}-label-max`)}
                          defaultValue={labels ? labels[field.slug] : ''}
                          placeholder={lc_data.jst[38]}
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
                          placeholder={lc_data.jst[35]}
                          onChange={e => this.handlePlaceholderChange(e.target.value, `${field.slug}-placeholder-min`)}
                          defaultValue={placeholders ? placeholders[field.slug] : ''}
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
                          placeholder={lc_data.jst[35]}
                          onChange={e => this.handlePlaceholderChange(e.target.value, `${field.slug}-placeholder-max`)}
                          defaultValue={placeholders ? placeholders[field.slug] : ''}
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
    const { labels, placeholders, keywords, minNumbers, maxNumbers, steps } = this.state;
    e.preventDefault();
    this.setState({ submitting: true });
    const data = new FormData(e.target);
    const headers = new Headers();
    let url = lc_data.search_builder_submit;
    headers.append('X-WP-Nonce', lc_data.nonce);
    data.append('type', 'home');
    map(labels, (field, name) => {
      data.append(`label[${name}]`, field);
    });
    map(placeholders, (field, name) => {
      data.append(`placeholder[${name}]`, field);
    });
    map(keywords, (field, name) => {
      data.append(`keywordOptions[${name}]`, field);
    });
    steps && map(steps, (field, name) => {
      data.append(`steps[${name}]`, field);
    });
    minNumbers && map(minNumbers, (field, name) => {
      data.append(`minNumbers[${name}]`, field);
    });
    maxNumbers && map(maxNumbers, (field, name) => {
      data.append(`maxNumbers[${name}]`, field);
    });
    fetch(url, {
      method: 'POST',
      credentials: 'same-origin',
      headers,
      body: data,
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
   * Renders the component.
   *
   * @return {Object}
   */
  render() {
    const { options } = this.props;
    const { chosen, labels, placeholder, fields } = this.state;

    return (
      <Fragment>
        {this.state.loading &&
        <div className="relative w-full bg:w-2/4 h-full">
          <LoaderSearchBuilder/>
        </div>
        }
        {!this.state.loading &&
        <form onSubmit={this.handleSubmit}>
          <div className="search-builder--page py-40 px-20 bg-grey-200 sm:p-40">
            <div>

              <div className="flex flex-col justify-between mb-20 sm:flex-row">
                <div>
                  <h2 className="mb-10 font-bold text-4xl">{lc_data.jst[5]}</h2>
                  <p
                    className="text-lg">{lc_data.jst[62]}</p>
                </div>

                <button
                  type="submit"
                  className="relative flex justify-center items-center my-20 sm:my-0 py-12 px-30 h-44 bg-blue-700 hover:bg-blue-800 rounded font-bold text-base text-white"
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

              <div className="field-groups flex mb-30 pb-30 border-b border-grey-300">
                <div className="field flex items-center mr-20">
                  <label htmlFor="sb-keyword" className="font-bold">
                    <input type="checkbox" id="sb-keyword" name="sb-keyword" onChange={this.handleChange}
                           className="mr-8"
                           checked={chosen.includes('sb-keyword')}/>
                    {lc_data.jst[63]}
                  </label>
                </div>
                <div className="field flex items-center mr-20">
                  <label htmlFor="sb-taxonomy" className="font-bold">
                    <input type="checkbox" id="sb-taxonomy" name="sb-taxonomy" onChange={this.handleChange}
                           className="mr-8"
                           checked={!isEmpty(fields.fields) && chosen.includes('sb-taxonomy')}/>
                    {lc_data.jst[54]}
                  </label>
                </div>
                <div className="field flex items-center">
                  <label htmlFor="sb-meta" className="font-bold">
                    <input type="checkbox" id="sb-meta" name="sb-meta" onChange={this.handleChange}
                           className="mr-8"
                           checked={!isEmpty(fields.fields) && chosen.includes('sb-meta')}/>
                    {lc_data.jst[55]}
                  </label>
                </div>
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
        </form>
        }
      </Fragment>
    );
  }
}

export default SearchHome;
