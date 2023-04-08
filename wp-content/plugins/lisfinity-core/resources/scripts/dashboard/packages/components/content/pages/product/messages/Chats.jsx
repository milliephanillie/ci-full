/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component, Fragment, createRef, useState, useEffect } from '@wordpress/element';
import { map, filter, isEmpty } from 'lodash';
import ReactSVG from 'react-svg';
import SearchIcon from '../../../../../../../../images/icons/search.svg';
import { Scrollbars } from 'react-custom-scrollbars';

const Chats = (props) => {
  const { openChat, chats, handleChatClick, clickedChats, business } = props;
  const [filteredChats, setFilteredChats] = useState(chats);
  const mapChats = !isEmpty(filteredChats) ? filteredChats : chats;

  const filterChats = (value) => {
    if (value !== '') {
      setFilteredChats(filter(chats, chat => chat.business_title.toLowerCase().includes(value.toLowerCase())));
    } else {
      setFilteredChats(chats);
    }
  };

  return (
    <div className="messages--chats flex flex-col pb-10">

      <div className="p-20">
        <div className="products--find flex items-center p-20 h-44 bg-grey-100 border border-grey-300 rounded">
          <ReactSVG
            src={`${lc_data.dir}dist/${SearchIcon}`}
            className="relative top-2 mr-10 w-20 h-20 fill-grey-700"
          />
          <input
            type="text"
            className="w-full bg-transparent font-semibold text-grey-900"
            placeholder={lc_data.jst[188]}
            onChange={e => filterChats(e.target.value)}
          />
        </div>
      </div>

      <Scrollbars style={{ zIndex: 20 }} autoHide={false} autoHeight autoHeightMin={60}
                  renderTrackHorizontal={props => <div {...props} className="hidden"/>}
                  renderThumbHorizontal={props => <div {...props} className="hidden"/>}
                  renderTrackVertical={props => <div {...props}
                                                     className="track--vertical top-0 right-0 bottom-0 w-2"/>}
                  renderThumbVertical={props => <div {...props}
                                                     className="thumb--vertical bg-grey-600 rounded opacity-25"/>}>
        {map(mapChats, (chat, index) => {
          const unread = filter(business.messages, { 'chat_id': chat.chat_id }).length || 0;
          return (
            <button
              key={index}
              type="button"
              className={`chat flex items-center py-10 px-20 w-full cursor-pointer ${chat.chat_id === openChat ? 'bg-blue-200' : ''}`}
              onClick={e => handleChatClick(e, chat.chat_id)}
            >
              <figure
                className={`avatar relative h-48 border-4 ${chat.chat_id === openChat ? 'border-white' : 'border-grey-100'}`}
                style={{ width: '55px', borderRadius: '14px' }}>
                {unread > 0 && <span
                  className="absolute flex-center w-18 h-18 bg-red-500 rounded text-sm text-white z-1"
                  style={{ top: -4, left: -4 }}>{unread}</span>}
                <img src={chat.thumbnail} alt="avatar"
                     className="absolute top-0 left-0 w-full h-full object-cover" style={{ borderRadius: '14px' }}/>
                {chat.message_count != 0 && !clickedChats.includes(chat.chat_id) &&
                <span className="marker--online absolute top-0 right-0 w-10 h-10 bg-green-800 rounded-full"></span>
                }
              </figure>
              <div className="pl-10 w-3/4 text-left">
                <div className="name">{chat.business_title}</div>
              </div>
            </button>
          );
        })}
      </Scrollbars>
    </div>
  );
};

export default Chats;
