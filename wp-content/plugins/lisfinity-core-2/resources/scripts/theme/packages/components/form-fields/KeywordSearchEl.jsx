/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component, Fragment, createRef } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { __, sprintf } from '@wordpress/i18n';
import { map, debounce, get, isEmpty, findIndex } from 'lodash';
import keyboard_icon from '../../../../../images/icons/keyboard.svg';
import LoaderIcon from '../../../../../images/icons/loader-rings.svg';
import onClickOutside from 'react-onclickoutside';
import cx from 'classnames';
import { Scrollbars } from 'react-custom-scrollbars';
import ReactSVG from 'react-svg';
import axios from 'axios';

const CancelToken = axios.CancelToken;
let cancel;

class KeywordSearchEl extends Component {
  constructor(props) {
    super(props);
    this.state = {
      loading: false,
      value: '',
      keyword: '',
      category: {},
      results: {},
      active: false,
    };
  }

  keyword = createRef();

  handleClickOutside = e => {
    this.setState({ results: {} });
  };

  handleKeyword = debounce((value, types) => {
    if (cancel !== undefined) {
      cancel();
    }
    const url = `${lc_data.search_keyword}/${value}`;
    this.setState({ loading: true });

    if (value.length < 1) {
      this.setState({ value: '' });
      this.setState({ results: {} });
      this.setState({ loading: false });
      this.keyword.focus();
      return false;
    }

    const headers = { 'X-WP-Nonce': lc_data.nonce };
    axios({
      url,
      method: 'GET',
      credentials: 'same-origin',
      headers,
      cancelToken: new CancelToken(function executor(c) {
        // An executor function receives a cancel function as a parameter
        cancel = c;
      }),
    }).then(response => {
      this.setState({ loading: false });
      this.setState({ results: response.data });
      this.keyword.focus();
    }).catch(error => {
      const result = error.response;
      //return Promise.reject(result);
    });
  }, 0, false);

  handleKeywordFetch = async (value, types) => {
    const { fields } = this.props;
    const options = get(fields, 'keywordOptions');
    this.setState({ value });
    this.props.keywordValueHandle(value);

    /*    if (lc_data.is_search || (options && options.suggestions !== 'true')) {
          this.setState({ value });
          return;
        }*/
    this.handleKeyword(value, types);
  };

  getHighlightedText(text, highlight) {
    // Split on highlight term and include term into parts, ignore case
    let parts = text.split(new RegExp(`(${highlight})`, 'gi'));
    return parts.map((part, i) =>
      <span key={i} className={part.toLowerCase() === highlight.toLowerCase() ? 'ml-3 font-bold' : ''}>
            {part}
        </span>);
  }

  handleResultClick = (type, keyword, name, value) => {
    let newValue = this.state.value;

    this.setState({ text: keyword });
    if (type === 'category') {
      this.setState({
        category: {
          name: name,
          value: value,
        }
      });

      newValue = sprintf(__('%s in %s', 'lisfinity-core'), newValue, value);
    }

    if (type === 'taxonomy') {
      this.setState({
        category: {
          name: name,
          value: value,
        }
      });

      newValue = sprintf(lc_data.jst[412], newValue, value);
    }

    if (type === 'category_separated') {
      this.setState({
        category: {
          name: name,
          value: value,
        }
      });

      newValue = value;
      this.setState({ text: '' });
    }

    this.setState({ results: {} });
    this.setState({ value: newValue });
  };

  /**
   * Renders the component.
   *
   * @return {Object}
   */
  render() {
    const { loading, value, results } = this.state;
    const { type, searchValues, fields, classname } = this.props;
    const placeholder = get(fields, 'placeholder');
    const label = get(fields, 'label');
    const options = get(fields, 'keywordOptions');
    const wrapperClass = cx({
      'flex-center px-24 py-10 h-44 rounded bg-white': 'header' !== type,
      'flex-center px-24 py-10 h-40 rounded bg-white': 'header' === type,
    });
    const labelClass = cx({
      'mr-10 font-semibold whitespace-no-wrap': 'header' !== type,
      'mr-10 font-bold': 'header' === type,
    });
    const inputClass = cx({
      'flex w-full italic': true,
      'flex bg-white w-full': !this.state.active,
      'flex bg-transparent w-full': this.state.active,
    });
    let resultsHeight = 198;

    if (options && options['suggestions-limit'] && results.query && options['suggestions-limit'] > results.query.length) {
      resultsHeight = results.query.length * 33;
    } else if (options && options['suggestions-limit']) {
      resultsHeight = options['suggestions-limit'] * 33;
    }
    if (undefined === results.query) {
      resultsHeight = 33;
    }

    let keywordIndex = null;
    this.props?.options?.order?.forEach((el, index) => {
      if ('keyword' === el) {
        keywordIndex = index;
      }
    });

    let keyboardIcon = null;
    let keyboardSvg = null;

    if (this.props?.options?.selected_icon_keyword !== null && this.props?.options?.selected_icon_keyword) {
      typeof this.props?.options.selected_icon_keyword['value'] === 'string' ? keyboardIcon = this.props?.options.selected_icon_keyword['value'] : keyboardSvg = this.props?.options.selected_icon_keyword['value']['url'];
    }

    let fieldWidth = null;
    let fieldGap = null;
    let displayField = '';

    if (this.props?.options && keywordIndex !== undefined && keywordIndex !== null) {
      fieldWidth = this.props?.options['lisfinity_hero_form_fields'][keywordIndex]['width']?.size && this.props?.options['lisfinity_hero_form_fields'][keywordIndex]['width']?.unit ? `${this.props?.options['lisfinity_hero_form_fields'][keywordIndex]['width'].size}${this.props?.options['lisfinity_hero_form_fields'][keywordIndex]['width'].unit}` : '100%';
      fieldGap = this.props?.options['lisfinity_hero_form_fields'][keywordIndex]['gap']?.size && this.props?.options['lisfinity_hero_form_fields'][keywordIndex]['gap']?.unit ? `${this.props?.options['lisfinity_hero_form_fields'][keywordIndex]['gap'].size}${this.props?.options['lisfinity_hero_form_fields'][keywordIndex]['gap'].unit} ` : '0px';
      displayField = this.props?.options['lisfinity_hero_form_fields'][keywordIndex]['display_field'] && this.props?.options['lisfinity_hero_form_fields'][keywordIndex]['display_field'] === 'yes' ? 'none' : '';
    }
    if (!lc_data.is_front && null === keywordIndex) {
      keywordIndex = 0;
    }

    return [
      <div key={1}
           className={`home-search-keyword lisfinity-order-${keywordIndex + 1} ${undefined !== classname && classname} ${keywordIndex !== -1 && keywordIndex !== undefined && keywordIndex !== null ? this.props?.options?.['lisfinity_hero_form_fields'][keywordIndex]['custom_id'] : ''} search-keyword relative w-full z-10 `}
           style={{
             width: fieldWidth,
             paddingLeft: fieldGap,
             paddingRight: fieldGap,
             display: displayField
           }}
      >
        {this.props?.options?.pull_label_keyword !== 'yes' &&
        <div className={`${wrapperClass}`}>
          {((this.props?.options?.remove_label_keyword !== 'yes' && this.props?.options?.remove_label_keyword) || this.props.displayLabel === 'yes') &&
          <label htmlFor="keyword"
                 className={`label ${keywordIndex !== -1 && keywordIndex !== undefined && keywordIndex !== null ? this.props?.options?.['lisfinity_hero_form_fields'][keywordIndex]['custom_id'] : ''}-label`}>{label ? label.keyword : lc_data.jst[63]}</label>
          }
          <input
            ref={input => {
              this.keyword = input;
            }}
            id="keyword"
            type="text"
            name={options && options.suggestions !== 'true' ? 'keyword' : ''}
            className={inputClass}
            placeholder={placeholder ? placeholder.keyword : __('None', 'lisfinity-core')}
            value={value}
            onChange={e => {
              this.setState({ text: e.target.value });
              this.handleKeywordFetch(e.target.value, searchValues);
            }}
            autoComplete="off"
          />
          {!isEmpty(this.state.text) && <input type="hidden" name="keyword" defaultValue={this.state.text}/>}
          {!isEmpty(this.state.category) &&
          <input type="hidden" name={this.state.category.name} defaultValue={this.state.category.value}/>}


          {(!loading && keywordIndex !== undefined && keywordIndex !== null && ((keyboardSvg === null && keyboardIcon === null) || (!keyboardSvg && !keyboardIcon))) && this.props?.options?.remove_icon_keyword !== 'yes' &&
          <ReactSVG src={`${lc_data.dir}dist/${keyboard_icon}`}
                    id={`${keywordIndex !== -1 ? this.props?.options?.['lisfinity_hero_form_fields'][keywordIndex]['custom_id'] : ''}-icon`}
                    className="relative mr-8 top-2 w-24 h-24 fill-grey-700"/>}


          {
            keywordIndex !== undefined && keywordIndex !== null && !loading && keyboardSvg && this.props?.options?.place_icon_keyword !== '' && this.props?.options?.remove_icon_keyword !== 'yes' &&
            <img src={keyboardSvg} alt="cart-icon"
                 id={`${keywordIndex !== -1 ? this.props?.options?.['lisfinity_hero_form_fields'][keywordIndex]['custom_id'] : ''}-icon`}
                 className="w-20 h-20 mr-8 fill-icon-reset pointer-events-none"/>
          }
          {
            !loading && keywordIndex !== null && this.props?.options?.place_icon_keyword !== '' && keyboardIcon && this.props?.options?.remove_icon_keyword !== 'yes' &&
            <i className={keyboardIcon}
               id={`${keywordIndex !== -1 ? this.props?.options['lisfinity_hero_form_fields'][keywordIndex]['custom_id'] : ''}-icon`}
               aria-hidden="true"
               style={{
                 marginRight: '8px'
               }}
            ></i>
          }
          {loading && <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`}
                                className="relative w-24 h-24 fill-grey-700"
                                style={{ zoom: .6, top: -9, right: 14 }}/>}
        </div>}
        {this.props?.options?.pull_label_keyword === 'yes' &&
        <Fragment>
          {
            ((this.props?.options?.remove_label_keyword !== 'yes' && this.props?.options?.remove_label_keyword) || this.props.displayLabel === 'yes') &&
            <label htmlFor="keyword"
                   className={`label ${keywordIndex !== -1 ? this.props?.options?.['lisfinity_hero_form_fields'][keywordIndex]['custom_id'] : ''}-label`}>{label ? label.keyword : lc_data.jst[63]}</label>
          }
          <div className={`${wrapperClass}`}>
            <input
              ref={input => {
                this.keyword = input;
              }}
              id="keyword"
              type="text"
              name={options && options.suggestions !== 'true' ? 'keyword' : ''}
              className={inputClass}
              placeholder={placeholder ? placeholder.keyword : __('None', 'lisfinity-core')}
              value={value}
              onChange={e => {
                this.setState({ text: e.target.value });
                this.handleKeywordFetch(e.target.value, searchValues);
              }}
              autoComplete="off"
            />
            {!isEmpty(this.state.text) && <input type="hidden" name="keyword" defaultValue={this.state.text}/>}
            {!isEmpty(this.state.category) &&
            <input type="hidden" name={this.state.category.name} defaultValue={this.state.category.value}/>}


            {(!loading && ((keyboardSvg === null && keyboardIcon === null) || (!keyboardSvg && !keyboardIcon))) && this.props?.options?.remove_icon_keyword !== 'yes' &&
            <ReactSVG src={`${lc_data.dir}dist/${keyboard_icon}`}
                      id={`${keywordIndex !== -1 ? this.props?.options['lisfinity_hero_form_fields'][keywordIndex]['custom_id'] : ''}-icon`}
                      className="relative top-2 w-24 h-24 fill-grey-700"/>}


            {
              !loading && keyboardSvg && this.props?.options?.place_icon_keyword !== '' && this.props?.options?.remove_icon_keyword !== 'yes' &&
              <img src={keyboardSvg} alt="cart-icon"
                   id={`${keywordIndex !== -1 ? this.props?.options['lisfinity_hero_form_fields'][keywordIndex]['custom_id'] : ''}-icon`}
                   className="w-20 h-20 fill-icon-reset pointer-events-none"/>
            }
            {
              !loading && this.props?.options?.place_icon_keyword !== '' && keyboardIcon && this.props?.options?.remove_icon_keyword !== 'yes' &&
              <i className={keyboardIcon}
                 id={`${keywordIndex !== -1 ? this.props?.options['lisfinity_hero_form_fields'][keywordIndex]['custom_id'] : ''}-icon`}
                 aria-hidden="true"></i>
            }
            {loading && <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`}
                                  className="relative w-24 h-24 fill-grey-700"
                                  style={{ zoom: .6, top: -9, right: 14 }}/>}
          </div>

        </Fragment>}
        {options && options.suggestions && !isEmpty(results) &&
        <div className="results absolute mt-2 py-8 w-full bg-white rounded shadow-partners">
          <Scrollbars
            style={{ zIndex: 20, height: resultsHeight }}
            autoHide={false}
            renderTrackHorizontal={props => <div {...props} className="hidden"/>}
            renderThumbHorizontal={props => <div {...props} className="hidden"/>}
            renderTrackVertical={props => <div {...props}
                                               className="track--vertical top-0 right-0 bottom-0 w-2"/>}
            renderThumbVertical={props => <div {...props}
                                               className="thumb--vertical bg-grey-600 rounded opacity-25"/>}
          >
            {results.query && map(results.query, (taxonomy, index) => {
              const regex = new RegExp(`(${value})`, 'gi');
              return (
                <div key={index} className="result">
                  {taxonomy.type === 'category' &&
                  <div
                    className="flex py-6 px-16 hover:bg-grey-200 cursor-pointer"
                    dangerouslySetInnerHTML={{
                      __html: sprintf(lc_data.jst[414], `<strong class="mx-2">${value}</strong>`,
                        `<strong class="mx-2">${taxonomy.taxonomy_name}</strong>`)
                    }}
                    onClick={() => this.handleResultClick('category', value, 'category-type', taxonomy.slug)}
                  />
                  }
                  {taxonomy.type === 'taxonomy' &&
                  <div
                    className="block py-6 px-16 hover:bg-grey-200 cursor-pointer"
                    dangerouslySetInnerHTML={{
                      __html: sprintf(lc_data.jst[415], `<strong class="mx-2">${value}</strong>`,
                        `<strong class="mx-2">${taxonomy.taxonomy_name}</strong>`, `<strong class="mx-2">${taxonomy.name}</strong>`),
                    }}
                    onClick={() => this.handleResultClick('taxonomy', value, `tax[${taxonomy.taxonomy}]`, taxonomy.slug)}
                  />
                  }
                  {(taxonomy.type === 'promotion' || !taxonomy.type) &&
                  <div key={index} className="result">
                    <a href={taxonomy.guid} className="block py-6 px-16 hover:bg-grey-200">
                      {this.getHighlightedText(taxonomy.post_title, value)}
                    </a>
                  </div>
                  }
                  {taxonomy.type === 'category_separated' &&
                  <div
                    className="block py-6 px-16 hover:bg-grey-200 cursor-pointer"
                    dangerouslySetInnerHTML={{
                      __html: sprintf(lc_data.jst[416], `<strong class="mx-2">${taxonomy.taxonomy_name}</strong>`)
                    }}
                    onClick={() => this.handleResultClick('category_separated', value, `category-type`, taxonomy.slug)}
                  />
                  }
                </div>
              );
            })}
            {results.message && <div className="no-results py-6 px-16">{results.message}</div>}
          </Scrollbars>
        </div>
        }
      </div>,
    ];
  }
}

export default onClickOutside(KeywordSearchEl);
