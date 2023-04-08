/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import * as actions from '../../store/actions';
import { useEffect, useRef, useState } from '@wordpress/element';
import ReactSVG from 'react-svg';
import CompareIcon from '../../../../../images/icons/shuffle.svg';
import { Fragment } from 'react';
import Modal from '../../../../theme/packages/components/modal/Modal';
import { __ } from '@wordpress/i18n';
import CompareProducts
  from '../../../../theme/packages/components/product-single/partials/product-actions/compare/CompareProducts';
import Login from '../../../../theme/packages/components/forms/Login';
import ReactTooltip from 'react-tooltip';

const HeaderCompareWrapper = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { business, menuOpen, notifications, notificationsMenu } = data;
  const [products, setProducts] = useState(lc_data.compare);
  const [modalOpen, setModalOpen] = useState(false);
  const [options, setOptions] = useState({});
  const [settings, setSettings] = useState({});
  const el = useRef(null);

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
    const id = document.getElementById('main-menu-ul');
    if (id) {
      const options = JSON.parse(id.dataset.options);
      setOptions(options);
    }
    const settingsId = el.current.closest('.compare--wrapper');
    if (settingsId && settingsId.dataset.settings) {
      const settings = JSON.parse(settingsId.dataset.settings);
      setSettings(settings);
    }
  }, []);

  useEffect(() => {
    window.addEventListener('keydown', escFunction, false);
    return () => {
      window.removeEventListener('keydown', escFunction, false);
    };
  });

  return (
    <Fragment>
      <div ref={el}>
        <button
          type="button"
          className={`relative user--notifications ml-20 ${props.mobile ? 'top-2' : 'top-2'}`}
          onClick={() => setModalOpen(true)}
          title={lc_data.jst[554]}
        >
          {(!settings || !settings.custom_icon || (!settings.custom_icon_url && !settings.custom_icon_font)) &&
          <ReactSVG
            src={`${lc_data.dir}dist/${CompareIcon}`}
            className="w-20 h-20 fill-icon-reset pointer-events-none"
            id="compare--icon"
          />
          }
          {
            settings?.custom_icon && settings?.custom_icon_url &&
            <img src={settings.custom_icon_url} alt="cart-icon" id="compare--icon"
                 className="w-20 h-20 fill-icon-reset pointer-events-none"/>
          }
          {
            settings?.custom_icon && settings?.custom_icon_font &&
            <i className={settings.custom_icon_font} id="compare--icon" aria-hidden="true"></i>
          }
        </button>

        {modalOpen &&
        <div
          className="modal--wrapper modal-send-message fixed top-0 left-0 flex justify-center w-full h-full overflow-y-auto"
        >
          <Modal
            open={modalOpen}
            title={lc_data.jst[309]}
            handleClickOutside={handleClickOutside}
            closeModal={() => setModalOpen(false)}
            width="w-90% md:w-3/5 lg:w-3/5 xxl:w-2/5"
          >
            {lc_data.logged_in &&
            <CompareProducts
              products={products}
              product={false}
              currentUser={lc_data.current_user_id}
              loading={false}
              closeModal={() => setModalOpen(false)}
            />}
            {!lc_data.logged_in &&
            <Login/>}
          </Modal>
        </div>}

      </div>
    </Fragment>
  );
};

export default HeaderCompareWrapper;
