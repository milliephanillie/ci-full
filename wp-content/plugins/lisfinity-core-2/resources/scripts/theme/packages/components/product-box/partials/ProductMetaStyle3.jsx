/* global lc_data, React */
/**
 * External dependencies.
 */
import {Component, createRef, useEffect} from '@wordpress/element';
import {connect} from 'react-redux';
import {__} from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import cx from 'classnames';

/**
 * Internal dependencies
 */
import hammerIcon from '../../../../../../images/icons/construction-hammer.svg';
import phoneIcon from '../../../../../../images/icons/phone-handset.svg';
import giftIcon from '../../../../../../images/icons/gift.svg';
import walletIcon from '../../../../../../images/icons/wallet.svg';
import tagIcon from '../../../../../../images/icons/tag.svg';
import clockIcon from '../../../../../../images/icons/alarm-clock.svg';
import {Fragment} from 'react';
import * as functions from '../../../../vendor/functions';

class ProductMetaStyle3 extends Component {
  constructor(props) {
    super(props);
    this.state = {
      settings: {}
    };
    this.timer = createRef();
  }

  componentDidMount() {
    this.initializeCountdownTimer();
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


  componentDidUpdate() {
    this.initializeCountdownTimer();
  }

  initializeCountdownTimer() {
    const {product} = this.props;
    if (product.meta.auction_status !== 'active' && lc_data.current_time < product.meta.auction_ends) {
      return;
    }
    const timer = this.timer.current;
    if (timer) {
      const endTime = timer.dataset.auctionEnds;
      functions.initializeClock(timer, endTime);
    }
  }


  displayIcon(type) {
    const {args} = this.props;
    let icon = '';
    switch (type) {
      case 'on-sale':
        if (this.state.settings === undefined || (!this.state.settings?.icon_on_sale_price || this.state.settings?.icon_on_sale_price.value === '') || !this.state.settings) {
          icon = <ReactSVG
            src={`${lc_data.dir}dist/${tagIcon}`}
            className={`mr-6 min-w-14 min-h-14 fill-icon-sale`}
          />;
        } else if (this.state.settings?.icon_on_sale_price?.value && this.state.settings?.icon_on_sale_price?.library !== 'svg') {
          icon =
            <i className={`mr-6 min-w-14 min-h-14 ${this.state.settings.icon_on_sale_price.value} fill-icon-sale`}></i>;
        } else if (this.state.settings?.icon_on_sale_price?.value.url && this.state.settings?.icon_on_sale_price?.library === 'svg') {
          icon = <ReactSVG
            src={`${this.state.settings.icon_on_sale_price.value.url}`}
            className={`mr-6 min-w-14 min-h-14 fill-icon-sale`}
          />;

        }
        break;
      case
      'auction':
        if (this.state.settings === undefined || (!this.state.settings?.icon_auction_price || this.state.settings?.icon_auction_price.value === '') || !this.state.settings) {
          icon = <ReactSVG
            src={`${lc_data.dir}dist/${hammerIcon}`}
            className={`mr-6 min-w-14 min-h-14 auction-icon fill-field-icon`}
          />;
        } else if (this.state.settings?.icon_auction_price?.value && this.state.settings?.icon_auction_price?.library !== 'svg') {
          icon = <i
            className={`mr-6 min-w-14 auction-icon min-h-14 ${this.state.settings.icon_auction_price.value} fill-field-icon`}></i>;
        } else if (this.state.settings?.icon_auction_price?.value.url && this.state.settings?.icon_auction_price?.library === 'svg') {
          icon = <ReactSVG
            src={`${this.state.settings.icon_auction_price.value.url}`}
            className={`mr-6 min-w-14 auction-icon min-h-14 fill-field-icon`}
          />;

        }

        break;
      case
      'price_on_call'
      :
        if (this.state.settings === undefined || (!this.state.settings?.icon_on_call_price || this.state.settings?.icon_on_call_price.value === '') || !this.state.settings) {
          icon = <ReactSVG
            src={`${lc_data.dir}dist/${phoneIcon}`}
            className={`mr-6 min-w-14 min-h-14 fill-icon-call`}
          />;
        } else if (this.state.settings?.icon_on_call_price?.value && this.state.settings?.icon_on_call_price?.library !== 'svg') {
          icon =
            <i className={`mr-6 min-w-14 min-h-14 ${this.state.settings.icon_on_call_price.value} fill-icon-call`}></i>;
        } else if (this.state.settings?.icon_on_call_price?.value.url && this.state.settings?.icon_on_call_price?.library === 'svg') {
          icon = <ReactSVG
            src={`${this.state.settings.icon_on_call_price.value.url}`}
            className={`mr-6 min-w-14 min-h-14 fill-icon-call`}
          />;

        }

        break;
      case
      'free'
      :
        if (this.state.settings === undefined || (!this.state.settings?.icon_free_price || this.state.settings?.icon_free_price.value === '')) {
          icon = <ReactSVG
            src={`${lc_data.dir}dist/${giftIcon}`}
            className={`mr-6 min-w-14 min-h-14 fill-icon-gift`}
          />;
        } else if (this.state.settings?.icon_free_price?.value && this.state.settings?.icon_free_price?.library !== 'svg') {
          icon =
            <i className={`mr-6 min-w-14 min-h-14 ${this.state.settings.icon_free_price.value} fill-icon-gift`}></i>;
        } else if (this.state.settings?.icon_free_price?.value.url && this.state.settings?.icon_free_price?.library === 'svg') {
          icon = <ReactSVG
            src={`${this.state.settings.icon_free_price.value.url}`}
            className={`mr-6 min-w-14 min-h-14 fill-icon-gift`}
          />;

        }

        break;
      default:
        if (this.state.settings === undefined || (!this.state.settings?.icon_default_price || this.state.settings?.icon_default_price.value === '')) {
          icon = <ReactSVG
            src={`${lc_data.dir}dist/${walletIcon}`}
            className={`mr-6 min-w-14 min-h-14 fill-field-icon default-products-icon`}
          />;
        } else if (this.state.settings?.icon_default_price?.value && this.state.settings?.icon_default_price?.library !== 'svg') {
          icon = <i
            className={`mr-6 min-w-14 min-h-14 ${this.state.settings.icon_default_price.value} default-products-icon`}></i>;
        } else if (this.state.settings?.icon_default_price?.value.url && this.state.settings?.icon_default_price?.library === 'svg') {
          icon = <ReactSVG
            src={`${this.state.settings.icon_default_price.value.url}`}
            className={`mr-6 min-w-14 min-h-14 default-products-icon`}
          />;

        }
        break;
    }

    return icon;
  }


  displayPrice(type) {
    const {product, args} = this.props;
    let price = '';
    switch (type) {
      case 'price_on_call':
        price = <span
          className={`lisfinity-product--meta__price text-blue-600 font-regular`}
        >
          {lc_data.jst[475]}
        </span>;
        break;
      case 'free':
        price = <span
          className={`lisfinity-product--meta__price text-green-900 font-regular`}
        >
          {lc_data.jst[128]}
        </span>;
        break;
      case 'on-sale':
        price = <span
          className="lisfinity-product--meta__price price-on-sale flex flex-row-reverse text-red-1100 font-regular"
          dangerouslySetInnerHTML={{__html: product.price_html}}></span>;
        break;
      case 'per_week':
        price =
          <Fragment><span
            className={`lisfinity-product--meta__price flex flex-row-reverse text-${args.textColor} font-regular`}
            dangerouslySetInnerHTML={{__html: product.price_html}}></span><span
            className={`ml-4 text-${args.textColor}`}>{lc_data.jst[692]}</span></Fragment>;
        break;
      case 'per_month':
        price =
          <Fragment><span
            className={`lisfinity-product--meta__price flex flex-row-reverse text-${args.textColor} font-regular`}
            dangerouslySetInnerHTML={{__html: product.price_html}}></span><span
            className={`ml-4 text-${args.textColor}`}>{lc_data.jst[693]}</span></Fragment>;
        break;
      case 'per_day':
        price =
          <Fragment><span
            className={`lisfinity-product--meta__price flex flex-row-reverse text-${args.textColor} font-regular`}
            dangerouslySetInnerHTML={{__html: product.price_html}}></span><span
            className={`ml-4 text-${args.textColor}`}>{lc_data.jst[781]}</span></Fragment>;
        break;
      case 'per_hour':
        price =
          <Fragment><span
            className={`lisfinity-product--meta__price flex flex-row-reverse text-${args.textColor} font-regular`}
            dangerouslySetInnerHTML={{__html: product.price_html}}></span><span
            className={`ml-4 text-${args.textColor}`}>{lc_data.jst[780]}</span></Fragment>;
        break;
      default:
        price =
          <span
            className={`lisfinity-product--meta__price price-default flex flex-row-reverse text-${args.textColor} font-regular`}
            dangerouslySetInnerHTML={{__html: product.price_html}}></span>;
        break;
    }
    return price;
  }

  displayAuction() {
    const {product, args} = this.props;

    let price = '';
    switch (product.meta.price_type) {
      case 'on-sale':
        if (this.state.settings.display_label_on_sale === 'yes') {
          price = <div className="lisfinity-product--meta flex absolute label--sale">
            <div
              className="lisfinity-product--meta__icon flex items-center justify-center pr-6 h-24 w-product-label-sale rounded bg-red-600 text-sm text-white">
              {this.state.settings === undefined || (!this.state.settings?.label_icon_url || this.state.settings?.label_icon_url.value === '') &&
              <ReactSVG
                src={`${lc_data.dir}dist/${tagIcon}`}
                className="mr-6 w-14 h-14 fill-white"
              />}
              {this.state.settings?.label_icon_url?.value && this.state.settings?.label_icon_url?.library !== 'svg' &&
              <i
                className={`${this.state.settings.label_icon_url.value} mr-6 w-14 h-14 fill-white`}></i>
              }
              {this.state.settings?.label_icon_url?.value.url && this.state.settings?.label_icon_url?.library === 'svg' &&
              <ReactSVG
                src={`${this.state.settings.label_icon_url.value.url}`}
                className={`mr-6 min-w-14 min-h-14 fill-white`}
              />
              }

              {lc_data.jst[482]}
            </div>
          </div>;
        }
        break;
      case 'negotiable':
        if (this.state.settings.display_label_default === 'yes') {
          price =
            <div className="lisfinity-product--meta flex">
              <div className="lisfinity-product--meta__icon flex items-center text-sm text-grey-600">
              <span
                className={`lisfinity-product--meta__price text-${args.textColor}font-regular`}
              >
                {lc_data.jst[237]}
              </span>
              </div>
            </div>;
        }
        break;
      case 'fixed':
        if (this.state.settings.display_label_default === 'yes') {
          price =
            <div className="lisfinity-product--meta price-fixed flex">
              <div className="lisfinity-product--meta__icon flex items-center text-sm text-grey-600">
              <span
                className={`lisfinity-product--meta__price text-${args.textColor}font-regular`}
              >
                {lc_data.jst[481]}
              </span>
              </div>
            </div>;
        }
        break;
      case 'auction':
        if (this.state.settings.display_product_countdown === 'yes') {
          if (product.meta.auction_status === 'active' && lc_data.current_time < product.meta.auction_ends) {
            price = <div className="lisfinity-product--meta flex">
              <div className="lisfinity-product--meta__icon flex items-center">
                <ReactSVG
                  src={`${lc_data.dir}dist/${clockIcon}`}
                  className={`mr-6 min-w-14 min-h-14 fill-icon-field`}
                />
                <span
                  ref={this.timer}
                  className={`text-${args.textColor}`}
                  data-auction-ends={product.meta.auction_ends}
                >
              {lc_data.jst[239]}
            </span>
              </div>
            </div>;
          } else {
            price = <div className="lisfinity-product--meta flex">
              <div className="lisfinity-product--meta__icon flex items-center">
                <ReactSVG
                  src={`${lc_data.dir}dist/${clockIcon}`}
                  className={`mr-6 min-w-14 min-h-14 fill-icon-field`}
                />
                <span
                  className={`text-${args.textColor}`}
                >
              {lc_data.jst[240]}
            </span>
              </div>
            </div>;
          }
        }
        break;
      default:
        price = '';
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
    const {product} = this.props;
    return (
      <Fragment>
        <div className="lisfinity-product--meta flex items-baseline">
          {this.displayIcon(product.meta.price_type)}
          {this.displayPrice(product.meta.price_type)}
        </div>
        {this.displayAuction()}
      </Fragment>
    );
  }
}

export default ProductMetaStyle3;
