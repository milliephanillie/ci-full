/* global lc_data, React */
/**
 * External dependencies.
 */
import onClickOutside from 'react-onclickoutside';
import { useState, useEffect, Fragment } from 'react';
import { __ } from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import cx from 'classnames';
import FrameExpandIcon from '../../../../../images/icons/frame-expand.svg';
import CloseIcon from '../../../../../images/icons/close.svg';

const Modal = (props) => {
  const { open, title, handleClickOutside, closeModal, width } = props;
  const [expanded, setExpanded] = useState(false);
  const modalExpandClass = cx({
    'fixed top-0 left-0 w-full h-full': expanded,
    'fixed top-30 left-0 w-full h-full': expanded && lc_data.logged_in,
  });

  Modal.handleClickOutside = () => handleClickOutside;

  return (
    open &&
    <div
      className={`modal my-auto relative z-2 whitespace-normal ${props.type === 'demo' ? 'xl:w-30%' : ''} ${modalExpandClass} ${width || ''}`}>

      <div className="modal--inner bg-white rounded shadow-xl overflow-hidden">

        <div className="modal--header flex justify-between items-center py-16 p-20 bg-grey-100">

          <h5 className={`modal--title font-bold ${props.type === 'demo' ? 'text-red-600' : ''}`}>{title}</h5>

          <div className="modal--header__right flex items-center">
            {props.expand && !expanded &&
            <button type="button" className="flex-center text-sm text-grey-700" onClick={() => setExpanded(true)}>
              {lc_data.jst[461]}
              <ReactSVG
                src={`${lc_data.dir}dist/${FrameExpandIcon}`}
                className="ml-8 w-16 h-16 fill-field-icon"
              />
            </button>}
            {props.expand && expanded &&
            <button type="button" className="flex-center text-sm text-grey-700" onClick={() => setExpanded(false)}>
              {lc_data.jst[462]}
              <ReactSVG
                src={`${lc_data.dir}dist/${FrameExpandIcon}`}
                className="ml-8 w-16 h-16 fill-field-icon"
              />
            </button>}
            <button type="button" className="flex-center ml-20 text-sm text-grey-700" onClick={closeModal}>
              {lc_data.jst[463]}
              <ReactSVG
                src={`${lc_data.dir}dist/${CloseIcon}`}
                className="ml-8 w-16 h-16 fill-field-icon"
              />
            </button>
          </div>

        </div>

        {props.children}

      </div>

    </div>
  );
};

const clickOutsideConfig = {
  handleClickOutside: () => Modal.handleClickOutside(),
};

export default onClickOutside(Modal, clickOutsideConfig);
