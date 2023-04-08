/* global lc_data, React */

import {__} from '@wordpress/i18n';
import {Map, Marker, TileLayer} from 'react-leaflet';
import {divIcon} from 'leaflet';
import {createRef, useEffect, useState} from "@wordpress/element";
import {useSelector} from "react-redux";

const BusinessContactEl = () => {
  const data = useSelector(state => state);
  const mapUrl = lc_data.mapbox_url !== '' ? lc_data.mapbox_url : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
  const image = data?.product?.premium_profile?.thumbnail &&
    `<figure class="marker--image relative flex-center p-24 bg-white rounded-full"><img src=${data?.product?.premium_profile?.thumbnail} class="absolute p-4 w-full h-full object-contain"
                    /><span class="marker--triangle"></span></figure>`;
  const markerBig = divIcon({
    className: 'business--divicon',
    html: `<div class="marker flex items-center">${image}</div>`,
  });

  const [options, setOptions] = useState({});
  let wrapper = null;
  let el = createRef();

  useEffect(() => {
    wrapper = el.current.closest('.elementor-business-contact');
    if (wrapper && wrapper.dataset.settings) {
      const settings = JSON.parse(wrapper.dataset.settings);
      setOptions(settings);
    }
  }, []);
  return (
    data && data?.product &&
    <section className="business--contact" ref={el}>
      { (options['membership_address'] === 'always' || (options['membership_address'] === 'logged_in' && lc_data.logged_in === '1')) &&
      <div className="container p-0">

        <div className="flex flex-wrap business--map-wrapper shadow-theme">

          {data?.product?.premium_profile?.location_map_show && options.display_map &&
          <div className="business--map w-full rounded-t overflow-hidden z-1">
            <Map
              zoom={16}
              zoomControl={false}
              scrollWheelZoom={false}
              dragging={false}
              center={[parseFloat(data?.product?.premium_profile?.location.lat), parseFloat(data?.product?.premium_profile?.location.lng)]}
              style={{height: '100%', width: '100%', borderRadius: '3px'}}
            >
              <TileLayer url={mapUrl}/>
              <Marker
                position={[parseFloat(data?.product?.premium_profile?.location.lat), parseFloat(data?.product?.premium_profile?.location.lng)]}
                icon={markerBig}
              />
            </Map>
          </div>
          }

        </div>
        {options.display_address && <p className="business--location">{data?.product?.premium_profile?.location?.address}</p>}
      </div>
      }
      { (options['membership_address'] === 'logged_in' && lc_data.logged_in !== '1') &&
      <div className="flex justify-center align-middle mx-10 mt-10 border border-blue-300 bg-blue-200 p-10 rounded">
        <a href={options.login_url} className="text-blue-600 hover:underline">{lc_data.jst[720]}</a>
      </div>
      }
    </section>
  );
};

export default BusinessContactEl;
