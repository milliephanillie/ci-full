/* global lc_data, React */
/**
 * Dependencies.
 */
import { Component, Fragment, useEffect, useState } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';
import { map, isEmpty, get, filter, findIndex } from 'lodash';
import cx from 'classnames';
import Select from 'react-select';
import Checkbox from './Checkbox';
import { useDispatch, useSelector } from 'react-redux';
import { fetchPosts } from '../page-search/store/actions';

const HomeTaxonomyOld = (props) => {
  const [loading, setLoading] = useState(false);
  const [chosen, setChosen] = useState({});
  const [classes, setClasses] = useState({});
  const [taxonomies, setTaxonomies] = useState({});
  const [category, setCategory] = useState('common');
  const data = useSelector(state => state);
  const { searchData } = data.searchData;
  const fields = data.homeFields;
  const dispatch = useDispatch();

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

  const handleChange = (selectedOption, taxonomy, name) => {
    const selected = selectedOption ? selectedOption.value : false;
    const values = { ...chosen };
    if (!selected) {
      delete values[taxonomy];
      const childs = getChilds(taxonomies, taxonomy);
      if (childs.length > 0) {
        map(childs, child => {
          delete values[child];
        });
      }
    } else {
      values[taxonomy] = selected;
    }
    setChosen(values);
    props.handleData(values);
  };

  const handleCheckboxChange = (value, taxonomy, name) => {
    const values = { ...chosen };
    if (!values[name]) {
      values[name] = [];
    }
    if (values[name].includes(value)) {
      const dataIndex = values[name].indexOf(value);
      values[name].splice(dataIndex, 1);
    } else {
      values[name].push(value);
    }

    setChosen(values);
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

  useEffect(() => {
    const taxes = {};
    fields?.taxonomies.common && (map(fields.taxonomies.common, (taxonomy) => {
      taxes[taxonomy.slug] = taxonomy;
    }));
    setTaxonomies(taxes);
  }, [data]);

  const handleCategoryChange = (value, name) => {
    if (null === value) {
      setCategory('common');
    } else {
      setCategory(value);
    }
  };

  return [
    loading && <div key={0} className="loading">{lc_data.jst[401]}</div>,
    <Fragment key={1}>
      {props.fields?.fields.includes('category-type') &&
      <div key={9999999} style={{
        width: `${props.columns === 100 ? props.width / props.options.columns : 100}%`,
        paddingLeft: props.options.padding,
        paddingRight: props.options.padding,
      }}>
        <div className={`${classes?.selectFieldClass}`}>
          <label htmlFor="categoryType"
                 className={classes?.labelClass}>{data?.homeFields?.fields?.label['category-type'] || lc_data.jst[473]}</label>
          <Select
            name="category-type" id="categoryType"
            onChange={selectedOption => handleCategoryChange(selectedOption && selectedOption.value, 'category-type')}
            placeholder={data?.homeFields?.fields?.placeholder ? data?.homeFields?.fields?.placeholder['category-type'] : lc_data.jst[400]}
            options={lc_data.category_select}
            styles={classes?.customStyles}
            // menuIsOpen={true}
            className={category !== 'common' && 'select-active'}
            isClearable
          />
        </div>
      </div>
      }
      {map(props.fields.fields, (group, index) => {
        const taxonomy = get(taxonomies, group);
        const parentOptions = get(taxonomies, taxonomy?.parent) || null;
        let options = [];
        switch (taxonomy?.type) {
          case 'select':
          case 'location':
            const parentValue = chosen[taxonomy.parent];
            let termParent = false;

            fields.terms[taxonomy.slug] && map(fields.terms[taxonomy.slug], term => {
              termParent = term?.meta?.parent_slug || false;
              if (term?.taxonomy === group && (isEmpty(taxonomy.parent) || (termParent && parentValue === termParent[0]))) {
                options.push({
                  value: term.slug,
                  label: term.name
                });
              }
            });

            let termParentOptions = false;
            if (parentValue) {
              termParentOptions = get(fields.terms[taxonomy.parent], parentValue);
            }
            let chosenIndex = false;
            map(options, (option, index) => {
              if (option.value === chosen[group]) {
                chosenIndex = index;
              }
            });
            return <div key={group} style={{
              width: `${props.columns === 100 ? props.width / props.options.columns : 100}%`,
              paddingLeft: props.options.padding,
              paddingRight: props.options.padding,
            }}>
              <div
                className={`${classes?.selectFieldClass} ${isEmpty(options) ? 'bg-grey-300' : ''}`}>
                <label htmlFor={group}
                       className={classes?.labelClass}>{taxonomy?.label || taxonomy?.single_name}</label>
                <Select
                  name={`tax[${group}]`} id={group}
                  onChange={selectedOption => handleChange(selectedOption, group, `tax[${group}]`)}
                  value={options[chosenIndex] || ''}
                  className={get(data, `tax[${group}]`) && 'select-active'}
                  options={options}
                  isClearable
                  // menuPortalTarget={document.body}
                  // menuIsOpen={true}
                  styles={classes?.customStyles}
                  placeholder={chosen[taxonomy.parent] && options && termParentOptions ? `${options.length} ${termParentOptions.name} ${taxonomy.plural_name}` : taxonomy.placeholder ? taxonomy.placeholder : taxonomy.single_name}
                  isRtl={lc_data.rtl}
                  isDisabled={isEmpty(options)}
                />
              </div>
            </div>;
          case 'checkbox':
            const terms = fields.terms[taxonomy.slug];
            return (
              <div key={group} className="mt-16 flex flex-wrap items-center w-full">
                {terms && map(terms, (term, index) => {
                  return (
                    term.slug &&
                    <div key={index}
                         className={`field--checkbox w-1/5 ${index > 4 && 'mt-16'}`}>
                      <Checkbox name={`tax[${group}][]`} id={`${group}[${term.slug}]`}
                                handleChange={e => handleCheckboxChange(term.slug, group, `tax[${group}]`)}
                                value={term.slug}
                                checked={get(chosen, `tax[${group}]`) && chosen[`tax[${group}]`].includes(term.slug)}
                      />
                      <label htmlFor={`${group}[${term.slug}]`} className="w-full text-white">
                        {term.name}
                      </label>
                    </div>
                  );
                })
                }
              </div>
            );
          case 'input':
            fields?.terms[taxonomy.slug] && map(fields.terms[taxonomy.slug], term => {
              termParent = term?.meta?.parent_slug || false;
              if (term?.taxonomy === group && (isEmpty(taxonomy.parent) || (termParent && parentValue === termParent[0]))) {
                options.push({
                  value: term.slug,
                  label: term.name
                });
              }
            });

            let taxonomyOptions = fields?.terms[taxonomy.slug];
            let prefix = '';
            let suffix = '';
            if (get(taxonomyOptions, 'prefix')) {
              prefix = taxonomyOptions.prefix;
            }
            if (get(taxonomyOptions, 'suffix')) {
              suffix = taxonomyOptions.suffix;
            }
            const stepOptions = [];
            let max = 9;
            if (!isEmpty(options)) {
              max = options.slice(-1);
              max = Math.ceil(parseFloat(max[0].label));
            }

            let fieldStep = parseFloat(fields?.fields?.steps?.[taxonomy?.slug]) || 1;
            let stepChosenMin = parseFloat(fields?.fields?.minNumbers?.[taxonomy?.slug]) || 0;
            let stepChosenMax = max;
            for (let i = stepChosenMin; i <= stepChosenMax; i += fieldStep) {
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
            const selectRangeClass = cx({
              'opacity-50': isEmpty(stepOptions),
            });
            return <div key={taxonomy.slug}
                        style={{
                          width: `${props.columns === 100 ? props.width / props.options.columns : 100}%`,
                          paddingLeft: props.options.padding,
                          paddingRight: props.options.padding,
                        }}
            >
              <div className={classes.selectFieldClass}
              >
                <label htmlFor={taxonomy.slug}
                       className={classes.labelClass}>{taxonomy?.label || taxonomy?.single_name}</label>
                <div className="flex justify-between w-full">
                  <div
                    className={`select--range relative flex items-center bg-transparent border-0 ${get(chosen, `range[${taxonomy.slug}][min]`) ? '' : ''}`}>
                    <span className="select--range__label mr-2">{lc_data.jst[417]}</span>
                    <Select
                      name={`range[${taxonomy.slug}][min]`} id={`${taxonomy.slug}[min]`}
                      onChange={selectedOption => handleChange(selectedOption, `range[${taxonomy.slug}][min]`, `range[${taxonomy.slug}][min]`)}
                      placeholder={`${prefix}${stepOptions[0]['label']}${suffix}`}
                      defaultValue=""
                      options={stepOptions}
                      styles={classes.customStyles}
                      className={selectRangeClass}
                      isRtl={lc_data.rtl}
                    />
                  </div>
                  <div
                    className={`select--range relative flex items-center bg-transparent border-0 ${get(chosen, `range[${taxonomy.slug}][max]`) ? '' : ''}`}>
                    <span className="select--range__label mr-2">{lc_data.jst[418]}</span>
                    <Select
                      name={`range[${taxonomy.slug}][max]`} id={`${taxonomy.slug}[max]`}
                      onChange={selectedOption => handleChange(selectedOption, `range[${taxonomy.slug}][max]`, `range[${taxonomy.slug}][max]`)}
                      placeholder={`${prefix}${max}${suffix}`}
                      defaultValue=""
                      options={stepOptions}
                      styles={classes.customStyles}
                      className={selectRangeClass}
                      isRtl={lc_data.rtl}
                    />
                  </div>
                </div>
              </div>
            </div>;
        }
      })}
    </Fragment>,
  ];
};

export default HomeTaxonomyOld;
