/* global lc_data, React */
/**
 * External dependencies.
 */
import { useSelector } from 'react-redux';
import { NavLink } from 'react-router-dom';
import { Fragment, useRef, useState } from '@wordpress/element';
import homeIcon from '../../../../../images/icons/home.svg';
import ReactSVG from 'react-svg';
import cogIcon from '../../../../../images/icons/cog.svg';
import searchIcon from '../../../../../images/icons/search.svg';
import LoaderIcon from '../../../../../images/icons/loader-rings-white.svg';
import LoaderSearchDetailed from '../loaders/LoaderSearchDetailed';
import { useEffect } from 'react';
import { useDispatch } from '@wordpress/data';
import SearchFiltersDetailedEl from './SearchFiltersDetailedEl';

const SearchDetailedEl = (props) => {
  const data = useSelector(state => state);
  const { searchData } = data;
  const stored = searchData;
  const { onChange, results } = props;
  const stickyFilter = useRef();
  const dispatch = useDispatch();
  const [sticky, setSticky] = useState(false);
  const [options, setOptions] = useState([]);
  const [settings, setSettings] = useState({});
  const [items, setItems] = useState({});

  const wrapper = useRef(null);

  useEffect(() => {
    window.addEventListener('scroll', handleScroll);

    const el = wrapper.current && wrapper.current.closest('#page-search-detailed-elementor');
    const elOther = wrapper.current && wrapper.current.closest('#page-search-detailed');
    if (el) {
      const settingsData = JSON.parse(el.dataset.options);
      setSettings(settingsData);
    }
    if (elOther) {
      const settingsData = JSON.parse(elOther.dataset.options);
      setSettings(settingsData);
    }

    return () => {
      window.removeEventListener('scroll', handleScroll);
    };
  }, []);

  useEffect(() => {
    setItems(data.postsByUrl.RECEIVE_POSTS?.items || {});
  }, [data.postsByUrl]);

  const handleScroll = () => {
    if (pageYOffset > 400) {
      setSticky(true);
    } else {
      setSticky(false);
    }
  };

  let icon = null;
  let svg = null;

  if (settings?.icon !== null && settings?.icon) {
    typeof settings.icon['value'] === 'string' ? icon = settings.icon['value'] : svg = settings.icon['value']['url'];
  }

  let iconStickyHeader = null;
  let svgStickyHeader = null;

  if (settings?.icon_sticky_header !== null && settings?.icon_sticky_header) {
    typeof settings.icon_sticky_header['value'] === 'string' ? iconStickyHeader = settings.icon_sticky_header['value'] : svgStickyHeader = settings.icon_sticky_header['value']['url'];
  }

  let iconHeaderButton = null;
  let svgHeaderButton = null;

  if (settings?.button_submit_icon_header_button !== null && settings?.button_submit_icon_header_button) {
    typeof settings.button_submit_icon_header_button['value'] === 'string' ? iconHeaderButton = settings.button_submit_icon_header_button['value'] : svgHeaderButton = settings.button_submit_icon_header_button['value']['url'];
  }

  let iconStickyHeaderButton = null;
  let svgStickyHeaderButton = null;

  if (settings?.button_submit_icon_sticky_header_button !== null && settings?.button_submit_icon_sticky_header_button) {
    typeof settings.button_submit_icon_sticky_header_button['value'] === 'string' ? iconStickyHeaderButton = settings.button_submit_icon_sticky_header_button['value'] : svgStickyHeaderButton = settings.button_submit_icon_sticky_header_button['value']['url'];
  }

  const btnText = settings?.button_text_header_button ? settings.button_text_header_button : lc_data.jst[457];

  const btnTextStickyHeader = settings?.button_text_sticky_header_button ? settings.button_text_sticky_header_button : lc_data.jst[457];

  return (
    <div className={`container mx-auto ${props.page === 'business' ? '' : 'pt-40 sm:pt-86 pb-86 sm:pb-128'}`}
         ref={wrapper}>
      <div key={0} className="filters filters--detailed bg-white rounded shadow">
        <Fragment>
          <div
            className="filters--header-wrapper">
            <div
              className="filters--header detailed flex flex-wrap items-center justify-between py-24 px-36 bg-white border-b border-grey-100">
              <div className="filters--title flex-center">
                {(icon === null && svg === null || '' == icon) &&
                <ReactSVG src={`${lc_data.dir}dist/${cogIcon}`}
                          className="mr-10 -ml-10 w-18 h-18 fill-filter-icon search-detailed-title-icon"/>
                }
                {
                  svg && settings.use_custom_icon !== '' &&
                  <img src={svg} alt="cart-icon"
                       className="mr-10 -ml-10 w-18 h-18 fill-filter-icon search-detailed-title-icon"/>
                }
                {
                  settings.use_custom_icon !== '' && icon &&
                  <i className={`${icon} mr-10 -ml-10 w-18 h-18 fill-filter-icon search-detailed-title-icon`} style={{
                    display: 'flex',
                    alignSelf: 'center'
                  }}
                     aria-hidden="true"
                  ></i>
                }
                <span
                  className="text-3xl font-semibold">{settings?.filter_text === null || settings?.filter_text === '' ? lc_data.jst[9] : settings.filter_text}</span>
              </div>
              <div className="filters--actions flex mt-20 w-full xs:mt-0 xs:ml-auto xs:w-auto">
                {props.page === 'business'
                &&
                <a
                  href={`${lc_data.site_url}${props.url}`}
                  className="flex-center header-search-button mr-6 py-10 px-32 w-full bg-blue-700 border border-blue-700 rounded font-bold text-lg text-white xs:w-auto hover:bg-blue-900 hover:border-blue-900"
                >
                  {!data.calculating &&
                  <div className="flex-center">
                    {(iconHeaderButton === null && svgHeaderButton === null || '' == iconHeaderButton) &&
                    <ReactSVG src={`${lc_data.dir}dist/${searchIcon}`}
                              className="mr-10 -ml-10 w-18 h-18 header-search-button-icon fill-white"/>
                    }
                    {
                      svgHeaderButton && settings.use_custom_button_icon_header_button !== '' &&
                      <img src={svgHeaderButton} alt="cart-icon"
                           className="mr-10 -ml-10 w-18 h-18 fill-filter-icon header-search-button-icon"/>
                    }
                    {
                      settings.use_custom_button_icon_header_button !== '' && iconHeaderButton &&
                      <i
                        className={`${iconHeaderButton} mr-10 -ml-10 w-18 h-18 fill-filter-icon header-search-button-icon`}
                        style={{
                          display: 'flex',
                          alignSelf: 'center'
                        }}
                        aria-hidden="true"
                      ></i>
                    }
                    {data.options.display_ads_count && btnText}
                    {data.options.display_ads_count &&
                    <span className="relative left-4">{(data.foundPosts || 0)}</span>}
                    {!data.options.display_ads_count && btnText}
                  </div>
                  }
                  {data.calculating && !data.options.display_ads_count &&
                  <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`}
                            className="flex-center relative w-18 h-18 fill-white"
                            style={{
                              zoom: .8,
                              height: 30,
                              width: 114,
                            }}
                  />
                  }

                  {data.calculating && data.options.display_ads_count &&
                  <div className="flex-center">
                    {(iconHeaderButton === null && svgHeaderButton === null || '' == iconHeaderButton) &&
                    <ReactSVG src={`${lc_data.dir}dist/${searchIcon}`}
                              className="mr-10 -ml-10 w-18 h-18 fill-white header-search-button-icon"/>
                    }
                    {
                      svgHeaderButton && settings.use_custom_button_icon_header_button !== '' &&
                      <img src={svgHeaderButton} alt="cart-icon"
                           className="mr-10 -ml-10 w-18 h-18 fill-filter-icon header-search-button-icon"/>
                    }
                    {
                      settings.use_custom_button_icon_header_button !== '' && iconHeaderButton &&
                      <i className={`${iconHeaderButton} mr-10 -ml-10 w-18 h-18 fill-white header-search-button-icon`}
                         style={{
                           display: 'flex',
                           alignSelf: 'center'
                         }}
                         aria-hidden="true"
                      ></i>
                    }
                    {data.options.display_ads_count && lc_data.jst[457]}
                    <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`} className="relative w-18 h-18 fill-white"
                              style={{
                                top: '-8px',
                                zoom: .6,
                                height: 30,
                                left: -6,
                              }}
                    />

                  </div>
                  }
                </a>
                }
                {props.page !== 'business' &&
                <a
                  href={data.options?.type ? `${lc_data.site_url}${lc_data.slug_category}/${data.options.type}` : `${lc_data.site_url}${lc_data.page_search_endpoint}`}
                  className="flex-center mr-6 py-10 px-32 w-full bg-blue-700 border border-blue-700 header-search-button rounded font-bold text-lg text-white xs:w-auto hover:bg-blue-900 hover:border-blue-900"
                >
                  {!data.calculating &&
                  <div className="flex-center relative pr-16">
                    {(iconHeaderButton === null && svgHeaderButton === null || '' == iconHeaderButton) &&
                    <ReactSVG src={`${lc_data.dir}dist/${searchIcon}`}
                              className="mr-10 -ml-10 w-18 h-18 fill-white header-search-button-icon"/>
                    }
                    {
                      svgHeaderButton && settings.use_custom_button_icon_header_button !== '' &&
                      <img src={svgHeaderButton} alt="cart-icon"
                           className="mr-10 -ml-10 w-18 h-18 fill-filter-icon header-search-button-icon"/>
                    }
                    {
                      settings.use_custom_button_icon_header_button !== '' && iconHeaderButton &&
                      <i className={`${iconHeaderButton} mr-10 -ml-10 w-18 h-18 fill-white header-search-button-icon`}
                         style={{
                           display: 'flex',
                           alignSelf: 'center'
                         }}
                         aria-hidden="true"
                      ></i>
                    }
                    {data.options?.display_ads_count && btnText}
                    {data.options?.display_ads_count &&
                    <span className="absolute"
                          style={{ left: 'calc(100% - 10px)' }}>{(data.foundPosts || 0)}</span>}
                    {!data.options?.display_ads_count && btnText}
                  </div>
                  }
                  {data.calculating && !data.options.display_ads_count &&
                  <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`}
                            className="flex-center relative w-18 h-18 fill-white"
                            style={{
                              zoom: .8,
                              height: 30,
                              width: 114,
                            }}
                  />
                  }
                  {data.calculating && data.options.display_ads_count &&
                  <div className="flex-center relative pr-16">
                    {(iconHeaderButton === null && svgHeaderButton === null || '' == iconHeaderButton) &&
                    <ReactSVG src={`${lc_data.dir}dist/${searchIcon}`}
                              className="mr-10 -ml-10 w-18 h-18 fill-white header-search-button-icon"/>
                    }
                    {
                      svgHeaderButton && settings.use_custom_button_icon_header_button !== '' &&
                      <img src={svgHeaderButton} alt="cart-icon"
                           className="mr-10 -ml-10 w-18 h-18 fill-filter-icon header-search-button-icon"/>
                    }
                    {
                      settings.use_custom_button_icon_header_button !== '' && iconHeaderButton &&
                      <i className={`${iconHeaderButton} mr-10 -ml-10 w-18 h-18 fill-white header-search-button-icon`}
                         style={{
                           display: 'flex',
                           alignSelf: 'center'
                         }}
                         aria-hidden="true"
                      ></i>
                    }
                    {data.options.display_ads_count && lc_data.jst[457]}
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
                </a>
                }
              </div>
            </div>
          </div>
          {sticky && <div
            className="filters--header-sticky fixed container -mx-col" style={{
            top: '0'
          }}>
            <div
              className="filters--header detailed flex flex-wrap items-center justify-between py-24 px-36 bg-white border-b border-grey-100 shadow-single-menu">
              <div className="filters--title flex-center">
                {(iconStickyHeader === null && svgStickyHeader === null || '' == iconStickyHeader) &&
                <ReactSVG src={`${lc_data.dir}dist/${cogIcon}`}
                          className="mr-10 -ml-10 w-18 h-18 sticky-header-title-icon fill-filter-icon"/>
                }
                {
                  svgStickyHeader && settings.use_custom_icon_sticky_header !== '' &&
                  <img src={svgStickyHeader} alt="cart-icon"
                       className="mr-10 -ml-10 w-18 h-18 fill-filter-icon sticky-header-title-icon"/>
                }
                {
                  settings.use_custom_icon_sticky_header !== '' && iconStickyHeader &&
                  <i className={`${iconStickyHeader} mr-10 -ml-10 w-18 h-18 fill-filter-icon sticky-header-title-icon`}
                     style={{
                       display: 'flex',
                       alignSelf: 'center'
                     }}
                     aria-hidden="true"
                  ></i>
                }
                <span
                  className="text-3xl font-semibold">{settings?.filter_text_sticky_header === null || settings?.filter_text_sticky_header === '' ? lc_data.jst[9] : settings.filter_text_sticky_header}</span>
              </div>
              <div className="filters--actions flex mt-20 w-full xs:mt-0 xs:ml-auto xs:w-auto">
                {props.page === 'business'
                &&
                <a
                  href={`${lc_data.site_url}${props.url}`}
                  className="flex-center mr-6 py-10 sticky-header-search-button px-32 w-full bg-blue-700 border border-blue-700 rounded font-bold text-lg text-white xs:w-auto hover:bg-blue-900 hover:border-blue-900"
                >
                  {!data.calculating &&
                  <div className="flex-center">
                    {(iconStickyHeaderButton === null && svgStickyHeaderButton === null || '' == iconStickyHeaderButton) &&
                    <ReactSVG src={`${lc_data.dir}dist/${searchIcon}`}
                              className="mr-10 -ml-10 w-18 h-18 sticky-header-search-button-icon fill-white"/>
                    }
                    {
                      svgStickyHeaderButton && settings.use_custom_button_icon_header_button !== '' &&
                      <img src={svgStickyHeaderButton} alt="cart-icon"
                           className="mr-10 -ml-10 w-18 h-18 fill-filter-icon sticky-header-search-button-icon"/>
                    }
                    {
                      settings.use_custom_button_icon_header_button !== '' && iconStickyHeaderButton &&
                      <i
                        className={`${iconStickyHeaderButton} mr-10 -ml-10 w-18 h-18 fill-white sticky-header-search-button-icon`}
                        style={{
                          display: 'flex',
                          alignSelf: 'center'
                        }}
                        aria-hidden="true"
                      ></i>
                    }
                    {data.options.display_ads_count && btnTextStickyHeader}
                    {data.options.display_ads_count &&
                    <span className="relative left-4">{(data.foundPosts || 0)}</span>}
                    {!data.options.display_ads_count && btnTextStickyHeader}
                  </div>
                  }
                  {data.calculating && !data.options.display_ads_count &&
                  <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`}
                            className="flex-center relative w-18 h-18 fill-white"
                            style={{
                              zoom: .8,
                              height: 30,
                              width: 114,
                            }}
                  />
                  }
                  {data.calculating && data.options.display_ads_count &&
                  <div className="flex-center">
                    {(iconStickyHeaderButton === null && svgStickyHeaderButton === null || '' == iconStickyHeaderButton) &&
                    <ReactSVG src={`${lc_data.dir}dist/${searchIcon}`}
                              className="mr-10 -ml-10 w-18 h-18 sticky-header-search-button-icon fill-white"/>
                    }
                    {
                      svgStickyHeaderButton && settings.use_custom_button_icon_header_button !== '' &&
                      <img src={svgStickyHeaderButton} alt="cart-icon"
                           className="mr-10 -ml-10 w-18 h-18 fill-filter-icon sticky-header-search-button-icon"/>
                    }
                    {
                      settings.use_custom_button_icon_header_button !== '' && iconStickyHeaderButton &&
                      <i
                        className={`${iconStickyHeaderButton} mr-10 -ml-10 w-18 h-18 fill-white sticky-header-search-button-icon`}
                        style={{
                          display: 'flex',
                          alignSelf: 'center'
                        }}
                        aria-hidden="true"
                      ></i>
                    }
                    {data.options.display_ads_count && lc_data.jst[457]}
                    <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`} className="relative w-18 h-18 fill-white"
                              style={{
                                top: '-8px',
                                zoom: .6,
                                height: 30,
                                left: -6,
                              }}
                    />
                  </div>
                  }
                </a>
                }
                {props.page !== 'business' &&
                <a
                  href={data.options.type ? `${lc_data.site_url}${lc_data.slug_category}/${data.options.type}` : `${lc_data.site_url}${lc_data.page_search_endpoint}`}
                  className="flex-center sticky-header-search-button mr-6 py-10 px-32 w-full bg-blue-700 border border-blue-700 rounded font-bold text-lg text-white xs:w-auto hover:bg-blue-900 hover:border-blue-900"
                >
                  {!data.calculating &&
                  <div className="flex-center relative pr-16">
                    {(iconStickyHeaderButton === null && svgStickyHeaderButton === null || '' == iconStickyHeaderButton) &&
                    <ReactSVG src={`${lc_data.dir}dist/${searchIcon}`}
                              className="mr-10 -ml-10 w-18 h-18 sticky-header-search-button-icon fill-white"/>
                    }
                    {
                      svgStickyHeaderButton && settings.use_custom_button_icon_header_button !== '' &&
                      <img src={svgStickyHeaderButton} alt="cart-icon"
                           className="mr-10 -ml-10 w-18 h-18 fill-filter-icon sticky-header-search-button-icon"/>
                    }
                    {
                      settings.use_custom_button_icon_header_button !== '' && iconStickyHeaderButton &&
                      <i
                        className={`${iconStickyHeaderButton} mr-10 -ml-10 w-18 h-18 fill-white sticky-header-search-button-icon`}
                        style={{
                          display: 'flex',
                          alignSelf: 'center'
                        }}
                        aria-hidden="true"
                      ></i>
                    }
                    {data.options.display_ads_count && btnTextStickyHeader}
                    {data.options.display_ads_count &&
                    <span className="absolute"
                          style={{ left: 'calc(100% - 10px)' }}>{(data.foundPosts || 0)}</span>}
                    {!data.options.display_ads_count && btnTextStickyHeader}
                  </div>
                  }
                  {data.calculating && !data.options.display_ads_count &&
                  <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`}
                            className="flex-center relative w-18 h-18 fill-white"
                            style={{
                              zoom: .8,
                              height: 30,
                              width: 114,
                            }}
                  />
                  }
                  {data.calculating && data.options.display_ads_count &&
                  <div className="flex-center relative pr-16">
                    {(iconStickyHeaderButton === null && svgStickyHeaderButton === null || '' == iconStickyHeaderButton) &&
                    <ReactSVG src={`${lc_data.dir}dist/${searchIcon}`}
                              className="mr-10 -ml-10 w-18 h-18 sticky-header-search-button-icon fill-white"/>
                    }
                    {
                      svgStickyHeaderButton && settings.use_custom_button_icon_header_button !== '' &&
                      <img src={svgStickyHeaderButton} alt="cart-icon"
                           className="mr-10 -ml-10 w-18 h-18 fill-filter-icon sticky-header-search-button-icon"/>
                    }
                    {
                      settings.use_custom_button_icon_header_button !== '' && iconStickyHeaderButton &&
                      <i
                        className={`${iconStickyHeaderButton} mr-10 -ml-10 w-18 h-18 fill-white sticky-header-search-button-icon`}
                        style={{
                          display: 'flex',
                          alignSelf: 'center'
                        }}
                        aria-hidden="true"
                      ></i>
                    }
                    {data.options.display_ads_count && lc_data.jst[457]}
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
                </a>
                }
              </div>
            </div>
          </div>
          }
        </Fragment>
        {(data.isFetching || !items) &&
        <div
          key={1}
          className="flex flex-wrap items-center justify-between py-24 px-36 border-b border-grey-100">
          <LoaderSearchDetailed/>
        </div>
        }
        {!data.isFetching && items &&
        <SearchFiltersDetailedEl
          key={2} type="detailed" onChange={onChange} page={props.page} url={props.url}
          loading={props.loading} options={data.options}/>
        }
      </div>
    </div>
  );
};

export default SearchDetailedEl;
