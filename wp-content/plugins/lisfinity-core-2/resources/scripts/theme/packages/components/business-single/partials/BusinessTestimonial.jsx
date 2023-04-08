/* global lc_data, React */

import ReactSVG from 'react-svg';
import { Fragment, useEffect, useState } from '@wordpress/element';
import StarIcon from '../../../../../../images/icons/star.svg';
import { __ } from '@wordpress/i18n';
import Modal from '../../modal/Modal';
import SendTestimonial from '../../forms/SendTestimonial';
import mapMarkerIcon from '../../../../../../images/icons/map-marker.svg';
import axios from 'axios';
import { map, isEmpty } from 'lodash';
import { storeStat } from '../../../../vendor/functions';
import CommentsIcon from '../../../../../../images/icons/comments-alt.svg';
import ModalDemo from '../../modal/ModalDemo';

const BusinessTestimonial = (props) => {
  const { product } = props;
  const currentUser = lc_data.current_user_id;
  const [modalOpen, setModalOpen] = useState(false);
  const [message, setMessage] = useState('');
  const [maxLength, setMaxLength] = useState(300);
  const [errors, setErrors] = useState({});
  const [success, setSuccess] = useState({});
  const [ratings, setRatings] = useState({});
  const [demoModalOpen, setDemoModalOpen] = useState(false);
  const avgRating = product?.rating;

  const escFunction = (event) => {
    if (event.keyCode === 27) {
      setModalOpen(false);
      const body = document.querySelector('body');
      body.style.overflow = 'auto';
    }
  };

  const handleClickOutside = () => {
    setModalOpen(false);
    const body = document.querySelector('body');
    body.style.overflow = 'auto';
  };

  const handleMessage = (e) => {
    setErrors(false);
    setMessage(e.target.value);
  };

  useEffect(() => {
    setMaxLength(props.options.reviews_length);
    window.addEventListener('keydown', escFunction, false);
    return () => {
      window.removeEventListener('keydown', escFunction, false);
    };
  });

  const submit = (e) => {
    e.preventDefault();
    if (lc_data.is_demo) {
      setDemoModalOpen(true);
      return false;
    }
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const url = `${lc_data.submit_review}`;
    const data = {};
    if (Object.keys(ratings).length < product.premium_profile.testimonial.options.length) {
      setErrors({ general: { rating: lc_data.jst[407] } });
      return false;
    }
    if (isEmpty(message)) {
      setErrors({ message: lc_data.jst[408] });
      return false;
    }
    map(ratings, (value, name) => data[`rating-${name}`] = value);
    data.message = message;
    data.author = currentUser;
    data.post = product.premium_profile.ID;
    data._nonce = lc_data.nonce;
    axios({
      credentials: 'same-origin',
      method: 'post',
      url,
      data,
      headers,
    }).then(data => {
      if (data.data.success) {
        setSuccess(data.data.message);
      }
      if (data.data.error) {
        setErrors({ general: { reviewed: data.data.message } });
      }
    });
  };

  const loginDemo = async () => {
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

  return (
    <div className={`business--testimonial mt-10 w-full ${props.type !== 'default' ? 'xl:w-1/2' : 'xl:w-2/3'}`}>
      {props.options.reviews && product.premium_profile.testimonial.options && !isEmpty(product.premium_profile.testimonial.options) &&
      <Fragment>
        {lc_data.logged_in &&
        <button
          type="button"
          className="flex-center py-8 px-30 w-full border border-yellow-700 rounded font-bold text-yellow-700 whitespace-no-wrap"
          onClick={() => setModalOpen(true)}
        >
          <ReactSVG
            src={`${lc_data.dir}dist/${StarIcon}`}
            className="relative mr-12 w-16 h-16 fill-yellow-700"
          />
          {lc_data.jst[409]}
        </button>
        }
        {!lc_data.logged_in &&
        <button
          type="button"
          className="flex-center py-8 px-30 w-full border border-yellow-700 rounded font-bold text-yellow-700 whitespace-no-wrap"
          onClick={() => setModalOpen(true)}
        >
          <ReactSVG
            src={`${lc_data.dir}dist/${StarIcon}`}
            className="relative mr-12 w-16 h-16 fill-yellow-700"
          />
          {lc_data.jst[409]}
        </button>
        }
      </Fragment>
      }

      {props.options.reviews !== '0' && modalOpen &&
      <div
        key={1}
        className="modal--wrapper modal-send-message fixed top-0 left-0 flex justify-center w-full h-full overflow-y-auto"
      >
        <Modal
          product={product}
          open={modalOpen}
          title={lc_data.jst[406]}
          handleClickOutside={handleClickOutside}
          closeModal={() => setModalOpen(false)}
        >
          {!lc_data.logged_in && !lc_data.is_demo &&
          <div className="modal--product-info flex flex-wrap flex-col p-30 pb-40">
            <div>
              <h6 className="font-bold text-grey-900">{lc_data.jst[624]}</h6>
              <p className="text-grey-700">{lc_data.jst[625]}</p>
            </div>
            <div className="inline-flex mt-10">
              <a
                className="btn bg-blue-700 rounded text-white"
                href={lc_data.page_login}
                target="_blank"
              >
                {lc_data.jst[419]}
              </a>
            </div>
          </div>
          }
          {!lc_data.logged_in && lc_data.is_demo &&
          <div className="modal--product-info flex flex-wrap flex-col p-30 pb-40">
            <div>
              <h6 className="font-bold text-grey-900">{lc_data.jst[602]}</h6>
              <p className="text-grey-700">{lc_data.jst[603]}</p>
            </div>
            <div className="mt-10">
              <button
                className="btn bg-blue-700 rounded text-white"
                onClick={() => loginDemo()}
              >
                {lc_data.jst[419]}
              </button>
            </div>
            <div className="mt-20 text-sm text-red-600">
              <strong className="mr-4">{lc_data.jst[604]}</strong>
              <span>{lc_data.jst[605]}</span>
            </div>
          </div>
          }
          {lc_data.logged_in && product.author == lc_data.current_user_id &&
          <div className="profile--header flex bg:flex-wrap lg:flex-no-wrap w-full">
            <div className="modal--product-info flex flex-wrap p-30 pb-40">
              <h5 className="mb-6 font-bold text-grey-700">{lc_data.jst[617]}</h5>
            </div>
          </div>
          }
          {lc_data.logged_in && product.author != lc_data.current_user_id &&
          <div className="profile--header flex bg:flex-wrap lg:flex-no-wrap w-full">
            <div className="modal--product-info flex flex-wrap p-30 pb-40 w-full">

              {product?.premium_profile?.thumbnail &&
              <figure
                className="profile--thumbnail flex-center mr-20 p-10 bg-white border-grey-100 rounded-2xl overflow-hidden bg:mb-20 lg:mb-0"
                style={{ width: '84px', height: '74px', borderWidth: '6px' }}
              >
                <img src={product.premium_profile.thumbnail} alt={product.premium_profile.title}/>
              </figure>
              }

              <div className="profile--meta flex flex-col w-5/6 bg:mb-10 bg:w-full lg:mb-0 lg:w-2/3">
                <h5 className="mb-6 font-bold">{product.premium_profile.title}</h5>
                <div className="lisfinity-product--info-wrapper flex items-center">
                  <div className="lisfinity-product--info flex-center mr-22">
                      <span className="flex-center min-w-32 h-32 rounded-full bg-yellow-300">
                      <ReactSVG
                        src={`${lc_data.dir}dist/${StarIcon}`}
                        className="w-14 h-14 fill-product-star-icon"
                      />
                      </span>
                    <span className="ml-6 text-sm text-grey-700">{avgRating}</span>
                  </div>

                  {
                    <div className="lisfinity-product--info flex-center">
                        <span className="flex-center min-w-32 h-32 rounded-full bg-cyan-300">
                          <ReactSVG
                            src={`${lc_data.dir}dist/${mapMarkerIcon}`}
                            className="w-14 h-14 fill-product-place-icon"
                          />
                        </span>
                      <span className="ml-6 text-sm text-grey-700">{product.premium_profile.location_formatted}</span>
                    </div>
                  }
                </div>
              </div>

            </div>
          </div>
          }
          {lc_data.logged_in && product.author != lc_data.current_user_id && props.options.reviews !== '0' &&
          <SendTestimonial
            product={product}
            currentUser={currentUser}
            handleMessage={handleMessage}
            handleSubmit={submit}
            message={message}
            ratings={(values) => {
              setErrors({});
              setRatings(values);
            }}
            maxLength={maxLength}
            errors={errors}
            success={success}
          />}
        </Modal>
      </div>}

      <ModalDemo
        isLogged={lc_data.logged_in}
        open={demoModalOpen}
        closeModal={() => setDemoModalOpen(false)}
        title={lc_data.jst[606]}
      >
        <div className="font-semibold text-lg text-grey-700" dangerouslySetInnerHTML={{
          __html: lc_data.jst[607],
        }}
        />
      </ModalDemo>
    </div>
  );
};

export default BusinessTestimonial;
