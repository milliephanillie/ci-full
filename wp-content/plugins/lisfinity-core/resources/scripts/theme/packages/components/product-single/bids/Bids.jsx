/* global lc_data, React */
/**
 * External dependencies.
 */
import store from '../../../../index';
import {Component, Fragment} from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import {isEmpty, get} from 'lodash';
import {sprintf, __} from '@wordpress/i18n';
/**
 * Internal dependencies
 */
import Modal from '../../modal/Modal';
import Login from '../../forms/Login';
import SendBid from '../../forms/SendBid';
import BidsList from './BidsList';
import AuctionTimer from '../partials/price-types/partials/AuctionTimer';
import {storeStat} from '../../../../vendor/functions';
import {toast} from 'react-toastify';
import axios from 'axios';
import ModalDemo from '../../modal/ModalDemo';

class Bids extends Component {
  constructor(props) {
    super(props);
    this.state = {
      loading: false,
      formOpen: false,
      userId: lc_data.current_user_id || false,
      productId: lc_data.current_listing_id || false,
      productOwner: lc_data.product_owner || false,
      logged: lc_data.logged_in || false,
      bids: {},
      value: 1,
      minimumBid: 1,
      bidDescription: '',
      notice: '',
      response: '',
      buyNowOpen: false,
      modalOpen: false,
    };
  }

  /**
   * Before a component has been mounted
   * -----------------------------------
   */
  componentWillMount() {
    this.getBids();
  }

  /**
   * React component has been mounted
   * --------------------------------
   */
  componentDidMount() {
    document.addEventListener('keydown', this.escFunction, false);
  }

  /**
   * Component will be unmounted
   * ---------------------------
   */
  componentWillUnmount() {
    document.removeEventListener('keydown', this.escFunction, false);
  }

  /**
   * On click escape close the modal
   * -------------------------------
   *
   * @param event
   */
  escFunction = (event) => {
    if (this.state.loading) {
      return false;
    }
    if (event.keyCode === 27) {
      this.setState({formOpen: false});
      this.setState({buyNowOpen: false});
      this.setState({modalOpen: false});
      const body = document.querySelector('body');
      body.style.overflow = 'auto';
    }
  };

  /**
   * Handle click outside of Modal
   * -----------------------------
   *
   * @param e
   * @returns {boolean}
   */
  handleClickOutside = e => {
    if (this.state.loading) {
      return false;
    }
    this.setState({formOpen: false});
    this.setState({buyNowOpen: false});
    this.setState({modalOpen: false});
    const body = document.querySelector('body');
    body.style.overflow = 'auto';
  };

  /**
   * Load bids
   * ---------
   */
  getBids() {
    const {productId, userId} = this.state;
    const url = `${lc_data.bids}${productId}/?_wpnonce=${lc_data.nonce}&owner=${lc_data.product_owner}&user_id=${userId}`;
    apiFetch({path: url}).then(bids => {
      this.setState({bids});
    });
  }

  /**
   * Open messages form
   * ------------------
   *
   * @param e
   */
  openForm = (e) => {
    const {formOpen} = this.state;
    const {bids} = this.state;
    const {product} = this.props;
    const productPrice = product.product_meta.start_price || 1;
    // if we're using minimum bid as starting point
    const minimumBid = isEmpty(bids) ? productPrice : (parseInt(bids[0].amount, 10) + 1);

    this.setState({response: ''});
    if (this.state.buyNowOpen) {
      this.setState({buyNowOpen: !this.state.buyNowOpen});
      this.setState({formOpen: false});
    }
    this.setState({formOpen: !formOpen});
    if (this.state.formOpen) {
      this.setState({buyNowOpen: false});
    }
    this.setState({value: minimumBid});
    if (!this.props?.options?.random_bidding) {
      this.setState({minimumBid: minimumBid - 1});
    }
  };

  /**
   * Handle bid value change
   * -----------------------
   *
   * @param e
   */
  handleBid = (e) => {
    const {value} = this.state;
    this.setState({notice: ''});
    this.setState({value: e.target.value});
  };

  handleBidDescription = (e) => {
    const {value} = this.state;
    this.setState({bidDescription: e.target.value});
  };

  /**
   * Handle bid submission
   * ---------------------
   *
   * @param e
   */
  handleSubmit = (e) => {
    e.preventDefault();
    if (lc_data.is_demo) {
      this.setState({modalOpen: true});
      return false;
    }
    const {minimumBid, value, notice, bidDescription} = this.state;

    const formData = new FormData();
    const headers = new Headers();
    headers.append('X-WP-Nonce', lc_data.nonce);
    formData.append('product_id', lc_data.current_product_id);
    formData.append('owner_id', lc_data.product_owner);
    formData.append('amount', value);
    formData.append('message', bidDescription);

    // Check if the message content is empty.
    if (value <= 0) {
      this.setState({notice: lc_data.jst[486]});
      return;
    }

    if (value <= minimumBid) {
      this.setState({notice: sprintf(lc_data.jst[487], minimumBid)});
      return;
    }

    this.setState({loading: true});
    this.setState({minimumBid: value});
    fetch(lc_data.submit_bid, {
      method: 'POST',
      credentials: 'same-origin',
      headers,
      body: formData,
    }).then(json => json.json().then(response => {
        if (response.success) {
          this.setState({response: response.message});
          toast(response.message, {
            position: toast.POSITION.BOTTOM_CENTER,
            autoClose: 3000,
          });
          this.getBids();
          setTimeout(() => {
            const body = document.querySelector('body');
            body.style.overflow = 'auto';
          }, 2000);
        } else {
          this.setState({notice: response.message});
        }
        this.setState({loading: false});
      }
    ));
  };

  handleBuyNow = (product) => {
    if (lc_data.is_demo) {
      this.setState({modalOpen: true});
      return false;
    }
    if (!product.product_meta.sell_on_site) {
      if (!confirm(lc_data.jst[488])) {
        return false;
      }
      const formData = new FormData();
      const headers = new Headers();
      headers.append('X-WP-Nonce', lc_data.nonce);
      formData.append('product', product.ID);
      fetch(lc_data.buy_bid, {
        method: 'POST',
        credentials: 'same-origin',
        headers,
        body: formData,
      }).then(json => json.json().then(response => {
          if (response.success) {
            this.setState({response: response.message});
            this.setState({formOpen: false});
            this.setState({buyNowOpen: true});
          } else {
            this.setState({notice: response.message});
            toast.error(response.message, {
              position: toast.POSITION.BOTTOM_CENTER,
              autoClose: 3000,
            });
          }
        }
      ));
    } else {
      const formData = new FormData();
      const headers = new Headers();
      headers.append('X-WP-Nonce', lc_data.nonce);
      formData.append('sell_on_site', 'yes');
      formData.append('product', product.ID);
      fetch(lc_data.buy_bid, {
        method: 'POST',
        credentials: 'same-origin',
        headers,
        body: formData,
      }).then(json => json.json().then(response => {
          if (response.success) {
            this.setState({response: response.message});
            this.setState({formOpen: false});
            window.location.href = response.permalink;
          } else {
            this.setState({notice: response.message});
          }
        }
      ));
    }
  };

  loginDemo = async () => {
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const url = lc_data.login_demo;
    await axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url,
      data: {
        username: 'demo',
        password: 'demo',
        redirect: window.location.href,
      }
    }).then(response => {
      if (response.data.success) {
        window.location.href = response.data.redirect;
      }
    });
  };

  /**
   * Renders the component.
   *
   * @return {Object}
   */

  render() {
    const {
      productOwner, logged, formOpen, notice, userId, productId, value, minimumBid, bids, response, buyNowOpen
    } = this.state;
    const {product, currentUser} = this.props;
    const seeBidsLink = `/${lc_data.myaccount}ad/${productId}/bids`;

    return [
      <div key={0} className="send-message">
        {!lc_data.is_demo && productOwner === userId &&
        <a
          href={seeBidsLink}
          className="p-6 px-20 bg-green-700 rounded font-semibold text-sm text-white hover:bg-green-900">
          {lc_data.jst[489]}
        </a>}
        {lc_data.is_demo && productOwner === userId &&
        <button
          onClick={this.openForm}
          className="p-6 px-20 bg-green-700 rounded font-semibold text-sm text-white hover:bg-green-900">
          {lc_data.jst[489]}
        </button>}
        {productOwner !== userId && product.product_meta.auction_status === 'active' && lc_data.current_time < product.product_meta.auction_ends && !bids.is_blocked &&
        <div className="bid--actions flex">
          <button
            type="button"
            onClick={() => {
              this.openForm();
              storeStat(productId, 2);
            }}
            className="p-6 px-20 bg-green-700 rounded font-semibold text-sm text-white hover:bg-green-900">
            {this.props.iconSettings?.text_place_bid || lc_data.jst[443]}
          </button>
          {!isEmpty(product.product_meta.price_html) &&
          <div className="flex-center ml-20">
            <div className="p-6 px-20 bg-red-100 buy-now-price rounded-l font-bold text-13 text-red-800"
                 dangerouslySetInnerHTML={{__html: product.product_meta.price_html}}>
            </div>
            <button
              type="button"
              onClick={() => this.handleBuyNow(product)}
              className="p-6 px-20 bg-red-500 rounded-r font-semibold text-sm text-white hover:bg-red-700">
              {this.props.iconSettings?.buy_now_text || this.props.options?.send_details ? lc_data.jst[704] : lc_data.jst[446]}
            </button>
          </div>
          }
        </div>
        }
      </div>,
      lc_data.is_demo && productOwner == userId && formOpen &&
      <div
        key={11}
        className="modal--wrapper modal-send-message fixed top-0 left-0 flex justify-center w-full h-full overflow-y-auto"
      >
        <Modal
          product={product}
          isLogged={currentUser}
          open={formOpen}
          title={lc_data.jst[489]}
          handleClickOutside={this.handleClickOutside}
          closeModal={this.openForm}
        >
          <div className="modal--product-info flex flex-wrap flex-col p-30 pb-40">
            <div>
              <p className="font-bold text-grey-700">{lc_data.jst[615]}</p>
            </div>
            <div className="mt-10">
              <a
                href={seeBidsLink}
                className="btn inline-flex bg-blue-700 rounded text-white"
              >
                {lc_data.jst[489]}
              </a>
            </div>
            <div className="mt-20">
              <p className="font-bold text-grey-700">{lc_data.demo}</p>
            </div>
            <div className="mt-10">
              <a
                href={lc_data.demo_product}
                className="btn bg-blue-700 inline-flex rounded text-white"
              >
                {lc_data.jst[612]}
              </a>
            </div>
          </div>
        </Modal>
      </div>,
      productOwner !== userId && formOpen &&
      <div
        key={1}
        className="modal--wrapper modal-send-message fixed top-0 left-0 flex justify-center w-full h-full overflow-y-auto"
      >
        {lc_data.is_demo && !lc_data.logged_in &&
        <Modal
          product={product}
          isLogged={currentUser}
          open={formOpen}
          title={lc_data.jst[601]}
          handleClickOutside={this.handleClickOutside}
          closeModal={this.openForm}
          type="demo"
        >
          <div className="modal--product-info flex flex-wrap flex-col p-30 pb-40">
            <div>
              <h6 className="font-bold text-grey-900">{lc_data.jst[614]}</h6>
              <p className="text-grey-700">{lc_data.jst[603]}</p>
            </div>
            <div className="mt-10">
              <button
                className="btn bg-blue-700 rounded text-white"
                onClick={() => this.loginDemo()}
              >
                {lc_data.jst[419]}
              </button>
            </div>
            <div className="mt-20 text-sm text-red-600">
              <strong className="mr-4">{lc_data.jst[604]}</strong>
              <span>{lc_data.jst[605]}</span>
            </div>
          </div>
        </Modal>
        }
        {logged &&
        <Modal
          product={product}
          isLogged={logged}
          open={formOpen}
          title={lc_data.jst[131]}
          handleClickOutside={this.handleClickOutside}
          closeModal={this.openForm}
        >
          <Fragment>
            <div className="modal--product-info flex flex-wrap p-30 pb-40">
              {product.thumbnail &&
              <figure className="relative flex w-1/5 min-w-1/5 h-auto sm:h-100">
                <img
                  className="absolute top-0 left-0 w-full h-full rounded object-cover" src={product.thumbnail.url}
                  alt={product.post_title}/>
              </figure>}
              <div className="flex flex-col pl-30 w-4/5">
                <div className="auction--info mb-10 flex items-center">
                  <span className="font-light text-sm text-grey-500">{lc_data.jst[490]}</span>
                  <AuctionTimer product={product} currentUser={currentUser}
                                iconClass="relative w-10 h-10 mr-6" style={{top: '-1px'}}/>
                </div>
                <h4 className="w-3/4 font-bold text-grey-1000">{product.post_title}</h4>
              </div>
            </div>
            <SendBid
              bids={bids}
              product={product}
              currentUser={currentUser}
              handleSubmit={this.handleSubmit}
              handleBid={this.handleBid}
              bidValue={value}
              bidDescription={this.state.bidDescription}
              handleBidDescription={this.handleBidDescription}
              minValue={minimumBid}
              notice={notice}
              response={response}
              handleBuyNow={() => this.handleBuyNow(product)}
              loading={this.state.loading}
              options={this.props.options}
            />
            {!isEmpty(bids) &&
            <BidsList
              currentUser={currentUser}
              bids={bids}
              options={this.props.options}
            />}
            {isEmpty(bids) &&
            <div
              className="modal--no-content flex-center p-60 bg-grey-100 font-bold text-sm-shadow text-grey-300">{lc_data.jst[491]}</div>}
          </Fragment>
        </Modal>
        }

        {!lc_data.is_demo && !logged &&
        <Modal
          product={product}
          isLogged={logged}
          open={formOpen}
          title={lc_data.jst[131]}
          handleClickOutside={this.handleClickOutside}
          closeModal={this.openForm}
        >
          <Login message={lc_data.jst[613]}/>
        </Modal>
        }
      </div>,
      buyNowOpen &&
      <div
        key={2}
        className="modal--wrapper modal-send-message fixed top-0 left-0 flex justify-center w-full h-full overflow-y-auto"
      >
        <Modal
          product={product}
          isLogged={logged}
          open={buyNowOpen}
          title={lc_data.jst[446]}
          handleClickOutside={this.handleClickOutside}
          closeModal={this.openForm}
        >
          <div
            className="flex flex-col p-40 text-base"
          >
            <div className="text-md"
                 dangerouslySetInnerHTML={{__html: lc_data.jst[492]}}></div>
            <div className="mt-20 text-md"
                 dangerouslySetInnerHTML={{__html: lc_data.jst[493]}}></div>
            <div className="mt-20 text-md"
                 dangerouslySetInnerHTML={{__html: lc_data.jst[494]}}></div>
          </div>
          <div
            className="modal--no-content flex-center p-60 bg-grey-100 font-bold text-sm-shadow text-grey-300">{lc_data.jst[495]}</div>
        </Modal>
      </div>,
      this.state.modalOpen &&
      <ModalDemo
        key={41}
        isLogged={lc_data.logged_in}
        open={this.state.modalOpen}
        closeModal={() => this.setState({modalOpen: false})}
        title={lc_data.jst[606]}
      >
        <div className="font-semibold text-lg text-grey-700" dangerouslySetInnerHTML={{
          __html: lc_data.jst[607],
        }}
        />
      </ModalDemo>,
    ];
  }
}

export default Bids;
