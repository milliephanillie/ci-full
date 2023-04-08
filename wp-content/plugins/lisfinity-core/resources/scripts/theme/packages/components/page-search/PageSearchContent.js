/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component, Fragment } from '@wordpress/element';
import { connect } from 'react-redux';
import { map, get, isEmpty } from 'lodash';
import Product from '../product-box/Product';
import { Scrollbars } from 'react-custom-scrollbars';
import LoaderSearchContent from '../loaders/LoaderSearchContent';
import PaginationA from '../partials/Pagination';
import PaginationFirstLast from '../partials/PaginationOld';

class PageSearchContent extends Component {
  constructor(props) {
    super(props);
    this.state = {
      loading: false,
      height: 600,
      productClasses: '',
      order: null,
    };
  }

  /**
   * Component has been mounted
   */
  componentDidMount() {
    this.contentHeight();
    this.productWidth();
    window.addEventListener('resize', this.contentHeight);
    window.addEventListener('resize', this.productWidth);
    window.addEventListener('resize', this.imageWidth);
    window.addEventListener('resize', this.fontSize);
  }

  /**
   * Just before a component has been unmounted
   */
  componentWillUnmount() {
    window.removeEventListener('resize', this.contentHeight);
    window.removeEventListener('resize', this.productWidth);
    window.removeEventListener('resize', this.imageWidth);
    window.removeEventListener('resize', this.fontSize);
  }

  /**
   * Calculate content height
   * ------------------------
   *
   * @param e
   */
  contentHeight = e => {
    const windowHeight = window.innerHeight;
    const headerHeight = document.querySelector('header').offsetHeight || 0;
    let filtersHeight = document.querySelector('.search--actions');
    filtersHeight = filtersHeight && filtersHeight.offsetHeight || 0;
    let breadcrumbHeight = document.querySelector('.search--breadcrumb');
    breadcrumbHeight = breadcrumbHeight && breadcrumbHeight.offsetHeight || 0;
    const chosenFiltersHeight = document.querySelector('.filters--chosen') ? document.querySelector('.filters--chosen').offsetHeight : 0;
    let loggedHeight = lc_data.logged_in ? 120 : 88;
    if (window.innerWidth < 640) {
      loggedHeight = lc_data.logged_in ? 234 : loggedHeight;
    }
    const height = windowHeight - headerHeight - filtersHeight - breadcrumbHeight - chosenFiltersHeight - loggedHeight;
    this.setState({ height });
  };

  /**
   * Width of the products content
   * -----------------------------
   *
   * @param e
   * @returns {string}
   */
  productWidth = () => {
    const filtersOpen  = this.props.filtersOpen;
    let productClasses = '';
    let  showMap  = this.props.showMap;
    productClasses += 'product-col mt-12 mb-12 px-10 pt-8 ';


    if (window.innerWidth > 2050) {
      productClasses += showMap === true ? 'w-1/3' : 'w-1/6';
    } else if (window.innerWidth > 1720 && window.innerWidth < 2050) {
      productClasses += showMap === true ? 'w-1/2' : 'w-1/4';
    } else if (window.innerWidth > 1460 && window.innerWidth < 1720) {
      productClasses += showMap === true ? 'w-1/2' : 'w-1/3';
    } else if (window.innerWidth > 1180 && window.innerWidth < 1460) {
      productClasses += showMap === true ? 'w-full' : 'w-1/3 ';
    } else if (window.innerWidth > 941 && window.innerWidth < 1180) {
      productClasses += showMap === true ? 'w-full' : 'w-1/2 ';
    } else if (window.innerWidth > 640 && window.innerWidth < 941) {
      productClasses += showMap || filtersOpen ? 'w-full' : 'w-1/2';
    } else if (window.innerWidth < 640 && (!filtersOpen && !showMap)) {
      productClasses += 'w-full';
    } else {
      productClasses += 'w-full';
    }

    return productClasses;
  };

  imageWidth = () => {
    let classes = 'h-product-2-thumb';
    if (window.innerWidth < 1460 && window.innerWidth > 1180 && (get(this.props, 'searchData') && false !== (get(this.props.searchData, 'showMap')))) {
      classes = 'h-product-2-thumb-smaller';
    }
    if (window.innerWidth < 1025 && window.innerWidth > 640) {
      classes = 'h-product-2-thumb-smaller';
    }

    return classes;
  };

  fontSize = () => {
    let classes = '';
    if (window.innerWidth < 1460 && window.innerWidth > 1180 && (get(this.props, 'searchData') && false !== (get(this.props.searchData, 'showMap')))) {
      classes = 'text-lg';
    }
    if (window.innerWidth < 1025 && window.innerWidth > 640) {
      classes = 'text-lg';
    }

    return classes;
  };

  /**
   * Renders the component.
   *
   * @return {Object}
   */
  render() {
    const { loading, height } = this.state;
    const { results, isFetching, filtersOpen, searchData } = this.props;
    let productClasses = this.productWidth();
    let imageClasses = this.imageWidth();
    let titleClasses = this.fontSize();

    return [
      ((isFetching) || this.props.filtersLoading) &&
      <LoaderSearchContent key={3} productClasses={productClasses}/>,

      <div key={1} id="lisfinityProducts" className="lisfinity-products -mx-10 w-auto overflow-y-hidden">
        {results && isEmpty(results.products) &&
        <div
          className="flex items-center px-20 h-44 bg-blue-100 border border-blue-300 text-blue-600 rounded">
          {lc_data.jst[656]}
        </div>
        }
        {this.props.options.different_scrollbars &&
        <Scrollbars ref="scrollbar" style={{ zIndex: 20 }} autoHide={false} autoHeight autoHeightMin={height}
                    renderTrackHorizontal={props => <div {...props} className="hidden"/>}
                    renderThumbHorizontal={props => <div {...props} className="hidden"/>}
                    renderTrackVertical={props => <div {...props}
                                                       className="track--vertical top-20 right-0 bottom-0 w-2"/>}
                    renderThumbVertical={props => <div {...props}
                                                       className="thumb--vertical bg-grey-600 rounded opacity-25"/>}>
          {(!isFetching && 'paginating' !== isFetching) &&
          <div className="relative row row--products mx-0 -mt-20 w-full overflow-y-hidden">
            {results
            && map(results.products, (post, index) => {
              return (
                <Product key={index} product={post} productClasses={productClasses} imageClasses={imageClasses}
                         showMap={this.props.showMap}
                         options={this.props.options}
                         titleClasses={titleClasses} fieldOptions={this.props.fieldOptions.fields || false}/>
              );
            })}
          </div>
          }

          {(!isFetching || isFetching === 'paginating') && results && results.max_num_pages > 1 && <div key={2}>
            <PaginationA
              scrollbar={this.refs.scrollbar}
              page={results.page}
              pages={results.max_num_pages}
              options={this.props.options}
              items={results.found_posts}
            />
            <PaginationFirstLast
              scrollbar={this.refs.scrollbar}
              page={results.page}
              pages={results.max_num_pages}
            />
          </div>}

        </Scrollbars>
        }
        {!this.props.options.different_scrollbars &&
        <Fragment>
          {(!isFetching && 'paginating' !== isFetching) &&
          <div className="relative row row--products mx-0 -mt-20 w-full overflow-y-hidden">
            {results
            && map(results.products, (post, index) => {
              return (
                <Product key={index} product={post} productClasses={productClasses} imageClasses={imageClasses}
                         showMap={this.props.showMap}
                         options={this.props.options}
                         titleClasses={titleClasses} fieldOptions={this.props.fieldOptions.fields || false}/>
              );
            })}
          </div>
          }

          {(!isFetching || isFetching === 'paginating') && results && results.max_num_pages > 1 && <div key={2}>
            <PaginationA
              scrollbar={this.refs.scrollbar}
              page={results.page}
              pages={results.max_num_pages}
              options={this.props.options}
              items={results.found_posts}
            />
            <PaginationFirstLast
              scrollbar={this.refs.scrollbar}
              page={results.page}
              pages={results.max_num_pages}
            />
          </div>
          }
        </Fragment>
        }
      </div>,

    ];
  }
}

function mapStateToProps(state) {
  const { postsByUrl, searchData, isFetching, fieldOptions } = state;
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
  };
}

export default connect(mapStateToProps)(PageSearchContent);
