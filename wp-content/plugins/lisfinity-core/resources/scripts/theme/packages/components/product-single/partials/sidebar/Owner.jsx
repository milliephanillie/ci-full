/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect, Fragment } from 'react';
import { __ } from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import { isEmpty } from 'lodash';

/**
 * Internal dependencies
 */
import ArrowRightIcon from '../../../../../../../images/icons/arrow-right.svg';
import starIcon from '../../../../../../../images/icons/star.svg';
import mapMarkerIcon from '../../../../../../../images/icons/map-marker.svg';
import OwnerPhones from './partials-owner/OwnerPhones';
import OwnerAddress from './partials-owner/OwnerAddress';
import OwnerHours from './partials-owner/OwnerHours';
import OwnerContact from './partials-owner/OwnerContact';
import BusinessTestimonial from '../../../business-single/partials/BusinessTestimonial';

function Owner(props) {
  const { product, currentUser } = props;
  const avgRating = product.premium_profile.rating || product.rating;

  return (
    <Fragment>

      {!props.businessPage &&
      <div className="profile--store text-right">
        <a href={product.premium_profile.url} className="flex justify-end items-center text-sm text-grey-500">
          {lc_data.jst[403]}
          <ReactSVG
            src={`${lc_data.dir}dist/${ArrowRightIcon}`}
            className="ml-6 w-16 h-16 fill-field-icon"
          />
        </a>
      </div>}

      <div className="profile--owner">

        <div className="profile--header flex items-center bg:flex-wrap lg:flex-no-wrap">
          {product.premium_profile.url &&
          <figure
            className="profile--thumbnail flex-center mr-20 p-10 w-1/3 border-grey-100 rounded-2xl overflow-hidden bg:mb-20 lg:mb-0"
            style={{ height: '84px', borderWidth: '6px' }}
          >
            <a href={product.premium_profile.url}>
              <img src={product.premium_profile.thumbnail} alt={product.premium_profile.title}/>
            </a>
          </figure>
          }

          <div className="profile--meta flex flex-col w-2/3 bg:mb-10 bg:w-full lg:mb-0 lg:w-2/3">
            <h6 className="mb-6 font-bold"><a href={product.premium_profile.url}>{product.premium_profile.title}</a>
            </h6>
            <div className="lisfinity-product--info-wrapper flex items-center">

              <div className="lisfinity-product--info flex-center mr-22">
                <span className="flex-center min-w-32 h-32 rounded-full bg-yellow-300">
            <ReactSVG
              src={`${lc_data.dir}dist/${starIcon}`}
              className="w-14 h-14 fill-product-star-icon"
            />
                </span>
                <span className="ml-6 text-sm text-grey-600">{avgRating}</span>
              </div>

              {
                <div className="lisfinity-product--info flex-center">
              <span className="flex-center min-w-32 h-32 rounded-full bg-cyan-300">
                <ReactSVG
                  src={`${lc_data.dir}dist/${mapMarkerIcon}`}
                  className="w-14 h-14 fill-product-place-icon"
                />
              </span>
                  <span className="ml-6 text-sm text-grey-600">{product.premium_profile.location_formatted}</span>
                </div>
              }

            </div>

          </div>
        </div>

        {!isEmpty(product.premium_profile.phones) && <OwnerPhones product={product}/>}

        {!isEmpty(product) && !props.businessPage &&
        <OwnerContact product={product} currentUser={currentUser} options={props.options}/>}

        {props.businessPage &&
        <BusinessTestimonial product={product} options={props.options} type="default"/>
        }

        {!isEmpty(product.premium_profile.location) && <OwnerAddress product={product}/>}

        {!isEmpty(product.premium_profile.hours) && <OwnerHours product={product}/>}

      </div>

    </Fragment>
  );
}

export default Owner;
