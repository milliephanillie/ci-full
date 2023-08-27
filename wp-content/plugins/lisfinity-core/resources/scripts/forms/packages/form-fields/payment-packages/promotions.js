/* global lc_data, React */
/**
 * External dependencies.
 */
import * as actions from '../../../../dashboard/packages/store/actions';
import {useEffect, useRef, useState} from '@wordpress/element';
import {__, sprintf} from '@wordpress/i18n';
import {map, get, omit, isEmpty, find} from 'lodash';
import {useDispatch, useSelector} from 'react-redux';
import ReactSVG from 'react-svg';
import CalendarIcon from '../../../../../images/icons/calendar.svg';
import QuestionIcon from '../../../../../images/icons/question-circle.svg';
import ReactTooltip from 'react-tooltip';
import cx from 'classnames';
import he from "he";
import * as functions from "../../../../theme/vendor/functions";

const Promotions = (props) => {
  const [value, setValue] = useState(props.value);
  const [products, setProducts] = useState(false);
  const [total, setTotal] = useState({});
  const [fieldActive, setFieldActive] = useState(false);
  const allData = useSelector(state => state);
  const data = allData.formData;
  const dispatch = useDispatch();
  const [prices, setPrices] = useState({});
  const [error, setError] = useState([]);
  const [disablePurchase, setDisablePurchase] = useState({disable: false, id: ''});

  const promotionsWrapper = useRef(null);

  useEffect(() => {
    if (!allData.costs['total']) {
      if (allData.costs === 0) {
        delete allData.costs;
        allData.costs = {};
      }
      allData.costs['total'] = {};
    }
    if (!allData.costs['total']['promo']) {
      allData.costs['total']['promo'] = 0;
    }
  }, []);

  const calculateTotal = () => {
    let price = 0;
    map(allData.costs['promo'], promo => {
      price += parseFloat(promo.cost);
    });
    allData.costs['total']['promo'] = price;
    dispatch(actions.updateCosts(allData.costs));

    if (allData.costs && allData.costs.total) {
      let final = (allData.costs['total']['media'] || 0) + (allData.costs['total']['promo']) || 0;
      if (allData?.costs['total']?.['commission']) {
        final += allData.costs['total']['commission'];
      }
      allData.costs['final'] = final;
      dispatch(actions.updateCosts(allData.costs));
    }
  };

  const dispatchPrice = (name, cost, remove = false) => {
    if (remove) {
      delete allData.costs.promo[name];
      allData.costs.total['promo'] -= parseFloat(cost);
    } else {
      if (!allData.costs['promo']) {
        allData.costs['promo'] = {};
      }
      allData.costs['promo'][name] = {cost};
    }
    dispatch(actions.updateCosts(allData.costs));
    calculateTotal();
  };

  /**
   * Handle storing value when days number for the promotion
   * has been changed.
   * -------------------------------------------------------
   *
   * @param e
   * @param product
   * @param index
   * @param fieldId
   */
  const handleInput = (e, product, index, fieldId) => {
    const enabled = document.getElementById(`${e.target.id}-enabled`).checked;
    const price = get(products, `[${index}][price]`);
    let days = e.target.value;
    if (enabled) {
      let inputField = document.getElementById(fieldId);
      let isValidInputField = inputField.checkValidity();
      if (isValidInputField) {
        const promotions = [];
        const promoName = e.target.name;
        promotions[promoName] = {
          id: product.ID,
          days,
          price,
          enabled,
        };
        const val = {...value, ...promotions};
        setValue(val);
        data[props.name] = val;
        dispatch(actions.updateFormData(data));
        dispatchPrice(promoName, (parseFloat(price) * days));
        const p = {...prices};
        p[product.ID] = parseFloat(price) * days;
        setPrices(p);
        let errors = error;
        errors[product.post_name] = {text: '', name: product.post_name};
        setError(errors);
        setDisablePurchase({disable: false, id: promotion.ID});
      } else {
        setDisablePurchase({disable: true, id: product.ID});
        let errors = error;
        errors[product.post_name] = {text: inputField.validationMessage, name: product.post_name};
        setError(errors);
      }
    }
  };

  /**
   * Handle storing value when promotion checkbox has been clicked
   * -------------------------------------------------------------
   *
   * @param e
   * @param id
   * @param index
   */
  const handleCheckbox = (e, id, index) => {
    const inputField = document.getElementById(e.target.dataset.relative);
    const days = inputField.value;
    const name = inputField.name;
    const enabled = e.target.checked;
    const price = get(products, `[${index}][price]`);
    const promotions = [];
    let val = {};

    if (enabled) {
      promotions[name] = {
        id,
        days,
        price,
        enabled,
      };
      val = {...value, ...promotions};
      dispatchPrice(name, (parseFloat(price) * days));
    } else {
      val = omit(value, name);
      dispatchPrice(name, (parseFloat(price) * days), true);
    }
    setValue(val);
    data[props.name] = val;
    dispatch(actions.updateFormData(data));
  };

  /**
   * Handler for selecting all available promotion packages
   * when the button has been clicked.
   * ------------------------------------------------------
   *
   * @param e
   */
  const handleEnableAll = (e) => {
    const promotionField = document.getElementsByClassName('data-promotion');
    const promotions = [];
    let val = {};
    map(promotionField, (promotion, index) => {
      promotion.checked = true;
      const inputField = document.getElementById(promotion.dataset.relative);
      const days = inputField.value;
      const name = inputField.name;
      const enabled = promotion.checked;
      const price = get(products, `[${index}][price]`);
      if (enabled) {
        promotions[name] = {
          days,
          price,
          enabled,
        };
        dispatchPrice(name, (parseFloat(price) * days));
      }
    });
    val = {...value, ...promotions};
    setValue(val);
    data[props.name] = val;
    dispatch(actions.updateFormData(data));
  };

  useEffect(() => {
    const promotionProducts = get(allData.business, 'promotion_products');
    if (!isEmpty(promotionProducts)) {
      setProducts(promotionProducts);
    }
  });

  const product_name = props.name;
  const promotionsLength = data[props.name] && Object.keys(data[props.name]).length || 0;
  const unused = find(products, ['product-type', 'bump-up']) ? products.length - promotionsLength - 1 : products.length - promotionsLength;
  const btnClass = cx({
    'bg-blue-700 hover:bg-blue-800': unused > 0,
    'bg-grey-400 cursor-default hover:bg-grey-400': unused === 0,
  });

  return [
    products &&
    <div key={0} className="products-wrapper w-full" ref={promotionsWrapper}>
      <div className="promotions flex flex-wrap">
        {map(products, (product, index) => {
          const productType = product['product-type'];
          const val = get(data, product_name);
          const checked = val && get(value[productType], 'enabled');
          const days = val && get(value[productType], 'days');
          const fieldId = productType.replace('ad', 'product');
          let defaultPrice = product?.price * parseFloat(product?.duration);
          {console.log(product)}
          return (
            productType !== 'bump-up' && (!props?.package?.free_promotions || !props?.package?.free_promotions.includes(productType)) &&
            <article key={index}
                     className="promotion flex flex-wrap flex-col sm:flex-row mb-30 w-full rounded overflow-hidden">
              {product.thumbnail &&
              <figure className="relative flex w-full sm:w-5/16 h-225 sm:h-auto">
                <img src={product.thumbnail.url} alt={product.post_title}
                     className="absolute top-0 left-0 w-full h-full object-cover"/>
              </figure>
              }
              <div
                className="flex flex-col p-30 w-full sm:w-11/16 bg-white border border-t-0 sm:border-t sm:border-l-0 border-grey-200 rounded">

                <div className="flex justify-between">
                  <div className="flex-center">
                    <ReactSVG src={`${lc_data.dir}dist/${CalendarIcon}`}
                              className="relative top-2 mr-8 w-16 h-16 fill-grey-700" style={{top: '-3px'}}/>
                    <span className="text-sm text-grey-700">
                    {lc_data.jst[372]}
                   </span>
                  </div>

                  {!isEmpty(product.post_content) &&
                  <div className="flex-center font-semibold text-sm text-blue-700" data-tip={product.post_content}>
                    {lc_data.jst[201]}
                    <ReactSVG src={`${lc_data.dir}dist/${QuestionIcon}`}
                              className="relative ml-6 w-14 h-14 fill-blue-700"/>
                    <ReactTooltip scrollHide={false}/>
                  </div>
                  }
                </div>

                <h6 className="mt-20 font-semibold text-grey-1000">{product.post_title}</h6>
                {disablePurchase?.disable && disablePurchase?.id === product.ID && error[product.post_name] &&
                <div
                  className="field--error text-sm mt-10 text-red-700 w-full text-left">{error[product.post_name].text}</div>
                }
                <div className="flex flex-wrap justify-between items-center mt-20">
                  <div className="flex items-center">
                    <span id={`price-${product.ID}`} className="font-semibold text-sm">
                        <span className="woocommerce-Price-amount amount">
                          <bdi>
                            <span className="woocommerce-Price-currencySymbol">{he.decode(product.currency)}</span>
                            <span
                              className="price">{functions.formatMoney((prices[product.ID] || defaultPrice), 2, product.decimals_separator, product.thousands_separator)}</span>
                          </bdi>
                        </span>
                      </span>
                    <span className="ml-3 text-sm text-grey-700">{lc_data.jst[202]}</span>

                    <input
                      type="number"
                      id={fieldId}
                      className={`flex ml-8 py-2 px-4 w-44 border border-grey-300 rounded font-semibold text-grey-1000 ${fieldActive ? 'bg-transparent' : 'bg-grey-100'}`}
                      name={productType}
                      min={product?.duration_min_value ? product?.duration_min_value : get(product, 'value')}
                      max={!isEmpty(product?.duration_max_value) ? product?.duration_max_value : 1}
                      step={product?.duration_step ? product?.duration_step : 1}
                      defaultValue={product?.duration ? product?.duration : days ? days : get(product, 'value')}
                      onChange={e => handleInput(e, product, index, fieldId)}
                      onFocus={() => setFieldActive(true)}
                      onBlur={() => setFieldActive(false)}
                    />

                    <span id={`text-${product.ID}`}
                          className="ml-6 text-sm text-grey-700">{lc_data.jst[203]}</span>
                  </div>

                  <div className="product-box--actions__group flex items-centered ml-auto">
                    <div
                      className={`search--action toggle flex items-center`}>
                      <span
                        className="toggle--label mr-8 font-semibold text-red-600">
                        {!checked
                          ?
                          lc_data.jst[373]
                          :
                          lc_data.jst[374]
                        }
                      </span>
                      <label htmlFor={`${fieldId}-enabled`} className="switch">
                        <input
                          type="checkbox"
                          id={`${fieldId}-enabled`}
                          className="data-promotion ml-2 input--toggle"
                          name={`${productType}-enabled`}
                          onChange={e => handleCheckbox(e, product.ID, index)}
                          data-relative={fieldId}
                          checked={checked || false}
                        />
                        <span className="slider"></span>
                      </label>
                    </div>
                  </div>
                </div>

              </div>
            </article>
          );
        })}
      </div>

    </div>,
  ];
};

export default Promotions;
