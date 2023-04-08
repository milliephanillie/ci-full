/* global lc_data, React */
/**
 * External dependencies.
 */
import { connect } from 'react-redux';
import { Component, createRef } from '@wordpress/element';
import { initializeClock } from '../../../../vendor/functions';
import hammerIcon from '../../../../../../images/icons/construction-hammer.svg';
import ReactSVG from 'react-svg';
import clockIcon from '../../../../../../images/icons/alarm-clock.svg';
import { __ } from '@wordpress/i18n';

class PopupTimer extends Component {
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
    const timer = this.timer.current;
    if (timer) {
      const endTime = timer.dataset.auctionEnds;
      initializeClock(timer, endTime);
    }
  }

  /**
   * Renders the component.
   *
   * @return {Object}
   */
  render() {
    const { product } = this.props;
    return (
      <div>
        <div className="lisfinity-product--meta flex absolute top-20 left-14 z-10">
          <div className="lisfinity-product--meta__icon flex items-baseline">
            <ReactSVG
              src={`${lc_data.dir}dist/${clockIcon}`}
              className="relative top-2 mr-6 min-w-14 min-h-14 fill-white"
            />
            {product.meta.auction_status === 'active' && lc_data.current_time < product.meta.auction_ends ?
              <span
                ref={this.timer}
                className="text-white text-base"
                data-auction-ends={product.meta.auction_ends}
              >
              {lc_data.jst[239]}
              </span>
              :
              <span className="text-white text-base">{lc_data.jst[240]}</span>
            }
          </div>
        </div>
      </div>
    );
  }
}

function mapStateToProps(state) {
  const { postsByUrl } = state;
  const {
    isFetching, lastUpdated, items: results,
  } = postsByUrl.RECEIVE_POSTS || {
    isFetching: true,
    results: [],
  };
  return {
    results,
    isFetching,
    lastUpdated,
  };
}

export default connect(mapStateToProps)(PopupTimer);
