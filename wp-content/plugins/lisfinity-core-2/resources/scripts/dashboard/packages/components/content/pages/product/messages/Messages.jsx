/* global lc_data, React */
/**
 * External dependencies.
 */
import * as actions from '../../../../../store/actions';
import { useDispatch, useSelector } from 'react-redux';
import { Fragment, useState, createRef, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { sprintf, __ } from '@wordpress/i18n';
import { isEmpty, get, map } from 'lodash';
import Chats from './Chats';
import Messenger from './Messenger';
import LoaderSearchContent from '../../../../../../../theme/packages/components/loaders/LoaderSearchContent';
import LoaderChats from '../../../../../../../theme/packages/components/loaders/LoaderChats';
import ModalDemo from '../../../../../../../theme/packages/components/modal/ModalDemo';

const Messages = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { business, menuOpen, notifications, notificationsMenu, options } = data;

  const [owner, setOwner] = useState(lc_data.is_owner || false);
  const [currentUser, setCurrentUser] = useState(lc_data.current_user_id || 0);
  const [message, setMessage] = useState('');
  const [limit, setLimit] = useState(300);
  const [chats, setChats] = useState({});
  const [chat, setChat] = useState({});
  const [messages, setMessages] = useState({});
  const [loadingMessages, setLoadingMessages] = useState(false);
  const [notice, setNotice] = useState('');
  const [clickedChats, setClickedChats] = useState([]);
  const [formOpen, setFormOpen] = useState(false);
  const [fetching, setFetching] = useState(false);
  const [modalOpen, setModalOpen] = useState(false);

  const { product } = props;

  useEffect(() => {
    const owner = (product.product_owner == lc_data.current_user_id) || lc_data.is_agent;
    setOwner(owner);
    setLimit(options.messenger_limit);
    getMe();
    getChats();
  }, []);

  const getNotifications = (businessId) => {
    const response = actions.fetchData(lc_data.get_notifications, {
      'business': businessId,
    });
    response.then((result) => {
      dispatch(actions.setNotifications(result.data));
    });
  };

  /**
   * Get current user
   * ----------------
   */
  const getMe = () => {
    const headers = new Headers();
    headers.append('X-WP-Nonce', lc_data.nonce);
    fetch(`${lc_data.user}/?_wpnonce=${lc_data.nonce}`, {
      method: 'POST',
      credentials: 'same-origin',
      headers,
    }).then(response => response.json()).then(current_user => setCurrentUser(current_user));
  };

  const getChats = () => {
    setFetching(true);
    let url = `${lc_data.chats}/${props.productId}?_wpnonce=${lc_data.nonce}`;
    if (product.product_owner != lc_data.current_user_id && !lc_data.is_agent) {
      url = `${lc_data.chats}/${props.productId}?sender_id=${lc_data.current_user_id}&_wpnonce=${lc_data.nonce}`;
      apiFetch({ path: url }).then(messages => {
        setChat(get(messages[0], 'chat_id'));
        setMessages(messages);
        setFetching(false);
      });
    } else {
      apiFetch({ path: url }).then(chats => {
        setChats(chats);
        setFetching(false);
      });
    }
  };

  const getChatMessages = (chat) => {
    if (!isEmpty(chat)) {
      const url = `${lc_data.messages_chat}/${chat}?_wpnonce=${lc_data.nonce}`;
      apiFetch({ path: url }).then(messages => {
        setLoadingMessages(false);
        setMessages(messages);
      });
    }
  };

  const handleCloseMessenger = () => {
    setMessages({});
    setChat({});
  };

  const handleChatClick = (e, chat) => {
    setLoadingMessages(true);
    setChat(chat);
    getChatMessages(chat);
    clickedChats.push(chat);
    setClickedChats(clickedChats);
  };

  /**
   * Store message content
   * ---------------------
   *
   * @param e
   */
  const handleMessage = (e) => {
    setMessage(e.target.value);
  };

  /**
   * Handle user blocking
   * --------------------
   *
   * @param e
   * @param chat
   * @returns {boolean}
   */
  const handleUserBlock = (e, chat) => {
    if (lc_data.is_demo) {
      setModalOpen(true);
      return false;
    }
    e.preventDefault();
    if (!confirm(lc_data.jst[189])) {
      return false;
    }
    const formData = new FormData();
    const headers = new Headers();
    headers.append('X-WP-Nonce', lc_data.nonce);
    formData.append('id', chat);
    formData.append('type', 'block');

    fetch(lc_data.message_update, {
      method: 'POST',
      credentials: 'same-origin',
      headers,
      body: formData,
    }).then(json => json.json().then(response => {
      if (response.success) {
        getChatMessages(chat);
      } else {
        setNotice(response.message);
      }
    }));
  };

  /**
   * Store message to the database
   * -----------------------------
   *
   * @param e
   * @param chat
   */
  const handleSubmit = (e, chat) => {
    e.preventDefault();
    if (lc_data.is_demo) {
      setModalOpen(true);
      return false;
    }

    const formData = new FormData();
    const headers = new Headers();
    headers.append('X-WP-Nonce', lc_data.nonce);
    if (chat) {
      formData.append('chat_id', chat);
      if (props.product.to_chat) {
        formData.append('to_chat', 'true');
      }
    }
    formData.append('product_id', product.id);
    formData.append('sender_id', lc_data.current_user_id);
    formData.append('message', message);

    // Check if the message content is empty.
    if (message.length === 0) {
      setNotice(lc_data.jst[190]);
      return;
    }

    fetch(lc_data.message_submit, {
      method: 'POST',
      credentials: 'same-origin',
      headers,
      body: formData,
    }).then(json => json.json().then(response => {
        if (response.success) {
          setFormOpen(false);
          setMessage('');
          if (owner) {
            getChatMessages(chat);
          } else {
            getChats();
          }
        } else {
          setNotice(response.message);
        }
      }
    ));
  };

  const messagesCount = () => {
    let count = 0;
    map(chats, (chat) => {
      count += parseInt(chat.message_count, 10);
    });

    return count;
  };

  return [
    <div key={0}
         className={`messages-container relative flex flex-wrap items-start ${owner ? '' : 'messages-container--owner'}`}
         style={{
           height: fetching ? 400 : 'auto',
         }}
    >
      {fetching && <LoaderChats style="white"/>}
      {!fetching && owner
        ?
        <Fragment>
          <aside
            className="messages--users flex flex-col mb-20 bg:mb-0 w-full bg:w-1/4 bg-white rounded shadow-theme overflow-hidden">
            {messages &&
            <Chats
              chats={chats}
              handleChatClick={handleChatClick}
              openChat={chat}
              clickedChats={clickedChats}
              business={business}
            />}
          </aside>
          <section className="messages--content relative w-full bg:pl-30 bg:w-3/4 overflow-hidden"
                   style={{
                     height: loadingMessages ? 300 : 'auto',
                   }}
          >
            {loadingMessages && <LoaderChats key={0}/>}
            {!loadingMessages && !isEmpty(messages) && <div className="bg-white rounded shadow-theme">
              <Messenger
                me={currentUser}
                chat={chat}
                chats={chats}
                owner={owner}
                messages={messages}
                loading={loadingMessages}
                handleMessage={handleMessage}
                handleSubmit={e => handleSubmit(e, chat)}
                handleCloseMessenger={handleCloseMessenger}
                handleUserBlock={e => handleUserBlock(e, chat)}
                limit={limit}
                message={message}
              />
            </div>}
            {!loadingMessages && isEmpty(messages) &&
            <div className="flex flex-col bg:pl-20 font-bold text-2xl">
                <span
                  className="mb-2 font-normal text-base text-grey-700">{sprintf(lc_data.jst[191], messagesCount())}</span>
              {lc_data.jst[192]}
            </div>
            }
          </section>
        </Fragment>
        :
        <section className="messages--content relative w-full bg:w-3/4 overflow-hidden"
                 style={{
                   height: loadingMessages ? 300 : 'auto',
                 }}
        >
          {loadingMessages && <LoaderChats key={0}/>}
          {!loadingMessages && !isEmpty(messages) && <div className="bg-white rounded shadow-theme">
            <Messenger
              me={currentUser}
              chat={chat}
              chats={false}
              owner={false}
              messages={messages}
              loading={loadingMessages}
              handleMessage={handleMessage}
              handleSubmit={e => handleSubmit(e, chat)}
              handleCloseMessenger={handleCloseMessenger}
              limit={limit}
              message={message}
            />
          </div>}
        </section>
      }
    </div>,
    <ModalDemo
      key={4}
      isLogged={lc_data.logged_in}
      open={modalOpen}
      closeModal={() => setModalOpen(false)}
      title={lc_data.jst[606]}
    >
      <div className="font-semibold text-lg text-grey-700" dangerouslySetInnerHTML={{
        __html: lc_data.jst[607],
      }}
      />
    </ModalDemo>,
  ];
};

export default Messages;
