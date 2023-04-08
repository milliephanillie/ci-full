/* global lc_data, React */
/**
 * External dependencies.
 */
import onClickOutside from 'react-onclickoutside';
import { useState, } from 'react';
import { __ } from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import CloseIcon from '../../../../images/icons/close.svg';

const Modal = (props) => {
  const { open, title, handleClickOutside, closeModal, width } = props;

  Modal.handleClickOutside = () => handleClickOutside;

  return (
    open &&
    <div className={`modal relative my-auto z-2 whitespace-normal ${width || ''} font-sans`}>

      <div className="modal--inner bg-white rounded shadow-xl overflow-hidden">

        <div className="modal--header flex justify-between items-center py-20 px-30 bg-grey-100">

          <h5 className="modal--title font-bold text-2xl text-grey-1000">{title}</h5>

          <div className="modal--header__right flex items-center">
            <button type="button" className="flex justify-center items-center ml-20 text-sm text-grey-700"
                    onClick={closeModal}>
              {lc_data.jst[95]}
              <ReactSVG
                src={`${lc_data.dir}dist/${CloseIcon}`}
                className="ml-8 w-16 h-16 fill-field-icon"
              />
            </button>
          </div>

        </div>

        <div className="p-30">
          {props.children}
        </div>

      </div>

    </div>
  );
};

const clickOutsideConfig = {
  handleClickOutside: () => Modal.handleClickOutside(),
};

export default onClickOutside(Modal, clickOutsideConfig);
