/* global lc_data, React */
/**
 * Dependencies.
 */
import * as actions from '../../store/actions';
import { useDispatch, useSelector } from 'react-redux';
import { Fragment, useEffect, useRef, useState } from '@wordpress/element';
import { NavLink, BrowserRouter } from 'react-router-dom';
import { map, isEmpty, get } from 'lodash';
import Select from 'react-select';
import Taxonomy from '../form-fields/taxonomy';
import cx from 'classnames';
import { Scrollbars } from 'react-custom-scrollbars';
import cogIcon from '../../../../../images/icons/cog.svg';
import spinnerIcon from '../../../../../images/icons/spinner-arrow.svg';
import searchIcon from '../../../../../images/icons/search.svg';
import Meta from '../form-fields/meta';
import ReactSVG from 'react-svg';
import LoaderIcon from '../../../../../images/icons/spinner.svg';
import { updateSearchData, updateSearchDataChosen } from '../../store/actions';
import { calculateFoundPosts, fetchPosts } from './store/actions';
import ModalNew from '../modal/ModalNew';
import React from 'react';
import AlarmIcon from '../../../../../images/icons/alarm.svg';
import axios from 'axios';
import queryString from 'query-string';

const SearchFiltersEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const { searchData } = data;
  const stored = data.searchData;
  const { onChange } = props;
  const [loading, setLoading] = useState(false);
  const [detailed, setDetailed] = useState('detailed');
  const [fields, setFields] = useState({});
  const [fieldsOptions, setFieldsOptions] = useState({});
  const [groups, setGroups] = useState({});
  const [terms, setTerms] = useState({});
  const [fieldGroups, setFieldGroups] = useState(false);
  const [height, setHeight] = useState(600);
  const [filtersHeight, setFiltersHeight] = useState(600);
  const [classes, setClasses] = useState({});
  const [filtersOpen, setFiltersOpen] = useState(data.showFilters);
  const [chosenIndex, setChosenIndex] = useState(null);
  const [selectActive, setSelectActive] = useState(false);
  const [options, setOptions] = useState([]);
  const [settings, setSettings] = useState({});
  const [storage, setStorage] = useState({});
  const [allOptions, setAllOptions] = useState({});
  const [premiumModal, setPremiumModal] = useState(false);
  const [searchSaved, setSearchSaved] = useState(false);
  const filtersWrapper = useRef(null);

  useEffect(() => {
    setLoading(true);

    const wrapper = filtersWrapper.current && filtersWrapper.current.closest('.page-search-sidebar-filter');
    if (wrapper) {
      const settingsData = JSON.parse(wrapper.dataset.options);
      setSettings(settingsData);
    }

    let fieldOptions = data.fieldOptions;
    const newFields = [];
    if (!isEmpty(fieldOptions.fields)) {
      map(fieldOptions.fields, (group, name) => {
        newFields[name] = group;
      });
    }

    setAllOptions(fieldOptions);
    setFieldGroups(fieldOptions?.fieldGroups?.sidebar);
    setFieldsOptions(fieldOptions.fields);
    setFields(newFields);
    setTerms(fieldOptions.terms);
    setGroups(fieldOptions.groups);
    setLoading(false);

    setSearchSaved(data?.postsByUrl?.RECEIVE_POSTS?.items?.saved || false);

    contentHeight();

    setStorage(data.searchData);

    window.addEventListener('resize', contentHeight);

    return () => window.removeEventListener('resize', contentHeight);
  }, [data]);

  const contentHeight = e => {
    const windowHeight = window.innerHeight;
    const headerHeight = document.querySelector('header').offsetHeight || 0;
    let loggedHeight = lc_data.logged_in ? 70 : 38;
    const height = windowHeight - headerHeight - loggedHeight;
    setHeight(height);

    let filtersHeight = 600;
    if (props.page !== 'business') {
      filtersHeight = height;
    }
    if (props.page === 'business' && fieldGroups && fieldGroups[data['category-type']] && fieldGroups[data['category-type']].any.taxonomies.length <= 8) {
      filtersHeight = fieldGroups[data['category-type']].any.taxonomies.length * 70 + 280;
    }
    setFiltersHeight(filtersHeight);
  };

  const handleGroupChange = (value, name) => {
    let sData = {};

    // check if premium.
    let premium = false;
    if (lc_data.check_for_premium) {
      map(groups, group => {
        if (group.slug === value && 'yes' === group.premium) {
          setPremiumModal(true);
          premium = true;
        }
      });
      if (premium) {
        return false;
      }
    }

    if (null === value) {
      sData[name] = 'common';
    } else {
      sData[name] = value;
    }
    const newData = { ...sData };
    data.searchData = sData;
    dispatch(updateSearchData(newData));
    //dispatch(updateSearchDataChosen(newData));
    dispatch(calculateFoundPosts(newData));
  };

  const handleReset = (e) => {
    const hasCommon = get(data.searchData, 'category-type');
    let sData = {};
    if (hasCommon) {
      sData['category-type'] = 'common';
    } else {
      sData = {};
    }
    data.searchData = sData;
    dispatch(updateSearchData(sData));
    dispatch(updateSearchDataChosen(sData));
    dispatch(fetchPosts(sData, 'calculate_posts'));
  };

  useEffect(() => {
    let values = { ...data.searchData };
    const selectOptions = [];
    {
      selectOptions.push({ value: 'common', label: lc_data.jst[400] });
      !isEmpty(groups) && map(groups, (group, index) => {
        selectOptions.push({ value: group.slug, label: group.plural_name });
      });
    }
    setOptions(selectOptions);
    const customStyles = {
      control: () => ({}),
    };

    let dataClass = { ...values };
    if (values['category-type'] === 'common') {
      delete dataClass['category-type'];
    }

    const resetClass = cx({
      'text-grey-300 action--reset__disabled cursor-default': isEmpty(dataClass),
      'text-green-800 action--reset__active': !isEmpty(dataClass),
    });

    map(selectOptions, (option, index) => {
      if (option.value === values['category-type']) {
        setChosenIndex(index);
      }
    });

    const selectClass = cx({
      'select-active': !isEmpty(values['category-type']) && 'common' !== values['category-type'],
      'focused': selectActive,
    });

    setClasses({ selectClass, resetClass, customStyles });

    const filtersOpen = data.showFilters;

    setFiltersOpen(filtersOpen);
  }, [data]);

  useEffect(() => {
    const selectOptions = [];
    {
      selectOptions.push({ value: 'common', label: lc_data.jst[400] });
      !isEmpty(groups) && map(groups, (group, index) => {
        selectOptions.push({ value: group.slug, label: group.plural_name });
      });
    }
    setOptions(selectOptions);
  }, [groups]);

  const saveSearch = () => {
    setSearchSaved(true);
    const headers = { 'X-WP-Nonce': lc_data.nonce };
    const { searchDataChosen } = data;
    const formData = {
      data: `${queryString.stringify(searchDataChosen)}`,
    };
    axios({
      url: `${lc_data.user_action}/save_search`,
      method: 'POST',
      credentials: 'same-origin',
      headers,
      data: formData,
    }).then(response => {
    });
  };

  const dataClass = { ...data.searchData };
  if (dataClass['category-type'] === 'common') {
    delete dataClass['category-type'];
  }

  const btnText = settings?.button_text ? settings.button_text : lc_data.jst[457];
  const detailedBtnText = settings?.detailed_button_text ? settings.detailed_button_text : lc_data.jst[9];
  const filterText = settings?.filter_text ? settings.filter_text : lc_data.jst[471];

  return [
    <Fragment key={0}>
      <BrowserRouter>
        <Scrollbars style={{ zIndex: 20 }} autoHide={false} autoHeight
                    autoHeightMin={filtersHeight}
                    renderTrackHorizontal={props => <div {...props} className="hidden"/>}
                    renderThumbHorizontal={props => <div {...props} className="hidden"/>}
                    renderTrackVertical={props => <div {...props}
                                                       className="track--vertical top-86 right-0 bottom-0 w-2"/>}
                    renderThumbVertical={props => <div {...props}
                                                       className="thumb--vertical bg-grey-600 rounded opacity-25"/>}>
          <div className="filters filters--wrapper px-14" ref={filtersWrapper}>
            <div
              className={`filters--header flex items-center justify-between mt-20`}>
              {window.innerWidth > 1030 &&
              <div className="filters--title flex-center">
                <div className="flex-center text-xl font-bold">
                  {!settings?.custom_icon?.value &&
                  <ReactSVG
                    src={`${lc_data.dir}dist/${cogIcon}`}
                    className={`mr-8 min-w-16 min-h-16 fill-filter-icon`}
                  />
                  }
                  {settings?.custom_icon?.value && settings?.custom_icon?.library !== 'svg' &&
                  <i className={`mr-8 ${settings.custom_icon.value} text-filter-icon`}></i>
                  }
                  {settings?.custom_icon?.value?.url && settings?.custom_icon?.library === 'svg' &&
                  <ReactSVG
                    src={`${settings.custom_icon.value.url}`}
                    className={`mr-8 w-16 h-16 fill-filter-icon`}
                  />
                  }
                  <span className="text-3xl">{filterText}</span>
                </div>
              </div>
              }
              <button type="button" className={`action--reset flex-center ${classes.resetClass}`}
                      onClick={e => {
                        if (!isEmpty(dataClass)) {
                          handleReset(e);
                        }
                      }}>
                {!settings?.custom_reset_icon?.value &&
                <ReactSVG
                  src={`${lc_data.dir}dist/${spinnerIcon}`}
                  className={`mr-8 w-14 h-14 ${isEmpty(dataClass) ? 'fill-grey-300' : 'fill-green-800'}`}
                />
                }
                {settings?.custom_reset_icon?.value && settings?.custom_reset_icon?.library !== 'svg' &&
                <i className={`mr-8 ${settings.custom_reset_icon.value} text-filter-icon`}></i>
                }
                {settings?.custom_reset_icon?.value?.url && settings?.custom_reset_icon?.library === 'svg' &&
                <ReactSVG
                  src={`${settings.custom_reset_icon.value.url}`}
                  className={`mr-8 w-14 h-14 fill-filter-icon`}
                />
                }
                <span
                  className="text-base font-light">{settings?.reset_text ? settings.reset_text : lc_data.jst[472]}</span>
              </button>
            </div>
            <Fragment>
              {!isEmpty(groups) && !data.options.type &&
              <div className="form-field mt-16 field--category-type">
                <label htmlFor="categoryType" className="filters--label">{lc_data.jst[473]}</label>
                <Select
                  name="category-type" id="categoryType"
                  className={classes.selectClass}
                  onChange={selectedOption => handleGroupChange(selectedOption && selectedOption.value, 'category-type')}
                  placeholder={lc_data.jst[400]}
                  options={options}
                  value={options[chosenIndex]}
                  styles={{ control: () => ({}) }}
                  onFocus={() => setSelectActive(true)}
                  onBlur={() => setSelectActive(false)}
                />
              </div>
              }
              {fieldGroups && !isEmpty(fieldGroups.common) && map(fieldGroups.common, (group, groupSlug) => {
                return (
                  'any' === groupSlug && (!stored['category-type'] || 'common' === stored['category-type']) &&
                  <div key={name} className="group">
                    {group.meta_fields && map(group.meta_fields, (meta, index) => {
                      return <Meta key={index} meta={meta} searchPage={true} data={stored}
                                   fieldOptions={allOptions} group={groupSlug}
                                   category={stored['category-type'] ?? 'common'}
                                   options={props.options}/>;
                    })}
                    {group.taxonomies &&
                    <Taxonomy searchPage={true} data={stored} taxonomies={group.taxonomies}
                              fieldOptions={allOptions} terms={terms} options={props.options} group={group}
                              settings={settings}/>
                    }
                  </div>
                );
              })}

              {fieldGroups && !isEmpty(fieldGroups.common) && map(fieldGroups.common, (group, groupSlug) => {
                return (
                  'any' !== groupSlug && (!stored['category-type'] || 'common' === stored['category-type']) &&
                  <div key={name} className="group">
                    {group.meta_fields && map(group.meta_fields, (meta, index) => {
                      return <Meta key={index} meta={meta} searchPage={true} data={stored}
                                   fieldOptions={allOptions} group={groupSlug}
                                   category={stored['category-type'] ?? 'common'}
                                   options={props.options}
                                   settings={settings}
                      />;
                    })}
                    {group.taxonomies &&
                    <Taxonomy searchPage={true} data={stored} taxonomies={group.taxonomies}
                              fieldOptions={allOptions} terms={terms} options={props.options} group={group}/>
                    }
                  </div>
                );
              })}

              {fieldGroups && !isEmpty(fieldGroups[stored['category-type']]) && 'common' !== stored['category-type'] && map(fieldGroups[stored['category-type']], (group, groupSlug) => {
                return (
                  'any' === groupSlug && <div key={groupSlug} className="group">
                    {group.meta_fields && map(group.meta_fields, (meta, index) => {
                      return <Meta key={index} meta={meta} searchPage={true} data={stored}
                                   fieldOptions={allOptions} group={groupSlug}
                                   category={stored['category-type'] ?? 'common'}
                                   options={props.options}/>;
                    })}
                    {group.taxonomies &&
                    <Taxonomy searchPage={true} data={stored} taxonomies={group.taxonomies}
                              fieldOptions={allOptions} terms={terms} options={props.options} group={group}
                              settings={settings}/>
                    }
                  </div>
                );
              })}

              {fieldGroups && !isEmpty(fieldGroups[stored['category-type']]) && 'common' !== stored['category-type'] && map(fieldGroups[stored['category-type']], (group, groupSlug) => {
                return (
                  'any' !== groupSlug && <div key={groupSlug} className="group">
                    {group.meta_fields && map(group.meta_fields, (meta, index) => {
                      return <Meta key={index} meta={meta} searchPage={true} data={stored}
                                   fieldOptions={allOptions} group={groupSlug}
                                   category={stored['category-type'] ?? 'common'}
                                   options={props.options}/>;
                    })}
                    {group.taxonomies &&
                    <Taxonomy searchPage={true} data={stored} taxonomies={group.taxonomies}
                              fieldOptions={allOptions} terms={terms} options={props.options} group={group}
                              settings={settings}/>
                    }
                  </div>
                );
              })}
            </Fragment>
          </div>
          <div className="relative my-30 sm:my-0 px-14">
            {lc_data.logged_in && props.options?.save_searches &&
            <div className="flex items-center mt-20 mb-6">
              <button type="button"
                      className={`flex items-center ml-auto py-2 px-10 ${searchSaved ? 'bg-grey-100 text-grey-300 cursor-default' : 'text-green-700'} hover:bg-grey-100 rounded`}
                      onClick={() => saveSearch()}
                      disabled={searchSaved || false}
              >
                <ReactSVG
                  src={`${lc_data.dir}dist/${AlarmIcon}`}
                  className={`mr-2 ${searchSaved ? 'fill-grey-300' : 'fill-icon-reset'} pointer-events-none`}
                  id="notification--icon"
                  style={{
                    height: 14,
                    width: 14,
                  }}
                />
                <span>{lc_data.jst[769]}</span>
              </button>
            </div>
            }
            <button
              type="button"
              className={`relative flex-center ${(!lc_data.logged_in || !props.options?.save_searches) ? 'mt-30' : ''} py-10 px-32 w-full bg-blue-700 border border-blue-700 rounded font-bold text-lg text-white hover:bg-blue-900 hover:border-blue-900`}
              onClick={() => {
                dispatch(fetchPosts(data.searchData, false, true));
                dispatch(actions.updateSearchDataChosen(data.searchData));
                if (window.innerWidth < 770) {
                  dispatch(actions.updateShowFilters(false));
                }
              }}
            >
              {!data.calculating &&
              <div className="flex-center relative pr-16">
                <ReactSVG src={`${lc_data.dir}dist/${searchIcon}`} className="mr-10 -ml-10 w-18 h-18 fill-white"/>
                {props.options.display_ads_count && lc_data.jst[457]}
                {props.options.display_ads_count &&
                <span className="absolute" style={{ left: 'calc(100% - 10px)' }}>{(data.foundPosts || 0)}</span>}
                {!props.options.display_ads_count && lc_data.jst[457]}
              </div>
              }
              {data.calculating && !props.options.display_ads_count &&
              <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`} className="relative w-18 h-18 fill-white"
                        style={{
                          top: -40,
                          zoom: .2,
                          height: 120,
                          left: -80,
                        }}
              />
              }
              {data.calculating && props.options.display_ads_count &&
              <div className="flex-center relative pr-16">
                <ReactSVG src={`${lc_data.dir}dist/${searchIcon}`} className="mr-10 -ml-10 w-18 h-18 fill-white"/>
                {props.options.display_ads_count && lc_data.jst[457]}
                <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`} className="absolute w-18 h-18 fill-white"
                          style={{
                            top: -13,
                            zoom: .15,
                            left: 'calc(100% - 40px)',
                          }}
                />
              </div>
              }
            </button>

            {props.page === 'business' && props.options.detailed_search !== '0' &&
            <NavLink
              to={`?p=${detailed}&category-type=${data.searchData['category-type'] || 'common'}`}
              onClick={onChange}
              className="btn btn--light flex-center mt-16 py-10 px-16 font-normal"
            >
              {lc_data.jst[9]}
            </NavLink>
            }
            {props.page !== 'business' && props.options.detailed_search !== '0' &&
            <NavLink
              exact
              to={props.options.type ? `${lc_data.site_url}${lc_data.slug_category}/${props.options.type}/?p=detailed` : `${lc_data.site_url}${lc_data.page_search_endpoint}/?p=${detailed}&category-type=${data.searchData['category-type'] || 'common'}`}
              onClick={onChange}
              className="btn btn--light flex-center mt-16 py-10 px-16 font-normal"
            >
              {lc_data.jst[9]}
            </NavLink>
            }
          </div>

        </Scrollbars>
      </BrowserRouter>
      <ModalNew
        open={premiumModal}
        closeModal={() => setPremiumModal(true)}
        title={lc_data.jst[props.options?.restricted_modal_type === 'premium' ? 757 : 758]}
      >
        {props.options?.restricted_modal_image &&
        <div className="image">
          <img src={props.options.restricted_modal_image.url} alt="premium-modal-image"/>
        </div>
        }
        <h5
          className="font-bold text-grey-900">{lc_data.jst[props.options?.restricted_modal_type === 'premium' ? 759 : 760]}</h5>
        <div className="mt-10 text-grey-600"
             dangerouslySetInnerHTML={{ __html: lc_data.jst[props.options?.restricted_modal_type === 'premium' ? 761 : 762] }}/>
        <div className="inline-block mt-20">
          {lc_data.logged_in &&
          <a href={`${lc_data.page_account}premium-profile`}
             className="relative flex justify-center items-center py-12 px-24 h-44 bg-blue-700 hover:bg-blue-800 rounded font-bold text-base text-white">{lc_data.jst[props.options?.restricted_modal_type === 'premium' ? 306 : 763]}</a>
          }
          {!lc_data.logged_in &&
          <a href={lc_data.page_register}
             className="relative flex justify-center items-center py-12 px-24 h-44 bg-blue-700 hover:bg-blue-800 rounded font-bold text-base text-white">{lc_data.jst[props.options?.restricted_modal_type === 'premium' ? 306 : 763]}</a>
          }
        </div>
      </ModalNew>
    </Fragment>,
  ];
};

export default SearchFiltersEl;
