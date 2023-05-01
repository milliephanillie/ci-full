/* global lc_data, React */
/**
 * Dependencies.
 */
import React, {useState, useEffect, Fragment} from 'react';
import ReactSVG from 'react-svg';
import {isEmpty, map} from 'lodash';
import starIcon from '../../../../../../../images/icons/star.svg';
import mapMarkerIcon from '../../../../../../../images/icons/map-marker.svg';
import OwnerPhones from './partials-owner/OwnerPhones';
import OwnerContact from './partials-owner/OwnerContact';
import World from '../../../../../../../images/icons/world.svg';
import EnvelopeIcon from '../../../../../../../images/icons/envelope.svg';
import PhoneIcon from    '../../../../../../../images/icons/phone-handset.svg';
import BusinessTestimonial from '../../../business-single/partials/BusinessTestimonial';
import OwnerAddressAlt from './partials-owner/OwnerAddressAlt';
import OwnerHoursAlt from './partials-owner/OwnerHoursAlt';
import {getSafe, storeStat} from '../../../../../vendor/functions';
import { QRCode } from 'react-qrcode-logo';
import QR from './partials-owner/QR';
import GravityForm from "react-gravity-form";
import LoaderGlobal from '../../../loaders/LoaderGlobal';
import OwnerPhone from "./partials-owner/OwnerPhone";

function OwnerAlt(props) {
  const { product, currentUser, options } = props;
  const avgRating = product.premium_profile.rating || product.rating;
  const thumbnail = options?.account_type === 'business' ? product.premium_profile?.thumbnail : product?.premium_profile?.user_avatar ? product?.premium_profile?.user_avatar : product?.products?.products[0] && product?.products?.products[0]['user_avatar'] ? product?.products?.products[0]['user_avatar'] : '';
  const [erevealed, setERevealed] = useState(false);
  const emailHidden = product.premium_profile.email;
  const censorWord = (str) => {
    return str[0] + "*".repeat(str.length - 2) + str.slice(-1);
  }
  const censorEmail = (email) => {
    let arr = email.split("@");
    return censorWord(arr[0]) + "@" + censorWord(arr[1]);
  }


  return (
    <Fragment>
      <div className={"profile--owner"}>

        <div className="profile--header flex items-center flex-wrap">
          {thumbnail &&
          <figure
            className="profile--thumbnail flex-center mb-20 p-30 w-full bg-grey-100 rounded"
            style={{ height: '150px' }}
          >
            <a href={product.premium_profile.url} className="h-full"
               target={`${lc_data.is_business_page ? '_self' : '_blank'}`}>
              <img src={thumbnail} alt={product.premium_profile.title} className="h-90"/>
            </a>
          </figure>
          }

          <div className="profile--meta flex flex-col w-2/3 bg:mb-10 bg:w-full lg:mb-0">
            {(options['membership-name'] === 'always' || (options['membership-name'] === 'logged_in' && lc_data.logged_in === '1')) &&
            <h6 className="mb-6 font-bold"><a href={product.premium_profile.url}>{product.premium_profile.title}</a>
            </h6>
            }
            <div className="lisfinity-product--info-wrapper flex items-center">
              {props.options.reviews && avgRating &&
              <div className="lisfinity-product--info flex-center mr-22">
                <span className="flex-center min-w-32 h-32 rounded-full bg-yellow-300">
                  <ReactSVG
                    src={`${lc_data.dir}dist/${starIcon}`}
                    className="w-14 h-14 fill-product-star-icon"
                  />
                </span>
                <span className="ml-6 text-sm text-grey-600">{avgRating}</span>
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
                {'owner_location' === options['product-search-map-location'] || !options['product-search-map-location'] &&
                <span className="ml-6 text-sm text-grey-600">{product.premium_profile.location_formatted}</span>
                }
                {'listing_location' === options['product-search-map-location'] &&
                <span className="ml-6 text-sm text-grey-600">{options?.address}</span>
                }
              </div>
              }

            </div>

          </div>
        </div>

        {!isEmpty(product.premium_profile.phones) && (product?.post_status != 'sold') && options['membership-phone'] !== 'never' &&
        <OwnerPhones product={product} options={options}/>}


        {/*{!isEmpty(product?.premium_profile?.email) && options?.display_email &&*/}
        {/*<div className="email-sidebar lisfinity-product--info flex">*/}
        {/*      <span className="relative flex-center min-w-32 h-32"*/}
        {/*            style={{*/}
        {/*              top: -4,*/}
        {/*            }}>*/}
        {/*        <ReactSVG*/}
        {/*          src={`${lc_data.dir}dist/${EnvelopeIcon}`}*/}
        {/*          className="w-14 h-14 fill-grey-1000"*/}
        {/*        />*/}
        {/*      </span>*/}
        {/*  <div className={"show-email"}>*/}
        {/*  <span*/}
        {/*    className="text-lg text-grey-700 mr-6">{lc_data.jst[724]}</span>*/}
        {/*  <a href={`mailto:${product?.premium_profile?.email}`}*/}
        {/*     className="text-lg text-grey-1000 break-all"> {product?.premium_profile?.email}</a>*/}
        {/*</div>*/}
        {/*  <div className={"show-email-text"}><button type="button" className={"text-13 text-grey-500"}>Show email</button></div>*/}
        {/*</div>*/}
        {/*}*/}

        {!isEmpty(product?.premium_profile?.email) && (product?.post_status != 'sold') && options?.display_email &&
         <div className="mb-20">
          {!erevealed && <div
              className={`font-bold text-lg bg:text-base lg:text-lg text-grey-1000 leading-none`}>{censorEmail(product?.premium_profile?.email)}</div>}
          {!erevealed &&
              <button
                  type="button"
                  onClick={() => {
                    setERevealed( true);
                    storeStat(product.ID, 2);
                  }}
                  className={`text-13 text-grey-500`}
              >
                Show email
              </button>
          }
          {erevealed && <div className="email-wrapper flex flex-start w-full justify-start">               <a href={`mailto:${product?.premium_profile?.email}`}
                className={`font-bold text-lg bg:text-base lg:text-lg text-grey-1000 leading-none`}> {product?.premium_profile?.email}</a></div>}
          {erevealed &&
              <button
                  type="button"
                  onClick={() => {
                    setERevealed(false);
                  }}
                  className={`text-13 text-grey-500`}
              >
                Hide email
              </button>
          }
        </div>}

        {/*{!isEmpty(product) && !props.businessPage &&*/}
        {/*    <OwnerContact product={product} currentUser={currentUser} options={props.options}/>}*/}

        {!isEmpty(product) && !product?.is_expired && !props.businessPage &&
            <div className='send-message send-message-gf'>
              <div className="or-send-message"><h4>Or send a message...</h4></div>
              <GravityForm
                  backendUrl={`${lc_data.url}wp-json/ci/v1/gf/forms`}
                  formID="3"
                  submitComponent={<LoaderGlobal title={"Sending Message"} />}
              />
            </div>
        }

        {/*{props.businessPage &&*/}
        {/*<BusinessTestimonial product={product} options={props.options} type="default"/>*/}
        {/*}*/}

        {/*{(!isEmpty(product.premium_profile.location) || !isEmpty(product.location?.lat)) && product.premium_profile.location_map_show && options.display_product_map && (options['membership-address'] === 'always' || (options['membership-address'] === 'logged_in' && lc_data.logged_in === '1')) &&*/}
        {/*<OwnerAddressAlt product={product} currentUser={currentUser} options={props.options}/>}*/}

        {/*{!isEmpty(product?.qr) &&*/}
        {/*<QR product={product} currentUser={currentUser} options={props.options}/>*/}
        {/*}*/}

        {/*{!isEmpty(product.premium_profile.hours) && product.premium_profile.hours_enabled && props.options.hours_enabled &&*/}
        {/*<OwnerHoursAlt product={product}/>}*/}

      </div>

    </Fragment>
  );
}

export default OwnerAlt;
