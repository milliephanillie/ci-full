/* global lc_data, React */

import ReactSVG from 'react-svg';
import starIcon from '../../../../../../images/icons/star.svg';
import mapMarkerIcon from '../../../../../../images/icons/map-marker.svg';
import { __ } from '@wordpress/i18n';
import { isEmpty } from 'lodash';
import { Fragment } from 'react';
import { storeStat } from '../../../../vendor/functions';
import OwnerPhones from '../../product-single/partials/sidebar/partials-owner/OwnerPhones';
import StarIcon from '../../../../../../images/icons/star.svg';
import BusinessTestimonial from './BusinessTestimonial';

const BusinessHeader = (props) => {
  const { product, options } = props;
  const avgRating = product.rating;

  const { hours } = product.premium_profile;
  const currentDay = new Date().getDay();
  const currentHours = hours[currentDay];
  const banner_image = product?.premium_profile?.banner ? product.premium_profile.banner : options?.banner_fallback_image;
  return (
    <header className="relative py-60 bg-cover bg-bottom bg-grey-1000"
            style={{ backgroundImage: `url(${banner_image})` }}>
      <span
        className="absolute top-0 left-0 w-full h-full z-1"
        style={{ background: 'linear-gradient(0deg, rgba(0, 0, 0, .5) 0%, rgba(255,255,255,0) 100%' }}
        onClick={() => storeStat(product.ID, 3)}
      >
      </span>

      <div className="container relative z-2">
        <div className="row flex-wrap justify-between mx-0">

          <div className="w-full sm:w-2/3">
            <div className="profile--owner">

              <div className="profile--header flex flex-wrap md:flex-no-wrap">
                {product?.premium_profile?.thumbnail &&
                <figure
                  className="profile--thumbnail flex-center mr-20 p-10 bg-white border-grey-100 rounded-2xl overflow-hidden bg:mb-20 lg:mb-0"
                  style={{ width: '84px', height: '74px', borderWidth: '6px' }}
                >
                  <img src={product.premium_profile.thumbnail} alt={product.premium_profile.title}/>
                </figure>
                }

                <div className="profile--meta flex flex-col w-5/6 text-white bg:mb-10 bg:w-full lg:mb-0 lg:w-2/3">
                  {product.premium_profile.title && (options['membership-name'] === 'always' || (options['membership-name'] === 'logged_in' && lc_data.logged_in === '1')) &&
                  <div>
                    <p className="text-white">{lc_data.jst[389]}</p>
                    <h1 className="mb-6 font-bold text-white">{product.premium_profile.title}</h1>
                  </div>
                  }
                  <div className="lisfinity-product--info-wrapper flex items-center">
                    {options?.reviews &&
                    <div className="lisfinity-product--info flex-center mr-22">
                      <span className="flex-center min-w-32 h-32 rounded-full bg-yellow-300">
                      <ReactSVG
                        src={`${lc_data.dir}dist/${starIcon}`}
                        className="w-14 h-14 fill-product-star-icon"
                      />
                      </span>
                      <span className="ml-6 text-sm text-white">{avgRating}</span>
                    </div>
                    }

                    {product?.premium_profile?.location_formatted && (options['membership-address'] === 'always' || (options['membership-address'] === 'logged_in' && lc_data.logged_in === '1')) &&
                    <div className="lisfinity-product--info flex-center">
                        <span className="flex-center min-w-32 h-32 rounded-full bg-cyan-300">
                          <ReactSVG
                            src={`${lc_data.dir}dist/${mapMarkerIcon}`}
                            className="w-14 h-14 fill-product-place-icon"
                          />
                        </span>
                      <span className="ml-6 text-sm text-white">{product.premium_profile.location_formatted}</span>
                    </div>
                    }
                    {!isEmpty(product.premium_profile.hours) && options?.hours_enabled &&
                    <div className="ml-20 font-light text-white leading-none">
                      {currentHours.type === 'working' &&
                      <Fragment>
                        <span dangerouslySetInnerHTML={{ __html: lc_data.jst[390] }}/>
                        <strong
                          className="mx-4 font-bold">{currentHours && currentHours.hours && currentHours.hours[0] && currentHours.hours[0].open.slice(0, -3)}</strong>
                        <span dangerouslySetInnerHTML={{ __html: lc_data.jst[391] }}/>
                        <strong
                          className="ml-2 font-bold">{currentHours && currentHours.hours && currentHours.hours[0] && currentHours.hours[0].close.slice(0, -3)}</strong>
                      </Fragment>}
                      {currentHours.type === 'full' && lc_data.jst[365]}
                      {currentHours.type === 'not_working' && lc_data.jst[392]}
                    </div>
                    }

                  </div>

                </div>
              </div>
            </div>

          </div>

          <div className="w-full mt-30 sm:mt-0 sm:w-1/3">
            {product.premium_profile.phones && (options?.['membership-phone'] === 'always' || (options?.['membership-phone'] === 'logged_in' && lc_data.logged_in === '1')) &&
            <div className="phones flex justify-end -mt-10 ml-auto w-full md-3/4">
              <OwnerPhones product={product} options={options} color="text-white" type="business"/>
            </div>
            }
            <div className="profile--actions flex justify-end mt-20">
              <BusinessTestimonial product={product} options={props.options}/>
            </div>
          </div>

        </div>
      </div>

    </header>
  );
};

export default BusinessHeader;
