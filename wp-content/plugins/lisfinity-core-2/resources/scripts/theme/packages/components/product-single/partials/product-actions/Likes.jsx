/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect } from 'react';
import ReactSVG from 'react-svg';
import cx from 'classnames';

/**
 * Internal dependencies.
 */
import HeartIcon from '../../../../../../../images/icons/heart.svg';

function Likes(props) {
  const { product, settings } = props;
  const [likeIps, setLikeIps] = useState(product.likes);
  const [likes, setLikes] = useState(product.product_meta.likes);
  const [liked, setLiked] = useState(false);
  let icon = null;
  let svg = null;
  let actionLikesIndex = null;

  actionLikesIndex = settings?.actions && settings?.actions.findIndex(action => action.actions === 'likes');

  if (settings?.actions[actionLikesIndex].selected_icon_action !== null && settings?.actions[actionLikesIndex].selected_icon_action) {
    typeof settings.actions[actionLikesIndex].selected_icon_action['value'] === 'string' ? icon = settings.actions[actionLikesIndex].selected_icon_action['value'] : svg = settings.actions[actionLikesIndex].selected_icon_action['value']['url'];
  }

  useEffect(() => {
    setLiked(likeIps.includes(lc_data.user_ip));
    setLikes(likeIps.length);
  });

  function manageLikes() {
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
      setLikeIps(response.like_ips);
    });
  }

  const iconClass = cx({
    'fill-field-icon': !liked,
    'fill-icon-home': liked,
  });

  let colorIcon = null;

  colorIcon = liked ? settings?.actions && settings?.actions[actionLikesIndex].action_icon_color_active : settings?.actions[actionLikesIndex].action_icon_color;

  return (
    <button
      type="button" className={`product--action text-base elementor-repeater-item-${props.elementId}`}
      onClick={() => manageLikes()}
      style={{
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center'
      }}
    >
      {(icon === null && svg === null || '' == icon) &&
      <ReactSVG
        src={`${lc_data.dir}dist/${HeartIcon}`}
        className={`mr-6 product-icon w-16 h-16 ${iconClass}`}
        style={{
          color: colorIcon,
          fill: colorIcon
        }}
      />}

      {
        svg && settings?.actions[actionLikesIndex].place_icon_action !== '' &&
        <img src={svg} alt="likes-icon"
             className="w-20 h-20 mr-8 product-icon fill-icon-reset pointer-events-none"/>
      }
      {
        settings?.actions[actionLikesIndex].place_icon_action !== '' && icon &&
        <i className={`${icon} product-icon`}
           style={{
             color: colorIcon,
             fill: colorIcon
           }}
           aria-hidden="true"
        ></i>
      }
      {likes}
    </button>
  );
}

export default Likes;
