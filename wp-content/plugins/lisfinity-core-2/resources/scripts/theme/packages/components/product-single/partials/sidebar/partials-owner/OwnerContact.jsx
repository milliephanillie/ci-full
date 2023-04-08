/* global lc_data, React */
/**
 * Dependencies.
 */
import { useState, useEffect, Fragment } from 'react';
import { isEmpty } from 'lodash';
import Messages from '../../../messages/Messages';
import ReactSVG from 'react-svg';
import CrownIcon from '../../../../../../../../images/icons/crown.svg';
import axios from 'axios';
import { toast } from 'react-toastify';

const OwnerContact = (props) => {
  const { product, currentUser, options } = props;
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

  return (
    !isEmpty(product) &&
    <div>
      <div
        className="profile--contact flex flex-wrap justify-between mt-6 mb-22 bg:flex-col xl:flex-row xl:flex-no-wrap">
        <a
          href={product.premium_profile.url}
          className="profile--my-store relative flex items-center flex-no-wrap px-0 btn bg-blue-200 h-40 w-48% border border-blue-300 font-normal text-blue-700 bg:w-full xl:w-48% bg:mb-10 xl:mb-0 xl:mr-4 hover:bg-blue-700 hover:border-blue-700 hover:text-white group"
        >
          <ReactSVG
            src={`${lc_data.dir}dist/${CrownIcon}`}
            className="mr-10 min-w-18 h-18 fill-icon-contact"
          />
          <span className="flex whitespace-no-wrap">{lc_data.jst[403]}</span>
        </a>
        {props.options.messenger &&
        <Messages product={product} currentUser={currentUser} options={options}/>}
      </div>

      {!isEmpty(product?.premium_profile?.store_referral) &&
      <div className="store-referral flex w-full mt-10">
        <a href={product.premium_profile.store_referral} target="_blank"
           className="relative flex items-center btn bg-green-200 h-40 w-full border border-green-300 rounded font-normal text-green-700 hover:bg-green-400 hover:text-white"
        >{lc_data.jst[729]}</a>
      </div>
      }
    </div>
  );
};

export default OwnerContact;
