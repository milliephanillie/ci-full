/* global lc_data, React */
/**
 * External dependencies.
 */
import {useState, useEffect, Fragment} from 'react';
import {sprintf, __} from '@wordpress/i18n';
import Select from 'react-select';
import ReactSVG from 'react-svg';
import CalculatorIcon from '../../../../../../images/icons/calculator.svg';
import * as functions from '../../../../vendor/functions';

function CalculatorEl(props) {
  const {product, currentUser, settings} = props;

  const selectOptions = [
    {value: 12, label: lc_data.jst[523]},
    {value: 24, label: lc_data.jst[524]},
    {value: 36, label: lc_data.jst[526]},
    {value: 48, label: lc_data.jst[529]},
    {value: 60, label: lc_data.jst[532]},
  ];

  const [calculated, setCalculated] = useState(false);
  const [price] = useState(product.product_meta.price);
  const [downPrice, setDownPrice] = useState(0);
  const [tradePrice, setTradePrice] = useState(0);
  const [tax, setTax] = useState(0);
  const [interest, setInterest] = useState(0);
  const [terms, setTerms] = useState(selectOptions[0]);
  const [calculation, setCalculation] = useState({});
  const {calculator} = product;

  const calculate = () => {
    const result = {
      toPay: price,
    };

    let toPay = price;

    if (tax >= 1) {
      toPay = price * (1 + (parseFloat(tax) / 100));
      result.toPayTaxes = result.toPay - price;
    }

    if (interest >= 1) {
      toPay = price * (1 + (parseFloat(interest) / 100));
      result.toPayInterest = toPay - price;
    }

    if (downPrice > 0) {
      toPay = toPay - downPrice;
    }

    if (tradePrice > 0) {
      toPay = toPay - tradePrice;
    }

    result.inTerms = toPay / terms.value;
    result.toPay = toPay;

    setCalculation(result);
  };

  let icon = null;
  let svg = null;

  if (settings?.icon_url !== null && settings?.icon_url) {
    typeof settings.icon_url['value'] === 'string' ? icon = settings.icon_url['value'] : svg = settings.icon_url['value']['url'];
  }


  return (
    <div className="calculator flex flex-wrap items-center">
      {'yes' === settings.title_display &&
      < h6 className="widget--label mb-20 font-bold text-xl bg:text-base lg:text-xl" id="calculator-title">{lc_data.jst[533]}</h6>
      }
      <div className="calculator--inner flex flex-wrap -mx-6">

        <div className="mb-16 px-6 w-1/2">
          <label className="flex mb-2 font-light text-grey-900 text-sm"
                 htmlFor="price">{lc_data.jst[231]}</label>
          <div className="flex items-center p-20 h-44 bg-grey-100 border border-grey-200 rounded calculator--fields">
            <span dangerouslySetInnerHTML={{__html: calculator.currency}}></span>
            <input
              type="number"
              id="price"
              value={price}
              readOnly
              className="w-full bg-transparent"
            />
          </div>
        </div>

        <div className="mb-16 px-6 w-1/2">
          <label className="flex mb-2 font-light text-grey-900 text-sm"
                 htmlFor="downPrice">{lc_data.jst[534]}</label>
          <div className="flex items-center p-20 h-44 bg-grey-100 border border-grey-200 rounded calculator--fields" >
            <span dangerouslySetInnerHTML={{__html: calculator.currency}}></span>
            <input
              type="number"
              id="downPrice"
              value={downPrice}
              min={0}
              onChange={e => setDownPrice(e.target.value)}
              className="w-full bg-transparent"
            />
          </div>
        </div>

        <div className="mb-16 px-6 w-1/2">
          <label className="flex mb-2 font-light text-grey-900 text-sm"
                 htmlFor="tradePrice">{lc_data.jst[519]}</label>
          <div className="flex items-center p-20 h-44 bg-grey-100 border border-grey-200 rounded calculator--fields">
            <span dangerouslySetInnerHTML={{__html: calculator.currency}}></span>
            <input
              type="number"
              id="tradePrice"
              value={tradePrice}
              min={0}
              onChange={e => setTradePrice(e.target.value)}
              className="w-full bg-transparent"
            />
          </div>
        </div>

        <div className="mb-16 px-6 w-1/2">
          <label className="flex mb-2 font-light text-grey-900 text-sm"
                 htmlFor="tax">{lc_data.jst[520]}</label>
          <div className="flex items-center p-20 h-44 bg-grey-100 border border-grey-200 rounded calculator--fields">
            <input
              type="number"
              id="tax"
              value={tax}
              min={1}
              step={0.1}
              onChange={e => setTax(e.target.value)}
              className="w-full bg-transparent"
            />
            <span>%</span>
          </div>
        </div>

        <div className="mb-16 px-6 w-1/2">
          <label className="flex mb-2 font-light text-grey-900 text-sm"
                 htmlFor="interest">{lc_data.jst[521]}</label>
          <div className="flex items-center p-20 h-44 bg-grey-100 border border-grey-200 rounded calculator--fields">
            <input
              type="number"
              id="interest"
              value={interest}
              min={1}
              step={0.1}
              onChange={e => setInterest(e.target.value)}
              className="w-full bg-transparent"
            />
            <span>%</span>
          </div>
        </div>

        <div className="mb-16 px-6 w-1/2">
          <label className="flex mb-2 font-light text-grey-900 text-sm"
                 htmlFor="terms">{lc_data.jst[522]}</label>
          <Select
            options={selectOptions}
            value={terms}
            id="terms"
            onChange={selected => setTerms(selected)}
            className="select-custom w-full bg-grey-100 border border-grey-200 rounded calculator--select"
          />
        </div>

        <div className="px-6 w-full">
          <button
            type="button"
            onClick={() => {
              setCalculated(true)
              calculate();
            }}
            className="flex-center mt-10 py-10 px-20 w-full bg-blue-700 rounded font-bold text-lg text-white calculator--button hover:bg-blue-900"
          >
            {(icon === null && svg === null || "" == icon) && 'yes' === settings.button_icon_display  &&
            <ReactSVG
              src={`${lc_data.dir}dist/${CalculatorIcon}`}
              className="mr-6 w-16 h-16 fill-white calculator-button-icon"
            />
            }
            {
              svg && settings.place_icon !== '' && 'yes' === settings.button_icon_display  &&
              <img src={svg} alt="cart-icon"
                   className="mr-6 w-16 h-16 fill-white calculator-button-icon"/>
            }
            {
              settings.place_icon !== '' && icon && 'yes' === settings.button_icon_display  &&
              <i className={`${icon} mr-6 w-16 h-16 fill-white calculator-button-icon`} style={{
                display: 'flex',
                alignSelf: 'center'
              }}
                 aria-hidden="true"
              ></i>
            }
            { 'yes' === settings.button_text_display &&
            <Fragment>{settings.button_text}</Fragment>
            }
          </button>
        </div>

        {calculated &&
        <div className="mt-20 px-6 w-full">
          <div className="calculator--result flex flex-col p-20 pb-10 bg-blue-200 rounded">
            <div className="calculator--result-price mb-10">
              <span className="font-bold calculator-result-label">{lc_data.jst[527]}</span>
              <span dangerouslySetInnerHTML={{__html: calculator.currency}}></span>
              {functions.formatMoney(calculation.toPay, 2, product.calculator.decimals_separator, product.calculator.thousands_separator)}
            </div>
            {calculation.toPayTaxes &&
            <div className="mb-10 calculator--result-price">
              <span className="font-bold calculator-result-label">{lc_data.jst[528]}</span>
              <span dangerouslySetInnerHTML={{__html: calculator.currency}}></span>
              {functions.formatMoney(calculation.toPayTaxes, 2, product.calculator.decimals_separator, product.calculator.thousands_separator)}
            </div>}
            {calculation.toPayInterest &&
            <div className="mb-10 calculator--result-price">
              <span className="font-bold calculator-result-label">{lc_data.jst[530]}</span>
              <span dangerouslySetInnerHTML={{__html: calculator.currency}}></span>
              {functions.formatMoney(calculation.toPayInterest, 2, product.calculator.decimals_separator, product.calculator.thousands_separator)}
            </div>}
            <div className="mb-10 calculator--result-price">
              <span className="font-bold calculator-result-label">{lc_data.jst[531]}</span>
              <span dangerouslySetInnerHTML={{__html: calculator.currency}}></span>
              {functions.formatMoney(calculation.inTerms, 2, product.calculator.decimals_separator, product.calculator.thousands_separator)}
            </div>
          </div>
        </div>
        }

      </div>

    </div>
  );
}

export default CalculatorEl;
