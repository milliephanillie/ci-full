/* global lc_data, React */
/**
 * External dependencies.
 */
import { __ } from '@wordpress/i18n';
import { isEmpty } from 'lodash';
import { Fragment } from 'react';
import { storeStat } from '../../../vendor/functions';
import LoaderIcon from '../../../../../images/icons/loader-rings-white.svg';
import ReactSVG from 'react-svg';

function SendBid(props) {
  const {
    product, currentUser, handleSubmit, bidValue, handleBid, bidDescription, handleBidDescription, bids, minValue, notice, response, handleBuyNow, loading,
  } = props;

  const bidTitle = isEmpty(bids) ? lc_data.jst[439] : lc_data.jst[440];
  return (
    <Fragment>
      {bids.is_blocked && <div
        className="flex py-40 px-30 bg-grey-100 font-bold text-xs-shadow text-grey-500 leading-tight">{lc_data.jst[441]}</div>}
      {product.product_meta.auction_status === 'active' && lc_data.current_time < product.product_meta.auction_ends && !bids.is_blocked &&
      <form key={0} className={`form flex flex-col px-30 pt-0 pb-40 bg-white ${isEmpty(bids.bids) && 'pt-0'}`}
            onSubmit={handleSubmit}>
        <label
          htmlFor="bid"
          className="mb-14 font-bold text-2xl text-grey-1000"
        >
          {bidTitle}
        </label>

        <div className="bid--actions flex flex-wrap items-center">
          <div className="bid--action">
            <div className="flex mb-4 text-sm text-grey-500">
              {lc_data.jst[442]}
              <span className="ml-4 font-bold text-sm text-grey-1100"
                    dangerouslySetInnerHTML={{ __html: product.product_meta.start_price_html }}></span>
            </div>
            <div className="relative flex">
              <input
                type="number"
                min={product.product_meta.start_price}
                value={bidValue}
                onChange={handleBid}
                onKeyUp={(e) => e.target.style.width = ((e.target.value.length + 1) * 32) + 'px'}
                className="mr-10 py-12 px-20 bg-grey-100 border border-grey-300 rounded font-semibold text-grey-800 w-128"
              />
              <button
                type="submit"
                className="py-12 px-20 bg-green-700 rounded font-semibold text-white hover:bg-green-900"
                disabled={loading}
              >
                {!loading && lc_data.jst[443]}
                {loading &&
                <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`} className="relative w-20 h-20 fill-white"
                          style={{
                            top: '-13px',
                            left: '3px',
                            width: '48px',
                            zoom: 0.8,
                          }}
                />
                }
              </button>

              <span className="bid--notice absolute left-0 text-sm text-red-700"
                    style={{ bottom: '-24px' }}>{notice}</span>
            </div>

          </div>

          {!isEmpty(product.product_meta.price_html) &&
          <Fragment>
            <span className="relative top-10 mx-20 text-grey-500">{lc_data.jst[444]}</span>

            <div className="bid--action mt-10 sm:mt-0">
              <div className="flex mb-4 text-sm text-grey-500">
                {lc_data.jst[445]}
                <span className="ml-4 font-bold text-sm text-grey-1100"
                      dangerouslySetInnerHTML={{ __html: product.product_meta.price_html }}></span>
              </div>
              <div>
              <span
                className="mr-10 py-12 pl-20 px-30 bg-red-100 border border-red-300 rounded font-semibold text-red-800"
                dangerouslySetInnerHTML={{ __html: product.product_meta.price_html }}></span>
                <input
                  type="hidden"
                  value={product.product_meta.price}
                  onChange={handleBid}
                  readOnly
                />
                <button
                  type="submit"
                  className="py-12 px-20 bg-red-500 rounded font-semibold text-sm text-white hover:bg-red-700"
                  onClick={() => {
                    handleBuyNow(product);
                    storeStat(product.ID, 2);
                  }}
                >
                  {props.send_details ? lc_data.jst[704] : lc_data.jst[446]}
                </button>
              </div>
            </div>
          </Fragment>
          }

        </div>

        {props?.options?.bidding_description &&
        <div className="bid--description flex flex-col mt-20">
          <label htmlFor="bidDescription">Bid Message</label>
          <textarea
            id="bidDescription"
            defaultValue={bidDescription}
            onChange={handleBidDescription}
            className="mr-10 py-12 px-20 bg-grey-100 border border-grey-300 rounded font-semibold text-grey-800"
            style={{
              width: 300,
              height: 120,
            }}
          />
        </div>
        }

        <div className="mt-10 bid--notification">
          <small
            className="w-2/3 text-xs text-grey-700 sm:text-sm"
            dangerouslySetInnerHTML={{ __html: lc_data.jst[447] }}></small>
        </div>

      </form>}
    </Fragment>
  );
}

export default SendBid;
