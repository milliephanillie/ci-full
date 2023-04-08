/* global lc_data, React */
/**
 * Dependencies.
 */
import * as actions from '../../store/actions';
import {connect} from 'react-redux';
import {Component, Fragment} from '@wordpress/element';
import {_x} from '@wordpress/i18n';
import {map, isEmpty, get} from 'lodash';
import Select from 'react-select';
import Taxonomy from '../form-fields/taxonomy';
import {calculateFoundPosts, fetchPosts} from '../page-search/store/actions';
import cx from 'classnames';
import {Scrollbars} from 'react-custom-scrollbars';
import cogIcon from '../../../../../images/icons/cog.svg';
import spinnerIcon from '../../../../../images/icons/spinner-arrow.svg';
import searchIcon from '../../../../../images/icons/search.svg';
import Meta from '../form-fields/meta';
import ReactSVG from 'react-svg';
import LoaderIcon from '../../../../../images/icons/loader-rings-white.svg';
import {setIsDetailed, updateSearchData, updateSearchDataChosen} from '../../store/actions';
import {NavLink} from 'react-router-dom';

class SearchFiltersEl extends Component {
  constructor(props) {
    super(props);
    this.state = {
      loading: false,
      fields: {},
      fieldsOptions: {},
      groups: {},
      terms: {},
      fieldGroups: {},
      height: 600,
      filtersOpen: this.props.showFilters,
    };

    this.filtersWrapper = React.createRef();
  }

  /**
   * Before component is mounted
   * ---------------------------
   */
  componentWillMount() {
    this.setState({loading: true});
  }

  /**
   * As soon as component has received the props
   * -------------------------------------------
   *
   * @param nextProps
   * @param nextContext
   */
  componentWillReceiveProps(nextProps, nextContext) {
    if (this.props.data !== nextProps.data) {
      this.setState({data: nextProps.data});
    }
  }

  /**
   * Component has been mounted
   * --------------------------
   */
  componentDidMount() {
    const {fieldOptions} = this.props;

    const newFields = [];
    if (!isEmpty(fieldOptions.fields)) {
      map(fieldOptions.fields, (group, name) => {
        newFields[name] = group;
      });
    }
    this.setState({fieldGroups: fieldOptions.fieldGroups.sidebar});
    this.setState({fieldsOptions: fieldOptions.fields});
    this.setState({fields: newFields});
    this.setState({terms: fieldOptions.terms});
    this.setState({groups: fieldOptions.groups});
    this.setState({loading: false});

    this.contentHeight();
    window.addEventListener('resize', this.contentHeight);
  }

  componentWillUnmount() {
    window.removeEventListener('resize', this.contentHeight);
  }

  contentHeight = e => {
    const windowHeight = window.innerHeight;
    const headerHeight = document?.querySelector('header')?.offsetHeight || 0;
    let loggedHeight = lc_data.logged_in ? 70 : 38;
    const height = windowHeight - headerHeight - loggedHeight;
    this.setState({height});
  };

  /**
   * Handle the change of the category
   * ---------------------------------
   *
   * @param value
   * @param name
   */
  handleGroupChange = (value, name) => {
    const {dispatch} = this.props;
    let data = {...this.props.searchData};
    if (null === value || !isEmpty(data) && data[name] !== value) {
      map(data, (item, taxonomy) => {
        if (taxonomy.includes('tax[') || taxonomy.includes('range[') || taxonomy.includes('mrange[')) {
          delete data[taxonomy];
        }
      });
    }
    if (null === value) {
      data[name] = 'common';
    } else {
      data[name] = value;
    }
    if (data['keyword']) {
      delete data['keyword'];
    }
    const newData = {...data};
    dispatch(updateSearchData(newData));
    //dispatch(updateSearchDataChosen(newData));
    dispatch(calculateFoundPosts(newData));
  };

  /**
   * Reset search data on click
   * --------------------------
   *
   * @param e
   */
  handleReset = (e) => {
    const {dispatch, searchData} = this.props;
    const hasCommon = get(searchData, 'category-type');
    let data = {};
    if (hasCommon) {
      data['category-type'] = 'common';
    } else {
      data = {};
    }
    dispatch(updateSearchData(data));
    dispatch(updateSearchDataChosen(data));
    dispatch(fetchPosts({}));
  };

  /**
   * Renders the component.
   *
   * @return {Object}
   */
  render() {
    const {loading, type, results, searchData, onChange, filtersHandler, dispatch} = this.props;
    const {groups, fields, fieldsOptions, fieldGroups, terms, height} = this.state;
    const data = {...this.props.searchData};
    const filtersOpen = this.props.showFilters;
    const detailed = _x('detailed', 'endpoint', 'lisfinity-core');
    const options = [];
    {
      options.push({value: 'common', label: lc_data.jst[400]});
      !isEmpty(groups) && map(groups, (group, index) => {
        options.push({value: group.slug, label: group.plural_name});
      });
    }
    const customStyles = {
      control: () => ({}),
    };
    const dataClass = {...data};
    if (data['category-type'] === 'common') {
      delete dataClass['category-type'];
    }
    const resetClass = cx({
      'text-grey-300': isEmpty(dataClass),
      'text-green-800': !isEmpty(dataClass),
    });
    let chosenIndex = false;
    map(options, (option, index) => {
      if (option.value === data['category-type']) {
        chosenIndex = index;
      }
    });
    const selectClass = cx({
      'select-active': !isEmpty(data['category-type']) && 'common' !== data['category-type'],
      'focused': this.state.selectActive,
    });
    let filtersHeight = 600;
    if (this.props.page !== 'business') {
      filtersHeight = height;
    }
    if (this.props.page === 'business' && fieldGroups && fieldGroups[data['category-type']] && fieldGroups[data['category-type']].any.taxonomies.length <= 8) {
      filtersHeight = fieldGroups[data['category-type']].any.taxonomies.length * 70 + 280;
    }

    const titleText = this.props.settings?.filter_text ? this.props.settings.filter_text : lc_data.jst[470];
    const resetText = this.props.settings?.reset_text ? this.props.settings.reset_text : lc_data.jst[472];
    const buttonText = this.props.settings?.business_button_text ? this.props.settings.business_button_text : lc_data.jst[457];
    const buttonTextDetailed = this.props.settings?.d_button_text ? this.props.settings.d_button_text : lc_data.jst[9];


    return [
      <Fragment key={0}>
        <Scrollbars style={{zIndex: 20}} autoHide={false} autoHeight
                    autoHeightMin={filtersHeight}
                    renderTrackHorizontal={props => <div {...props} className="hidden"/>}
                    renderThumbHorizontal={props => <div {...props} className="hidden"/>}
                    renderTrackVertical={props => <div {...props}
                                                       className="track--vertical top-86 right-0 bottom-0 w-2"/>}
                    renderThumbVertical={props => <div {...props}
                                                       className="thumb--vertical bg-grey-600 rounded opacity-25"/>}>
          <div className="filters--wrapper px-14" ref={this.filtersWrapper}>
            <div
              className={`filters--header flex items-center justify-between ${this.props.page === 'business' ? '' : 'mt-20 pt-3'}`}>
              {window.innerWidth > 1030 &&
              <div className="filters--title flex-center">
                <div className="flex-center text-xl font-bold">
                  {this.props.page !== 'business' &&
                  <ReactSVG
                    src={`${lc_data.dir}dist/${cogIcon}`}
                    className={`mr-8 min-w-16 min-h-16 fill-filter-icon`}
                  />
                  }
                  {this.props.page === 'business'
                    ?
                    titleText
                    :
                    lc_data.jst[471]
                  }
                </div>
              </div>
              }
              <button type="button" className={`action--reset flex-center ${resetClass}`}
                      onClick={e => {
                        if (!isEmpty(dataClass)) {
                          this.handleReset(e);
                        }
                      }}>
                {!this.props.settings?.custom_reset_icon?.value &&
                <ReactSVG
                  src={`${lc_data.dir}dist/${spinnerIcon}`}
                  className={`mr-8 w-14 h-14 text-filter-icon ${isEmpty(dataClass) ? 'fill-grey-300' : 'fill-green-800'}`}
                />
                }
                {this.props.settings?.custom_reset_icon?.value && this.props.settings?.custom_reset_icon?.library !== 'svg' &&
                <i className={`mr-8 ${this.props.settings.custom_reset_icon.value} text-filter-icon`}></i>
                }
                {this.props.settings?.custom_reset_icon?.value?.url && this.props.settings?.custom_reset_icon?.library === 'svg' &&
                <ReactSVG
                  src={`${this.props.settings.custom_reset_icon.value.url}`}
                  className={`mr-8 w-14 h-14 text-filter-icon`}
                />
                }
                <span className="text-base reset-text font-light">{resetText}</span>
              </button>
            </div>
            <Fragment>
              {!isEmpty(groups) &&
              <div className="form-field mt-16 field--category-type">
                <label htmlFor="categoryType" className="filters--label">{lc_data.jst[473]}</label>
                <Select
                  name="category-type" id="categoryType"
                  className={selectClass}
                  onChange={selectedOption => this.handleGroupChange(selectedOption && selectedOption.value, 'category-type')}
                  placeholder={lc_data.jst[400]}
                  options={options}
                  value={options[chosenIndex]}
                  styles={customStyles}
                  onFocus={() => this.setState({selectActive: true})}
                  onBlur={() => this.setState({selectActive: false})}
                />
              </div>
              }
              {fieldGroups && !isEmpty(fieldGroups.common) && map(fieldGroups.common, (group, groupSlug) => {
                return (
                  'any' === groupSlug && (!data['category-type'] || 'common' === data['category-type']) &&
                  <div key={name} className="group">
                    {group.meta_fields && map(group.meta_fields, (meta, index) => {
                      return <Meta key={index} meta={meta} searchPage={true} data={data}
                                   fieldsOptions={fieldsOptions} group={groupSlug} category={data['category-type']}
                                   options={this.props?.options}/>;
                    })}
                    {group.taxonomies &&
                    <Taxonomy searchPage={true} data={data} taxonomies={group.taxonomies}
                              fieldsOptions={fieldsOptions} terms={terms} options={this.props?.options}/>
                    }
                  </div>
                );
              })}

              {fieldGroups && !isEmpty(fieldGroups.common) && map(fieldGroups.common, (group, groupSlug) => {
                return (
                  'any' !== groupSlug && (!data['category-type'] || 'common' === data['category-type']) &&
                  <div key={name} className="group">
                    {group.meta_fields && map(group.meta_fields, (meta, index) => {
                      return <Meta key={index} meta={meta} searchPage={true} data={data}
                                   fieldsOptions={fieldsOptions} group={groupSlug} category={data['category-type']}
                                   options={this.props?.options}/>;
                    })}
                    {group.taxonomies &&
                    <Taxonomy searchPage={true} data={data} taxonomies={group.taxonomies}
                              fieldsOptions={fieldsOptions} terms={terms} options={this.props?.options}/>
                    }
                  </div>
                );
              })}

              {fieldGroups && !isEmpty(fieldGroups[data['category-type']]) && 'common' !== data['category-type'] && map(fieldGroups[data['category-type']], (group, groupSlug) => {
                return (
                  'any' === groupSlug &&
                  <div key={groupSlug} className="group">
                    {group.meta_fields && map(group.meta_fields, (meta, index) => {
                      return <Meta key={index} meta={meta} searchPage={true} data={data}
                                   fieldsOptions={fieldsOptions} group={groupSlug} category={data['category-type']}
                                   options={this.props?.options}/>;
                    })}
                    {group.taxonomies &&
                    <Taxonomy searchPage={true} data={data} taxonomies={group.taxonomies}
                              fieldsOptions={fieldsOptions} terms={terms} options={this.props?.options}/>
                    }
                  </div>
                );
              })}

              {fieldGroups && !isEmpty(fieldGroups[data['category-type']]) && 'common' !== data['category-type'] && map(fieldGroups[data['category-type']], (group, groupSlug) => {
                return (
                  'any' !== groupSlug && <div key={groupSlug} className="group">
                    {group.meta_fields && map(group.meta_fields, (meta, index) => {
                      return <Meta key={index} meta={meta} searchPage={true} data={data}
                                   fieldsOptions={fieldsOptions} group={groupSlug} category={data['category-type']}
                                   options={this.props?.options}/>;
                    })}
                    {group.taxonomies &&
                    <Taxonomy searchPage={true} data={data} taxonomies={group.taxonomies}
                              fieldsOptions={fieldsOptions} terms={terms} options={this.props?.options}/>
                    }
                  </div>
                );
              })}
            </Fragment>
          </div>

          <div className="my-30 sm:my-0 px-14">
            <button
              type="button"
              className="flex-center btn--search mt-20 py-10 px-32 w-full bg-blue-700 border border-blue-700 rounded font-bold text-lg text-white hover:bg-blue-900 hover:border-blue-900"
              onClick={() => {
                dispatch(fetchPosts(searchData, false, true));
                dispatch(updateSearchDataChosen(searchData));
                if (window.innerWidth < 770) {
                  dispatch(actions.updateShowFilters(false));
                }
              }}
            >
              {!this.props.calculating &&
              <div className="flex-center relative pr-16">
                {!this.props.settings?.business_button_submit_icon?.value &&
                <ReactSVG src={`${lc_data.dir}dist/${searchIcon}`} className="mr-10 -ml-10 w-18 h-18 fill-white"/>
                }
                {this.props.settings?.business_button_submit_icon?.value && this.props.settings?.business_button_submit_icon?.library !== 'svg' &&
                <i className={`mr-8 ${this.props.settings.business_button_submit_icon.value} text-filter-icon`}></i>
                }
                {this.props.settings?.business_button_submit_icon?.value?.url && this.props.settings?.business_button_submit_icon?.library === 'svg' &&
                <ReactSVG
                  src={`${this.props.settings.business_button_submit_icon.value.url}`}
                  className={`mr-8 w-14 h-14 text-filter-icon`}
                />
                }
                {this.props?.options?.display_ads_count && buttonText}
                {this.props?.options?.display_ads_count &&
                <span className="absolute" style={{left: 'calc(100% - 10px)'}}>{(this.props.foundPosts || 0)}</span>}
                {!this.props?.options?.display_ads_count && buttonText}
              </div>
              }
              {this.props.calculating && !this.props?.options?.display_ads_count &&
              <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`} className="relative w-18 h-18 fill-white"
                        style={{
                          top: '-8px',
                          zoom: .8,
                          height: 30,
                        }}
              />}

            </button>
            {this.props.page === 'business' && data?.options?.detailed_search !== '0' &&
            <a
              href={`${lc_data.page_search_detailed}`}
              onClick={() => dispatch(setIsDetailed(true))}
              className="btn btn--light flex-center mt-16 py-10 px-16 font-normal"
            >
              {buttonTextDetailed}
            </a>
            }
            {this.props.calculating && this.props?.options?.display_ads_count &&
            <div className="flex-center relative pr-16">
              {!this.props.settings?.business_button_submit_icon?.value &&
              <ReactSVG src={`${lc_data.dir}dist/${searchIcon}`} className="mr-10 -ml-10 w-18 h-18 fill-white"/>
              }
              {this.props.settings?.business_button_submit_icon?.value && this.props.settings?.business_button_submit_icon?.library !== 'svg' &&
              <i className={`mr-8 ${this.props.settings.business_button_submit_icon.value} text-filter-icon`}></i>
              }
              {this.props.settings?.business_button_submit_icon?.value?.url && this.props.settings?.business_button_submit_icon?.library === 'svg' &&
              <ReactSVG
                src={`${this.props.settings.business_button_submit_icon.value.url}`}
                className={`mr-8 w-14 h-14 text-filter-icon`}
              />
              }
              {this.props?.options?.display_ads_count && buttonText}
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

          </div>

        </Scrollbars>
      </Fragment>,
    ];
  }
}

function mapStateToProps(state) {
  const {postsByUrl, searchData, isFetching, fieldOptions, foundPosts, calculating} = state;
  const {
    lastUpdated, items: results,
  } = postsByUrl.RECEIVE_POSTS || {
    results: [],
  };
  return {
    results,
    lastUpdated,
    searchData,
    isFetching,
    fieldOptions,
    foundPosts,
    calculating,
  };
}

export default connect(mapStateToProps)(SearchFiltersEl);
