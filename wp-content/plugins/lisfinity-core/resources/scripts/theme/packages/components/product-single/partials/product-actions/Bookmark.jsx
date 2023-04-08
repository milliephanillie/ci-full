/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect } from 'react';
import { __ } from '@wordpress/i18n';
import { filter, get, isEmpty } from 'lodash';
import cx from 'classnames';
import ReactSVG from 'react-svg';

/**
 * Internal dependencies.
 */
import BookmarkIcon from '../../../../../../../images/icons/bookmark.svg';

function Bookmark(props) {
  const { product, currentUser, settings } = props;
  const [bookmarks, setBookmarks] = useState(get(currentUser, 'bookmarks')) || {};
  const [bookmarked, setBookmarked] = useState(false);
  const [message, setMessage] = useState({});

  useEffect(() => {
    // check if the product has been bookmarked
    filter(bookmarks, (o) => {
      if (o.id == product.ID) {
        setBookmarked(true);
      }
    });
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

  const bookmarkClass = cx({
    'fill-field-icon': !bookmarked,
    'fill-product-star-icon': bookmarked,
  });

  let icon = null;
  let svg = null;
  let actionBookmarkIndex = null;

  actionBookmarkIndex = settings?.actions && settings?.actions.findIndex(action => action.actions === 'bookmark');

  if (settings?.actions[actionBookmarkIndex].selected_icon_action !== null && settings?.actions[actionBookmarkIndex].selected_icon_action) {
    typeof settings.actions[actionBookmarkIndex].selected_icon_action['value'] === 'string' ? icon = settings.actions[actionBookmarkIndex].selected_icon_action['value'] : svg = settings.actions[actionBookmarkIndex].selected_icon_action['value']['url'];
  }

  return (
    !isEmpty(product) &&
    <button
      type="button"
      className={`product--action bookmark text-base elementor-repeater-item-${props.elementId}`}
      onClick={() => manageBookmarks()}
    >
      {(icon === null && svg === null || '' == icon) &&
      <ReactSVG
        src={`${lc_data.dir}dist/${BookmarkIcon}`}
        className={`mr-6 w-16 h-16 product-icon ${bookmarkClass}  ${bookmarked ? 'bookmarked-icon' : 'bookmark-icon'}`}
      />
      }

      {
        svg && settings?.actions[actionBookmarkIndex].place_icon_action !== '' &&
        <img src={svg} alt="bookmark-icon"
             className={`w-20 h-20 mr-8 product-icon  ${bookmarked ? 'bookmarked-icon' : 'bookmark-icon'} fill-icon-reset pointer-events-none`}/>
      }
      {
        settings?.actions[actionBookmarkIndex].place_icon_action !== '' && icon &&
        <i className={`${icon} product-icon  ${bookmarked ? 'bookmarked-icon' : 'bookmark-icon'}`}
           aria-hidden="true"
        ></i>
      }
      {bookmarked ? lc_data.jst[507] : lc_data.jst[311]}
    </button>
  );
}

export default Bookmark;
