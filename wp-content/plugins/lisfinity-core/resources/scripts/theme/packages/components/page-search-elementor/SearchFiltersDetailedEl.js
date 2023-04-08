/* global lc_data, React */
/**
 * Dependencies.
 */
import { useEffect, useRef, useState } from '@wordpress/element';
import { map, isEmpty } from 'lodash';
import Select from 'react-select';
import Taxonomy from '../form-fields/taxonomy';
import cx from 'classnames';
import Meta from '../form-fields/meta';
import { updateSearchData } from '../../store/actions';
import { useDispatch, useSelector } from 'react-redux';
import { calculateFoundPosts } from '../page-search/store/actions';

const SearchFiltersDetailedEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const { searchData } = data;
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
  const [allOptions, setAllOptions] = useState({});

  const wrapper = useRef(null);

  useEffect(() => {
    setLoading(true);

    const el = wrapper.current && wrapper.current.closest('#page-search-detailed-elementor');
    const elOther = wrapper.current && wrapper.current.closest('#page-search-detailed');
    if (el) {
      const settingsData = JSON.parse(el.dataset.options);
      setSettings(settingsData);
    }
    if (elOther) {
      const settingsData = JSON.parse(elOther.dataset.options);
      setSettings(settingsData);
    }

    let fieldOptions = JSON.parse(localStorage.getItem('fieldOptions')) || false;
    if (!fieldOptions) {
      fieldOptions = data.fieldOptions;
    }

    const newFields = [];
    if (!isEmpty(fieldOptions.fields)) {
      map(fieldOptions.fields, (group, name) => {
        newFields[name] = group;
      });
    }

    setAllOptions(fieldOptions);
    setFieldGroups(fieldOptions?.fieldGroups?.detailed);
    setFieldsOptions(fieldOptions.fields);
    setFields(newFields);
    setTerms(fieldOptions.terms);
    setGroups(fieldOptions.groups);
    setLoading(false);

  }, [data]);

  const handleGroupChange = (value, name) => {
    let sData = {};

    if (null === value) {
      sData[name] = 'common';
    } else {
      sData[name] = value;
    }

    const newData = { ...sData };
    dispatch(updateSearchData(newData));
//    dispatch(updateSearchDataChosen(newData));
    dispatch(calculateFoundPosts(newData));
  };

  useEffect(() => {
    const values = { ...searchData };
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
    const dataClass = { ...values };
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

    setClasses({ selectClass, resetClass, customStyles });

    setFiltersHeight(height);

    const filtersOpen = data.showFilters;

    setFiltersOpen(filtersOpen);

    localStorage.setItem('storage', JSON.stringify(searchData));
  }, [data]);

  const dataClass = { ...searchData };
  if (dataClass['category-type'] === 'common') {
    delete dataClass['category-type'];
  }

  return (
    <div ref={wrapper}>
      {!loading &&
      <div key={1000} className="filters--main p-36">
        {!isEmpty(groups) && !data.options.type &&
        <div className="filters--group">
          <div className="field">
            <label htmlFor="categoryType" className="filters--label">{lc_data.jst[473]}</label>
            <Select
              name="category-type" id="categoryType"
              className={classes.selectClass}
              onChange={selectedOption => handleGroupChange(selectedOption && selectedOption.value, 'category-type')}
              placeholder={lc_data.jst[400]}
              options={options}
              isClearable
              styles={classes.customStyles}
              value={options[chosenIndex]}
              onFocus={() => setSelectActive(true)}
              onBlur={() => setSelectActive(false)}
            />
          </div>
        </div>
        }

        {fieldGroups && !isEmpty(fieldGroups.common) && map(fieldGroups.common, (group, groupSlug) => {
          return (
            ('any' === groupSlug && (!stored['category-type'] || 'common' === stored['category-type'])) &&
            <div key={groupSlug} className="filters--group">
              <h6 className="filters--group-title text-4xl">{group.name}</h6>
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
            ('any' !== groupSlug && (!stored['category-type'] || 'common' === stored['category-type'])) &&
            <div key={groupSlug} className="filters--group">
              <h6 className="filters--group-title text-4xl">{group.name}</h6>
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
            'any' === groupSlug &&
            <div key={groupSlug} className="filters--group">
              <h6 className="filters--group-title text-4xl">{group.name}</h6>
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
            'any' !== groupSlug &&
            <div key={groupSlug} className="filters--group">
              <h6 className="filters--group-title text-4xl">{group.name}</h6>
              {group.meta_fields && map(group.meta_fields, (meta, index) => {
                return <Meta key={index} meta={meta} searchPage={true} data={stored}
                             fieldOptions={allOptions} group={groupSlug}
                             category={stored['category-type']}
                             options={props.options}/>;
              })}
              {group.taxonomies &&
              <Taxonomy searchPage={true} data={stored} taxonomies={group.taxonomies}
                        fieldOptions={allOptions} terms={terms} options={props.options}/>
              }
            </div>
          );
        })}
      </div>
      }
    </div>
  );
};

export default SearchFiltersDetailedEl;
