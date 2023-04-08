/* global lc_data, React */
/**
 * External dependencies.
 */
import { connect, useSelector } from 'react-redux';
import { NavLink } from 'react-router-dom';
import { Fragment, useRef, useState } from '@wordpress/element';
import FiltersDetailed from './SearchFiltersDetailed';
import homeIcon from '../../../../../images/icons/home.svg';
import ReactSVG from 'react-svg';
import cogIcon from '../../../../../images/icons/cog.svg';
import searchIcon from '../../../../../images/icons/search.svg';
import LoaderIcon from '../../../../../images/icons/spinner.svg';
import LoaderSearchDetailed from '../loaders/LoaderSearchDetailed';
import { useEffect } from 'react';
import { useDispatch } from '@wordpress/data';

const SearchDetailed = (props) => {
  const data = useSelector(state => state);
  const { onChange, results } = props;
  const [sticky, setSticky] = useState(false);
  const stickyFilter = useRef();
  const dispatch = useDispatch();

  useEffect(() => {
    window.addEventListener('scroll', handleScroll);
    const body = document.getElementsByTagName("BODY")[0];
    if (body) {
      body.classList.add('is-detailed');
    }
    return () => {
      window.removeEventListener('scroll', handleScroll);
    };
  });

  const handleScroll = () => {
    if (pageYOffset > 400) {
      setSticky(true);
    } else {
      setSticky(false);
    }
  };

  return (
    <div className={`container mx-auto ${props.page === 'business' ? '' : 'pt-40 sm:pt-86 pb-86 sm:pb-128'}`}>
      {props.page !== 'business' && props.options.breadcrumbs &&
      <nav className="filters--breadcrumb p-36 pt-0">
        <ul className="flex items-center -mx-4">
          <li className="flex items-center px-2">
            <ReactSVG
              src={`${lc_data.dir}dist/${homeIcon}`}
              className="injectable mr-8 w-16 h-16 fill-icon-home"
            />
            <a href={lc_data.site_url} className="text-grey-900">{lc_data.jst[467]}</a>
          </li>
          <li className="px-2">
            <span className="mr-2">/</span>
            {props.page === 'business'
              ?
              <NavLink
                to={`${lc_data.site_url}${props.url}`}
                onClick={onChange}
                className="text-grey-900"
              >
                {lc_data.jst[633]}
              </NavLink>
              :
              <NavLink
                to={`${lc_data.site_url}${lc_data.page_search_endpoint}`}
                onClick={onChange}
                className="text-grey-900"
              >
                {lc_data.jst[469]}
              </NavLink>
            }
          </li>
        </ul>
      </nav>}
      <div key={0} className="filters filters--detailed bg-white rounded shadow">
        <Fragment>
          <div
            className="filters--header-wrapper">
            <div
              className="filters--header detailed flex flex-wrap items-center justify-between py-24 px-36 bg-white border-b border-grey-100">
              <div className="filters--title flex-center">
                <ReactSVG src={`${lc_data.dir}dist/${cogIcon}`} className="mr-10 -ml-10 w-18 h-18 fill-filter-icon"/>
                <span className="text-3xl font-semibold">{lc_data.jst[9]}</span>
              </div>
              <div className="filters--actions flex mt-20 w-full xs:mt-0 xs:ml-auto xs:w-auto">
                {props.page === 'business'
                  ?
                  <NavLink
                    to={`${lc_data.site_url}${props.url}`}
                    onClick={onChange}
                    className="flex-center mr-6 py-10 px-32 w-full bg-blue-700 border border-blue-700 rounded font-bold text-lg text-white xs:w-auto hover:bg-blue-900 hover:border-blue-900"
                  >
                    {!data.calculating &&
                    <div className="flex-center">
                      <ReactSVG src={`${lc_data.dir}dist/${searchIcon}`} className="mr-10 -ml-10 w-18 h-18 fill-white"/>
                      {props.options.display_ads_count && lc_data.jst[457]}
                      {props.options.display_ads_count &&
                      <span className="relative left-4">{(data.foundPosts || 0)}</span>}
                      {!props.options.display_ads_count && lc_data.jst[457]}
                    </div>
                    }
                    {data.calculating && !props.options.display_ads_count &&
                    <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`} className="relative w-18 h-18 fill-white"
                              style={{
                                top: -40,
                                zoom: .2,
                                height: 120,
                                left: -80,
                              }}
                    />
                    }
                    {data.calculating && props.options.display_ads_count &&
                    <div className="flex-center">
                      <ReactSVG src={`${lc_data.dir}dist/${searchIcon}`} className="mr-10 -ml-10 w-18 h-18 fill-white"/>
                      {props.options.display_ads_count && lc_data.jst[457]}
                      <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`} className="absolute w-18 h-18 fill-white"
                                style={{
                                  top: -13,
                                  zoom: .15,
                                  left: 'calc(100% - 40px)',
                                }}
                      />
                    </div>
                    }
                  </NavLink>
                  :
                  <NavLink
                    to={props.options.type ? `${lc_data.site_url}${lc_data.slug_category}/${props.options.type}` : `${lc_data.site_url}${lc_data.page_search_endpoint}`}
                    onClick={onChange}
                    className="flex-center mr-6 py-10 px-32 w-full bg-blue-700 border border-blue-700 rounded font-bold text-lg text-white xs:w-auto hover:bg-blue-900 hover:border-blue-900"
                  >
                    {!data.calculating &&
                    <div className="flex-center relative pr-16">
                      <ReactSVG src={`${lc_data.dir}dist/${searchIcon}`} className="mr-10 -ml-10 w-18 h-18 fill-white"/>
                      {props.options.display_ads_count && lc_data.jst[457]}
                      {props.options.display_ads_count &&
                      <span className="absolute" style={{ left: 'calc(100% - 10px)' }}>{(data.foundPosts || 0)}</span>}
                      {!props.options.display_ads_count && lc_data.jst[457]}
                    </div>
                    }
                    {data.calculating && !props.options.display_ads_count &&
                    <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`} className="relative w-18 h-18 fill-white"
                              style={{
                                top: -40,
                                zoom: .2,
                                height: 120,
                                left: -80,
                              }}
                    />
                    }
                    {data.calculating && props.options.display_ads_count &&
                    <div className="flex-center relative pr-16">
                      <ReactSVG src={`${lc_data.dir}dist/${searchIcon}`} className="mr-10 -ml-10 w-18 h-18 fill-white"/>
                      {props.options.display_ads_count && lc_data.jst[457]}
                      <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`} className="absolute w-18 h-18 fill-white"
                                style={{
                                  top: -13,
                                  zoom: .15,
                                  left: 'calc(100% - 60px)',
                                }}
                      />
                    </div>
                    }
                  </NavLink>
                }
              </div>
            </div>
          </div>
          {sticky && <div
            className="filters--header-sticky fixed container -mx-col">
            <div
              className="filters--header detailed flex flex-wrap items-center justify-between py-24 px-36 bg-white border-b border-grey-100 shadow-single-menu">
              <div className="filters--title flex-center">
                <ReactSVG src={`${lc_data.dir}dist/${cogIcon}`} className="mr-10 -ml-10 w-18 h-18 fill-filter-icon"/>
                <span className="text-3xl font-semibold">{lc_data.jst[9]}</span>
              </div>
              <div className="filters--actions flex mt-20 w-full xs:mt-0 xs:ml-auto xs:w-auto">
                {props.page === 'business'
                  ?
                  <NavLink
                    to={`${lc_data.site_url}${props.url}`}
                    onClick={onChange}
                    className="flex-center mr-6 py-10 px-32 w-full bg-blue-700 border border-blue-700 rounded font-bold text-lg text-white xs:w-auto hover:bg-blue-900 hover:border-blue-900"
                  >
                    {!props.calculating &&
                    <Fragment>
                      <ReactSVG src={`${lc_data.dir}dist/${searchIcon}`} className="mr-10 -ml-10 w-18 h-18 fill-white"/>
                      {lc_data.jst[457]}
                    </Fragment>
                    }
                    {props.calculating &&
                    <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`}
                              className="flex-center relative w-18 h-18 fill-white"
                              style={{
                                zoom: .8,
                                height: 30,
                                width: 114,
                              }}
                    />
                    }
                  </NavLink>
                  :
                  <NavLink
                    to={`${lc_data.site_url}${lc_data.page_search_endpoint}`}
                    onClick={onChange}
                    className="flex-center mr-6 py-10 px-32 w-full bg-blue-700 border border-blue-700 rounded font-bold text-lg text-white xs:w-auto hover:bg-blue-900 hover:border-blue-900"
                  >
                    {!props.calculating &&
                    <Fragment>
                      <ReactSVG src={`${lc_data.dir}dist/${searchIcon}`} className="mr-10 -ml-10 w-18 h-18 fill-white"/>
                      {lc_data.jst[457]}
                    </Fragment>
                    }
                    {props.calculating &&
                    <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`}
                              className="flex-center relative w-18 h-18 fill-white"
                              style={{
                                zoom: .8,
                                height: 30,
                                width: 114,
                              }}
                    />
                    }
                  </NavLink>
                }
              </div>
            </div>
          </div>
          }
        </Fragment>
        {(props.loading || !props.results) &&
        <div
          key={1}
          className="flex flex-wrap items-center justify-between py-24 px-36 border-b border-grey-100">
          <LoaderSearchDetailed/>
        </div>
        }
        {!props.loading && props.results &&
        <FiltersDetailed key={2} type="detailed" onChange={onChange} page={props.page} url={props.url}
                         loading={props.loading} options={props.options}/>}
      </div>
    </div>
  );
};

export default SearchDetailed;
