/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import { Fragment, useState, useEffect } from '@wordpress/element';
import { map, isEmpty, get, filter } from 'lodash';
import cx from 'classnames';
import queryString from 'query-string';
import Select, { components } from 'react-select';
import Checkbox from './Checkbox';
import { updateSearchData, updateSearchDataChosen } from '../../store/actions';
import he from 'he';
import { calculateFoundPosts } from '../page-search/store/actions';
import ModalNew from '../modal/ModalNew';
import React from 'react';

const Taxonomy = (props) => {
  const data = useSelector(state => state);
  const { searchData } = data;
  const { fieldOptions } = props;
  const dispatch = useDispatch();
  const [classes, setClasses] = useState({});
  const [loading, setLoading] = useState(false);
  const [chosen, setChosen] = useState({});
  const [selectActive, setSelectActive] = useState(false);
  const [selectInputActive, setSelectInputActive] = useState(false);
  const [selectInputActive2, setSelectInputActive2] = useState(false);
  const [group, setGroup] = useState(searchData['category-type'] || 'common');
  const [premiumModal, setPremiumModal] = useState(props?.options?.listings_for_premium_only || false);

  useEffect(() => {
    setGroup(searchData['category-type'] || 'common');
  }, [searchData]);

  const getChild = (taxonomies, taxonomy) => {
    const childs = [];
    taxonomies && map(taxonomies, tax => tax.parent === taxonomy && !childs.includes(tax.slug) && childs.push(tax.slug));

    return childs;
  };

  const getChilds = (taxonomies, taxonomy, childs) => {
    childs = childs || [];
    const child = getChild(taxonomies, taxonomy);
    if (!isEmpty(child)) {
      map(child, c => {
        childs.push(c);
        getChilds(taxonomies, c, childs);
      });
    }

    return childs;
  };

  const handleChange = (selectedOption, taxonomy, name, taxonomyOption) => {
    const selected = selectedOption ? selectedOption.value : false;
    const values = { ...chosen };
    let sData = searchData;
    const taxonomies = fieldOptions?.taxonomies[group];

    if ('yes' === taxonomyOption?.premium && lc_data.check_for_premium) {
      setPremiumModal(true);
      return false;
    }

    const premiumOption = fieldOptions?.terms?.[taxonomyOption?.slug]?.[selected]?.meta?.premium;
    if (lc_data.check_for_premium && ('yes' === premiumOption || 'yes' === premiumOption?.[0])) {
      setPremiumModal(true);
      return false;
    }

    if (!selected) {
      delete values[taxonomy];
      delete sData[name];
      const childs = getChilds(taxonomies, taxonomy);
      if (childs.length > 0) {
        map(childs, child => {
          delete values[child];
          delete sData[`tax[${child}]`];
        });
      }
    } else {
      values[taxonomy] = selected;
      sData[name] = selected;

    }
    setChosen(values);

    if (props?.options?.display_ads_count) {
      dispatch(calculateFoundPosts(sData));
    }
    if (props.detailed) {
      dispatch(updateSearchDataChosen(sData));
    }
  };

  const handleCheckboxChange = (value, taxonomy, name) => {
    const values = { ...chosen };
    let sData = searchData;
    if (!sData[name]) {
      sData[name] = [];
    }
    if (sData[name].includes(value)) {
      const dataIndex = sData[name].indexOf(value);
      sData[name].splice(dataIndex, 1);
    } else {
      sData[name].push(value);
    }
    values[name] = sData[name];

    setChosen(values);
    if (props.options.display_ads_count) {
      dispatch(updateSearchData(sData));
      dispatch(calculateFoundPosts(sData));
    }
  };

  useEffect(() => {
    const customStyles = {
      control: () => ({}),
    };
    const labelClass = cx({
      'mr-10 font-semibold whitespace-no-wrap': lc_data.is_front,
      'filters--label': !lc_data.is_front,
    });
    const wrapperClass = cx({
      'flex flex-wrap justify-center items-center w-full': lc_data.is_front,
      'flex flex-col flex-wrap': !lc_data.is_front,
    });
    const selectFieldClass = cx({
      'relative select-banner flex items-center pl-24 h-44 w-full bg-white rounded mt-10': lc_data.is_front,
      'mt-16': !lc_data.is_front,
    });

    const data = {
      customStyles, labelClass, wrapperClass, selectFieldClass,
    };
    setClasses(data);

  }, [chosen]);

  const displayFields = (taxonomy, terms) => {
    const sData = { ...searchData };
    const params = queryString.parse(location.search);
    const isDetailed = params && params.p;

    const options = [];
    const parentValue = sData[`tax[${taxonomy.parent}]`];
    let termParent = false;

    fieldOptions?.terms[taxonomy.slug] && map(fieldOptions.terms[taxonomy.slug], term => {
      termParent = term?.meta?.parent_slug || false;
      if (term?.taxonomy === taxonomy.slug && term?.slug !== 'custom' && (isEmpty(taxonomy.parent) || (termParent && parentValue === termParent[0]))) {
        options.push({
          value: term.slug,
          label: he.decode(term.name)
        });
      }
    });
    switch (taxonomy.type) {

      case 'select':
      case 'location':

        let termParentOptions = false;
        if (parentValue) {
          termParentOptions = get(fieldOptions.terms[taxonomy.parent], parentValue);
        }
        let chosenIndex = false;
        map(options, (option, index) => {
          if (option.value === sData[`tax[${taxonomy.slug}]`]) {
            chosenIndex = index;
          }
        });
        const customStyles = {
          control: () => ({}),
        };
        const selectClass = cx({
          'select-active': get(sData, `tax[${taxonomy.slug}]`) && 'select-active',
          'focused': selectActive,
          'opacity-50': isEmpty(options),
        });
        return (!props?.options?.hide_child_categories || (props?.options?.hide_child_categories && !isEmpty(options))) &&
          <div key={group} style={{
            width: `${props.columns === 100 ? props.width / props.options.columns : 100}%`,
            paddingLeft: props?.options?.padding,
            paddingRight: props?.options?.padding,
          }}>
            <div
              className={`${classes?.selectFieldClass}`}>
              <label htmlFor={group}
                     className={classes?.labelClass}>{taxonomy?.label || taxonomy?.single_name}</label>
              <Select
                name={`tax[${taxonomy.slug}]`} id={taxonomy.slug}
                onChange={selectedOption => handleChange(selectedOption, taxonomy.slug, `tax[${taxonomy.slug}]`, taxonomy)}
                value={options[chosenIndex] || ''}
                options={options}
                isClearable
                styles={customStyles}
                className={selectClass}
                placeholder={sData[`tax[${taxonomy.parent}]`] && options && termParentOptions ? `${options.length} ${termParentOptions.name} ${taxonomy.plural_name}` : taxonomy.placeholder ? taxonomy.placeholder : taxonomy.single_name}
                isRtl={lc_data.rtl}
                isDisabled={isEmpty(options)}
              />
            </div>
          </div>;
      case 'checkbox':
        const chTerms = fieldOptions?.terms[taxonomy.slug];
        return (
          <div key={taxonomy.slug}
               className={`flex flex-wrap ${isDetailed ? 'detailed--checkbox -mx-col' : 'mt-20'}`}>
            {!isDetailed && <label htmlFor={taxonomy.slug}
                                   className={`w-full ${classes?.labelClass} ${!isDetailed ? '-mb-10' : ''}`}>{taxonomy?.label || taxonomy?.single_name}</label>}
            {map(chTerms, (option, index) => {
              const checkboxClass = cx({
                'w-full xs:w-1/2 sm:w-1/3 bg:w-1/3 px-col': isDetailed,
                'w-full xs:w-1/2 mt-16': !isDetailed,
              });
              return (
                option && option.name &&
                <div key={index}
                     className={`field--checkbox ${checkboxClass}`}>
                  <Checkbox name={`tax[${taxonomy.slug}][]`} id={`${taxonomy.slug}[${option.slug}]`}
                            handleChange={e => handleCheckboxChange(option.slug, taxonomy.slug, `tax[${taxonomy.slug}]`)}
                            value={option.slug}
                            checked={get(searchData, `tax[${taxonomy.slug}]`) && searchData[`tax[${taxonomy.slug}]`].includes(option.slug)}
                  />
                  <label htmlFor={`${taxonomy.slug}[${option.slug}]`} className="w-full">
                    {option.name}
                  </label>
                </div>
              );
            })
            }
          </div>
        );
      case 'input':
        let taxonomyOptions = fieldOptions[taxonomy.field_group] && get(fieldOptions[taxonomy.field_group].options, taxonomy.slug);
        taxonomyOptions = get(fieldOptions?.fields[taxonomy.field_group].options, taxonomy.slug);

        let prefix = '';
        let suffix = '';
        if (get(taxonomyOptions, 'prefix')) {
          prefix = taxonomyOptions.prefix;
        }
        if (get(taxonomyOptions, 'suffix')) {
          suffix = taxonomyOptions.suffix;
        }
        let stepOptions = [];
        let max = 9;
        if (!isEmpty(options)) {
          let setMax = options.slice(-1);
          max = Math.ceil(parseFloat(setMax[0].label));
        }
        let fieldStep = 1;
        if (get(taxonomyOptions, 'step')) {
          fieldStep = parseInt(taxonomyOptions.step, 10);
        }

        // set minimum step depending on maximum value.
        // // todo find a way to exponentially rise step values.
        // if (max < 100) {
        //   max = Math.ceil(max);
        // } else {
        //   max = Math.ceil(max / 10) * 10;
        // }
        // if (max > 100 && fieldStep < 10) {
        //   fieldStep = 10;
        // }
        // if (max > 1000 && fieldStep < 50) {
        //   fieldStep = 50;
        // }
        // if (max > 10000 && fieldStep < 100) {
        //   fieldStep = 100;
        // }
        // if (max > 100000 && fieldStep < 1000) {
        //   fieldStep = 10000;
        // }
        // if (max > 1000000 && fieldStep < 10000) {
        //   fieldStep = 10000;
        // }

        for (let i = 0; i <= max; i += fieldStep) {
          if (!isNaN(i)) {
            stepOptions.push({ value: i, label: `${prefix}${i}${suffix}` });
          }
        }
        if (isEmpty(stepOptions)) {
          return [];
        }
        if (stepOptions[stepOptions.length - 1].value !== max) {
          stepOptions.push({ value: max, label: `${prefix}${max}${suffix}` });
        }

        let stepChosenMin = false;
        map(stepOptions, (option, index) => {
          if (taxonomyOptions?.minNumber) {
            stepOptions = stepOptions.filter(stepOption => stepOption.value >= taxonomyOptions.minNumber);
          }
        });
        let stepChosenMax = false;
        map(stepOptions, (option, index) => {
          if (option.value == searchData[`range[${taxonomy.slug}][max]`]) {
            stepChosenMax = index;
          }
          if (option.value == searchData[`range[${taxonomy.slug}][min]`]) {
            stepChosenMin = index;
          }
        });
        const selectRangeClass = cx({
          'focused': selectActive,
          'opacity-50': isEmpty(stepOptions),
        });
        const selectInputClass = cx({
          'bg-transparent': selectInputActive,
        });
        const selectInputClass2 = cx({
          'bg-transparent': selectInputActive2,
        });
        return <div key={taxonomy.slug}
                    className={classes.selectFieldClass}>
          <label htmlFor={taxonomy.slug}
                 className={classes.labelClass}>{taxonomy?.label || taxonomy?.single_name}</label>
          <div className="flex justify-between w-full">
            <div
              className={`select--range relative flex items-center ${get(searchData, `range[${taxonomy.slug}][min]`) ? 'select-active' : ''} ${selectInputClass}`}>
              <span className="select--range__label mr-2">{lc_data.jst[417]}</span>
              <Select
                name={`range[${taxonomy.slug}][min]`} id={`${taxonomy.slug}[min]`}
                onChange={selectedOption => handleChange(selectedOption, taxonomy.slug, `range[${taxonomy.slug}][min]`, taxonomy)}
                placeholder={stepOptions[0]['label']}
                value={stepOptions[stepChosenMin] || ''}
                options={stepOptions}
                styles={classes.customStyles}
                className={selectRangeClass}
                isRtl={lc_data.rtl}
                onFocus={() => setSelectInputActive(true)}
                onBlur={() => setSelectInputActive(false)}
              />
            </div>
            <div
              className={`select--range relative flex items-center ${get(searchData, `range[${taxonomy.slug}][max]`) ? 'select-active' : ''} ${selectInputClass2}`}>
              <span className="select--range__label mr-2">{lc_data.jst[418]}</span>
              <Select
                name={`range[${taxonomy.slug}][max]`} id={`${taxonomy.slug}[max]`}
                onChange={selectedOption => handleChange(selectedOption, taxonomy.slug, `range[${taxonomy.slug}][max]`, taxonomy)}
                placeholder={`${prefix}${max}${suffix}`}
                value={stepOptions[stepChosenMax] || ''}
                options={stepOptions}
                styles={classes.customStyles}
                className={selectRangeClass}
                isRtl={lc_data.rtl}
                onFocus={() => setSelectInputActive2(true)}
                onBlur={() => setSelectInputActive2(false)}
              />
            </div>
          </div>
        </div>;
    }
  };

  const wrapperClass = cx({
    'flex items-center w-full': lc_data.is_front,
    'flex flex-col flex-wrap': !lc_data.is_front,
  });
  const Group = props => {
    return (
      <div className="groupings">
        <Group {...props} />
      </div>
    );
  };

  return [
    loading && <div key={0} className="loading">{lc_data.jst[401]}</div>,
    props.taxonomies &&
    <Fragment key={1}>
      {props.taxonomies && map(props.taxonomies, (taxonomy, index) => {
        let tax = [];
        if ('common' !== group) {
          tax = filter(fieldOptions?.taxonomies['common'], val => val.slug === taxonomy);
        }
        filter(fieldOptions?.taxonomies[group], val => {
          if (val.slug === taxonomy) {
            tax.push(val);
          }
        });
        const terms = get(fieldOptions?.terms, taxonomy);
        return (
          tax[0] && terms &&
          <div key={index} className={`search-taxonomy ${wrapperClass} taxonomy--${taxonomy} ${tax[0].type}`}>
            {displayFields(tax[0], terms)}
          </div>
        );
      })}
      <ModalNew
        open={premiumModal}
        closeModal={() => setPremiumModal(true)}
        disableClosing={props?.options?.listings_for_premium_only ?? false}
        title={lc_data.jst[props.options?.restricted_modal_type === 'premium' ? 757 : 758]}
      >
        {props.options?.restricted_modal_image?.url &&
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

export default Taxonomy;
