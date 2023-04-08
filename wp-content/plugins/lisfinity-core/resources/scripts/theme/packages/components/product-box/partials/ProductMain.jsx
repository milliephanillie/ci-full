/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component } from '@wordpress/element';

/**
 * Internal dependencies
 */
import ProductMeta from './ProductMeta';
import { storeStat } from '../../../../vendor/functions';

class ProductMain extends Component {
  constructor(props) {
    super(props);
    this.state = {
      imageLoaded: false,
    };
  }

  imageLoaded = () => {
    this.setState({ imageLoaded: true });
  };

  /**
   * Renders the component.
   *
   * @return {Object}
   */
  render() {
    const { product, productStyle, imageClasses, heightClass, is_ad, options } = this.props;
    const bgColor = productStyle === 1 && product.promoted_color ? 'linear-gradient(0deg, rgba(100, 109, 90, .8) 0%, rgba(255,255,255,0) 100%' : 'linear-gradient(0deg, rgba(0, 0, 0, .5) 0%, rgba(255,255,255,0) 100%';

    return (
      productStyle !== 3 ?
        <div className={`lisfinity-product--main relative flex items-end ${heightClass || 'h-product-thumb'}`}>
          <a
            href={product.permalink}
            className="absolute w-full h-full z-1"
            style={{ background: bgColor }}
            onClick={() => storeStat(product.ID, 3)}
          >
          </a>

          {(product.thumbnail || options?.fallback_image) &&
          <img src={product.thumbnail ? product.thumbnail : options?.fallback_image } alt={product.post_title}
               className={`absolute w-full h-full object-cover ${this.state.imageLoaded ? '' : 'hidden'}`}
               onLoad={() => this.imageLoaded()}/>}

          <div className="lisfinity-product--content relative flex flex-col py-30 px-24 w-full">

            <div className="lisfinity-product--meta-wrapper flex items-center justify-between mb-16 z-2">
              <ProductMeta product={product} args={{ color: 'fill-white', textColor: 'white' }}/>
            </div>

            <div className="lisfinity-product--title">
              <h6 className="relative product--title font-semibold text-white z-2">
                <a href={product.permalink}
                   className="flex items-baseline"
                   onClick={() => storeStat(product.ID, 3)}
                >
                  {(is_ad || product.promoted_category) &&
                  <span
                    className="label--promoted relative top-0 mr-4 py-2 px-4 border rounded text-xs text-yellow-600">{lc_data.jst[586]}</span>
                  }
                  {product.post_title}
                </a>
              </h6>
            </div>

          </div>

        </div>
        :
        <div className={`lisfinity-product--main relative flex items-end ${imageClasses}`}>
          <a
            href={product.permalink}
            className="absolute w-full h-full z-1"
            onClick={() => storeStat(product.ID, 3)}
          >
          </a>
          {(product.thumbnail|| options?.fallback_image) &&
          <img src={product.thumbnail ? product.thumbnail : options?.fallback_image } alt={product.post_title}
               className={`absolute w-full h-full object-cover ${this.state.imageLoaded ? '' : 'hidden'}`}
               onLoad={() => this.imageLoaded()}
          />}
        </div>
    );
  }
}

export default ProductMain;
