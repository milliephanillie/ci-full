/* global lc_data, React */
/**
 * External dependencies.
 */
import PhoneIcon from '../../../../../../../../images/icons/phone-handset.svg';
import ReactSVG from 'react-svg';
import { __ } from '@wordpress/i18n';

function OnCall(props) {
  const { product, currentUser } = props;

  return (
    <div className="auction--infos flex">

      <div className="auction--info flex">
        <ReactSVG
          src={`${lc_data.dir}dist/${PhoneIcon}`}
          className={`relative top-1 mr-8 w-16 h-16 fill-icon-call`}
        />
        <div className={`product--price on-sale flex flex-row-reverse text-blue-700 font-semibold`}>{lc_data.jst[238]}</div>
      </div>

    </div>
  );
}

export default OnCall;
