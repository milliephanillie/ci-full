/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component, createRef } from '@wordpress/element';
import { connect } from 'react-redux';
import { __ } from '@wordpress/i18n';
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
import { Fragment } from 'react';
import * as functions from '../../../../vendor/functions';

class ProductMeta extends Component {
  constructor(props) {
    super(props);
    this.state = {};
    this.timer = createRef();
  }

  componentDidMount() {
    this.initializeCountdownTimer();
  }

  componentDidUpdate() {
    this.initializeCountdownTimer();
  }

  initializeCountdownTimer() {
    const { product } = this.props;
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
    const { args } = this.props;
    let icon = '';
    switch (type) {
      case 'on-sale':
        icon = <ReactSVG
          src={`${lc_data.dir}dist/${tagIcon}`}
          className={`mr-8 min-w-16 min-h-16 ${args.color}`}
        />;
        break;
      case 'auction':
        icon = <ReactSVG
          src={`${lc_data.dir}dist/${hammerIcon}`}
          className={`mr-8 min-w-16 min-h-16 auction-icon ${args.color}`}
        />;
        break;
      case 'price_on_call':
        icon = <ReactSVG
          src={`${lc_data.dir}dist/${phoneIcon}`}
          className={`mr-8 min-w-16 min-h-16 ${args.color}`}
        />;
        break;
      case 'free':
        icon = <ReactSVG
          src={`${lc_data.dir}dist/${giftIcon}`}
          className={`mr-8 min-w-16 min-h-16 ${args.color}`}
        />;
        break;
      default:
        icon = <ReactSVG
          src={`${lc_data.dir}dist/${walletIcon}`}
          className={`mr-8 min-w-16 min-h-16 ${args.color}`}
        />;
        break;
    }

    return icon;
  }

  //todo add to a custom function like the one on SearchMap.jsx
  displayPrice(type) {
    const { product, args } = this.props;
    let price = '';
    switch (type) {
      case 'price_on_call':
        price = <span
          className={`lisfinity-product--meta__price text-${args.textColor} font-bold`}
        >
          {lc_data.jst[475]}
        </span>;
        break;
      case 'free':
        price = <span
          className={`lisfinity-product--meta__price text-${args.textColor} font-bold`}
        >
          {lc_data.jst[128]}
        </span>;
        break;
      case 'per_week':
        price =
          <Fragment><span className={`lisfinity-product--meta__price flex flex-row-reverse text-${args.textColor} font-bold`}
                          dangerouslySetInnerHTML={{ __html: product.price_html }}></span><span className={`ml-4 text-${args.textColor}`}>{lc_data.jst[692]}</span></Fragment>;
        break;
      case 'per_month':
        price =
          <Fragment><span className={`lisfinity-product--meta__price flex flex-row-reverse text-${args.textColor} font-bold`}
                          dangerouslySetInnerHTML={{ __html: product.price_html }}></span><span className={`ml-4 text-${args.textColor}`}>{lc_data.jst[693]}</span></Fragment>;
        break;
      default:
        price = <span className={`lisfinity-product--meta__price text-${args.textColor} font-bold`}
                      dangerouslySetInnerHTML={{ __html: product.price_html }}></span>;
        break;
    }
    return price;
  }

  displayAuction() {
    const { product, args } = this.props;
    let price = '';
    switch (product.meta.price_type) {
      case 'negotiable':
        price =
          <div className="lisfinity-product--meta flex">
            <div className="lisfinity-product--meta__icon flex items-center text-sm text-grey-600">
              <span
                className={`lisfinity-product--meta__price text-${args.textColor} font-bold`}
              >
                {lc_data.jst[237]}
              </span>
            </div>
          </div>;
        break;
      case 'fixed':
        price =
          <div className="lisfinity-product--meta flex">
            <div className="lisfinity-product--meta__icon flex items-center text-sm text-grey-600">
              <span
                className={`lisfinity-product--meta__price text-${args.textColor} font-bold`}
              >
                {lc_data.jst[481]}
              </span>
            </div>
          </div>;
        break;
      case 'auction':
        price = <div className="lisfinity-product--meta flex">
          <div className="lisfinity-product--meta__icon flex items-center">
            <ReactSVG
              src={`${lc_data.dir}dist/${clockIcon}`}
              className={`mr-8 min-w-16 min-h-16 ${args.color}`}
            />
            {product.meta.auction_status === 'active' && lc_data.current_time < product.meta.auction_ends ?
              <span
                ref={this.timer}
                className={`text-${args.textColor} font-regular`}
                data-auction-ends={product.meta.auction_ends}
              >
              {lc_data.jst[239]}
            </span>
              :
              <span className={`font-bold text-13 text-${args.textColor}`}>{lc_data.jst[240]}</span>
            }
          </div>
        </div>;
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
    const { product } = this.props;
    return (
      <Fragment>
        <div className="lisfinity-product--meta flex items-center">
          {this.displayIcon(product.meta.price_type)}
          {this.displayPrice(product.meta.price_type)}
        </div>
        {this.displayAuction()}
      </Fragment>
    );
  }
}

export default ProductMeta;
