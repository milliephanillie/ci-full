/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component } from '@wordpress/element';
import Textarea from 'react-textarea-autosize';
import { sprintf, __ } from '@wordpress/i18n';
import { Fragment } from 'react';
import ReactSVG from 'react-svg';
import LoaderIcon from '../../../../../images/icons/loader-rings-white.svg';

const SendMessage = (props) => {
  const {
    handleSubmit, handleMessage, messages, message, maxLength, notice, loading
  } = props;
  const messageLength = message ? message.length : 0;

  return (
    <Fragment>
      {messages === 'blocked' && <div
        className="flex p-40 bg-grey-100 font-bold text-xs-shadow text-grey-500 leading-tight">{lc_data.jst[441]}</div>}
      {messages !== 'blocked' &&
      <form key={0} className={`form flex flex-col p-40 pb-60 bg-white ${!messages && 'pt-0'}`} onSubmit={handleSubmit}>
        {!messages && <label
          htmlFor="chat-message"
          className="mb-20 font-bold text-xl text-grey-1000"
        >
          {lc_data.jst[448]}
        </label>}
        <div className="relative p-16 bg-grey-100 border border-grey-300 rounded">
          <Textarea
            name="message"
            id="chat-message"
            rows="2"
            className="w-full min-h-40 bg-grey-100 font-semibold text-grey-900 outline-none"
            placeholder={lc_data.jst[195]}
            onChange={handleMessage}
            value={message}
            maxLength={maxLength}
          />

          {notice &&
          <span className="description absolute bottom-4 right-14 font-light text-sm text-red-700">{notice}</span>}
          {!notice && maxLength !== messageLength && <span
            className="description absolute bottom-4 right-14 font-light text-sm"
            dangerouslySetInnerHTML={{ __html: sprintf(lc_data.jst[196], maxLength - messageLength) }}></span>}
          {!notice && maxLength === messageLength &&
          <span
            className="description absolute bottom-4 right-14 font-light text-sm text-red-700">{lc_data.jst[197]}</span>}

        </div>
        <div className="flex flex-wrap mt-10 p-10 pr-0">
          {props.note && <small
            className="w-2/3 text-xs text-grey-900 sm:text-sm"
            dangerouslySetInnerHTML={{ __html: props.note.replace('FAQ', `<a href="javascript" class="text-blue-700 hover:underline">${props.noteDisplay}</a>`) }}></small>
          }
          <button
            type="submit"
            className="ml-auto btn h-40 bg-green-700 text-white hover:bg-green-900"
          >
            {!loading && lc_data.jst[198]}
            {loading &&
            <ReactSVG src={`${lc_data.dir}dist/${LoaderIcon}`} className="relative w-20 h-20 fill-white"
                      style={{
                        top: '-13px',
                        left: '36px',
                        width: '114px',
                        zoom: 0.8,
                      }}
            />
            }
          </button>
        </div>
      </form>}
    </Fragment>
  );
};

export default SendMessage;
