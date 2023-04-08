/* global lc_data, React */
/**
 * External dependencies.
 */
import { Fragment, useEffect, useRef, useState } from '@wordpress/element';
import { useDispatch, useSelector } from 'react-redux';
import { map, get, isEmpty } from 'lodash';
import Pagination from '../partials/Pagination';
import Product from '../product-box/Product';
import { Scrollbars } from 'react-custom-scrollbars';
import LoaderSearchContent from '../loaders/LoaderSearchContent';
import SearchMap from '../page-search/SearchMap';
import PaginationA from "../partials/Pagination";
import PaginationFirstLast from "../partials/PaginationOld";

const PageSearchContentEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const { searchData } = data;
  const stored = searchData;
  const [results, setResults] = useState({});
  const [loading, setLoading] = useState(false);
  const [height, setHeight] = useState(600);
  const [productClasses, setProductClasses] = useState('');
  const [imageClasses, setImageClasses] = useState('');
  const [titleClasses, setTitleClasses] = useState('');
  const [order, setOrder] = useState(null);
  const [settings, setSettings] = useState({});
  const wrapper = useRef(null);
  const scrollbar = useRef();

  useEffect(() => {
    window.addEventListener('resize', contentHeight);
    window.addEventListener('resize', productWidth);
    window.addEventListener('resize', imageWidth);
    window.addEventListener('resize', fontSize);

    const el = wrapper.current && wrapper.current.closest('.page-search-listings');
    if (el) {
      const settingsData = JSON.parse(el.dataset.options);
      setSettings(settingsData);
    }

    contentHeight();
    productWidth();
    fontSize();
    imageWidth();

    return () => {
      window.addEventListener('resize', contentHeight);
      window.addEventListener('resize', productWidth);
      window.addEventListener('resize', imageWidth);
      window.addEventListener('resize', fontSize);
    };
  }, []);

  useEffect(() => {
  setResults(data.postsByUrl.RECEIVE_POSTS?.items || {});
  }, [data.postsByUrl]);



  const contentHeight = e => {
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
    setHeight(height);
  };

  const productWidth = () => {
    const { showMap, filtersOpen } = searchData;
    let classes = '';
    classes += 'product-col mt-16 mb-16 px-14 ';

    if (window.innerWidth > 2050) {
      classes += showMap === true ? 'w-1/3' : 'w-1/6';
    } else if (window.innerWidth > 1720 && window.innerWidth < 2050) {
      classes += showMap === true ? 'w-1/2' : 'w-1/4';
    } else if (window.innerWidth > 1460 && window.innerWidth < 1720) {
      classes += showMap === true ? 'w-1/2' : 'w-1/3';
    } else if (window.innerWidth > 1180 && window.innerWidth < 1460) {
      classes += showMap === true ? 'w-full' : 'w-1/3 ';
    } else if (window.innerWidth > 941 && window.innerWidth < 1180) {
      classes += showMap === true ? 'w-full' : 'w-1/2 ';
    } else if (window.innerWidth > 640 && window.innerWidth < 941) {
      classes += showMap || filtersOpen ? 'w-full' : 'w-1/2';
    } else if (window.innerWidth < 640 && (!filtersOpen && !showMap)) {
      classes += 'w-full';
    } else {
      classes += 'w-full';
    }

    setProductClasses(classes);
  };

  const imageWidth = () => {
    let classes = 'h-product-2-thumb';
    if (window.innerWidth < 1460 && window.innerWidth > 1180 && searchData.showMap) {
      classes = 'h-product-2-thumb-smaller';
    }
    if (window.innerWidth < 1025 && window.innerWidth > 640) {
      classes = 'h-product-2-thumb-smaller';
    }

    setImageClasses(classes);
  };

  const fontSize = () => {
    let classes = '';
    if (window.innerWidth < 1460 && window.innerWidth > 1180 && searchData?.showMap) {
      classes = 'text-lg';
    }
    if (window.innerWidth < 1025 && window.innerWidth > 640) {
      classes = 'text-lg';
    }

    setTitleClasses(classes);
  };


  return [
    ((data.isFetching) || data.filtersLoading) &&
    <LoaderSearchContent key={1} productClasses={productClasses}/>,

    <div className="flex" key={2}>
      <div
        className={`lisfinity-products ${(settings?.display_map === 'on' && searchData?.showMap) ? 'w-1/2 map-active' : 'w-full'}`}
        ref={wrapper}>
        {results && isEmpty(results.products) &&
        <div
          className="flex items-center px-20 bg-blue-100 border border-blue-300 text-blue-600 rounded">
          {lc_data.jst[656]}
        </div>
        }
        {settings.full_content_height === 'yes' &&
        <Scrollbars ref={scrollbar} style={{ zIndex: 20 }} autoHide={false} autoHeight autoHeightMin={height}
                    renderTrackHorizontal={props => <div {...props} className="hidden"/>}
                    renderThumbHorizontal={props => <div {...props} className="hidden"/>}
                    renderTrackVertical={props => <div {...props}
                                                       className="track--vertical top-20 right-0 bottom-0 w-2"/>}
                    renderThumbVertical={props => <div {...props}
                                                       className="thumb--vertical bg-grey-600 rounded opacity-25"/>}
                    universal={true}
        >
          {(!data.isFetching && 'paginating' !== data.isFetching) &&
          <div className="relative row row--products -mx-col w-full overflow-y-hidden">
            {results
            && map(results.products, (post, index) => {
              return (
                <Product key={index}
                         product={post}
                         productClasses={productClasses}
                         imageClasses={imageClasses}
                         titleClasses={titleClasses}
                         fieldOptions={searchData?.fieldOptions?.fields || false}
                         style={settings?.style}
                         settings={settings}
                         type="elementor"
                         options={settings}
                />
              );
            })}
          </div>
          }

          {(!data.isFetching || data.isFetching === 'paginating') && results && results.max_num_pages > 1 &&
          <div key={2}>
            <PaginationA
              scrollbar={scrollbar}
              page={results.page}
              pages={results.max_num_pages}
              options={props.options}
              items={results.found_posts}
            />
            <PaginationFirstLast
              scrollbar={scrollbar}
              page={results.page}
              pages={results.max_num_pages}
            />
          </div>}

        </Scrollbars>
        }
        {settings.full_content_height !== 'yes' &&
        <Fragment>
          {(!data.isFetching && 'paginating' !== data.isFetching) &&
          <div className="relative row row--products -mx-col -mt-20 w-full overflow-y-hidden">
            {results
            && map(results.products, (post, index) => {
              return (
                <Product key={index}
                         product={post}
                         productClasses={productClasses}
                         imageClasses={imageClasses}
                         options={searchData.options}
                         titleClasses={titleClasses}
                         fieldOptions={searchData.fieldOptions.fields || false}
                         style={settings?.style}
                         type="elementor"
                />
              );
            })}
          </div>
          }

          {(!data.isFetching || data.isFetching === 'paginating') && results && results.max_num_pages > 1 &&
          <div key={2}>
            <PaginationA
              scrollbar={scrollbar}
              page={results.page}
              pages={results.max_num_pages}
              options={props.options}
              items={results.found_posts}
            />
            <PaginationFirstLast
              scrollbar={scrollbar}
              page={results.page}
              pages={results.max_num_pages}
            />
          </div>
          }
        </Fragment>
        }
      </div>
      {(settings.display_map === 'on') &&
      <SearchMap results={results} classes="w-1/2"/>
      }
    </div>,
  ];
};

export default PageSearchContentEl;
