/* global lc_data, React */
/**
 * Dependencies.
 */
import {useState, useEffect, Fragment} from 'react';
import {Map, Marker, TileLayer} from 'react-leaflet';
import {divIcon} from 'leaflet';
import Modal from '../../../../modal/Modal';
import {storeStat} from '../../../../../../vendor/functions';
import PinIcon from '../../../../../../../../images/icons/map-marker.svg';
import ReactSVG from 'react-svg';
import {isEmpty} from 'lodash';

const OwnerAddressAlt = (props) => {
  const {product, options, currentUser} = props;
  const [modalOpen, setModalOpen] = useState(false);
  let location = options['product-search-map-location'] === 'listing_location' && !isEmpty(product?.location?.lat) ? product?.location : options['product-search-map-location'] === 'listing_location' && !isEmpty(product?.location?.lat) && options.account_type === 'personal' ? product?.location : product.premium_profile.location;
  location['address'] = options['product-search-map-location'] === 'listing_location' && isEmpty(product?.location) ? options?.address : options['product-search-map-location'] === 'listing_location' && !isEmpty(product?.location.address) ? product?.location.address : options['product-search-map-location'] === 'listing_location' && (options.account_type === 'personal' || options.account_type === 'business')  && isEmpty(product?.location.address) ? options?.address : options['product-search-map-location'] === 'listing_location' && (options.account_type === 'personal' || options.account_type === 'business') && !isEmpty(product?.location.address) ? product?.location.address : product.premium_profile.location.address;
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
    <div className="profile--location flex flex-wrap mt-30">
      <div className="profile--widget-title flex items-center mb-20 py-14 px-20 -ml-20 rounded-r bg-grey-100"
           style={{
             width: 'calc(100% + 16px)',
           }}
      >
        <ReactSVG
          src={`${lc_data.dir}dist/${PinIcon}`}
          className="mr-10 w-16 h-16 fill-blue-700"
        />
        <span className="font-bold text-grey-900">{lc_data.jst[598]}</span>
      </div>
      <div className="profile--map pr-6 w-full rounded overflow-hidden" style={{height: 170}}>
        <Map
          zoom={8}
          zoomControl={false}
          scrollWheelZoom={false}
          dragging={false}
          center={[parseFloat(location.lat), parseFloat(location.lng)]}
          style={{height: '100%', width: '100%', borderRadius: '3px'}}
        >
          <TileLayer url={mapUrl}/>
          <Marker
            position={[parseFloat(location.lat), parseFloat(location.lng)]}
            icon={marker}
          />
        </Map>
      </div>
      <div className="profile--address mt-10">
        <address
          className="mt-2 font-bold not-italic capitalize text-grey-900 leading-none">{location.address}</address>
        <button
          type="button"
          className="text-13 text-grey-500"
          onClick={() => {
            setModalOpen(true);
            storeStat(product.ID, 2);
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
                center={[parseFloat(location.lat), parseFloat(location.lng)]}
                style={{height: '100%', width: '100%', borderRadius: '3px'}}
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

export default OwnerAddressAlt;
