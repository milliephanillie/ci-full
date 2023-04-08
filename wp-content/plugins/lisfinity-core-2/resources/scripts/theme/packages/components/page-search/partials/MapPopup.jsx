/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component, createRef } from '@wordpress/element';
import { connect } from 'react-redux';
import { __ } from '@wordpress/i18n';
import { Popup } from 'react-leaflet';
import ProductBookmark from '../../product-box/partials/ProductBookmark';
import PopupTimer from '../partials/PopupTimer';

class MapPopup extends Component {
  constructor(props) {
    super(props);
    this.state = {};
    this.popup = createRef();
  }

  //todo add to a custom function like the one in ProductMeta.jsx
  displayPrice(product, type) {
    let price = '';
    switch (type) {
      case 'price_on_call':
        price = <span
          className="lisfinity-product--meta__price text-blue-600 font-bold">{lc_data.jst[475]}</span>;
        break;
      case 'free':
        price = <span
          className={`lisfinity-product--meta__price text-green-900 font-bold`}
        >
          {lc_data.jst[128]}
        </span>;
        break;
      case 'on-sale':
        price = <span className={`lisfinity-product--meta__price popup-on-sale text-grey-900 font-bold`}
                      dangerouslySetInnerHTML={{ __html: product.price_html }}></span>;
        break;
      default:
        price = <span className={`lisfinity-product--meta__price text-grey-900 font-bold`}
                      dangerouslySetInnerHTML={{ __html: product.price_html }}></span>;
        break;
    }
    return price;
  }

  /**
   * Renders the component.
   *
   * @return {Object}
   */
  render() {
    const { product } = this.props;
    return (
      <Popup ref={this.popup} position={[parseFloat(product.meta.location.lat), parseFloat(product.meta.location.lng)]}>
        <div className="map-popup rounded shadow overflow-hidden">
          <div className="map-popup--image">
            {product.thumbnail &&
            <figure className="relative h-product-popup-thumb">
              {product.meta.price_type === 'auction' && <PopupTimer product={product}/>}
              <ProductBookmark product={product} productStyle="popup"/>
              <a href={product.permalink}
                 className="absolute w-full h-full z-1"
                 style={{ background: 'linear-gradient(0deg, rgba(0, 0, 0, .2) 0%, rgba(255, 255, 255, 0) 100%)' }}>
              </a>
              <img src={product.thumbnail}
                   className="absolute top-0 left-0 w-full h-full object-cover"
                   alt={lc_data.jst[478]}/>
            </figure>}
          </div>
          <div className="map-popup--content relative p-20 bg-white font-sans z-2">
            <div
              className="map-popup-content--price absolute left-0 py-4 px-20 bg-white rounded rounded-l-none font-semibold text-grey-1000">
              {this.displayPrice(product, product.meta.price_type)}
            </div>
            <a href={product.permalink}>
              <h6 className="text-grey-1000 font-bold text-base leading-normal">
                {product.post_title}
              </h6>
            </a>
          </div>
        </div>
      </Popup>
    );
  }
}

function mapStateToProps(state) {
  const { postsByUrl, searchData } = state;
  const {
    isFetching, lastUpdated, items: results,
  } = postsByUrl.RECEIVE_POSTS || {
    isFetching: true,
    results: [],
  };
  return {
    results,
    isFetching,
    lastUpdated,
    searchData,
  };
}

export default connect(mapStateToProps)(MapPopup);
