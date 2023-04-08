/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect, Fragment } from 'react';
import { sprintf, __ } from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import LockIcon from '../../../../../../images/icons/lock.svg';

function SafetyTipsEl(props) {
  const { product, currentUser, settings } = props;

  let icon = null;
  let svg = null;

  if (settings?.selected_icon !== null && settings?.selected_icon) {
    typeof settings.selected_icon['value'] === 'string' ? icon = settings.selected_icon['value'] : svg = settings.selected_icon['value']['url'];
  }

  return (
    <div className="tips flex flex-wrap items-center">

      <div className="tips--icon">
        <div className="flex-center tips--icon-wrapper bg:mb-10 xl:mb-0 p-10 bg-blue-200 rounded-full">
          {(icon === null && svg === null || "" == icon) &&
          <ReactSVG
            src={`${lc_data.dir}dist/${LockIcon}`}
            className="w-36 h-36 safety-tips-icon fill-icon-tips"
          />
          }
          {
            svg && settings.place_icon !== '' &&
            <img src={svg} alt="cart-icon"
                 className="w-36 h-36 safety-tips-icon fill-icon-tips"/>
          }
          {
            settings.place_icon !== '' && icon &&
            <i className={`${icon} w-36 h-36 safety-tips-icon fill-icon-tips`} style={{
              display: 'flex',
              alignSelf: 'center'
            }}
               aria-hidden="true"
            ></i>
          }
        </div>
      </div>

      <div className="tips--content flex flex-col w-2/3 bg:w-full xl:w-2/3">
        <h6 className="widget--label mb-5 font-bold text-xl bg:text-base lg:text-xl safety-tips-title">{settings.title_text}</h6>
        <p id='safety-tips-text'>{settings.text_text}</p>
        <a
          href={'' !== settings.different_link && '' !== settings?.link_url && settings?.link_url?.url ? settings.link_url.url : product.tips_permalink}
          target="_blank"
          rel="noopener noreferrer"
          className="text-blue-600 safety-tips-link"
        >
          {settings.link_text}
        </a>
      </div>

    </div>
  );
}

export default SafetyTipsEl;
