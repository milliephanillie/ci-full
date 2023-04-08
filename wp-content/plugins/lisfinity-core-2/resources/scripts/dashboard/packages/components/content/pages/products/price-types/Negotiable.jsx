/* global lc_data, React */
/**
 * External dependencies.
 */
import HandshakeIcon from '../../../../../../../../images/icons/handshake.svg';
import ReactSVG from 'react-svg';
import { __ } from '@wordpress/i18n';
import cx from 'classnames';

function Negotiable(props) {
  const { product, currentUser } = props;

  return (
    <div className="auction--infos flex">

      <div className="auction--info flex">
        <ReactSVG
          src={`${lc_data.dir}dist/${HandshakeIcon}`}
          className={`relative top-3 mr-8 w-16 h-16 fill-field-icon`}
        />
        <div className={`product--price on-sale flex flex-row-reverse text-grey-1100 font-semibold`}>{lc_data.jst[237]}</div>
      </div>

    </div>
  );
}

export default Negotiable;
