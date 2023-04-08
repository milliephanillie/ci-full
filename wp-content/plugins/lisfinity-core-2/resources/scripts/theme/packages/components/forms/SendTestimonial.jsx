/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect } from 'react';
import Textarea from 'react-textarea-autosize';
import { sprintf, __ } from '@wordpress/i18n';
import { map, isEmpty } from 'lodash';
import ChooseRating from '../partials/ChooseRating';

const SendTestimonial = (props) => {
  const {
    product, handleSubmit, handleMessage, message, maxLength, success, errors, ratings,
  } = props;
  const messageLength = message ? message.length : 0;
  const [loading, setLoading] = useState(false);
  const [avgValue, setAvgValue] = useState(0);
  const [values, setValues] = useState({});

  const handleChange = (name, value) => {
    values[name] = value;
    const count = Object.keys(values).length;
    let sum = 0;
    map(values, (value, key) => {
      sum += value;
    });

    setAvgValue(sum / count);
    ratings(values);
  };

  return [
    !isEmpty(success) && <div key={0} className="flex p-40 pt-0 bg-white text-green-700">{success}</div>,
    isEmpty(product.premium_profile.testimonial.options) &&
    <div key={1} className="flex p-40 pt-0 bg-white font-semibold text-xl text-grey-700">{lc_data.jst[559]}</div>,
    !loading && isEmpty(success) && !isEmpty(product.premium_profile.testimonial.options) &&
    <form
      key={2}
      className="product--reports form flex flex-col p-40 pt-0 pb-60 bg-white"
      onSubmit={(e) => handleSubmit(e)}
    >
      <label
        htmlFor="chat-message"
        className="mt-20 mb-20 font-bold text-2xl text-grey-1000"
      >
        {lc_data.jst[453]}
      </label>

      <div className="flex justify-between items-normal">
        <div className="mt-10 mb-30">
          {map(product.premium_profile.testimonial.options, (option, index) => {
            return option?.label &&
              <ChooseRating key={index} label={option?.label} name={option?.slug} onChange={handleChange}/>;
          })}
        </div>
        <div className="flex-center flex-col pr-40">
          <p className="mb-10 text-lg font-semibold text-green-1000">{lc_data.jst[454]}</p>
          <div className="p-40 bg-green-200 rounded-full font-bold text-6xl text-green-900">
            <span>{avgValue.toFixed(1)}</span>
          </div>
        </div>
      </div>

      <div
        className={`relative p-16 bg-grey-100 border border-grey-300 rounded ${product.premium_profile.testimonial.options.length < 3 ? 'mt-20' : ''}`}>
        <Textarea
          name="message"
          id="testimonial-message"
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
          dangerouslySetInnerHTML={{ __html: sprintf(lc_data.jst[455], maxLength - messageLength) }}></span>}
        {!errors.message && maxLength === messageLength &&
        <span
          className="description absolute bottom-4 right-14 font-light text-sm text-red-700">{lc_data.jst[197]}</span>}

      </div>
      <div className="flex flex-col mt-10 p-10 pr-0">
        <small
          className="w-2/3 text-xs text-grey-900 sm:text-sm"
          dangerouslySetInnerHTML={{ __html: __('You can be banned for violent messages, check our <a href="#" target="_blank" class="font-semibold text-blue-800">FAQ</a> page for more info.', 'lisfinity-core') }}></small>
        <button
          type="submit"
          className="mt-20 px-20 w-fulll sm:w-1/3 xxl:w-1/4 h-40 bg-blue-700 rounded font-semibold text-white hover:bg-blue-900 whitespace-no-wrap"
        >
          {lc_data.jst[456]}
        </button>
      </div>
      {errors.general && map(errors.general, (error, index) => {
        return <div key={index}
                    className="flex mt-10 pl-10 description font-semibold text-sm text-red-700">{error}</div>;
      })}
    </form>,
  ];
};

export default SendTestimonial;
