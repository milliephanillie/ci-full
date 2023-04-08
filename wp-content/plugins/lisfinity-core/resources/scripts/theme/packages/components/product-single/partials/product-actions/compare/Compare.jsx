/* global lc_data, React */
/**
 * External dependencies.
 */
import {useState, useEffect, Fragment} from 'react';
import ReactSVG from 'react-svg';

/**
 * Internal dependencies.
 */
import AddFileIcon from '../../../../../../../../images/icons/add-file.svg';
import Modal from '../../../../modal/Modal';
import CompareProducts from './CompareProducts';

const Compare = (props) => {
  const {product, currentUser, settings, options} = props;
  const [products, setProducts] = useState(lc_data.compare);
  const [modalOpen, setModalOpen] = useState(false);
  const [loading, setLoading] = useState(true);

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

  const manageProducts = (proceed = true) => {
    const formData = new FormData();
    formData.append('product_id', product.ID);
    formData.append('type', product.product_meta.category);
    let user = lc_data.current_user_id;
    if (!user || user == 0) {
      user = lc_data.user_ip;
    }
    setLoading(true);
    fetch(`${lc_data.product_action}/compare?user_id=${user}`, {
      method: 'POST',
      body: formData,
    }).then(response => response.json()).then(response => {
      setProducts(response.products);
      setModalOpen(true);
      setLoading(false);
    });
  };

  let icon = null;
  let svg = null;
  let actionCompareIndex = null;

  actionCompareIndex = settings?.actions.findIndex(action => action.actions === 'compare');

  if (settings?.actions[actionCompareIndex].selected_icon_action !== null && settings?.actions[actionCompareIndex].selected_icon_action) {
    typeof settings.actions[actionCompareIndex].selected_icon_action['value'] === 'string' ? icon = settings.actions[actionCompareIndex].selected_icon_action['value'] : svg = settings.actions[actionCompareIndex].selected_icon_action['value']['url'];
  }
  return (
    <Fragment>
      <button
        type="button"
        className={`flex-center py-4 px-10 text-semibold text-grey-600 bg-white rounded elementor-repeater-item-${props.elementId}`}
        onClick={() => manageProducts(false)}
        style={{
          display: 'flex',
          justifyContent: 'center',
          alignItems: 'center'
        }}
      >
        {(icon === null && svg === null || "" == icon) &&
        <ReactSVG
          src={`${lc_data.dir}dist/${AddFileIcon}`}
          className={`mr-8 product-icon w-16 h-16 fill-blue-700`}
        />
        }

        {
          svg && settings?.actions[actionCompareIndex].place_icon_action !== '' &&
          <img src={svg} alt="compare-icon"
               className="w-20 h-20 mr-8 product-icon fill-icon-reset pointer-events-none"/>
        }
        {
          settings?.actions[actionCompareIndex].place_icon_action !== '' && icon &&
          <i className={`${icon} product-icon`}
             aria-hidden="true"
          ></i>
        }
        {lc_data.jst[513]}
      </button>

      {modalOpen && <div
        key={1}
        className="modal--wrapper modal-send-message fixed top-0 left-0 flex justify-center w-full h-full overflow-y-auto"
      >
        <Modal
          product={product}
          open={modalOpen}
          title={lc_data.jst[309]}
          handleClickOutside={handleClickOutside}
          width="w-90% md:w-3/5 lg:w-3/5 xxl:w-2/5"
          closeModal={() => setModalOpen(false)}
        >
          <CompareProducts
            products={products}
            product={product}
            currentUser={currentUser}
            options={options}
            loading={loading}
          />
        </Modal>
      </div>}

    </Fragment>
  );
};

export default Compare;
