/* global lc_data, React */
/**
 * Dependencies.
 */
import { Component } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { map, filter, isEmpty } from 'lodash';
import cx from 'classnames';
import ReactSVG from 'react-svg';
import KeywordOld from '../form-fields/keywordOld';
import Meta from '../form-fields/meta';
import searchIcon from '../../../../../images/icons/search.svg';
import LoaderIcon from '../../../../../images/icons/loader-rings-white.svg';
import HomeTaxonomyOld from '../form-fields/HomeTaxonomyOld';
import axios from 'axios';
import { Fragment } from 'react';
import { connect } from 'react-redux';
import { dispatch } from '@wordpress/data';
import { setHomeFields } from '../../store/actions';

class HomeSearch extends Component {
  constructor(props) {
    super(props);
    this.state = {
      loading: true,
      waiting: false,
      fields: {},
      keyword: [],
      taxonomy: [],
      taxData: {},
      meta: [],
      terms: {},
      keywordValue: '',
      options: {},
      width: 75,
      columns: 100,
    };
  }

  /**
   * After React component has been mounted
   * --------------------------------------
   */
  componentDidMount() {
    this.getSettings();
    const id = document.getElementById('home-search');
    if (id) {
      const options = JSON.parse(id.dataset.options);
      this.setState({ options });
    }
    this.setWidth();
    window.addEventListener('resize', this.setWidth);
  }

  componentWillUnmount() {
    window.removeEventListener('resize', this.setWidth);
  }

  setWidth = () => {
    if (window.innerWidth > 770) {
      this.setState({ width: 75 });
      this.setState({ columns: 100 });
    } else {
      this.setState({ width: 100 });
      this.setState({ columns: this.state.options.columns });
    }
  };

  /**
   * Get available search builder settings
   * -------------------------------------
   */
  getSettings() {
    const { settings } = this.state;
    const { dispatch } = this.props;
    const keyword = [];
    const taxonomy = [];
    const meta = [];
    if (this.props?.searchData?.homeFields && !isEmpty(this.props.searchData.homeFields)) {
      const result = this.props.searchData.homeFields;

      this.setState({ fields: result.fields });
      map(result.fields.fields, field => {
        if (field !== 'title' && field !== 'description' && field !== 'category' && field !== 'author' && field !== 'type' && field !== 'sb-keyword' && field !== 'sb-taxonomy' && field !== 'sb-meta') {
          taxonomy.push(field);
        }
        keyword.push(field);
        if (field === 'category_type' || field === 'price') {
          meta.push(field);
        }
      });
      this.setState({ keyword });
      this.setState({ taxonomy });
      this.setState({ meta });

      this.setState({ terms: filter(result.terms, term => taxonomy.includes(term?.options?.slug)) });

      this.setState({ loading: false });
    } else {
      apiFetch({ path: `${lc_data.search_builder_fields}/home` }).then(result => {
        if (result && result.fields) {
          dispatch(setHomeFields(result));
          this.setState({ fields: result.fields });
          map(result.fields.fields, field => {
            if (field !== 'title' && field !== 'description' && field !== 'category' && field !== 'author' && field !== 'type' && field !== 'sb-keyword' && field !== 'sb-taxonomy' && field !== 'sb-meta') {
              taxonomy.push(field);
            }
            keyword.push(field);
            if (field === 'category_type' || field === 'price') {
              meta.push(field);
            }
          });
          this.setState({ keyword });
          this.setState({ taxonomy });
          this.setState({ meta });

          this.setState({ terms: result.terms });

          this.setState({ loading: false });
        }
      });
    }
  }

  keywordValueHandle = (value) => {
    this.setState({ keywordValue: value });
  };

  goToSingleAd = (id) => {
    this.setState({ loading: true });
    const headers = { 'X-WP-Nonce': lc_data.nonce };
    const data = {
      id,
      method: 'permalink',
    };
    axios({
      url: lc_data.get_product_method,
      method: 'POST',
      credentials: 'same-origin',
      headers,
      data,
    }).then(response => {
      if (response.success) {
        window.location.href = response.data.permalink;
      }
      this.setState({ loading: false });
    });
  };

  handleTaxonomy = (value) => {
    this.setState({ taxData: value });
  };

  /**
   * Renders the component.
   *
   * @return {Object}
   */
  render() {
    const {
      loading, fields, keyword, taxonomy, terms, meta, options, taxData,
    } = this.state;
    const fieldsStyle = options.fields_style || 1;
    const containerClass = cx({
      'flex-wrap': keyword.includes('sb-keyword') && isEmpty(taxonomy) && isEmpty(meta) && window.innerWidth < 640,
      'w-full flex-wrap': keyword.includes('sb-keyword') && !isEmpty(taxonomy) && !isEmpty(meta),
      'w-full flex-wrap bg:flex-no-wrap': !keyword.includes('sb-keyword') && !isEmpty(taxonomy) || keyword.includes('sb-keyword') && !isEmpty(taxonomy) && isEmpty(meta),
      'w-full': !keyword.includes('sb-keyword') || isEmpty(taxonomy),
    });
    const wrapperClass = cx({
      'w-full mb-10 mr-0': keyword.includes('sb-keyword') && isEmpty(taxonomy) && isEmpty(meta) && window.innerWidth < 640,
      'w-full mb-10': keyword.includes('sb-keyword') && !isEmpty(meta) && !isEmpty(taxonomy) || keyword.includes('sb-keyword') && isEmpty(meta) && !isEmpty(taxonomy),
      'w-full': !keyword.includes('sb-keyword') || isEmpty(taxonomy),
    });
    const btnClass = cx({
      'mb-10': !keyword.includes('sb-keyword') && isEmpty(taxonomy) || keyword.includes('sb-keyword') && !isEmpty(taxonomy) && isEmpty(meta),
      'mb-0': !keyword.includes('sb-keyword') || isEmpty(taxonomy),
    });
    const btnStyle = {
      paddingLeft: options.padding,
      paddingRight: options.padding,
    };
    if (fieldsStyle === '2') {
      btnStyle.position = 'absolute';
      btnStyle.left = '100%';
      btnStyle.bottom = 0;
    }
    return (
      <Fragment>
        {loading &&
        <div className="flex-center">
          <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`} className="relative min-w-20 min-h-20 w-20 h-20 fill-white"
                    style={{
                      top: '-13px',
                      left: '-10px',
                      width: '65px',
                    }}
          />
        </div>
        }
        {!loading &&
        <form key={1} action={lc_data.page_search}
              className={`relative ${fieldsStyle === '2' ? 'flex flex-wrap' : 'flex-center flex-wrap -mb-10'}`}
              style={{
                marginLeft: `-${options.padding}px`,
                marginRight: `-${options.padding}px`,
                width: fieldsStyle !== '2' ? '100%' : '80%',
              }}
        >
          {keyword.includes('sb-keyword') &&
          <div className={`mt-10`} style={{
            width: `${fieldsStyle !== '2' && this.state.columns === 100 ? this.state.width / options.columns : 100}%`,
            paddingLeft: options.padding,
            paddingRight: options.padding,
          }}>
            <KeywordOld keywordValueHandle={(value) => this.keywordValueHandle(value)} fields={fields}
                        searchValues={keyword}
                        options={options}
                        classname="mr-8"/>
          </div>
          }
          {!isEmpty(taxonomy) && !isEmpty(terms) &&
          <HomeTaxonomyOld fields={fields} terms={terms} type="home" options={options} columns={this.state.columns}
                           width={fieldsStyle === '2' ? 100 : this.state.width}
                           handleData={value => this.handleTaxonomy(value)}/>
          }
          {!isEmpty(meta) &&
          <Meta fields={fields} meta={meta} type="home" options={options} columns={this.state.columns}
                width={fieldsStyle === '2' ? 100 : this.state.width}/>
          }
          <div className={`mt-10 w-full md:w-1/4`} style={btnStyle}>
            <button
              type="submit"
              className={`btn btn-lg bg-blue-600 hover:bg-blue-800 w-full`}
              onClick={(e) => {
                this.setState({ waiting: true });
                if ('' !== this.state.keywordValue && !isNaN(this.state.keywordValue)) {
                  e.preventDefault();
                  this.goToSingleAd(this.state.keywordValue);
                }
                if (options?.to_custom_category && isEmpty(this.state.keywordValue) && !isEmpty(taxData)) {
                  e.preventDefault();
                  map(taxData, (term, tax) => {
                    window.location.href = `${lc_data.url}/${tax}/${term}`;
                  });
                }
              }}
              disabled={loading}
            >
              {!loading && !this.state.waiting &&
              <Fragment>
                <ReactSVG src={`${lc_data.dir}dist/${searchIcon}`}
                          className="mr-8 -ml-8 min-w-20 min-h-20 w-20 h-20 fill-white"/>
                {lc_data.jst[457]}
              </Fragment>
              }
              {(loading || this.state.waiting) &&
              <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`}
                        className="relative min-w-20 min-h-20 w-20 h-20 fill-white"
                        style={{
                          top: '-13px',
                          left: '-10px',
                          width: '64px',
                        }}
              />
              }
            </button>
          </div>
        </form>
        }
      </Fragment>
    );
  }
}

function mapStateToProps(state) {
  const { searchData } = state;
  return {
    searchData,
  };
}

export default connect(mapStateToProps)(HomeSearch);
