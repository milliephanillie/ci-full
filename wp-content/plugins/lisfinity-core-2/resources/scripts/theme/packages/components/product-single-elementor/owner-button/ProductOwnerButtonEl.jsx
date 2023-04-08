/* global lc_data, React */
/**
 * Dependencies.
 */
import { Component, useRef, Fragment, useEffect, useState } from '@wordpress/element';
import { toast, ToastContainer } from 'react-toastify';
import { useDispatch, useSelector } from 'react-redux';
import { isEmpty } from 'lodash';
import ReactSVG from 'react-svg';
import CrownIcon from '../../../../../../images/icons/crown.svg';
import MessagesEl from './MessagesEl';
import axios from 'axios';
import queryString from "query-string";

/**
 * Internal dependencies
 */

const ProductOwnerButtonEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const { product, options, user } = data;

  const [elSettings, setElSettings] = useState({});
  let wrapper = null;

  const el = useRef(null);

  useEffect(() => {
    wrapper = el.current.closest('.elementor-product-owner-button');
    if (wrapper && wrapper.dataset.settings) {
      const settings = JSON.parse(wrapper.dataset.settings);
      setElSettings(settings);
    }
  }, []);

  const [message, setMessage] = useState(false);

  const sendEmail = async (productId, type) => {
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const url = lc_data.request_call;
    await axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url,
      data: {
        product_id: productId,
        type,
      }
    }).then(response => {
      if (response.data.success) {
        toast(response.data.message, {
          position: toast.POSITION.BOTTOM_CENTER,
          autoClose: 3000,
        });
      } else {
        toast.error(response.data.message, {
          position: toast.POSITION.BOTTOM_CENTER,
          autoClose: 3000,
        });
      }
    });
  };

  let icon = null;
  let svg = null;

  if (elSettings?.selected_icon_visit_store_button !== null && elSettings?.selected_icon_visit_store_button) {
    typeof elSettings.selected_icon_visit_store_button['value'] === 'string' ? icon = elSettings.selected_icon_visit_store_button['value'] : svg = elSettings.selected_icon_visit_store_button['value']['url'];
  }

  return (
     <div className="profile--contact flex flex-wrap justify-between mt-6 mb-22 bg:flex-col xl:flex-row xl:flex-no-wrap"
         ref={el}>
      <Fragment>
        {(elSettings?.display_visit_store_button) &&
        <a
          href={product?.premium_profile?.url}
          className="profile--my-store relative flex items-center flex-no-wrap px-0 btn bg-blue-200 h-40 w-48% border border-blue-300 font-normal text-blue-700 bg:w-full xl:w-48% bg:mb-10 xl:mb-0 xl:mr-4 hover:bg-blue-700 hover:border-blue-700 hover:text-white group"
        >
          {(icon === null && svg === null || '' == icon) &&
          <ReactSVG
            src={`${lc_data.dir}dist/${CrownIcon}`}
            className="fill-icon-contact visit-store-icon"
          />
          }
          {
            svg && elSettings?.place_icon_visit_store_button !== '' &&
            <img src={svg} alt="cart-icon"
                 className="fill-icon-contact visit-store-icon"/>
          }
          {
            elSettings?.place_icon_visit_store_button !== '' && icon &&
            <i className={`${icon} visit-store-icon`} style={{
              display: 'flex',
              alignSelf: 'center'
            }}
               aria-hidden="true"
            ></i>
          }
          <span className="flex whitespace-no-wrap">{lc_data.jst[403]}</span>
        </a>}
        {(options?.messenger && elSettings?.display_messages_button) &&
        <MessagesEl product={product} settings={elSettings} currentUser={user} options={options}/>}
      </Fragment>
    </div>
  );
};

export default ProductOwnerButtonEl;
