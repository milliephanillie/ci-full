/* global lc_data, React */
/**
 * Dependencies.
 */
import {Component, createRef, Fragment, useEffect, useState} from '@wordpress/element';
import {useDispatch, useSelector} from 'react-redux';
import ReactSVG from "react-svg";
import starIcon from "../../../../../images/icons/star.svg";
import mapMarkerIcon from "../../../../../images/icons/map-marker.svg";

/**
 * Internal dependencies
 */

const ProductOwnerInfoIconEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const {product, options} = data;
  const avgRating = product.premium_profile?.rating || product?.rating;

  const [elSettings, setElSettings] = useState({});
  let wrapper = null;

  let el = createRef();

  useEffect(() => {
    wrapper = el.current.closest('.elementor-product-owner-info-icon');
    if (wrapper && wrapper.dataset.settings) {
      const settings = JSON.parse(wrapper.dataset.settings);
      setElSettings(settings);
    }
  }, []);

  let icon = null;
  let svg = null;

  if (elSettings?.selected_icon !== null && elSettings?.selected_icon) {
    typeof elSettings.selected_icon['value'] === 'string' ? icon = elSettings.selected_icon['value'] : svg = elSettings.selected_icon['value']['url'];
  }

  return (

    <div className="lisfinity-product--info-wrapper  flex items-center" ref={el}>
      {('rating' === elSettings.actions || ('location' === elSettings.actions && (elSettings['membership_address'] === 'always' || (elSettings['membership_address'] === 'logged_in' && lc_data.logged_in === '1')))) &&
      <Fragment>
      <span
        className={`flex-center min-w-32 h-32 owner-icon-wrapper rounded-full ${elSettings.actions && 'rating' === elSettings.actions ? 'bg-yellow-300' : 'bg-cyan-300'}`}>
        {(icon === null && svg === null || "" == icon) &&
        <ReactSVG
          src={`${lc_data.dir}dist/${elSettings.actions && 'rating' === elSettings.actions ? starIcon : mapMarkerIcon}`}
          className={`w-14 h-14 owner-icon ${elSettings.actions && 'rating' === elSettings.actions ? 'fill-product-star-icon ' : 'fill-product-place-icon'}`}
        />}
        {
          svg && elSettings.place_icon !== '' &&
          <img src={svg} alt="cart-icon"
               className={`owner-icon ${elSettings.actions && 'rating' === elSettings.actions ? 'fill-product-star-icon ' : 'fill-product-place-icon'}`}/>
        }
        {
          elSettings.place_icon !== '' && icon &&
          <i
            className={`${icon} owner-icon ${elSettings.actions && 'rating' === elSettings.actions ? 'fill-product-star-icon ' : 'fill-product-place-icon'}`}
            style={{
              display: 'flex',
              alignSelf: 'center'
            }}
            aria-hidden="true"
          ></i>
        }
      </span>
        {('owner_location' === elSettings['product-search-map-location'] && 'location' === elSettings.actions) &&
        <span className="ml-6 text-sm text-grey-600">{product?.premium_profile?.location_formatted}</span>
        }
        {('listing_location' === elSettings['product-search-map-location'] && 'location' === elSettings.actions) &&
        <span className="ml-6 text-sm text-grey-600">{elSettings?.address}</span>
        }
        <span
          className="ml-6 text-sm owner-icon-text text-grey-600">{elSettings.actions && 'rating' === elSettings.actions && avgRating}
      </span>
      </Fragment>
      }

    </div>
  );
};

export default ProductOwnerInfoIconEl;
