/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component } from '@wordpress/element';
import ProductMetaStyle3 from './ProductMetaStyle3';
import { storeStat } from '../../../../vendor/functions';
import { isEmpty } from 'lodash';

class ProductContent extends Component {
  constructor(props) {
    super(props);
    this.state = {
      settings: {}
    };
  }

  componentDidMount() {
    let idBusiness = document.getElementById('business-store');
    let idSearch = document.getElementById('search-listings');

    if (idBusiness) {
      const optionsNewBusiness = JSON.parse(idBusiness.dataset.settings);
      this.setState({
        ...this.state,
        settings: optionsNewBusiness
      });
    } else if (idSearch) {
      const optionsNewSearch = JSON.parse(idSearch.dataset.options);
      this.setState({
        ...this.state,
        settings: optionsNewSearch
      });
    }
  }

  /**
   * Renders the component.
   *
   * @return {Object}
   */
  render() {

    const { product, productStyle, titleClasses, is_ad, settings } = this.props;
    let description = product?.description?.replace('<p>', '');
    description = description?.replace('<\/p>', '');
    return (
      <div className="lisfnity-product--image relative flex flex-col z-1">

        <div className="lisfinity-product--meta-wrapper flex items-center justify-between mb-10">
          <ProductMetaStyle3 product={product} productStyle={productStyle}
                             args={{ color: 'grey-900', textColor: 'grey-900' }}/>
        </div>

        <div className="lisfinity-product--content">
          {(isEmpty(this.state?.settings) || this.state.settings.hide_show_product_title !== 'no') &&
          <div className="lisfinity-product--title">
            <h6 className={`product--title font-semibold text-grey-900 ${titleClasses}`}>
              <a href={product.permalink}
                 onClick={() => storeStat(product.ID, 3)}
              >
                {(is_ad || product.promoted_category) &&
                <span
                  className="label--promoted relative -top-2 mr-4 py-2 px-4 border rounded text-xs text-yellow-600">{lc_data.jst[586]}</span>
                }
                {product.post_title}
              </a>
            </h6>
          </div>}
          {settings?.product_description_displayed &&
            <div className='search-listing-description'>{description}</div>
          }

        </div>

      </div>
    );
  }
}

export default ProductContent;
