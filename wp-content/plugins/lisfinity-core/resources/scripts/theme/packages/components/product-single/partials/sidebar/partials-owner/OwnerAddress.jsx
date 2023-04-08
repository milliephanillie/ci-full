/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect, Fragment } from 'react';
import { isEmpty } from 'lodash';
import { __ } from '@wordpress/i18n';
import { Map, Marker, TileLayer } from 'react-leaflet';
import { divIcon } from 'leaflet';

/**
 * Internal dependencies
 */
import starIcon from '../../../../../../../../images/icons/star.svg';
import OwnerPhone from './OwnerPhone';
import Modal from '../../../../modal/Modal';
import { storeStat } from '../../../../../../vendor/functions';

const OwnerAddress = (props) => {
  const { product } = props;
  const [modalOpen, setModalOpen] = useState(false);
  const { location } = product.premium_profile;
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

  return (
    <div className="profile--location flex flex-wrap mt-20">
      <div className="profile--map pr-6 h-86 w-2/5 rounded overflow-hidden">
        <Map
          zoom={19}
          zoomControl={false}
          scrollWheelZoom={false}
          dragging={false}
          center={[parseFloat(location.lat), parseFloat(location.lng)]}
          style={{ height: '100%', width: '100%', borderRadius: '3px' }}
        >
          <TileLayer url={mapUrl}/>
          <Marker
            position={[parseFloat(location.lat), parseFloat(location.lng)]}
            icon={marker}
          />
        </Map>
      </div>
      <div className="profile--address pl-8">
        <address className="font-light not-italic capitalize text-grey-1000">{location.address}</address>
        <button
          type="button"
          className="text-sm text-grey-500"
          onClick={() => {
            setModalOpen(true)
            storeStat(product.ID, 2)
          }}
        >
          {lc_data.jst[539]}
        </button>

        {modalOpen && <div
          key={1}
          className="modal--wrapper modal-send-message fixed top-0 left-0 flex justify-center w-full h-full overflow-y-auto"
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
                center={[parseFloat(location.lat), parseFloat(location.lng)]}
                style={{ height: '100%', width: '100%', borderRadius: '3px' }}
              >
                <TileLayer url={mapUrl}/>
                <Marker
                  position={[parseFloat(location.lat), parseFloat(location.lng)]}
                  icon={markerBig}
                />
              </Map>
            </div>
          </Modal>
        </div>}

      </div>
    </div>
  );
};

export default OwnerAddress;
