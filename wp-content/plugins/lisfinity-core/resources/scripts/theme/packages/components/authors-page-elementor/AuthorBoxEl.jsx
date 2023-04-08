/* global lc_data, React */
/**
 * External dependencies.
 */
import { useEffect, useRef, useState } from '@wordpress/element';
import { useDispatch, useSelector } from 'react-redux';
import { map } from 'lodash';
import ReactSVG from 'react-svg';
import starIcon from '../../../../../images/icons/star.svg';
import mapMarkerIcon from '../../../../../images/icons/map-marker.svg';
import axios from 'axios';
import produce from 'immer';
import { setVendors, setVendorsFL, setVendorsLoading } from '../../store/actions';
import LoaderBusinessArchiveQuery from '../business-archive/LoaderBusinessArchiveQuery';

const AuthorBoxEl = (props) => {
  const state = useSelector(state => state);
  const { vendors } = state;
  const dispatch = useDispatch();
  const wrapper = useRef(null);
  const [data, setData] = useState(false);
  const [firstLoad, setFirstLoad] = useState(true);
  const [settings, setSettings] = useState(true);
  const [vendorsArray, setVendorsArray] = useState([]);

  useEffect(() => {
    const el = wrapper.current && wrapper.current.closest('.elementor-author-box');

    if (el) {
      const settingsData = JSON.parse(el.dataset.settings);
      setSettings(settingsData);
    }
  }, [data]);

  const fetchData = (allData = true, paginationPage = 1) => {
    dispatch(setVendorsLoading(true));
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const url = lc_data.business_archive;
    const fetchData = {
      page_id: lc_data.page_id,
      page: paginationPage,
    };
    axios({
      credentials: 'same-origin',
      headers,
      method: 'get',
      url,
      params: fetchData,
    }).then(result => {
      if (!allData) {
        const nextState = produce(vendors, draft => {
          draft.vendors = result.data.vendors;
        });
        setData(nextState);
        dispatch(setVendors(nextState));
      } else {
        setData(result.data);
        setFirstLoad(false);
        dispatch(setVendorsFL(false));
        dispatch(setVendors(result.data));
      }
      dispatch(setVendorsLoading(false));
    });
  };

  useEffect(() => {
    fetchData();
  }, []);

  let vendorsId = [];

  if (settings && settings.profiles_handpicked) {
    settings.profiles_handpicked.map(element => {
      vendorsId.push(element['profile_id']);
    });
  }

  useEffect(() => {
    let vendorArray = [];
    {
      vendors?.vendors && settings.display_handpicked_authors === 'yes' &&
      Object.keys(vendors.vendors.query).map(function (key, index) {
        if (vendorsId.includes(key)) {
          vendorArray.push(
            <div key={key} className="mb-20 w-full sm:w-1/2 lg:w-1/3" style={{ padding: '0 8px 0 8px' }}>
              <article
                className="vendor flex flex-wrap sm:flex-no-wrap items-center p-20 bg-white rounded shadow-theme">
                {vendors.vendors.query[key].thumbnail &&
                <figure
                  className="profile--thumbnail flex-center mr-20 p-10 border-grey-100 rounded-2xl overflow-hidden bg:mb-20 lg:mb-0"
                >
                  <a href={vendors.vendors.query[key].url}>
                    <img src={vendors.vendors.query[key].thumbnail} alt={vendors.vendors.query[key].title}/>
                  </a>
                </figure>
                }

                <div className="vendor--content">
                  <h6 className="text-lg">
                    <a href={vendors.vendors.query[key].url}>
                      {vendors.vendors.query[key].title}
                    </a>
                  </h6>

                  <div className="flex items-center mt-10">
                    {settings.display_info_mark === 'yes' &&
                    <div className="lisfinity-product--info flex-center mr-22">
                                <span className="flex-center min-w-32 h-32 rounded-full bg-yellow-300">
                                   {(!settings?.option_ratings.icon_ratings || settings?.option_ratings.icon_ratings.value === '') &&
                                   <ReactSVG
                                     src={`${lc_data.dir}dist/${starIcon}`}
                                     className="w-14 h-14 fill-product-star-icon"
                                   />}
                                  {settings?.option_ratings.icon_ratings?.value && settings?.option_ratings.icon_ratings?.library !== 'svg' &&
                                  <i
                                    className={`${settings.option_ratings.icon_ratings.value} fill-product-star-icon`}></i>
                                  }
                                  {settings?.option_ratings.icon_ratings?.value.url && settings?.option_ratings.icon_ratings?.library === 'svg' &&
                                  <ReactSVG
                                    src={`${settings.option_ratings.icon_ratings.value.url}`}
                                    className={`w-16 h-16 fill-product-star-icon`}
                                  />
                                  }
                                </span>
                      <span className="ml-6 text-sm text-grey-600">{vendors.vendors.query[key].rating}</span>
                    </div>}

                    {settings.display_info_location === 'yes' && vendors.vendors.query[key].location.address &&
                    <div className="lisfinity-product--info flex-center">
                              <span className="flex-center min-w-32 h-32 rounded-full bg-cyan-300">
                                {(!settings?.option_location.icon_location || settings?.option_location.icon_location.value === '') &&
                                <ReactSVG
                                  src={`${lc_data.dir}dist/${mapMarkerIcon}`}
                                  className="w-14 h-14 fill-product-place-icon"
                                />}{settings?.option_location.icon_location?.value && settings?.option_location.icon_location?.library !== 'svg' &&
                              <i
                                className={`${settings.option_location.icon_location.value} fill-product-place-icon`}></i>
                              }
                                {settings?.option_location.icon_location?.value.url && settings?.option_location.icon_location?.library === 'svg' &&
                                <ReactSVG
                                  src={`${settings.option_location.icon_location.value.url}`}
                                  className={`w-16 h-16 fill-product-place-icon`}
                                />
                                }
                              </span>
                      <span className="ml-6 text-sm text-grey-600">{vendors.vendors.query[key].location.address}</span>
                    </div>
                    }
                  </div>
                </div>

              </article>
            </div>
          );
        }
      });
    }

    {
      vendors?.vendors && settings.display_all_authors === 'yes' &&
      Object.keys(vendors.vendors.query).map(function (key, index) {
        vendorArray.push(
          <div key={key} className="mb-20 w-full sm:w-1/2 lg:w-1/3" style={{ padding: '0 8px 0 8px' }}>
            <article
              className="vendor flex flex-wrap sm:flex-no-wrap items-center p-20 bg-white rounded shadow-theme">
              {vendors.vendors.query[key].thumbnail &&
              <figure
                className="profile--thumbnail flex-center mr-20 p-10 border-grey-100 rounded-2xl overflow-hidden bg:mb-20 lg:mb-0"
              >
                <a href={vendors.vendors.query[key].url}>
                  <img src={vendors.vendors.query[key].thumbnail} alt={vendors.vendors.query[key].title}/>
                </a>
              </figure>
              }

              <div className="vendor--content">
                <h6 className="text-lg">
                  <a href={vendors.vendors.query[key].url}>
                    {vendors.vendors.query[key].title}
                  </a>
                </h6>

                <div className="flex items-center mt-10">
                  {settings.display_info_mark === 'yes' &&
                  <div className="lisfinity-product--info flex-center mr-22">
                                <span className="flex-center min-w-32 h-32 rounded-full bg-yellow-300">
                                 {(!settings?.option_ratings.icon_ratings || settings?.option_ratings.icon_ratings.value === '') &&
                                 <ReactSVG
                                   src={`${lc_data.dir}dist/${starIcon}`}
                                   className="w-14 h-14 fill-product-star-icon"
                                 />}
                                  {settings?.option_ratings.icon_ratings?.value && settings?.option_ratings.icon_ratings?.library !== 'svg' &&
                                  <i
                                    className={`${settings.option_ratings.icon_ratings.value} fill-product-star-icon`}></i>
                                  }
                                  {settings?.option_ratings.icon_ratings?.value.url && settings?.option_ratings.icon_ratings?.library === 'svg' &&
                                  <ReactSVG
                                    src={`${settings.option_ratings.icon_ratings.value.url}`}
                                    className={`w-16 h-16 fill-product-star-icon`}
                                  />
                                  }
                                </span>
                    <span className="ml-6 text-sm text-grey-600">{vendors.vendors.query[key].rating}</span>
                  </div>
                  }
                  {vendors.vendors.query[key].location.address && settings.display_info_location === 'yes' &&
                  <div className="lisfinity-product--info flex-center">
                              <span className="flex-center min-w-32 h-32 rounded-full bg-cyan-300">
                                 {(!settings?.option_location.icon_location || settings?.option_location.icon_location.value === '') &&
                                 <ReactSVG
                                   src={`${lc_data.dir}dist/${mapMarkerIcon}`}
                                   className="w-14 h-14 fill-product-place-icon"
                                 />}{settings?.option_location.icon_location?.value && settings?.option_location.icon_location?.library !== 'svg' &&
                              <i
                                className={`${settings.option_location.icon_location.value} fill-product-place-icon`}></i>
                              }
                                {settings?.option_location.icon_location?.value.url && settings?.option_location.icon_location?.library === 'svg' &&
                                <ReactSVG
                                  src={`${settings.option_location.icon_location.value.url}`}
                                  className={`w-16 h-16 fill-product-place-icon`}
                                />
                                }
                                   </span>
                    <span className="ml-6 text-sm text-grey-600">{vendors.vendors.query[key].location.address}</span>
                  </div>
                  }
                </div>
              </div>

            </article>
          </div>
        );
      });
    }

    {
      vendors?.vendors && settings.display_promoted_authors === 'yes' &&
      Object.keys(data.vendors.promoted).map(function (key, index) {
        vendorArray.push(
          <div key={key} className="mb-20 w-full sm:w-1/2 lg:w-1/3" style={{ padding: '0 8px 0 8px' }}>
            <article
              className="vendor flex flex-wrap sm:flex-no-wrap items-center p-20 bg-white rounded shadow-theme">
              {data.vendors.promoted[key].thumbnail &&
              <figure
                className="profile--thumbnail flex-center mr-20 p-10 border-grey-100 rounded-2xl overflow-hidden bg:mb-20 lg:mb-0"
              >
                <a href={vendors.vendors.promoted[key].url}>
                  <img src={vendors.vendors.promoted[key].thumbnail} alt={vendors.vendors.promoted[key].title}/>
                </a>
              </figure>
              }

              <div className="vendor--content">
                <h6 className="text-lg">
                  <a href={vendors.vendors.promoted[key].url}>
                    {vendors.vendors.promoted[key].title}
                  </a>
                </h6>

                <div className="flex items-center mt-10">
                  {settings.display_info_mark === 'yes' &&
                  <div className="lisfinity-product--info flex-center mr-22">
                                   <span className="flex-center min-w-32 h-32 rounded-full bg-yellow-300">
                                   {(!settings?.option_ratings.icon_ratings || settings?.option_ratings.icon_ratings.value === '') &&
                                   <ReactSVG
                                     src={`${lc_data.dir}dist/${starIcon}`}
                                     className="w-14 h-14 fill-product-star-icon"
                                   />}
                                     {settings?.option_ratings.icon_ratings?.value && settings?.option_ratings.icon_ratings?.library !== 'svg' &&
                                     <i
                                       className={`${settings.option_ratings.icon_ratings.value} fill-product-star-icon`}></i>
                                     }
                                     {settings?.option_ratings.icon_ratings?.value.url && settings?.option_ratings.icon_ratings?.library === 'svg' &&
                                     <ReactSVG
                                       src={`${settings.option_ratings.icon_ratings.value.url}`}
                                       className={`w-16 h-16 fill-product-star-icon`}
                                     />
                                     }
                                   </span>
                    <span className="ml-6 text-sm text-grey-600">{vendors.vendors.promoted[key].rating}</span>
                  </div>}

                  {vendors.vendors.promoted[key].location.address && settings.display_info_location === 'yes' &&
                  <div className="lisfinity-product--info flex-center">
                                   <span className="flex-center min-w-32 h-32 rounded-full bg-cyan-300">
                                   {(!settings?.option_location.icon_location || settings?.option_location.icon_location.value === '') &&
                                   <ReactSVG
                                     src={`${lc_data.dir}dist/${mapMarkerIcon}`}
                                     className="w-14 h-14 fill-product-place-icon"
                                   />}{settings?.option_location.icon_location?.value && settings?.option_location.icon_location?.library !== 'svg' &&
                                   <i
                                     className={`${settings.option_location.icon_location.value} fill-product-place-icon`}></i>
                                   }
                                     {settings?.option_location.icon_location?.value.url && settings?.option_location.icon_location?.library === 'svg' &&
                                     <ReactSVG
                                       src={`${settings.option_location.icon_location.value.url}`}
                                       className={`w-16 h-16 fill-product-place-icon`}
                                     />
                                     }
                                   </span>
                    <span className="ml-6 text-sm text-grey-600">{vendors.vendors.promoted[key].location.address}</span>
                  </div>
                  }
                </div>
              </div>

            </article>
          </div>
        );
      });
    }

    setVendorsArray(vendorArray);
  }, [vendors]);

  return (
    <div className="flex flex-wrap sm:justify-between items-center" ref={wrapper}>
      {state.vendorsLoading && <LoaderBusinessArchiveQuery promoted={true}/>}
      {state.vendorsLoading && <LoaderBusinessArchiveQuery promoted={false}/>}
      {!state.vendorsLoading && vendorsArray &&
      <div className="vendors--query flex flex-wrap mt-20 mb-10 -mx-col w-full">
        {vendorsArray}
      </div>
      }
    </div>
  );
};

export default AuthorBoxEl;
