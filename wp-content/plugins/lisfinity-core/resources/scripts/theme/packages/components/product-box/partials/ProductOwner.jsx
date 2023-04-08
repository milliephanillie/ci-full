/* global lc_data, React */
/**
 * External dependencies.
 */
import {Component, Fragment} from '@wordpress/element';
import {connect} from 'react-redux';
import {__} from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import cx from 'classnames';

/**
 * Internal dependencies
 */
import starIcon from '../../../../../../images/icons/star.svg';
import mapMarkerIcon from '../../../../../../images/icons/map-marker.svg';
import {isEmpty} from 'lodash';
import ProductStyle1 from '../product-styles/ProductStyle1';

class ProductOwner extends Component {
  constructor(props) {
    super(props);
    this.state = {
      display_product_owner_logo: '',
      hide_show_product_info_place: '',
      hide_show_product_info_mark: '',
      reviews: true

    };
    this.image = React.createRef();
  }

  componentDidMount() {
    let id = document.getElementById('business-store');
    let idSearch = document.getElementById('search-listings');
    let defaultSearchListingId = document.getElementById('page-search');
    // let el = this.image.current.closest('.lisfinity-product--author-image');
    //
    // setTimeout(() => {
    //   let bounding = el.getBoundingClientRect();
    //   if (el.offsetWidth > 99) {
    //     el.style.height = 'auto';
    //     el.style.width = '100px';
    //   }
    // }, 500);

    if (id) {
      const optionsNew = JSON.parse(id.dataset.settings);
      this.setState({
        ...this.state,
        display_product_owner_logo: optionsNew.display_product_owner_logo,
        hide_show_product_info_mark: optionsNew.hide_show_product_info_mark,
        hide_show_product_info_place: optionsNew.hide_show_product_info_place,
        reviews: optionsNew.reviews
      });
    } else if (idSearch) {
      const optionsNewSearch = JSON.parse(idSearch.dataset.options);
      this.setState({
        ...this.state,
        display_product_owner_logo: optionsNewSearch.display_product_owner_logo,
        hide_show_product_info_place: optionsNewSearch.hide_show_product_info_place,
        hide_show_product_info_mark: optionsNewSearch.hide_show_product_info_mark,
        reviews: optionsNewSearch.reviews
      });
    } else if (defaultSearchListingId) {
      const options = JSON.parse(defaultSearchListingId.dataset.options);
      this.setState({
        ...this.state,
        reviews: options.reviews
      });
    }
  }


  /**
   * Renders the component.
   *
   * @return {Object}
   */
  render() {
    const {product, options} = this.props;
    const {location_format} = lc_data;

    const avgRating = product.rating || product.premium_profile.rating;
    const account_type = product.account_type || 'business';
    const address = product?.location_formatted && !isEmpty(product?.location_formatted) ? product?.location_formatted : product?.meta?.location?.address;

    return (
      <Fragment>
        <div className="lisfinity-product--info-wrapper flex items-center">

          {(((this.props.type === 'elementor' && this.state.hide_show_product_info_mark === 'yes') || this.props.type === 'default') && this.state.reviews && account_type === 'business') &&
          <div className="lisfinity-product--info lisfinity__rating flex-center mr-22">
            <div className="flex-center min-w-32 w-32 h-32 rounded-full bg-yellow-300">
              <ReactSVG
                src={`${lc_data.dir}dist/${starIcon}`}
                className="w-14 h-14 fill-product-star-icon"
              />
            </div>
            <span className="ml-6 text-sm text-grey-600">{avgRating}</span>
          </div>
          }
          {
            (location_format && ((this.props.type === 'elementor' && this.state.hide_show_product_info_place === 'yes') || this.props.type === 'default')) && (this.props?.options?.['membership-address'] === 'always' || (this.props?.options?.['membership-address'] === 'logged_in' && lc_data.logged_in === '1')) &&
            <div className="lisfinity-product--info lisfinity__location flex-center">
              <div className="flex-center w-32 min-w-32 h-32 rounded-full bg-cyan-300">
                <ReactSVG
                  src={`${lc_data.dir}dist/${mapMarkerIcon}`}
                  className="w-14 h-14 fill-product-place-icon"
                />
              </div>
              {'owner_location' === options['product-search-map-location'] && product?.location_formatted_user &&
              <span className="ml-6 text-sm text-grey-600">{product?.location_formatted_user}</span>
              }
              {'listing_location' === options['product-search-map-location'] &&
              <span className="ml-6 text-sm text-grey-600">{address}</span>
              }
            </div>
          }

        </div>

        {
          (product.profile_image && ((this.props.type === 'elementor' && this.state.display_product_owner_logo === 'yes') || this.props.type === 'default') && account_type === 'business') &&
          <div className="lisfinity-product--author flex-center">
            {product.profile_permalink_enabled &&
            <a href={product.profile_permalink}>
              <img src={product.profile_image} alt={lc_data.jst[483]}
                   ref={this.image}
                   className="lisfinity-product--author-image"/>
            </a>
            }
            {!product.profile_permalink_enabled &&
            <img src={product.profile_image} alt={lc_data.jst[483]} className="lisfinity-product--author-image"/>
            }
          </div>
        }
      </Fragment>
    );
  }
}

export default ProductOwner;
