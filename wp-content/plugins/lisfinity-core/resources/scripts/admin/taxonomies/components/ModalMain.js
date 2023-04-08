/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect } from 'react';
import ReactSVG from 'react-svg';
import cx from 'classnames';
import FrameExpandIcon from '../../../../images/icons/frame-expand.svg';
import CloseIcon from '../../../../images/icons/close.svg';
import { useRef } from '@wordpress/element';

const ModalMain = (props) => {
  const { title, width } = props;
  const [expanded, setExpanded] = useState(false);
  const [open, setOpen] = useState(props.open);
  const modalExpandClass = cx({
    'fixed top-0 left-0 w-full h-full': expanded,
  });
  const wrapperRef = useRef(null);

  const escFunction = (event) => {
    if (event.keyCode === 27) {
      setOpen(false);
      props.closeModal();
      const body = document.querySelector('body');
      body.style.overflow = 'auto';
    }
  };

  const clickedOutside = (e) => {
    if (wrapperRef.current && !wrapperRef.current.contains(e.target)) {
      setOpen(false);
      props.closeModal();
      const body = document.querySelector('body');
      body.style.overflow = 'auto';
    }
  };

  useEffect(() => {
    setOpen(props.open);
  }, [props.open]);

  useEffect(() => {
    document.addEventListener('keydown', escFunction, false);
    document.addEventListener('mousedown', clickedOutside);

    return () => {
      document.removeEventListener('keydown', escFunction, false);
      document.removeEventListener('mousedown', clickedOutside);
    };
  }, []);

  return (
    open && <div
      className="modal--wrapper modal-send-message fixed top-0 left-0 flex justify-center w-full h-full overflow-y-auto"
    >
      <div
        ref={wrapperRef}
        className={`modal my-auto relative z-2 whitespace-normal ${props.type === 'demo' ? 'xl:w-30%' : ''} ${modalExpandClass} ${width || ''}`}>

        <div className="modal--inner bg-white rounded shadow-xl overflow-hidden">

          <div className="modal--header flex justify-between items-center py-16 p-20 bg-grey-100">

            <h5
              className={`modal--title font-bold text-2xl ${props.type === 'demo' ? 'text-red-600' : ''}`}>{title || lc_data.jst[606]}</h5>

            <div className="modal--header__right flex items-center">
              {props.expand && !expanded &&
              <button type="button" className="flex justify-center items-center text-sm text-grey-700" onClick={() => setExpanded(true)}>
                {lc_data.jst[461]}
                <ReactSVG
                  src={`${lc_data.dir}dist/${FrameExpandIcon}`}
                  className="ml-8 w-16 h-16 fill-field-icon"
                />
              </button>}
              {props.expand && expanded &&
              <button type="button" className="flex justify-center items-center text-sm text-grey-700" onClick={() => setExpanded(false)}>
                {lc_data.jst[462]}
                <ReactSVG
                  src={`${lc_data.dir}dist/${FrameExpandIcon}`}
                  className="ml-8 w-16 h-16 fill-field-icon"
                />
              </button>}
              <button type="button" className="flex justify-center items-center ml-20 text-sm text-grey-700" onClick={() => {
                props.closeModal();
                setOpen(false);
              }}>
                {lc_data.jst[463]}
                <ReactSVG
                  src={`${lc_data.dir}dist/${CloseIcon}`}
                  className="ml-8 w-16 h-16 fill-field-icon"
                />
              </button>
            </div>

          </div>

          <div className="py-30 px-40">
            {props.children}
          </div>

        </div>

      </div>
    </div>
  );
};

export default ModalMain;
