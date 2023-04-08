/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect } from 'react';
import Textarea from 'react-textarea-autosize';
import { sprintf, __ } from '@wordpress/i18n';
import { map, isEmpty } from 'lodash';
import Select from 'react-select';

const SendReport = (props) => {
  const {
    handleSubmit, handleMessage, message, maxLength, success, errors,
  } = props;
  const messageLength = message ? message.length : 0;
  const [loading, setLoading] = useState(true);
  const [reasons, setReasons] = useState(false);
  const [reason, setReason] = useState(reasons[0]);

  const getReasons = () => {
    fetch(lc_data.report_options, {}).then(response => response.json()).then(response => {
      const reasonsObj = [];
      map(response.reasons, (label, value) => {
        reasonsObj.push({ value, label });
      });
      setReasons(reasonsObj);
      setLoading(false);
    });
  };

  useEffect(() => {
    getReasons();
  }, []);

  return [
    !isEmpty(success) && <div key={0} className="flex p-40 pt-0 bg-white">{success}</div>,
    !loading && isEmpty(success) &&
    <form
      key={1}
      className="product--reports form flex flex-col p-40 pt-0 pb-60 bg-white"
      onSubmit={(e) => handleSubmit(e, reason)}
    >
      <label
        htmlFor="chat-message"
        className="mb-20 font-bold text-xl text-grey-1000"
      >
        {lc_data.jst[449]}
      </label>
      {props.options.report_reasons !== 'no' &&
      <div className="relative">
        <Select
          options={reasons}
          name="reason"
          onChange={(selected) => setReason(selected)}
          placeholder={lc_data.jst[450]}
        />
        {errors.reason &&
        <span className="description absolute bottom-4 left-14 font-light text-sm text-red-700">{errors.reason}</span>}
      </div>
      }
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

        {errors.message &&
        <span
          className="description absolute bottom-4 right-14 font-light text-sm text-red-700">{errors.message}</span>}
        {!errors.message && maxLength !== messageLength && <span
          className="description absolute bottom-4 right-14 font-light text-sm"
          dangerouslySetInnerHTML={{ __html: sprintf(lc_data.jst[196], maxLength - messageLength) }}></span>}
        {!errors.message && maxLength === messageLength &&
        <span
          className="description absolute bottom-4 right-14 font-light text-sm text-red-700">{lc_data.jst[197]}</span>}

      </div>
      <div className="flex flex-wrap mt-10 p-10 pr-0">
        <small
          className="mb-10 bg:mb-0 w-full bg:w-2/3 text-xs text-grey-900 sm:text-sm"
          dangerouslySetInnerHTML={{ __html: lc_data.jst[451] }}></small>
        <button
          type="submit"
          className="ml-auto btn h-40 bg-blue-700 text-white hover:bg-blue-900"
        >
          {lc_data.jst[452]}
        </button>
      </div>
      {errors.general && map(errors.general, (error, index) => {
        return <div key={index}
                    className="flex -mt-20 pl-10 description font-semibold text-sm text-red-700">{error}</div>
      })}
    </form>,
  ];
};

export default SendReport;
