/* global lc_data, React */
/**
 * External dependencies.
 */
import {useState, useEffect, Fragment} from 'react';
import {map, isEmpty} from 'lodash';
import {__} from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import starIcon from '../../../../../../../../images/icons/star.svg';
import PhoneIcon from '../../../../../../../../images/icons/phone-handset.svg';
import OwnerPhone from './OwnerPhone';
import ReactSVG from 'react-svg';

function OwnerPhones(props) {
  const {product, color, options} = props;
  const {phones, telegram} = product.premium_profile;
  const [revealed, setRevealed] = useState(false);

  return (
    <div className="profile--phones mt-20 mb-10">
      <div className="phone-wrapper flex flex-start w-full justify-between">
      {product?.premium_profile?.phones && product?.premium_profile?.phones[0] && !isEmpty(product?.premium_profile?.phones[0]['profile-phone']) && map(phones, (phone, index) => <OwnerPhone key={index} product={product} phone={phone} options={options}
                                                telegram={telegram} color={color} type={props.type || 'default'}/>)}
      </div>
    </div>
  );
}

export default OwnerPhones;
