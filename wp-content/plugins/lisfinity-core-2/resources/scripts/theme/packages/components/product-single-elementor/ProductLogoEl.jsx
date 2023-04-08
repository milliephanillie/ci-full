/* global lc_data, React */
/**
 * Dependencies.
 */
import {useSelector} from 'react-redux';

/**
 * Internal dependencies
 */

const ProductLogoEL = (props) => {
  const data = useSelector(state => state);
  const {product, options} = data;

  const thumbnail = options?.account_type === 'business' ? product.premium_profile?.thumbnail : product?.premium_profile?.user_avatar ? product?.premium_profile?.user_avatar : product?.products?.products[0] && product?.products?.products[0]['user_avatar'] ? product?.products?.products[0]['user_avatar'] : '';

  return (
    <figure
      id="profile--thumbnail--elementor"
      className="profile--thumbnail flex-center mb-20 p-30 w-full bg-grey-100 rounded"
    >
      <a href={product?.premium_profile?.url} className="h-full" target="_blank">
        <img src={thumbnail} alt={product?.premium_profile?.title} className="h-90 relative"/>
      </a>
    </figure>

  );
};

export default ProductLogoEL;
