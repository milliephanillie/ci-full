/* global lc_data, React */

import {__} from '@wordpress/i18n';
import {Map, Marker, TileLayer} from 'react-leaflet';
import {divIcon} from 'leaflet';
import {isEmpty} from 'lodash';
import OwnerPhones from '../../product-single/partials/sidebar/partials-owner/OwnerPhones';
import OwnerHours from '../../product-single/partials/sidebar/partials-owner/OwnerHours';
import ReactSVG from 'react-svg';
import FacebookIcon from '../../../../../../images/icons/facebook-original.svg';
import TwitterIcon from '../../../../../../images/icons/twitter-filled.svg';
import InstagramIcon from '../../../../../../images/icons/instagram-filled.svg';
import VKIcon from '../../../../../../images/icons/vk.svg';

const BusinessContact = (props) => {
  const {business, options} = props;
  const {location, social} = business.premium_profile;
  const mapUrl = lc_data.mapbox_url !== '' ? lc_data.mapbox_url : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
  const image = business.premium_profile.thumbnail &&
    `<figure class="marker--image relative flex-center p-24 bg-white rounded-full"><img src=${business.premium_profile.thumbnail} class="absolute p-4 w-full h-full object-contain"
                    /><span class="marker--triangle"></span></figure>`;
  const markerBig = divIcon({
    className: 'business--divicon',
    html: `<div class="marker flex items-center">${image}</div>`,
  });

  return (
    business &&
    <section className="business--contact pt-20 pb-86">
      <div className="container">

        <div className="flex flex-wrap shadow-theme">

          {business.premium_profile.location_map_show && (options['membership-address'] === 'always' || (options['membership-address'] === 'logged_in' && lc_data.logged_in === '1')) &&
          <div className="business--map w-full rounded-t overflow-hidden z-1" style={{height: '380px'}}>
            <Map
              zoom={16}
              zoomControl={false}
              scrollWheelZoom={false}
              dragging={false}
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
          }

          <div className="business-information p-40 w-full bg-white rounded-b">
            <div className="flex flex-wrap -mx-20">

              {business.premium_profile.phones && (options['membership-phone'] === 'always' || (options['membership-phone'] === 'logged_in' && lc_data.logged_in === '1')) &&
              <div className="flex flex-col px-20 w-full md:w-1/2 bg:w-1/3">
                <h6 className="mb-20 font-semibold">{lc_data.jst[386]}</h6>
                <OwnerPhones product={business} options={options} />
              </div>
              }

              {!isEmpty(business.premium_profile.hours) && options?.hours_enabled &&
              <div className="flex flex-col px-20 w-full mt-30 md:w-1/2 md:mt-0 bg:w-1/3">
                <h6 className="-mb-10 font-semibold">{lc_data.jst[387]}</h6>
                <OwnerHours product={business} alwaysOpen={true}/>
              </div>
              }

              <div className="flex flex-col px-20 w-full mt-30 md:w-1/2  bg:mt-0 bg:w-1/3">
                <h6 className="mb-20 font-semibold">{lc_data.jst[388]}</h6>
                <div>
                  {
                    location && (options['membership-address'] === 'always' || (options['membership-address'] === 'logged_in' && lc_data.logged_in === '1')) &&
                    <p className="font-light">{location?.address}</p>
                  }
                  {
                    business?.premium_profile && business?.premium_profile?.website &&
                    <div className="flex">
                      <p className="-mb-10 font-light mr-3">{lc_data.jst[735]}</p>
                      <a href={business?.premium_profile?.website}
                         className="font-light text-blue-700 hover:text-blue-800" style={{ wordBreak: 'break-word'}}>{business?.premium_profile?.website}</a>
                    </div>
                  }
                  {
                    business?.premium_profile && business?.premium_profile?.email &&
                    <div className="flex">
                      <p className="-mb-10 font-light mr-3">{lc_data.jst[724]}</p>
                      <a href={`mailto:${business?.premium_profile?.email}`}
                         className="font-light text-blue-700 hover:text-blue-800" style={{ wordBreak: 'break-word'}}>{business?.premium_profile?.email}</a>
                    </div>
                  }
                  <div className="business--social flex flex-wrap -mx-6 mt-20">
                    {social.facebook &&
                    <a href={social.facebook} rel="nofollow" target="_blank"
                       className="flex-center mx-6 w-36 h-36 border-2 border-grey-700 rounded-full">
                      <ReactSVG
                        src={`${lc_data.dir}dist/${FacebookIcon}`}
                        className="relative w-20 h-20 fill-grey-700"
                      />
                    </a>
                    }
                    {social.twitter &&
                    <a href={social.twitter} rel="nofollow" target="_blank"
                       className="flex-center mx-6 w-36 h-36 border-2 border-grey-700 rounded-full">
                      <ReactSVG
                        src={`${lc_data.dir}dist/${TwitterIcon}`}
                        className="relative w-20 h-20 fill-grey-700"
                      />
                    </a>
                    }
                    {social.instagram &&
                    <a href={social.instagram} rel="nofollow" target="_blank"
                       className="flex-center mx-6 w-36 h-36 border-2 border-grey-700 rounded-full">
                      <ReactSVG
                        src={`${lc_data.dir}dist/${InstagramIcon}`}
                        className="relative w-20 h-20 fill-grey-700"
                      />
                    </a>
                    }
                    {social.vk &&
                    <a href={social.vk} rel="nofollow" target="_blank"
                       className="flex-center mx-6 w-36 h-36 border-2 border-grey-700 rounded-full">
                      <ReactSVG
                        src={`${lc_data.dir}dist/${VKIcon}`}
                        className="relative w-20 h-20 fill-grey-700"
                      />
                    </a>
                    }
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>
    </section>
  );
};

export default BusinessContact;
