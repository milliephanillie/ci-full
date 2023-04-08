/* global lc_data, React */
/**
 * External dependencies.
 */
import GiftIcon from '../../../../../../../../images/icons/gift.svg';
import ReactSVG from 'react-svg';
import { __ } from '@wordpress/i18n';

function Free(props) {
  const { product, currentUser } = props;

  return (
    <div className="auction--infos flex">

      <div className="auction--info flex">
        <ReactSVG
          src={`${lc_data.dir}dist/${GiftIcon}`}
          className={`relative top-1 mr-8 w-16 h-16 fill-icon-gift`}
        />
        <div className={`product--price on-sale flex flex-row-reverse text-green-700 font-semibold`}>{lc_data.jst[128]}</div>
      </div>

    </div>
  );
}

export default Free;
