/* global lc_data, React */
/**
 * External dependencies.
 */
import {useState, useEffect, Fragment} from 'react';
import {map, isEmpty} from 'lodash';
import {__} from '@wordpress/i18n';
import ReactSVG from 'react-svg';

/**
 * Internal dependencies
 */
import starIcon from '../../../../../../../../images/icons/star.svg';
import OwnerPhone from './OwnerPhone';

function OwnerPhones(props) {
  const {product, color, options} = props;
  const {phones, telegram} = product.premium_profile;
  const [revealed, setRevealed] = useState(false);

  return (
    <div className="profile--phones mt-20">
      {product?.premium_profile?.phones && product?.premium_profile?.phones[0] && !isEmpty(product?.premium_profile?.phones[0]['profile-phone']) &&
      map(phones, (phone, index) => <OwnerPhone key={index} product={product} phone={phone} options={options}
                                                telegram={telegram} color={color} type={props.type || 'default'}/>)}
    </div>
  );
}

export default OwnerPhones;
