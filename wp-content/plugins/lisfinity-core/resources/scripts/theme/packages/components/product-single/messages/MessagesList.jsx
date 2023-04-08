/* global lc_data, React */
import { useRef } from '@wordpress/element';
import { useEffect } from 'react';
import { map } from 'lodash';
import cx from 'classnames';
import ReactSVG from 'react-svg';
import TickIcon from '../../../../../../images/icons/tick_icon.svg';
import LoaderIcon from '../../../../../../images/icons/loader-rings.svg';
import he from 'he';

const MessagesList = (props) => {
  const { messages, currentUser, loading } = props;
  const messagesEnd = useRef(null);

  const statusIcon = (status) => {
    if (status == 1) {
      return <div className="message--status relative top-4 flex items-center">
        <ReactSVG
          src={`${lc_data.dir}dist/${TickIcon}`}
          className="ml-10 w-10 h-10 fill-green-700"
        />
        <ReactSVG
          src={`${lc_data.dir}dist/${TickIcon}`}
          className="relative w-10 h-10 fill-green-700"
          style={{ left: '-5px' }}
        />
      </div>;
    } else {
      return <ReactSVG
        src={`${lc_data.dir}dist/${TickIcon}`}
        className="relative top-4 ml-10 w-10 h-10 fill-field-icon"
      />;
    }
  };

  useEffect(() => {
    messagesEnd.current.scrollIntoView();
  });

  return (
    <div className="messages relative p-30 bg-grey-100">
      <div className="messages--header">
        <h6 className="mb-20 font-bold text-xl text-grey-1000">{lc_data.jst[200]}</h6>
      </div>

      <div
        className={`messages--wrapper flex flex-col p-20 sm:p-40 bg-white rounded max-h-512 overflow-y-auto ${loading ? 'opacity-75' : ''}`}>
        {loading &&
        <div className={`absolute bottom-0 left-0 flex-center w-full ${messages.length > 3 ? 'h-512' : 'h-full'}`}>
          <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`} className="relative w-20 h-20 fill-grey-1000"
                    style={{
                      zoom: 0.8,
                    }}
          />
        </div>
        }
        {map(messages, (message, index) => {
          const messageWrapperClasses = cx({
            'ml-auto justify-end': message.is_author,
            'mr-auto': !message.is_author,
          });
          const messageClasses = cx({
            'bg-blue-200 rounded-r-none rounded-b-xl': message.is_author,
            'bg-grey-200 rounded-l-none rounded-b-xl': !message.is_author,
          });
          return (
            <div key={index} className={`flex mt-20 w-full sm:w-2/3 ${messageWrapperClasses}`}>
              {!message.is_author && message.thumbnail &&
              <figure
                className="relative flex-center top-20 mr-10 p-4 border-4 sm:border-6 border-grey-100 rounded-2xl w-48 h-48 min-w-48 sm:min-w-75 sm:w-75 sm:h-75"
              >
                <img
                  src={message.thumbnail}
                  alt={message.post_title}
                  className="rounded"
                />
              </figure>}
              <div className="w-full sm:w-auto">
                <div className="message--header flex justify-between mb-4 pl-20 pr-10">
                  <div
                    className="message--author font-light text-xs text-grey-500">{he.decode(!message.is_author ? message.post_title : lc_data.jst[193])}</div>
                  <div className="message--seen font-light text-xs text-grey-500">{statusIcon(message.status)}</div>
                </div>
                <div
                  className={`relative message--wrapper flex flex-wrap flex-col p-10 sm:p-20 pb-30 rounded-xl ${messageClasses}`}>
                  <div className="message--content mb-10 text-grey-900 leading-normal whitespace-pre-wrap">
                    {message.message}
                  </div>
                  <span
                    className="message--time absolute bottom-10 mt-5 self-end font-light text-xs text-grey-500">{message.created}</span>
                </div>
              </div>
            </div>
          );
        })}
        <div ref={messagesEnd}></div>
      </div>

    </div>
  );
};

export default MessagesList;
