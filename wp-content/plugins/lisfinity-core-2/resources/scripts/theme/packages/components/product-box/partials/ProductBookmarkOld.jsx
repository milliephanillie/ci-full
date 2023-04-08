/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component, useEffect, useState } from '@wordpress/element';
import ReactSVG from 'react-svg';
import cx from 'classnames';
import { filter } from 'lodash';

/**
 * Internal dependencies
 */
import bookmarkIcon from '../../../../../../images/icons/bookmark.svg';

const ProductBookmarkOld = (props) => {
  const { product, productStyle } = props;
  const [bookmarks, setBookmarks] = useState(lc_data.bookmarks);
  const [bookmarked, setBookmarked] = useState(false);
  const [message, setMessage] = useState({});
  useEffect(() => {
    // check if the product has been bookmarked
    if (bookmarks) {
      setBookmarked(bookmarks.includes(product.ID));
    }
  }, []);
  const iconClass = cx({
    'fill-white': !bookmarked,
    'fill-theme': bookmarked,
  });
  const buttonClass = cx({
    'top-30 right-24': productStyle !== 'popup',
    'top-20 right-14': productStyle === 'popup',
  });

  function manageBookmarks() {
    const headers = new Headers();
    headers.append('X-WP-Nonce', lc_data.nonce);
    const formData = new FormData();
    formData.append('product_id', product.ID);
    formData.append('_wpnonce', lc_data.nonce);
    fetch(`${lc_data.user_action}/bookmark`, {
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
    <button
      className={`action--bookmark absolute ${buttonClass} z-10`}
      onClick={() => manageBookmarks()}
    >
      <ReactSVG
        src={`${lc_data.dir}dist/${bookmarkIcon}`}
        className={`ml-10 w-18 h-18 ${iconClass}`}
      />
    </button>
  );
};

export default ProductBookmarkOld;
