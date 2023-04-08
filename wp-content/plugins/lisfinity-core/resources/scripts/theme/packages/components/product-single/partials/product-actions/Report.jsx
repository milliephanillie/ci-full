/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect, Fragment } from 'react';
import ReactSVG from 'react-svg';
import cx from 'classnames';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies.
 */
import FlagIcon from '../../../../../../../images/icons/flag.svg';
import Modal from '../../../modal/Modal';
import { Map, Marker, TileLayer } from 'react-leaflet';
import SendReport from '../../../forms/SendReport';

function Report(props) {
  const { product, currentUser, settings } = props;
  const [modalOpen, setModalOpen] = useState(false);
  const [message, setMessage] = useState('');
  const [maxLength, setMaxLength] = useState(300);
  const [errors, setErrors] = useState({});
  const [success, setSuccess] = useState({});

  let icon = null;
  let svg = null;
  let actionReportIndex = null;

  actionReportIndex = settings?.actions && settings?.actions.findIndex(action => action.actions === 'report');

  if ( settings?.actions[actionReportIndex].selected_icon_action !== null && settings?.actions[actionReportIndex].selected_icon_action) {
    typeof settings.actions[actionReportIndex].selected_icon_action['value'] === 'string' ? icon = settings.actions[actionReportIndex].selected_icon_action['value'] : svg = settings.actions[actionReportIndex].selected_icon_action['value']['url'];
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

  const handleMessage = (e) => {
    setErrors(false);
    setMessage(e.target.value);
  };

  useEffect(() => {
    window.addEventListener('keydown', escFunction, false);
    return () => {
      window.removeEventListener('keydown', escFunction, false);
    };
  });

  const submit = (e, reason) => {
    e.preventDefault();
    const headers = new Headers();
    headers.append('X-WP-Nonce', lc_data.nonce);
    const formData = new FormData();
    formData.append('product', product.ID);
    formData.append('reason', reason);
    formData.append('message', message);
    formData.append('_wpnonce', lc_data.nonce);
    fetch(`${lc_data.report_submit}`, {
      method: 'POST',
      credentials: 'same-origin',
      headers,
      body: formData,
    }).then(response => response.json()).then(response => {
      if (response.error) {
        setErrors(response.errors);
      }
      if (response.success) {
        setSuccess(response.message);
      }
    });
  };

  return (
    <Fragment>
      <button
        type="button"
        className={`product--action text-base elementor-repeater-item-${props.elementId}`}
        onClick={() => setModalOpen(true)}
        style={{
          display: 'flex',
          justifyContent: 'center',
          alignItems: 'center'
        }}
      >
        {(icon === null && svg === null || "" == icon)  &&
        <ReactSVG
          src={`${lc_data.dir}dist/${FlagIcon}`}
          className={`mr-2 w-16 h-16 product-icon fill-field-icon`}
        />
        }

        {
          svg && settings?.actions[actionReportIndex].place_icon_action !== '' &&
          <img src={svg} alt="report-icon"
               className="w-20 h-20 mr-8 product-icon fill-icon-reset pointer-events-none"/>
        }
        {
          settings?.actions[actionReportIndex].place_icon_action !== '' && icon &&
          <i className={`${icon} product-icon`}
             aria-hidden="true"
          ></i>
        }
        {lc_data.jst[509]}
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
          <SendReport
            product={product}
            currentUser={currentUser}
            handleMessage={handleMessage}
            handleSubmit={submit}
            message={message}
            maxLength={maxLength}
            errors={errors}
            success={success}
            options={props.options}
          />
        </Modal>
      </div>}

    </Fragment>
  );
}

export default Report;
