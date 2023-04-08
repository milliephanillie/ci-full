/* global lc_data, React */
/**
 * External dependencies.
 */
import { connect } from 'react-redux';
import { Component, Fragment } from '@wordpress/element';
import cx from 'classnames';
import { isEmpty, get, debounce } from 'lodash';
import { updateSearchData } from '../../store/actions';
import { calculateFoundPosts, fetchPosts } from '../page-search/store/actions';

class Meta extends Component {
  constructor(props) {
    super(props);
    this.state = {
      loading: false,
      value: '',
      results: {},
      stateData: {},
      selectActive: false,
      selectActive1: false,
    };
  }

  /**
   * Handle meta changes
   * -------------------
   *
   * @param value
   * @param name
   */
  handleChange = debounce((data) => {
    const { dispatch } = this.props;
  }, 275, false);

  /**
   * Handle change of the meta fields value
   * --------------------------------------
   *
   * @param value
   * @param name
   * @returns {Promise<void>}
   */
  handleMetaChange = async (value, name) => {
    const { dispatch } = this.props;
    const data = this.props.searchData;
    if (isEmpty(value)) {
      delete data[name];
    } else {
      data[name] = value;
    }
    this.setState({ stateData: data });
    this.handleChange(data);
    if (this.props?.options?.display_ads_count) {
      dispatch(calculateFoundPosts(data));
    }
  };

  /**
   * Renders the component.
   *
   * @return {Object}
   */
  render() {
    const { loading, stateData } = this.state;
    const { meta, fieldOptions, fields, group, category } = this.props;
    const data = this.props.searchData;
    const labelClass = cx({
      'mr-10 font-semibold whitespace-no-wrap': lc_data.is_front,
      'filters--label': !lc_data.is_front,
    });
    const minPrefixClass = cx({
      'text-grey-500': undefined === get(stateData, `mrange[${meta}][min]`),
    });
    const maxPrefixClass = cx({
      'text-grey-500': undefined === get(stateData, `mrange[${meta}][max]`),
    });

    const labelTextMin = this.props?.fields && this.props?.fields?.label[`${meta}-label-min`] ?  this.props.fields.label[`${meta}-label-min`] : lc_data.jst[417];
    const labelTextMax = this.props?.fields && this.props?.fields?.label[`${meta}-label-max`] ?  this.props.fields.label[`${meta}-label-max`] : lc_data.jst[418];
    const options = fieldOptions?.fields[category || 'common'] || false;
    const label = options?.labels?.[meta] || 'Price';
    const placeholderMin = this.props?.fields && this.props?.fields?.placeholder && this.props?.fields?.placeholder[`${meta}-placeholder-min`] ?  this.props.fields.placeholder[`${meta}-placeholder-min`] : options?.placeholders?.[`${meta}-min`] || '0';
    const placeholderMax = this.props?.fields && this.props?.fields?.placeholder && this.props?.fields?.placeholder[`${meta}-placeholder-max`] ?  this.props.fields.placeholder[`${meta}-placeholder-max`] : options?.placeholders?.[`${meta}-max`] || '99.999';
    const prefix = options?.options?.[meta]?.prefix || '';
    const suffix = options?.options?.[meta]?.suffix || '';
    const selectClass = cx({
      'bg-transparent': this.state.selectActive,
    });
    const selectClass1 = cx({
      'bg-transparent': this.state.selectActive1,
    });

    let priceIndex = null;

    this.props?.options?.order?.forEach((el, index) => {
      if ('price' === el) {
        priceIndex = index;
      }
    });
    let fieldWidth = null;
    let fieldGap = null;
    let displayField = '';
    if (priceIndex) {
      fieldWidth = this.props.options['lisfinity_hero_form_fields'][priceIndex]['width']?.size && this.props.options['lisfinity_hero_form_fields'][priceIndex]['width']?.unit ? `${this.props.options['lisfinity_hero_form_fields'][priceIndex]['width'].size}${this.props.options['lisfinity_hero_form_fields'][priceIndex]['width'].unit}` : '100%';
      fieldGap = this.props.options['lisfinity_hero_form_fields'][priceIndex]['gap']?.size && this.props.options['lisfinity_hero_form_fields'][priceIndex]['gap']?.unit ? `${this.props.options['lisfinity_hero_form_fields'][priceIndex]['gap'].size}${this.props.options['lisfinity_hero_form_fields'][priceIndex]['gap'].unit}` : '0px';
      displayField = this.props.options['lisfinity_hero_form_fields'][priceIndex]['display_field'] && this.props.options['lisfinity_hero_form_fields'][priceIndex]['display_field'] === 'yes' ? 'none' : '';
    }

    return (
      <div
        className={`search-meta flex flex-col flex-wrap lisfinity-order-${priceIndex + 1} ${priceIndex !== -1 && priceIndex !== undefined && priceIndex !== null ? this.props.options['lisfinity_hero_form_fields'][priceIndex]['custom_id'] : ''} ${meta} ${this.props.type === 'home' ? 'w-full' : ''}`}
        style={{
          width: fieldWidth,
          paddingLeft: fieldGap,
          paddingRight: fieldGap,
          display: displayField
        }}
      >
        {this.props?.options?.pull_label_price !== 'yes' &&
        <div className={`${this.props.type !== 'home' ? 'mt-18' : 'mt-10 w-full'}`}
             style={{
               paddingLeft: this.props.options ? this.props.options.padding : 0,
               paddingRight: this.props.options ? this.props.options.padding : 0,
             }}
        >
          {this.props.type !== 'home' && <label htmlFor={meta} className={labelClass}>{label}</label>}

          <div className="field--input__range flex justify-between w-full">

            <div
              className={`field--with-icon flex items-center ${this.props.type === 'home' ? 'bg-white' : ''} ${selectClass}`}>
              {this.props?.options?.remove_label_price !== 'yes' &&
              <span className="label field--with-icon__label"
                    style={{
                      width: 'fit-content'
                    }}>{labelTextMin}</span>}
              {prefix && <span className={`field--icon relative top-1 ${minPrefixClass}`}>{prefix}</span>}
              <input type="number" id={`${meta}[min]`} min={0}
                     name={`mrange[${meta}][min]`}
                     onChange={e => this.handleMetaChange(e.target.value, `mrange[${meta}][min]`)}
                     placeholder={placeholderMin}
                     value={get(data, `mrange[${meta}][min]`) || ''}
                     onFocus={() => this.setState({ selectActive: true })}
                     onBlur={() => this.setState({ selectActive: false })}
              />
              {suffix && <span className={`field--icon ${minPrefixClass}`}>{suffix}</span>}
            </div>

            <div
              className={`field--with-icon flex items-center ${this.props.type === 'home' ? 'bg-white' : ''} ${selectClass1}`}>
              {this.props?.options?.remove_label_price !== 'yes' &&
              <span className="label field--with-icon__label"
                    style={{
                      width: 'fit-content'
                    }}>{labelTextMax}</span>}
              {prefix && <span className={`field--icon ${maxPrefixClass}`}>{prefix}</span>}
              <input type="number" id={`${meta}[max]`} min={1}
                     name={`mrange[${meta}][max]`}
                     onChange={e => this.handleMetaChange(e.target.value, `mrange[${meta}][max]`)}
                     placeholder={placeholderMax}
                     value={get(data, `mrange[${meta}][max]`) || ''}
                     onFocus={() => this.setState({ selectActive1: true })}
                     onBlur={() => this.setState({ selectActive1: false })}
              />
              {suffix && <span className={`field--icon ${maxPrefixClass}`}>{suffix}</span>}
            </div>

          </div>
        </div>
        }
        {this.props?.options?.pull_label_price === 'yes' &&
        <Fragment>
          <div className={`${this.props.type !== 'home' ? 'mt-18' : 'mt-10 w-full'}`}
               style={{
                 paddingLeft: this.props.options ? this.props.options.padding : 0,
                 paddingRight: this.props.options ? this.props.options.padding : 0,
               }}
          >
            {this.props.type !== 'home' && <label htmlFor={meta} className={labelClass}>{label}</label>}

            <div className="field--input__range flex justify-between w-full">
             <span className="field--with-icon__label"
                   style={{
                     width: 'fit-content'
                   }}>{labelTextMin}</span>
              <div
                className={`field--with-icon flex items-center ${this.props.type === 'home' ? 'bg-white' : ''} ${selectClass}`}>
                {prefix && <span className={`field--icon relative top-1 ${minPrefixClass}`}>{prefix}</span>}

                <input type="number" id={`${meta}[min]`} min={0}
                       name={`mrange[${meta}][min]`}
                       onChange={e => this.handleMetaChange(e.target.value, `mrange[${meta}][min]`)}
                       placeholder={placeholderMin}
                       value={get(data, `mrange[${meta}][min]`) || ''}
                       onFocus={() => this.setState({ selectActive: true })}
                       onBlur={() => this.setState({ selectActive: false })}
                />
                {suffix && <span className={`field--icon ${minPrefixClass}`}>{suffix}</span>}
              </div>
              <span className="field--with-icon__label" style={{
                width: 'fit-content'
              }}>{labelTextMax}</span>
              <div
                className={`field--with-icon flex items-center ${this.props.type === 'home' ? 'bg-white' : ''} ${selectClass1}`}>
                {prefix && <span className={`field--icon ${maxPrefixClass}`}>{prefix}</span>}
                <input type="number" id={`${meta}[max]`} min={1}
                       name={`mrange[${meta}][max]`}
                       onChange={e => this.handleMetaChange(e.target.value, `mrange[${meta}][max]`)}
                       placeholder={placeholderMax}
                       value={get(data, `mrange[${meta}][max]`) || ''}
                       onFocus={() => this.setState({ selectActive1: true })}
                       onBlur={() => this.setState({ selectActive1: false })}
                />
                {suffix && <span className={`field--icon ${maxPrefixClass}`}>{suffix}</span>}
              </div>

            </div>
          </div>
        </Fragment>
        }
      </div>
    );
  }
}

function mapStateToProps(state) {
  const { postsByUrl, searchData } = state;
  const {
    isFetching, lastUpdated, items: results,
  } = postsByUrl.RECEIVE_POSTS || {
    isFetching: true,
    results: [],
  };
  return {
    results,
    isFetching,
    lastUpdated,
    searchData,
  };
}

export default connect(mapStateToProps)(Meta);
