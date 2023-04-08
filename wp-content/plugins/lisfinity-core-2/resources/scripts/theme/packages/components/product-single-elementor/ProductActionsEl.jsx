/* global lc_data, React */
/**
 * Dependencies.
 */
import {Component, createRef, Fragment, useEffect, useState} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import {getProduct, setProduct} from '../../store/actions';
import LoaderProductSingle from '../loaders/LoaderProductSingle';
import {ToastContainer} from 'react-toastify';
import {useDispatch, useSelector} from 'react-redux';
import {isEmpty} from "lodash";
import Bookmark from "../product-single/partials/product-actions/Bookmark";
import Print from "../product-single/partials/product-actions/Print";
import Visits from "../product-single/partials/product-actions/Visits";
import Likes from "../product-single/partials/product-actions/Likes";
import Report from "../product-single/partials/product-actions/Report";
import Share from "../product-single/partials/product-actions/Share";
import CalculatorButton from "../product-single/partials/product-actions/calculator/CalculatorButton";
import Compare from "../product-single/partials/product-actions/compare/Compare";
import ShareList from '../product-single/partials/product-actions/share/ShareList';

/**
 * Internal dependencies
 */

const ProductActionsEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const {product, options, user} = data;
  const [shareDisplay, setShareDisplay] = useState('default');
  const [elSettings, setElSettings] = useState({});
  let wrapper = null;
  let el = createRef();


  const shareIconDisplay = () => {
    if (window.innerWidth < 640) {
      setShareDisplay('small-screen');
    } else {
      setShareDisplay('default');
    }
  };

  useEffect(() => {
    wrapper = el.current && el.current.closest('.elementor-product-actions');
    if (wrapper && wrapper.dataset.settings) {
      const settings = JSON.parse(wrapper.dataset.settings);
      setElSettings(settings);
    }
  }, []);

  return (
    <section className="product--section product--section__actions flex flex-wrap justify-between" ref={el}>
      {!isEmpty(product) &&
      <div className="flex flex-wrap">
        {elSettings.actions && elSettings.actions.map(action => {
          return (
            <Fragment key={action.actions}>
              {action.actions === 'bookmark' && !isEmpty(lc_data?.logged_in) &&
              <Bookmark product={product} settings={elSettings} currentUser={user} elementId={action._id}
                        key={action.actions}/>}
              {action.actions === 'print' &&
              <Print product={product} settings={elSettings} elementId={action._id} key={action.actions}/>}
              {action.actions === 'visits' && (elSettings['membership_listings_visits'] === 'always' || (elSettings['membership_listings_visits'] === 'logged_in' && lc_data.logged_in === '1')) &&
              <Visits product={product} settings={elSettings} elementId={action._id} key={action.actions}/>}
              {action.actions === 'likes' &&
              <Likes product={product} settings={elSettings} elementId={action._id} currentUser={user}/>}
              {action.actions === 'report' &&
              <Report product={product} settings={elSettings} elementId={action._id} options={options}
                      key={action.actions}/>}
              {action.actions === 'share' && action?.display_modal !== '' &&
              <Share product={product} settings={elSettings} elementId={action._id} key={action.actions}/>}
              {action.actions === 'share' && action?.display_modal === '' &&
              <ShareList product={product} elementId={action._id} currentUser={user} key={action.actions}/>}
              {action.actions === 'calculator' &&
              <CalculatorButton product={product} settings={elSettings} currentUser={user} elementId={action._id}
                                key={action.actions}/>}
              {action.actions === 'compare' &&
              <Compare product={product} settings={elSettings} elementId={action._id} currentUser={user}
                       key={action.actions}/>}
            </Fragment>
          )
        })}
      </div>
      }
    </section>
  );
};

export default ProductActionsEl;
