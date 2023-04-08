/* global lc_data, React */
/**
 * External dependencies.
 */
import {useState, useEffect, Fragment} from 'react';
import ReactSVG from 'react-svg';
import {isEmpty} from 'lodash';
import {__} from '@wordpress/i18n';

/**
 * Internal dependencies.
 */
import ShareIcon from '../../../../../../../images/icons/share.svg';
import Modal from '../../../modal/Modal';
import ShareList from './share/ShareList';

function Share(props) {
  const {product, currentUser, settings} = props;
  const [reportIps, setReportIps] = useState([]);
  const [modalOpen, setModalOpen] = useState(false);
  let icon = null;
  let svg = null;
  let actionShareIndex = null;

  actionShareIndex = settings?.actions && settings?.actions.findIndex(action => action.actions === 'share');

  if (settings?.actions[actionShareIndex].selected_icon_action !== null && settings?.actions[actionShareIndex].selected_icon_action) {
    typeof settings.actions[actionShareIndex].selected_icon_action['value'] === 'string' ? icon = settings.actions[actionShareIndex].selected_icon_action['value'] : svg = settings.actions[actionShareIndex].selected_icon_action['value']['url'];
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
  }, []);

  return (
    !isEmpty(product.share) && <Fragment>
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
        {(icon === null && svg === null || "" == icon) &&
        <ReactSVG
          src={`${lc_data.dir}dist/${ShareIcon}`}
          className={`mr-6 w-16 product-icon h-16 fill-field-icon`}
        />}

        {
          svg && settings?.actions[actionShareIndex].place_icon_action !== '' &&
          <img src={svg} alt="share-icon"
               className="w-20 h-20 mr-8 product-icon fill-icon-reset pointer-events-none"/>
        }
        {
          settings?.actions[actionShareIndex].place_icon_action !== '' && icon &&
          <i className={`${icon} product-icon`} aria-hidden="true"
          ></i>
        }
        {lc_data.jst[510]}
      </button>
      {modalOpen && <div
        key={1}
        className="modal--wrapper modal-send-message fixed top-0 left-0 flex justify-center w-full h-full overflow-y-auto"
      >
        <Modal
          product={product}
          isLogged={currentUser}
          open={modalOpen}
          title={lc_data.jst[510]}
          handleClickOutside={handleClickOutside}
          closeModal={() => setModalOpen(false)}
        >
          <div className="modal--product-info flex flex-wrap p-30 pb-40">
            {product?.thumbnail?.url &&
            <figure className="relative flex w-1/5 min-w-1/5 h-auto sm:h-100">
              <img
                className="absolute top-0 left-0 w-full h-full rounded object-cover" src={product.thumbnail.url}
                alt={product.post_title}/>
            </figure>}
            <div className={`flex flex-col w-4/5 ${product?.thumbnail?.url ? 'pl-30' : ''}`}>
              <h4 className="font-bold text-grey-1000">{product.post_title}</h4>
            </div>
          </div>
          <ShareList product={product} currentUser={currentUser}/>
        </Modal>
      </div>}
    </Fragment>
  );
}

export default Share;
