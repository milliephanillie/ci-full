/* global lc_data, React */
/**
 * External dependencies.
 */
import hammerIcon from '../../../../../../../images/icons/construction-hammer.svg';
import ReactSVG from 'react-svg';
import Bids from '../../bids/Bids';
import AuctionTimer from './partials/AuctionTimer';
import cx from 'classnames';
import { Fragment } from '@wordpress/element';

function Auction(props) {
  const { product, currentUser, fromSticky, iconSettings, options } = props;
  let optionsSingleListing = false;
  if (document.getElementById('page-single')) {
     optionsSingleListing = JSON.parse(document.getElementById('page-single').dataset.options);
  }

  const classes = cx({
    'mt-0 bg:flex-nowrap bg:justify-end': !fromSticky,
    'mt-0 flex-wrap sm:flex-no-wrap md:justify-end': fromSticky,
  });

  const buttonClasses = cx({
    'md:mt-0 md:ml-10': !fromSticky,
    'ml-0 mt-10 sm:mt-0 lg:ml-10': fromSticky,
  });

  let icon = null;
  let svg = null;

  if (iconSettings?.icon_price !== null && iconSettings?.icon_price) {
    typeof iconSettings.icon_price['value'] === 'string' ? icon = iconSettings.icon_price['value'] : svg = iconSettings.icon_price['value']['url'];
  }

  let membership_listings_bids = '';
  let login_url = '';

  if (options && options['membership-listings-bids']) {
    membership_listings_bids = options['membership-listings-bids'];
    login_url = options.login_url;
  } else if (iconSettings['membership_listings_bids']) {
    membership_listings_bids = iconSettings.membership_listings_bids;
    login_url = iconSettings.membership_listings_bids;
  }
  return (
    <div className={`auction--infos flex flex-wrap items-center whitespace-no-wrap text-sm ${classes}`}>

      {(!iconSettings || 'yes' === iconSettings?.display_countdown) &&
      <AuctionTimer product={product} currentUser={currentUser} iconClass="mr-6" iconSettings={iconSettings}/>
      }
      {
        (!membership_listings_bids || membership_listings_bids === 'always' || (membership_listings_bids === 'logged_in' && lc_data.logged_in === '1')) &&
        <Fragment>
          {(!iconSettings || 'yes' === iconSettings?.display_price) &&
          <div className="auction--info flex mx-10">
            {
              !icon && !svg &&
              <ReactSVG
                src={`${lc_data.dir}dist/${hammerIcon}`}
                className={`relative top-1 mr-6 w-16 h-16 fill-field-icon products-icon`}
              />
            }
            {
              svg && iconSettings?.place_icon_price !== '' &&
              <img src={svg} alt="cart-icon"
                   className="w-20 h-20 mr-8 products-icon fill-icon-reset pointer-events-none"/>
            }
            {
              iconSettings?.place_icon_price !== '' && icon &&
              <i className={`${icon} products-icon`} style={{
                display: 'flex',
                alignSelf: 'center'
              }}
                 aria-hidden="true"
              ></i>
            }
            <span className={`lisfinity-product--meta__price text-grey-1100 font-bold text-13`}
                  dangerouslySetInnerHTML={{ __html: product.product_meta.start_price_html }}></span>
          </div>
          }
          <div className={`auction--info flex ${buttonClasses}`}>
            <Bids product={product} currentUser={currentUser} options={props.options} iconSettings={iconSettings}/>
          </div>
        </Fragment>
      }
      {((!membership_listings_bids && lc_data.logged_in !== '1') || (membership_listings_bids === 'logged_in' && lc_data.logged_in !== '1')) &&
      <div className="flex justify-center align-middle mx-10 mt-10 border border-blue-300 bg-blue-200 p-10 rounded">
        <a href={login_url} className="text-blue-600 hover:underline">{lc_data.jst[720]}</a>
      </div>
      }

    </div>
  );
}

export default Auction;
