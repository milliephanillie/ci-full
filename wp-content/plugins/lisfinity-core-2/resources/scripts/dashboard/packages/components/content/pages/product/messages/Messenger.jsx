/* global lc_data, React */
import { useEffect, Fragment, useRef, useState } from '@wordpress/element';
import { sprintf } from '@wordpress/i18n';
import { map, isEmpty, findKey } from 'lodash';
import cx from 'classnames';
import ReactSVG from 'react-svg';
import Textarea from 'react-textarea-autosize';
import TickIcon from '../../../../../../../../images/icons/tick_icon.svg';
import BanIcon from '../../../../../../../../images/icons/na.svg';
import CloseIcon from '../../../../../../../../images/icons/close.svg';
import { Scrollbars } from 'react-custom-scrollbars';
import { useSelector } from 'react-redux';
import he from 'he';

const Messenger = (props) => {
  const {
    owner,
    messages,
    loading,
    handleSubmit,
    handleMessage,
    handleCloseMessenger,
    handleUserBlock,
    message,
    limit,
    notice,
    me,
  } = props;
  const currentUser = lc_data.current_user_id || 0;
  const messageLength = message ? message.length : 0;
  const messagesEnd = useRef(null);
  const [isBlocked, setIsBlocked] = useState(false);
  const data = useSelector(state => state);
  const { options } = data;
  const { business } = data.business;

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
    if (!isEmpty(messages) && null !== messagesEnd.current) {
      messagesEnd.current.scrollIntoView();
    }
    if (!isEmpty(messages)) {
      if (findKey(messages, { is_blocked: true })) {
        setIsBlocked(true);
      } else {
        setIsBlocked(false);
      }
    }
  });

  return [
    !loading && !isEmpty(messages) &&
    <div key={1} className="messages bg-grey-100">

      <div className="messages--header flex justify-between items-center p-40 pb-20 bg-white rounded-t">
        <div className="flex flex-col">
          <p className="mb-6 text-grey-1000">{me.display_name}</p>
          <h6 className="font-bold text-xl text-grey-1000">{lc_data.jst[200]}</h6>
        </div>
        <div>
          {owner && !isBlocked &&
          <button type="button" onClick={handleUserBlock} title={lc_data.jst[608]}>
            <ReactSVG
              src={`${lc_data.dir}dist/${BanIcon}`}
              className="mr-20 w-20 h-20 fill-grey-700"
            />
          </button>
          }
          {owner &&
          <button type="button" onClick={handleCloseMessenger}>
            <ReactSVG
              src={`${lc_data.dir}dist/${CloseIcon}`}
              className="mr-10 w-20 h-20 fill-red-500"
            />
          </button>}
        </div>
      </div>

      <div className="messages--wrapper flex flex-col p-16 sm:p-40 pt-0 bg-white rounded">
        {!isEmpty(messages)
        &&
        <Fragment>
          <Scrollbars style={{ zIndex: 20 }} autoHide={false} autoHeight autoHeightMin={400}
                      renderTrackHorizontal={props => <div {...props} className="hidden"/>}
                      renderThumbHorizontal={props => <div {...props} className="hidden"/>}
                      renderTrackVertical={props => <div {...props}
                                                         className="track--vertical top-0 right-0 bottom-0 w-2"/>}
                      renderThumbVertical={props => <div {...props}
                                                         className="thumb--vertical bg-grey-600 rounded opacity-25"/>}>
            <div className="messages flex flex-col p-16 sm:p-30 bg-grey-100 rounded" style={{
              minHeight: 400,
            }}>
              {map(messages, (message, index) => {
                const isAuthor = message.is_author;
                const messageWrapperClasses = cx({
                  'ml-auto justify-end': isAuthor,
                  'mr-auto justify-start': !isAuthor,
                });
                const messageClasses = cx({
                  'bg-blue-200 rounded-r-none rounded-b-xl': isAuthor,
                  'bg-grey-200 rounded-l-none rounded-b-xl': !isAuthor,
                });
                return (
                  <div key={index} className={`flex mt-20 w-full sm:max-w-2/3 ${messageWrapperClasses}`}>
                    <div className="message--author flex flex-wrap sm:flex-nowrap">
                      {!isAuthor && message.thumbnail &&
                      <figure
                        className="relative flex-center top-10 mr-4 p-4 border-grey-100 rounded-2xl"
                        style={{ borderWidth: '6px', height: '75px', width: '75px', minWidth: '75px' }}
                      >
                        <img
                          src={message.thumbnail}
                          alt={message.post_title}
                          className="rounded"
                        />
                      </figure>
                      }
                      <div className="relative ml-auto w-full sm:w-auto">
                        <div className="message--header flex justify-between mb-4 pl-20 pr-10">
                          <div
                            className="message--author font-light text-xs text-grey-500">{!isAuthor ? message.post_title : lc_data.jst[193]}</div>
                          <div
                            className="message--seen font-light text-xs text-grey-500">{statusIcon(message.status)}</div>
                        </div>
                        <div
                          className={`relative message--wrapper flex flex-col p-20 pb-30 rounded-xl ${messageClasses}`}>
                          <div className="message--content mb-10 text-grey-900 leading-normal whitespace-pre-wrap">
                            {message.message}
                          </div>
                          <span
                            className="message--time absolute bottom-10 mt-5 self-end font-light text-xs text-grey-500">{message.created}</span>
                        </div>
                        {message.is_blocked && <span
                          className="message--blocked absolute left-20 mt-5 font-light text-xs text-red-500"
                          style={{ bottom: '-20px' }}>{lc_data.jst[194]}</span>}
                      </div>

                    </div>
                  </div>
                );
              })}
              <div ref={messagesEnd}></div>
            </div>
          </Scrollbars>
          {!isBlocked || owner ?
            <form className="form flex flex-col pt-40 pb-0 bg-white" onSubmit={handleSubmit}>
              <div className="relative p-16 bg-grey-100 border border-grey-300 rounded">
                <Textarea
                  name="message"
                  id="chat-message"
                  rows="2"
                  className="w-full min-h-40 bg-grey-100 font-semibold text-grey-900 outline-none"
                  placeholder={lc_data.jst[195]}
                  onChange={handleMessage}
                  value={message}
                  maxLength={limit}
                />

                {notice &&
                <span
                  className="description absolute bottom-4 right-14 font-light text-sm text-red-700">{notice}</span>}
                {!notice && limit !== messageLength && <span
                  className="description absolute bottom-4 right-14 font-light text-sm"
                  dangerouslySetInnerHTML={{ __html: sprintf(lc_data.jst[196], limit - messageLength) }}></span>}
                {!notice && limit === messageLength &&
                <span
                  className="description absolute bottom-4 right-14 font-light text-sm text-red-700">{lc_data.jst[197]}</span>}

              </div>
              <div className="flex flex-wrap mt-10 p-10 pr-0">
                {options.messenger_note && <small
                  className="w-2/3 text-xs text-grey-900 sm:text-sm"
                  dangerouslySetInnerHTML={{ __html: options.messenger_note.replace('FAQ', `<a href="javascript" class="text-blue-700 hover:underline">${options.messenger_note_translation}</a>`) }}></small>
                }
                <button
                  type="submit"
                  className="ml-auto btn h-40 bg-green-700 text-white hover:bg-green-900"
                >
                  {lc_data.jst[198]}
                </button>
              </div>
            </form>
            :
            <div className="notification mt-20 p-40 bg-grey-100 rounded font-bold text-xl text-grey-900">
              {lc_data.jst[199]}
            </div>
          }
        </Fragment>}
      </div>
    </div>,
  ];
};

export default Messenger;
