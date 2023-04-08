/* global lc_data, React */
/**
 * Dependencies.
 */
import { createRef, useEffect, useState} from '@wordpress/element';
import { useDispatch, useSelector } from 'react-redux';
import {map} from "lodash";
import OwnerPhoneEl from "./OwnerPhoneEl";

/**
 * Internal dependencies
 */

const ProductOwnerPhoneEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const { product, options, color } = data;
  const [phones, setPhones] = useState([]);
  const [telegram, setTelegram] = useState('')

  useEffect( () => {
    setPhones(product?.premium_profile?.phones)
  });

  useEffect( () => {
    setTelegram(product?.premium_profile?.telegram)
  });

  return (
    <div className="profile--phones mt-20">
      {map(phones, (phone, index) => <OwnerPhoneEl key={index} product={product} phone={phone} telegram={telegram} color={color} type={props.type || 'default'} />)}
    </div>
  );
};

export default ProductOwnerPhoneEl;
