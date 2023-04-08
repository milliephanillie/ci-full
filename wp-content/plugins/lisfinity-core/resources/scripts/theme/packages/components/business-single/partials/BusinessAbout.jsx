/* global lc_data, React */

import { __ } from '@wordpress/i18n';
import { Map, Marker, TileLayer } from 'react-leaflet';
import { divIcon } from 'leaflet';
import OwnerPhones from '../../product-single/partials/sidebar/partials-owner/OwnerPhones';
import OwnerHours from '../../product-single/partials/sidebar/partials-owner/OwnerHours';
import ReactSVG from 'react-svg';
import FacebookIcon from '../../../../../../images/icons/facebook-original.svg';
import TwitterIcon from '../../../../../../images/icons/twitter-filled.svg';
import InstagramIcon from '../../../../../../images/icons/instagram-filled.svg';
import VKIcon from '../../../../../../images/icons/vk.svg';

const BusinessAbout = (props) => {
  const { business } = props;

  return (
    business &&
    <section className="business--about pt-20 pb-86">
      {business.premium_profile.about_us &&
      <div
        className="xl:w-3/4"
        dangerouslySetInnerHTML={{ __html: business.premium_profile.about_us }}/>
      }
    </section>
  );
};

export default BusinessAbout;
