/* global lc_data, React */
/**
 * External dependencies.
 */
import { map, filter, isEmpty } from 'lodash';
import cx from 'classnames';
import { useEffect, useState } from '@wordpress/element';

function BidsList(props) {
  const {
    bids,
    currentUser,
    options,
  } = props;
  const [userBids, setUserBids] = useState(bids);

  useEffect(() => {
    setUserBids(filter(bids, bid => bid.bidder_id == currentUser.ID));
  }, [bids]);

  return (
    <div className="bids p-30 bg-grey-100">
      <div className="bid--header pl-20">
        <h6 className="mb-20 font-bold text-xl text-grey-1000">{lc_data.jst[496]}</h6>
      </div>
      <div className="bid--wrapper flex flex-col p-40 bg-white rounded max-h-512 overflow-y-auto">
        {(options?.hide_bidding && isEmpty(userBids)) && <div className="font-semibold text-lg">{lc_data.jst[683]}</div>}
        {map(bids, (bid, index) => {
          const bidWrapperClasses = cx({
            'ml-auto': currentUser.ID == bid.bidder_id,
            'mr-auto': currentUser.ID != bid.bidder_id,
          });
          const bidClasses = cx({
            'bg-blue-200 rounded-r-none rounded-b-xl': currentUser.ID == bid.bidder_id,
            'bg-grey-200 rounded-l-none rounded-b-xl': currentUser.ID != bid.bidder_id,
          });
          return (
            ((options?.hide_bidding && currentUser.ID == bid.bidder_id) || !options?.hide_bidding) &&
            <div key={index} className={`flex mt-20 w-4/5 sm:w-3/5 ${bidWrapperClasses}`}>
              {currentUser.ID != bid.bidder_id && bid.thumbnail &&
              <figure className="relative top-20 w-1/4 mr-20"><img src={bid.thumbnail} alt={bid.post_title}
                                                                   className="rounded"/>
              </figure>}
              <div className="w-full">
                <div className="bid--header flex justify-between mb-4 px-20">
                  <div
                    className="bid--author font-light text-xs text-grey-500">{currentUser.ID != bid.bidder_id ? bid.post_title : lc_data.jst[497]}</div>
                </div>
                <div
                  className={`bid--wrapper flex flex-wrap justify-between items-center p-10 sm:p-20 w-full rounded-xl ${bidClasses}`}>
                  <div className="bid--content font-semibold text-grey-900"
                       dangerouslySetInnerHTML={{ __html: bid.amount_html }}>
                  </div>
                  <span
                    className="bid--time font-light text-xs text-grey-500 w-full sm:w-auto">{bid.created}</span>
                </div>
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
}

export default BidsList;
