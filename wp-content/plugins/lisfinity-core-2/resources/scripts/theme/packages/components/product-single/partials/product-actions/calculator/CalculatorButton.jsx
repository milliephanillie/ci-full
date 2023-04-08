/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect, Fragment } from 'react';
import ReactSVG from 'react-svg';

/**
 * Internal dependencies.
 */
import CalculatorIcon from '../../../../../../../../images/icons/calculator.svg';
import { sprintf, __ } from '@wordpress/i18n';
import Modal from '../../../../modal/Modal';
import Calculator from '../../sidebar/Calculator';

const CalculatorButton = (props) => {
  const { product, currentUser, settings } = props;
  const [modalOpen, setModalOpen] = useState(false);
  let icon = null;
  let svg = null;
  let actionCalculatorIndex;

  actionCalculatorIndex = settings?.actions && settings?.actions.findIndex(action => action.actions === 'calculator');

  if (settings?.actions[actionCalculatorIndex].selected_icon_action !== null && settings?.actions[actionCalculatorIndex].selected_icon_action) {
    typeof settings.actions[actionCalculatorIndex].selected_icon_action['value'] === 'string' ? icon = settings.actions[actionCalculatorIndex].selected_icon_action['value'] : svg = settings.actions[actionCalculatorIndex].selected_icon_action['value']['url'];
  }

  const escFunction = (event) => {
    if (event.keyCode === 27) {
      setModalOpen(false);
      const body = document.querySelector('body');
      body.style.overflow = 'auto';
    }
  };

  const handleClickOutside = () => {
    setModalOpen(false);
    const body = document.querySelector('body');
    body.style.overflow = 'auto';
  };

  useEffect(() => {
    window.addEventListener('keydown', escFunction, false);
    return () => {
      window.removeEventListener('keydown', escFunction, false);
    };
  });

  return (
    <Fragment>
      <button
        type="button"
        className={`flex-center py-4 px-10 text-semibold text-grey-600 bg-white rounded elementor-repeater-item-${props.elementId}`}
        onClick={() => setModalOpen(true)}
        style={{
          display: 'flex',
          justifyContent: 'center',
          alignItems: 'center'
        }}
      >
        {(icon === null && svg === null || '' == icon) &&
        <ReactSVG
          src={`${lc_data.dir}dist/${CalculatorIcon}`}
          className={`mr-8 w-16 product-icon h-16 fill-yellow-700`}
        />
        }

        {
          svg && settings?.actions[actionCalculatorIndex].place_icon_action !== '' &&
          <img src={svg} alt="calculator-icon"
               className="w-20 h-20 mr-8 product-icon fill-icon-reset pointer-events-none"/>
        }
        {
          settings?.actions[actionCalculatorIndex].place_icon_action !== '' && icon &&
          <i className={`${icon} product-icon`}
             aria-hidden="true"
          ></i>
        }
        {lc_data.jst[511]}
      </button>
      {modalOpen && <div
        key={1}
        className="modal--wrapper modal-send-message fixed top-0 left-0 flex justify-center w-full h-full overflow-y-auto"
      >
        <Modal
          product={product}
          open={modalOpen}
          title={lc_data.jst[509]}
          handleClickOutside={handleClickOutside}
          closeModal={() => setModalOpen(false)}
        >
          <div className="modal--product-info flex flex-wrap p-30 pb-40">
            {product.thumbnail &&
            <figure className="relative flex w-1/5 min-w-1/5 h-auto sm:h-100">
              <img
                className="absolute top-0 left-0 w-full h-full rounded object-cover" src={product.thumbnail.url}
                alt={product.post_title}/>
            </figure>}
            <div className="flex flex-col pl-30 w-4/5">
              <h4 className="font-bold text-grey-1000">{product.post_title}</h4>
            </div>
          </div>
          <div className="px-30 pb-40">
            <Calculator product={product} currentUser={currentUser}/>
          </div>
        </Modal>
      </div>}
    </Fragment>
  );
};

export default CalculatorButton;
