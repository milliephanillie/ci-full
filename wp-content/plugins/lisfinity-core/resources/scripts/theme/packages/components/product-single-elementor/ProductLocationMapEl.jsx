/* global lc_data, React */
/**
 * Dependencies.
 */
import {Component, createRef, Fragment, useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import {getProduct, setProduct} from '../../store/actions';
import LoaderProductSingle from '../loaders/LoaderProductSingle';
import {ToastContainer} from 'react-toastify';
import {useDispatch, useSelector} from 'react-redux';
import ReactSVG from "react-svg";
import PinIcon from "../../../../../images/icons/map-marker.svg";
import {Map, Marker, TileLayer} from "react-leaflet";
import {storeStat} from "../../../vendor/functions";
import Modal from "../modal/Modal";
import {divIcon} from "leaflet";
import queryString from "query-string";
import {isEmpty} from "lodash";

/**
 * Internal dependencies
 */

const ProductLocationMapEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const {product, options} = data;
  const [modalOpen, setModalOpen] = useState(false);

  const [elSettings, setElSettings] = useState({});
  let wrapper = null;

  let el = createRef();

  useEffect(() => {
    wrapper = el.current.closest('.elementor-product-location-map');
    if (wrapper && wrapper.dataset.settings) {
      const settings = JSON.parse(wrapper.dataset.settings);
      setElSettings(settings);
    }
  }, []);

  let location = elSettings && elSettings.location === 'listing_location' ? product?.location : product?.premium_profile?.location;

  if(location) {
    location['address'] = elSettings && elSettings.location === 'listing_location' ? elSettings['address']: product?.premium_profile?.location.address;
  }

  const mapUrl = lc_data.mapbox_url !== '' ? lc_data.mapbox_url : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
  const marker = divIcon({
    className: 'profile--divicon',
    html: `<div class="marker flex items-center p-5 bg-blue-500 border-4 border-white rounded-full"></div>`,
  });
  const markerBig = divIcon({
    className: 'profile--divicon',
    html: `<div class="marker flex items-center p-10 bg-blue-500 border-4 border-white rounded-full"></div>`,
  });

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
  let lat = null;
  let lng = null;

  if (!isNaN(parseFloat(location?.lat))) {
    lat = location && location?.lat ? parseFloat(location?.lat) : location?.lat;
    lng = location && location?.lng ? parseFloat(location?.lng) : location?.lng;
  }


  return (
    <div className="profile--location flex flex-wrap mt-30" ref={el}>
      {(elSettings['membership_address'] === 'always' || (elSettings['membership_address'] === 'logged_in' && lc_data.logged_in === '1')) &&
      <Fragment>
        <div className="profile--map pr-6 w-full rounded overflow-hidden" style={{height: 170}}>
          <Map
            zoom={8}
            zoomControl={false}
            scrollWheelZoom={false}
            dragging={false}
            center={lat !== null && lng !== null ? [lat, lng] : ['', '']}>

            <TileLayer url={mapUrl}/>
            <Marker
              position={lat !== null && lng !== null ? [lat, lng] : ['', '']}
              icon={marker}
            />
          </Map>
        </div>

        <div className="profile--address mt-10">
          <address
            className="mt-2 font-bold not-italic capitalize text-grey-900 leading-none">{location?.address}</address>
          <button
            type="button"
            className="text-13 text-grey-500 profile--address-button"
            onClick={() => {
              setModalOpen(true);
              storeStat(product?.ID, 2);
            }}
          >
            {lc_data.jst[599]}
          </button>

          {modalOpen &&
          <div
            key={1}
            className="modal--wrapper fixed top-0 left-0 flex justify-center w-full h-full overflow-y-auto"
          >
            <Modal
              product={product}
              open={modalOpen}
              title={lc_data.jst[540]}
              handleClickOutside={handleClickOutside}
              closeModal={() => setModalOpen(false)}
            >
              <div className="profile--map h-512 w-full rounded overflow-hidden">
                <Map
                  zoom={17}
                  zoomControl
                  scrollWheelZoom
                  dragging
                  center={lat !== null && lng !== null ? [lat, lng] : ['', '']}
                  style={{height: '100%', width: '100%', borderRadius: '3px'}}
                >
                  <TileLayer url={mapUrl}/>
                  <Marker
                    position={lat !== null && lng !== null ? [lat, lng] : ['', '']}
                    icon={markerBig}
                  />
                </Map>
              </div>
            </Modal>
          </div>}

        </div>
      </Fragment>}
    </div>
  );
};

export default ProductLocationMapEl;
