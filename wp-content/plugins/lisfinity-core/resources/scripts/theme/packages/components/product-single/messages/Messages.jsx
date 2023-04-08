/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component, Fragment } from '@wordpress/element';
import ReactSVG from 'react-svg';
import { __ } from '@wordpress/i18n';
import { get } from 'lodash';
import cx from 'classnames';

/**
 * Internal dependencies.
 */
import Modal from '../../modal/Modal';
import SendMessage from '../../forms/SendMessage';
import MessagesList from './MessagesList';
import Login from '../../forms/Login';
import CommentsIcon from '../../../../../../images/icons/comments-alt.svg';
import { storeStat } from '../../../../vendor/functions';
import { toast } from 'react-toastify';
import axios from 'axios';
import ModalDemo from '../../modal/ModalDemo';

class Messages extends Component {
  constructor(props) {
    super(props);
    this.state = {
      loading: false,
      sending: false,
      chat: '',
      messages: {},
      formOpen: false,
      message: '',
      notice: '',
      response: '',
      maxLength: this.props.options.messenger_limit,
      note: this.props.options.messenger_note,
      noteDisplay: this.props.options.messenger_note_translation,
      modalOpen: false,
    };
  }

  componentWillMount() {
    this.getMessages();
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
    document.addEventListener('keydown', this.escFunction, false);
  }

  /**
   * Handle click outside of Modal
   * -----------------------------
   *
   * @param e
   * @returns {boolean}
   */
  handleClickOutside = e => {
    this.setState({ formOpen: false });
    const body = document.querySelector('body');
    body.style.overflow = 'auto';
  };

  /**
   * On click escape close the modal
   * -------------------------------
   *
   * @param event
   */
  escFunction = (event) => {
    if (event.keyCode === 27) {
      this.setState({ formOpen: false });
      const body = document.querySelector('body');
      body.style.overflow = 'auto';
    }
  };

  /**
   * Get messages from the database
   * ------------------------------
   */
  getMessages() {
    const { product } = this.props;
    this.setState({ loading: true });
    const formData = new FormData();
    const headers = new Headers();
    headers.append('X-WP-Nonce', lc_data.nonce);
    formData.append('receiver_id', product.premium_profile.owner);
    formData.append('product_id', lc_data.current_product_id);
    formData.append('sender_id', lc_data.current_user_id);

    fetch(lc_data.messages, {
      method: 'POST',
      credentials: 'same-origin',
      headers,
      body: formData,
    }).then(json => json.json().then(messages => {
        this.setState({ chat: get(messages, '[0].chat_id') });
        this.setState({ messages });
        this.setState({ loading: false });
        this.setState({ sending: false });
      }
    ));
  }

  /**
   * Open messages form
   * ------------------
   *
   * @param e
   */
  openForm = (e) => {
    const { formOpen } = this.state;
    const body = document.querySelector('body');
    if (!formOpen) {
      body.style.overflow = 'hidden';
    } else {
      body.style.overflow = 'auto';
    }
    this.setState({ response: '' });
    this.setState({ formOpen: !formOpen });
  };

  /**
   * Store message content
   * ---------------------
   *
   * @param e
   */
  handleMessage = (e) => {
    const { message, maxLength } = this.state;
    this.setState({ notice: '' });
    this.setState({ message: e.target.value });
  };

  /**
   * Store message to the database
   * -----------------------------
   *
   * @param e
   */
  handleSubmit = (e) => {
    e.preventDefault();
    if (lc_data.is_demo) {
      this.setState({ modalOpen: true });
      return false;
    }
    const { product } = this.props;
    const { chat, message, messages, notice } = this.state;
    this.setState({ sending: true });

    const formData = new FormData();
    const headers = new Headers();
    headers.append('X-WP-Nonce', lc_data.nonce);
    if (chat) {
      formData.append('chat_id', chat);
      formData.append('to_chat', 'true');
    }
    formData.append('product_id', lc_data.current_product_id);
    formData.append('sender_id', lc_data.current_user_id);
    formData.append('message', message);

    // Check if the message content is empty.
    if (message.length === 0) {
      this.setState({ sending: false });
      this.setState({ notice: lc_data.jst[190] });
      return;
    }

    fetch(lc_data.message_submit, {
      method: 'POST',
      credentials: 'same-origin',
      headers,
      body: formData,
    }).then(json => json.json().then(response => {
        if (response.success) {
          this.setState({ message: '' });
          this.getMessages();
          this.setState({ response: response.message });
          setTimeout(() => {
            const body = document.querySelector('body');
            body.style.overflow = 'auto';
          }, 2000);
          toast(response.message, {
            position: toast.POSITION.BOTTOM_CENTER,
            autoClose: 3000,
          });
        } else {
          this.setState({ notice: response.message });
          toast.error(response.message, {
            position: toast.POSITION.BOTTOM_CENTER,
            autoClose: 3000,
          });
        }
      }
    ));
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
    const { messages, formOpen, message, maxLength, notice, response } = this.state;
    const { product, currentUser, options } = this.props;
    const { owner } = product.premium_profile;
    const readMessagesLink = `${lc_data.site_url}${lc_data.myaccount}ad/${product.ID}/messages`;
    const btnClass = cx({
      'opacity-50': !lc_data.logged_in,
    });
    return [
      <div key={0} className="send-message w-48% bg:w-full xl:w-48% xl:pl-4">
        {(owner == currentUser.ID || lc_data.is_agent) && lc_data.is_demo &&
        <button type="button"
                className={`relative flex-center btn btn--transparent-red font-normal ${btnClass} px-24 whitespace-no-wrap w-full`}
                onClick={() => this.openForm()}
        >{lc_data.jst[498]}</button>
        }
        {(owner == currentUser.ID || lc_data.is_agent) && !lc_data.is_demo &&
        <a href={readMessagesLink} onClick={this.openForm}
           className="relative flex-center btn btn--transparent-red font-normal whitespace-no-wrap w-full">
          {lc_data.jst[498]}
        </a>
        }
        {lc_data.logged_in && (owner != currentUser.ID && !lc_data.is_agent) &&
        <button type="button"
                className={`relative flex-center btn btn--transparent-red font-normal ${btnClass} px-24 whitespace-no-wrap w-full`}
                onClick={() => {
                  this.openForm();
                  storeStat(product.ID, 2);
                }}
        >
          {lc_data.jst[499]}
        </button>}
        {!lc_data.logged_in &&
        <button type="button"
                className={`relative flex-center btn btn--transparent-red font-normal ${btnClass} px-24 whitespace-no-wrap w-full`}
                onClick={() => {
                  this.openForm();
                  storeStat(product.ID, 2);
                }}
        >
            <span
              className="absolute left-10 px-4 bg-white text-sm text-grey-500">{lc_data.jst[500]}</span>
          <ReactSVG
            src={`${lc_data.dir}dist/${CommentsIcon}`}
            className="mr-8 w-18 h-18 min-w-18 fill-icon-home"
          />
          {lc_data.jst[501]}
        </button>}
      </div>,
      (owner == currentUser.ID || lc_data.is_agent) && lc_data.is_demo && formOpen &&
      <div
        key={11}
        className="modal--wrapper modal-send-message fixed top-0 left-0 flex justify-center w-full h-full overflow-y-auto"
      >
        <Modal
          product={product}
          isLogged={currentUser}
          open={formOpen}
          title={lc_data.jst[609]}
          handleClickOutside={this.handleClickOutside}
          closeModal={this.openForm}
        >
          <div className="modal--product-info flex flex-wrap flex-col p-30 pb-40">
            <div>
              <p className="font-bold text-grey-700">{lc_data.jst[610]}</p>
            </div>
            <div className="mt-10">
              <a
                href={readMessagesLink}
                className="btn inline-flex bg-blue-700 rounded text-white"
              >
                {lc_data.jst[498]}
              </a>
            </div>
            <div className="mt-20">
              <p className="font-bold text-grey-700">{lc_data.jst[611]}</p>
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
      (owner != currentUser.ID && !lc_data.is_agent)&& formOpen &&
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
              <h6 className="font-bold text-grey-900">{lc_data.jst[602]}</h6>
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
        {currentUser &&
        <Modal
          product={product}
          isLogged={currentUser}
          open={formOpen}
          title={lc_data.jst[132]}
          handleClickOutside={this.handleClickOutside}
          closeModal={this.openForm}
        >
          <Fragment>
            <div className="modal--product-info flex flex-wrap p-30 pb-40">
              {product.thumbnail &&
              <figure className="relative flex w-1/5 min-w-1/5 h-auto sm:h-100">
                <img
                  className="absolute top-0 left-0 w-full h-full rounded object-cover" src={product?.thumbnail?.url ? product?.thumbnail?.url : options?.fallback_image ? options?.fallback_image : '' }
                  alt={product.post_title}/>
              </figure>}
              <div className="flex flex-col pl-30 w-4/5">
                <h4 className="font-bold text-grey-1000">{product.post_title}</h4>
              </div>
            </div>
            {messages && messages !== 'blocked' &&
            <MessagesList
              messages={messages}
              currentUser={currentUser}
              loading={this.state.sending}
            />}
            <SendMessage
              handleSubmit={this.handleSubmit}
              handleMessage={this.handleMessage}
              messages={messages}
              message={message}
              maxLength={maxLength}
              notice={notice}
              note={this.state.note}
              noteDisplay={this.state.noteDisplay}
              loading={this.state.sending}
            />
            {!messages &&
            <div
              className="modal--no-content flex-center p-60 bg-grey-100 font-bold text-sm-shadow text-grey-300">{lc_data.jst[502]}</div>}
          </Fragment>
        </Modal>
        }
        {!lc_data.is_demo && !currentUser &&
        <Modal
          product={product}
          isLogged={currentUser}
          open={formOpen}
          title={lc_data.jst[132]}
          handleClickOutside={this.handleClickOutside}
          closeModal={this.openForm}
        >
          <Login/>
        </Modal>
        }
      </div>,
      this.state.modalOpen &&
      <ModalDemo
        key={41}
        isLogged={lc_data.logged_in}
        open={this.state.modalOpen}
        closeModal={() => this.setState({ modalOpen: false })}
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

export default Messages;
