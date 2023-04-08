/* global lc_data, React */
/**
 * Dependencies.
 */
import * as actions from '../../store/actions';
import {useDispatch, useSelector} from 'react-redux';
import {Fragment, useEffect, useRef, useState} from '@wordpress/element';
import {BrowserRouter} from 'react-router-dom';
import {map, isEmpty, get} from 'lodash';
import Select from 'react-select';
import Taxonomy from '../form-fields/taxonomy';
import cx from 'classnames';
import {Scrollbars} from 'react-custom-scrollbars';
import cogIcon from '../../../../../images/icons/cog.svg';
import spinnerIcon from '../../../../../images/icons/spinner-arrow.svg';
import searchIcon from '../../../../../images/icons/search.svg';
import Meta from '../form-fields/meta';
import ReactSVG from 'react-svg';
import LoaderIcon from '../../../../../images/icons/loader-rings-white.svg';
import {setIsDetailed, updateSearchData, updateSearchDataChosen} from '../../store/actions';
import {calculateFoundPosts, fetchPosts} from '../page-search/store/actions';

const SearchFiltersEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  let {searchData} = data;
  const stored = searchData;
  const [loading, setLoading] = useState(false);
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
  const filtersWrapper = useRef(null);

  useEffect(() => {
    setLoading(true);

    const wrapper = filtersWrapper.current && filtersWrapper.current.closest('.page-search-sidebar-filter');
    if (wrapper) {
      const settingsData = JSON.parse(wrapper.dataset.options);
      setSettings(settingsData);
    }

    let fieldOptions = JSON.parse(localStorage.getItem('fieldOptions')) || false;
    if (!fieldOptions) {
      fieldOptions = data.fieldOptions;
    }

    const newFields = [];
    if (!isEmpty(data?.fieldOptions?.fields)) {
      map(data.fieldOptions.fields, (group, name) => {
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

    contentHeight();

    localStorage.setItem('storage', JSON.stringify(searchData));
    setStorage(searchData);

    window.addEventListener('resize', contentHeight);

    return () => window.removeEventListener('resize', contentHeight);
  }, [data]);

  const contentHeight = e => {
    const windowHeight = window.innerHeight;
    const headerHeight = document.querySelector('header').offsetHeight || 0;
    let loggedHeight = lc_data.logged_in ? 70 : 38;
    const height = windowHeight - headerHeight - loggedHeight;
    setHeight(height);
  };

  const handleGroupChange = (value, name) => {
    let sData = {};

    if (null === value) {
      sData[name] = 'common';
    } else {
      sData[name] = value;
    }
    const newData = {...sData};
    searchData = sData;
    dispatch(updateSearchData(newData));
    //dispatch(updateSearchDataChosen(newData));
    dispatch(calculateFoundPosts(newData));
  };

  const handleReset = (e) => {
    const hasCommon = get(searchData, 'category-type');
    let sData = {};
    if (hasCommon) {
      sData['category-type'] = 'common';
    } else {
      sData = {};
    }
    searchData = sData;
    dispatch(updateSearchData(sData));
    dispatch(updateSearchDataChosen(sData));
    dispatch(fetchPosts(sData, true));
  };

  useEffect(() => {
    const values = {...searchData};
    const selectOptions = [];
    {
      selectOptions.push({value: 'common', label: lc_data.jst[400]});
      !isEmpty(groups) && map(groups, (group, index) => {
        selectOptions.push({value: group.slug, label: group.plural_name});
      });
    }
    setOptions(selectOptions);
    const customStyles = {
      control: () => ({}),
    };
    const dataClass = {...values};
    if (values['category-type'] === 'common') {
      delete dataClass['category-type'];
    }
    const resetClass = cx({
      'text-grey-300 action--reset__disabled': isEmpty(dataClass),
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

    setClasses({selectClass, resetClass, customStyles});

    setFiltersHeight(height);

    const filtersOpen = data.showFilters;

    setFiltersOpen(filtersOpen);
  }, [data]);

  const dataClass = {...searchData};
  if (dataClass['category-type'] === 'common') {
    delete dataClass['category-type'];
  }


  const btnText = settings?.button_text ? settings.button_text : lc_data.jst[457];
  const detailedBtnText = settings?.detailed_button_text ? settings.detailed_button_text : lc_data.jst[9];
  const filterText = settings?.filter_text ? settings.filter_text : lc_data.jst[471];

  return [
    <Fragment key={0}>
      {filtersOpen &&
      <BrowserRouter>
        <div className="filters filters--wrapper" ref={filtersWrapper}>
          <div
            className={`filters--header flex items-center justify-between`}>
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
                className={`mr-8 w-14 h-14 text-filter-icon ${isEmpty(dataClass) ? 'fill-grey-300' : 'fill-green-800'}`}
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
            {!isEmpty(groups) && !data?.options?.type &&
            <div className="form-field mt-16 field--category-type">
              <label htmlFor="categoryType" className="filters--label">{lc_data.jst[473]}</label>
              <Select
                name="category-type" id="categoryType"
                className={classes.selectClass}
                onChange={selectedOption => handleGroupChange(selectedOption && selectedOption.value, 'category-type')}
                placeholder={lc_data.jst[400]}
                options={options}
                value={options[chosenIndex]}
                styles={{control: () => ({})}}
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
                                 fieldOptions={allOptions} group={groupSlug} category={stored['category-type']}
                                 options={data.options}/>;
                  })}
                  {group.taxonomies &&
                  <Taxonomy searchPage={true} data={stored} taxonomies={group.taxonomies}
                            fieldOptions={allOptions} terms={terms} options={data.options}/>
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
                                 fieldOptions={allOptions} group={groupSlug} category={stored['category-type']}
                                 options={data.options}/>;
                  })}
                  {group.taxonomies &&
                  <Taxonomy searchPage={true} data={stored} taxonomies={group.taxonomies}
                            fieldOptions={allOptions} terms={terms} options={data.options}/>
                  }
                </div>
              );
            })}

            {fieldGroups && !isEmpty(fieldGroups[stored['category-type']]) && 'common' !== stored['category-type'] && map(fieldGroups[stored['category-type']], (group, groupSlug) => {
              return (
                'any' === groupSlug && <div key={groupSlug} className="group">
                  {group.meta_fields && map(group.meta_fields, (meta, index) => {
                    return <Meta key={index} meta={meta} searchPage={true} data={stored}
                                 fieldOptions={allOptions} group={groupSlug} category={stored['category-type']}
                                 options={data.options}/>;
                  })}
                  {group.taxonomies &&
                  <Taxonomy searchPage={true} data={stored} taxonomies={group.taxonomies}
                            fieldOptions={allOptions} terms={terms} options={data.options}/>
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
                                 category={stored['category-type']}
                                 options={data.options}/>;
                  })}
                  {group.taxonomies &&
                  <Taxonomy searchPage={true} data={stored} taxonomies={group.taxonomies}
                            fieldOptions={allOptions} terms={terms} options={data.options}/>
                  }
                </div>
              );
            })}
          </Fragment>
        </div>

        <div className="my-30 sm:my-0">
          <button
            type="button"
            className="btn--search flex-center mt-20 py-10 px-32 w-full bg-blue-700 border border-blue-700 rounded font-bold text-lg text-white hover:bg-blue-900 hover:border-blue-900"
            onClick={() => {
              dispatch(fetchPosts(searchData, false, true));
              dispatch(actions.updateSearchDataChosen(searchData));
              if (window.innerWidth < 770) {
                dispatch(actions.updateShowFilters(false));
              }
            }}
          >
            {!data.calculating &&
            <div className="flex-center relative pr-16">
              {!settings?.custom_button_icon?.value &&
              <ReactSVG src={`${lc_data.dir}dist/${searchIcon}`} className="mr-10 -ml-10 w-18 h-18 fill-white"/>
              }
              {settings?.custom_button_icon?.value && settings?.custom_button_icon?.library !== 'svg' &&
              <i className={`mr-8 ${settings.custom_button_icon.value} text-filter-icon`}></i>
              }
              {settings?.custom_button_icon?.value?.url && settings?.custom_button_icon?.library === 'svg' &&
              <ReactSVG
                src={`${settings.custom_button_icon.value.url}`}
                className={`mr-8 w-18 h-18 fill-filter-icon`}
              />
              }
              {data?.options?.display_ads_count && btnText}
              {data?.options?.display_ads_count &&
              <span className="absolute"
                    style={{left: 'calc(100% - 10px)'}}>{(data.foundPosts || 0)}</span>}
              {!data?.options?.display_ads_count && btnText}
            </div>
            }
            {data?.calculating && !data?.options?.display_ads_count &&
            <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`} className="relative w-18 h-18 fill-white"
                      style={{
                        top: '-8px',
                        zoom: .8,
                        height: 30,
                      }}
            />
            }
            {data?.calculating && data?.options?.display_ads_count &&
            <div className="flex-center relative pr-16">
              {!settings?.custom_button_icon?.value &&
              <ReactSVG src={`${lc_data.dir}dist/${searchIcon}`} className="mr-10 -ml-10 w-18 h-18 fill-white"/>
              }
              {settings?.custom_button_icon?.value && settings?.custom_button_icon?.library !== 'svg' &&
              <i className={`mr-8 ${settings.custom_button_icon.value} text-filter-icon`}></i>
              }
              {settings?.custom_button_icon?.value?.url && settings?.custom_button_icon?.library === 'svg' &&
              <ReactSVG
                src={`${settings.custom_button_icon.value.url}`}
                className={`mr-8 w-18 h-18 fill-filter-icon`}
              />
              }
              {data.options.display_ads_count && btnText}
              <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`} className="absolute w-18 h-18 fill-white"
                        style={{
                          top: -1,
                          zoom: .6,
                          height: 30,
                          left: 'calc(100% - 26px)',
                          width: 16,
                        }}
              />
            </div>
            }
          </button>

          {data?.options?.detailed_search !== '0' &&
          <a
            href={data?.options?.type ? `${lc_data.site_url}${lc_data.slug_category}/${data.options.type}/?p=detailed` : `${lc_data.page_search_detailed}`}
            onClick={() => dispatch(setIsDetailed(true))}
            className="btn btn--light flex-center mt-16 py-10 px-16 font-normal"
          >
            {detailedBtnText}
          </a>
          }
        </div>

      </BrowserRouter>
      }
    </Fragment>,
  ];
};

export default SearchFiltersEl;
