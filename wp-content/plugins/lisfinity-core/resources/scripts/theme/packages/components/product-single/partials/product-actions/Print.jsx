/* global lc_data, React */
/**
 * External dependencies.
 */
import {__} from '@wordpress/i18n';
import ReactSVG from 'react-svg';

/**
 * Internal dependencies.
 */
import PrintIcon from '../../../../../../../images/icons/printer.svg';
import {useState} from "react";

function Print(props) {
  const {product, currentUser, settings} = props;

  let icon = null;
  let svg = null;
  let actionPrintIndex = null;

  actionPrintIndex = settings?.actions && settings?.actions.findIndex(action => action.actions === 'print');

  if (settings?.actions[actionPrintIndex].selected_icon_action !== null && settings?.actions[actionPrintIndex].selected_icon_action) {
    typeof settings.actions[actionPrintIndex].selected_icon_action['value'] === 'string' ? icon = settings.actions[actionPrintIndex].selected_icon_action['value'] : svg = settings.actions[actionPrintIndex].selected_icon_action['value']['url'];
  }

  return (
    <button
      type="button"
      className={`product--action text-base elementor-repeater-item-${props.elementId}`}
      onClick={() => window.print()}
      style={{
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center'
      }}
    >
      {(icon === null && svg === null || "" == icon) &&
      <ReactSVG
        src={`${lc_data.dir}dist/${PrintIcon}`}
        className="mr-6 product-icon w-16 h-16 fill-field-icon"
      />
      }

      {
        svg && settings.actions[actionPrintIndex].place_icon_action !== '' &&
        <img src={svg} alt="print-icon"
             className="w-20 h-20 mr-8 product-icon fill-icon-reset pointer-events-none"/>
      }
      {
        settings?.actions[actionPrintIndex].place_icon_action !== '' && icon &&
        <i className={`${icon} product-icon`}
           aria-hidden="true"
        ></i>
      }
      {lc_data.jst[508]}
    </button>
  );
}

export default Print;
