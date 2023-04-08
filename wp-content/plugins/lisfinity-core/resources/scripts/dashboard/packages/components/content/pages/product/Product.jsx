/* global lc_data, React */
/**
 * Dependencies.
 */
import {Route} from 'react-router-dom';
import {useDispatch, useSelector} from 'react-redux';
import {useState, useEffect, useRef} from '@wordpress/element';
import * as actions from '../../../../store/actions';
import {isEmpty, filter, map, sortBy} from 'lodash';
import {__} from '@wordpress/i18n';
import ProductTabs from './ProductTabs';
import ProductInfo from './ProductInfo';
import {Fragment} from 'react';
import ActivePromotions from './widgets/ActivePromotions';
import ActivePackage from './widgets/ActivePackage';
import Messages from './messages/Messages';
import ProductInfoChat from './ProductInfoChat';
import LoaderDashboard from '../../../../../../theme/packages/components/loaders/LoaderDashboard';
import LoaderDashboardProduct from '../../../../../../theme/packages/components/loaders/LoaderDashboardProduct';
import LoaderDashboardWidgets from '../../../../../../theme/packages/components/loaders/LoaderDashboardWidgets';

const Product = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const {loading, business, product} = data;
  const [productLoading, setProductLoading] = useState(true);

  useEffect(() => {
    let ignore = false;

    const response = actions.fetchData(lc_data.get_product, {product_id: props.match.params.id});

    response.then((result) => {
      if(!result.data.thumbnail && result.data && data?.options?.fallback_image) {
        result.data.thumbnail = data?.options?.fallback_image;
      }
      if (!ignore) {
        dispatch(actions.setProduct(result.data));
        dispatch(actions.setLoading(false));
        setProductLoading(false);
      }
    });


    return () => {
      ignore = true;
    };
  }, []);

  return (
    <div className="product-single flex flex-wrap">
      <div className="product-single--inner w-full xl:w-11/16">
        {productLoading &&
        <LoaderDashboardProduct/>
        }
        {!isEmpty(product) && !productLoading && product.to_chat &&
        <Fragment>
          <ProductInfoChat/>
          <Messages productId={product.product_id} product={product}/>
        </Fragment>
        }
        {!productLoading && !isEmpty(product?.reject_reason) &&
        <div
          className="mb-20 p-20 bg-yellow-600 rounded font-semibold text-white shadow-theme">{product.reject_reason}</div>}
        {!isEmpty(product) && !productLoading && !product.to_chat &&
        <Fragment>
          <ProductInfo/>
          <ProductTabs productId={props.match.params.id}/>
        </Fragment>
        }
        {!product && !productLoading &&
        <div
          className="modal--no-content bg-grey-100 font-bold text-sm-shadow text-grey-300">{lc_data.jst[162]}</div>}
      </div>
      {!isEmpty(product) && !productLoading && !product.to_chat &&
      <aside className="flex flex-wrap xl:flex-col mt-30 xl:mt-0 w-full xl:w-5/16 xl:pl-20">
        {data?.options?.packages &&
        <ActivePackage product={product} productId={props.match.params.id}/>
        }
        <ActivePromotions product={product} productId={props.match.params.id}/>
      </aside>
      }
      {productLoading &&
      <LoaderDashboardWidgets/>
      }
    </div>
  );
};

export default Product;
