/* global lc_data, React */
/**
 * External dependencies.
 */
import {Component, useEffect, useState} from '@wordpress/element';
import ReactSVG from 'react-svg';
import cx from 'classnames';
import {filter} from 'lodash';

/**
 * Internal dependencies
 */
import bookmarkIcon from '../../../../../../images/icons/heart.svg';
import {createRef} from "react";
import * as actions from "../../../store/actions";

const ProductBookmark = (props) => {
  const {product, productStyle, displayBookmark, changeIcon} = props;
  const [bookmarks, setBookmarks] = useState(product?.likes || []);
  const [bookmarked, setBookmarked] = useState(product?.liked || false);
  const [message, setMessage] = useState({});
  const [settings, setSettings] = useState({});

  const iconClass = cx({
    'fill-white': !bookmarked,
    'fill-red-600': bookmarked,
  });
  const buttonClass = cx({
    'top-30 right-24': productStyle !== 'popup',
    'top-20 right-14': productStyle === 'popup',
  });

  useEffect(() => {
    let idBusiness = document.getElementById('business-store');
    let idSearch = document.getElementById('search-listings');

    if (idBusiness) {
      const optionsNew = JSON.parse(idBusiness.dataset.settings);
      setSettings(optionsNew);
    } else if (idSearch) {
      const optionsNewSearch = JSON.parse(idSearch.dataset.options);
      setSettings(optionsNewSearch);
    }
  }, []);

  function manageBookmarks() {
    const headers = new Headers();
    headers.append('X-WP-Nonce', lc_data.nonce);
    const formData = new FormData();
    formData.append('product_id', product.ID);
    formData.append('_wpnonce', lc_data.nonce);
    fetch(`${lc_data.product_action}/like`, {
      method: 'POST',
      credentials: 'same-origin',
      headers,
      body: formData,
    }).then(response => response.json()).then(response => {
      setBookmarks(response.bookmarks);
      if (response.success) {
        setBookmarked(true);
      }
      if (response.error) {
        setBookmarked(false);
      }
      setMessage(response);
    });
  }

  return (
    <div>
      {(settings.hide_show_action_bookmark === 'yes' || !settings) &&
      <button
        className={`action--like absolute ${buttonClass} z-10`}
        onClick={() => {
          manageBookmarks();
          setBookmarked(!bookmarked);
        }}
      >
        {(!settings.icon_bookmark || settings.icon_bookmark.value === '') &&
        <ReactSVG
          src={`${lc_data.dir}dist/${bookmarkIcon}`}
          className={`ml-10 w-18 h-18 bookmark-icon ${iconClass}`}
        />
        }
        {settings.icon_bookmark?.value && settings.icon_bookmark?.library !== 'svg' &&
        <i className={`ml-10 w-18 h-18 ${settings.icon_bookmark.value} ${iconClass} bookmark-icon`}></i>
        }
        {settings.icon_bookmark?.value.url && settings.icon_bookmark?.library === 'svg' &&
        <ReactSVG
          src={`${settings.icon_bookmark.value.url}`}
          className={`ml-10 w-18 h-18 ${iconClass} bookmark-icon`}
        />
        }
      </button>}
    </div>
  );
};

export default ProductBookmark;
