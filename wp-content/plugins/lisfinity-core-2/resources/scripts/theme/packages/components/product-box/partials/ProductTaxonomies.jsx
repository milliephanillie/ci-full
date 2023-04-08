/* global lc_data, React */
/**
 * External dependencies.
 */
import { map, isEmpty } from 'lodash';
import { sprintf } from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import he from 'he';

/**
 * Internal dependencies
 */

const ProductTaxonomies = (props) => {
  const { product, productStyle, fieldOptions } = props;

  return (
    product.taxonomies && map(product.taxonomies, (taxonomy, slug) => {
      return (
        <a key={slug} href={taxonomy.link}
           className="lisfinity-product--cf flex-center mt-1 mr-2 py-2 px-8 bg-grey-100 rounded text-sm text-grey-800">
          {
            taxonomy.icon && taxonomy.icon.charAt(taxonomy.icon.length-3) === 's' &&
            <ReactSVG
              src={taxonomy.icon}
              className={`mr-2 injectable fill-taxonomy-icon w-${taxonomy['icon-size']} h-${taxonomy['icon-size']}`}
            />
          }
          {
            taxonomy.icon && taxonomy.icon.charAt(taxonomy.icon.length-3) !== 's' &&
            <img src={taxonomy.icon} className={`mr-2 injectable fill-taxonomy-icon w-${taxonomy['icon-size']} h-${taxonomy['icon-size']}`} alt=""/>
          }
          {!taxonomy.icon && props.options && props.options.enable_taxonomy_labels && fieldOptions && fieldOptions[product.meta.category] && fieldOptions[product.meta.category]['options'] && fieldOptions[product.meta.category]['options'][slug] && !fieldOptions[product.meta.category]['options'][slug]['prefix'] && !fieldOptions[product.meta.category]['options'][slug]['suffix'] &&
          <span>{sprintf('%s:', he.decode(taxonomy.name))}</span>
          }
          {product && product.meta && product.meta.category &&
          <span className="ml-4 font-semibold">
              {!isEmpty(taxonomy.prefix) &&
              <span
                className="taxonomy--prefix">{taxonomy.prefix}</span> || ''}
            <span>{taxonomy.term}</span>
            {!isEmpty(taxonomy.suffix) &&
            <span
              className="taxonomy--suffix">{taxonomy.suffix}</span> || ''}

            </span>
          }
        </a>
      );
    })
  );
};

export default ProductTaxonomies;
