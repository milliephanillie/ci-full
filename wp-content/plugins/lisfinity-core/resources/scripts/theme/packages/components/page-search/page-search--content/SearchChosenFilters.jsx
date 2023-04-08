/* global lc_data, React */
/**
 * External dependencies.
 */
import { connect } from 'react-redux';
import { Component } from '@wordpress/element';
import { map, get, isEmpty, pull, filter } from 'lodash';
import { __ } from '@wordpress/i18n';
import ReactSVG from 'react-svg';

/**
 * Internal dependencies
 */
import * as actions from '../../../store/actions';
import { fetchPosts } from '../store/actions';
import closeIcon from '../../../../../../images/icons/close.svg';
import { Fragment } from 'react';
import { updateSearchData, updateSearchDataChosen } from '../../../store/actions';

class SearchChosenFilters extends Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  /**
   * Get the taxonomy name from the search option
   * --------------------------------------------
   *
   * @param taxonomy
   * @returns {*}
   */
  getTaxonomyName = (taxonomy) => {
    if (taxonomy === 'keyword') {
      return taxonomy;
    }
    const regex = /\[(.*?)\]/g;
    const matches = Array.from(taxonomy.matchAll(regex));

    return matches[0][1] || '';
  };

  getChild = (taxonomies, taxonomy) => {
    const childs = [];
    taxonomies && map(taxonomies, tax => tax.parent === taxonomy && !childs.includes(tax.slug) && childs.push(tax.slug));

    return childs;
  };

  getChilds = (taxonomies, taxonomy, childs) => {
    childs = childs || [];
    const child = this.getChild(taxonomies, taxonomy);
    if (!isEmpty(child)) {
      map(child, c => {
        childs.push(c);
        this.getChilds(taxonomies, c, childs);
      });
    }

    return childs;
  };

  /**
   * Handle click on a chosen filter
   * -------------------------------
   *
   * @param e
   * @param option
   * @param value
   * @param isArray
   * @param isRange
   */
  handleFilterClick = (e, option, value, isArray, isRange) => {
    const { dispatch, searchData } = this.props;
    const group = searchData['category-type'] || 'common';
    const data = this.props.searchDataChosen;
    const taxonomyOptions = this.props.taxonomyOptions;
    const regex = /\[(.*?)\]/g;

    if (isRange) {
      const matches = option.match(regex);
      const taxonomy = matches[0];
      map(data, (value, searchOption) => {
        if (searchOption.indexOf(taxonomy) > -1) {
          delete data[searchOption];
        }
      });
    } else if (isArray) {
      pull(data[option], value);
      if (data[option].length === 0) {
        delete data[option];
      }
    } else {
      delete data[option];
    }

    const taxOption = this.getTaxonomyName(option);
    const taxonomy = filter(this.props.fieldOptions?.taxonomies[group], tax => tax.slug === taxOption);
    if (taxonomy[0]) {
      const childs = this.getChilds(this.props.fieldOptions.taxonomies[group], taxonomy[0].slug);
      if (childs) {
        map(childs, child => {
          delete data[`tax[${child}]`];
          delete data[`range[${child}][min]`];
          delete data[`range[${child}][max]`];
        });
      }
    }

    dispatch(fetchPosts(data, false, true));
    dispatch(updateSearchDataChosen(data));
    dispatch(updateSearchData(data));
  };

  /**
   * Renders the component.
   *
   * @return {Object}
   */
  render() {
    const data = { ...this.props.searchDataChosen };
    const fieldOptions = { ...this.props.fieldOptions };

    const optionsCheck = { ...data };
    delete optionsCheck['category-type'];
    delete optionsCheck['offset'];
    delete optionsCheck['business'];
    delete optionsCheck['order'];
    delete optionsCheck['latitude'];
    delete optionsCheck['longitude'];
    delete optionsCheck['p'];
    delete optionsCheck['_wpnonce'];

    const options = {};
    data && map(data, (value, option) => {
      if (option !== '_wpnonce' && option !== 'p' && option !== 'category-type' && option !== 'offset' && option !== 'business' && option !== 'order' && option !== 'latitude' && option !== 'longitude') {
        if (Array.isArray(value) && !isEmpty(value)) {
          const selectedOptions = [];
          map(value, el => {
            selectedOptions.push(el);
            options[option] = { option, value: selectedOptions, isArray: true, isRange: false };
          });
        }
        if (!Array.isArray(value)) {
          const rangeField = option.indexOf('range') > -1;
          const regex = /\[(.*?)\]/g;
          if (rangeField) {
            const matches = Array.from(option.matchAll(regex));
            const name = matches[0][1];
            const range = matches[1][1];
            if (options[name]) {
              options[name].value = { ...{ [range]: value }, ...options[name].value };
            } else {
              options[name] = {
                value: { [range]: value }, option, isArray: false, isRange: true
              };
            }
          } else {
            options[option] = { option, value, isArray: false, isRange: false };
          }
        }
      }
    });
    return (
      !isEmpty(fieldOptions) && !isEmpty(optionsCheck) &&
      <div
        className={`filters--chosen flex flex-wrap items-center ${this.props.page === 'business' ? 'mb-20' : 'mt-10 mb-20'}`}>

        <span className="mt-8 mr-4 text-grey-500 label">{lc_data.jst[477]}</span>

        {map(options, (value, taxonomy) => {
          let prefix = '';
          let suffix = '';
          let tax = taxonomy;
          let title = null;

          const regex = /\[(.*?)\]/g;
          if (value.isRange) {
            const matches = Array.from(value.option.matchAll(regex));
            prefix = taxonomy !== 'category-type' && taxonomy !== 'offset' && get(fieldOptions.fields[data['category-type']], ['options', matches[0][1], 'prefix']) || '';
            suffix = taxonomy !== 'category-type' && taxonomy !== 'offset' && get(fieldOptions.fields[data['category-type']], ['options', matches[0][1], 'suffix']) || '';
            tax = matches[0][1];
            title = fieldOptions?.terms?.[tax]?.[value?.value] || null;
          } else {
            prefix = taxonomy !== 'category-type' && taxonomy !== 'offset' && get(fieldOptions.fields[data['category-type']], ['options', taxonomy, 'prefix']) || '';
            suffix = taxonomy !== 'category-type' && taxonomy !== 'offset' && get(fieldOptions.fields[data['category-type']], ['options', taxonomy, 'suffix']) || '';

            const matches = Array.from(value.option.matchAll(regex));
            if (matches && matches[0] && matches[0][1]) {
              tax = matches[0][1];
            }
            title = fieldOptions?.terms?.[tax]?.[value?.value] || null;
          }
          const min = value.value.min || 0;
          const max = value.value.max || '';
          return !value.isArray
            ?
            <button
              key={taxonomy}
              type="button"
              onClick={e => this.handleFilterClick(e, value.option, value.value, value.isArray, value.isRange)}
              className="flex items-center justify-between mt-8 ml-8 py-4 px-12 bg-white chosen-item rounded shadow hover:bg-blue-100"
            >
              <span className="inline-block text-sm">
                {
                  value.isRange
                    ?
                    <div className="range">
                      {prefix !== '' && <span className="text-xs normal-case">{prefix}</span>}
                      {min}
                      {suffix !== '' && <span className="text-xs normal-case">{suffix}</span>}
                      <span className="mx-4">-</span>
                      {max !== '' && prefix !== '' && <span className="text-xs normal-case">{prefix}</span>}
                      {max}
                      {max !== '' && suffix !== '' && <span className="text-xs normal-case">{suffix}</span>}
                    </div>
                    :
                    <Fragment>
                      {this.props.options && this.props.options.chosen_labels === '1' &&
                      <span className="mr-2 inline-block text-sm">
                        {`${title?.taxonomy}:` || sprintf('%s:', taxonomy.replace('tax[', '').replace(']', ''))}
                      </span>
                      }
                      <span
                        className="font-semibold">{title?.name || (Number.isInteger(value?.value) ? value?.value : value?.value.replace('-', ' '))}</span>
                    </Fragment>
                }
              </span>
              {(!this.props.settings?.business_items_submit_icon || this.props.settings?.business_items_submit_icon.value === '' || !this.props.settings) &&
              <ReactSVG
                src={`${lc_data.dir}dist/${closeIcon}`}
                className="ml-10 w-8 h-8 fill-icon-search close-icon"
              />
              }
              {this.props.settings?.business_items_submit_icon?.value && this.props.settings?.business_items_submit_icon?.library !== 'svg' &&
              <i
                className={`ml-10 w-8 h-8 ${this.props.settings.business_items_submit_icon.value} filter-icon close-icon`}></i>
              }
              {this.props.settings?.business_items_submit_icon?.value.url && this.props.settings?.business_items_submit_icon?.library === 'svg' &&
              <ReactSVG
                src={`${this.props.settings.business_items_submit_icon.value.url}`}
                className={`ml-10 w-8 h-8 close-icon filter-icon`}
              />
              }
            </button>
            :
            map(value.value, (el, index) => {
              const elTitle = fieldOptions?.terms?.[tax]?.[el]?.name || el.replace(/-/g, ' ');
              return <button
                key={index}
                type="button"
                onClick={e => this.handleFilterClick(e, value.option, el, value.isArray, value.isRange)}
                className="flex mt-8 ml-8 items-center justify-between py-4 px-12 bg-white rounded shadow hover:bg-blue-100"
              >
                <span className="inline-block text-sm capitalize">
                  {elTitle}
                </span>
                <ReactSVG
                  src={`${lc_data.dir}dist/${closeIcon}`}
                  className="ml-10 w-8 h-8 fill-icon-search"
                />
              </button>;
            });
        })}

      </div>
    );
  }
}

function mapStateToProps(state) {
  const { postsByUrl, searchData, fieldOptions, searchDataChosen } = state;
  const {
    isFetching, lastUpdated, items: results,
  } = postsByUrl?.RECEIVE_POSTS || {
    isFetching: true,
    results: [],
  };
  return {
    results,
    isFetching,
    lastUpdated,
    searchData,
    fieldOptions,
    searchDataChosen,
  };
}

export default connect(mapStateToProps)(SearchChosenFilters);
