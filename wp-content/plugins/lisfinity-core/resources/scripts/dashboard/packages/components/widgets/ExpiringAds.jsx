/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import ReactSVG from 'react-svg';
import icon from '../../../../../images/icons/spinner-arrow.svg';
import { map, orderBy } from 'lodash';
import { Scrollbars } from 'react-custom-scrollbars';
import Modal from '../../../../theme/packages/components/modal/Modal';
import { useEffect, useState } from '@wordpress/element';
import axios from 'axios';
import { toast } from 'react-toastify';
import * as actions from '../../store/actions';
import { NavLink } from 'react-router-dom';
import loader from '../../../../../images/icons/loader-rings-white.svg';
import loaderBlue from '../../../../../images/icons/loader-rings-blue.svg';
import { Fragment } from 'react';
import { sprintf } from '@wordpress/i18n';
import ModalDemo from '../../../../theme/packages/components/modal/ModalDemo';
import he from 'he';

const ExpiringAds = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { business, menuOpen } = data;
  const [modalOpen, setModalOpen] = useState(false);
  const [selectActive, setSelectActive] = useState(false);
  const [adRenew, setAdRenew] = useState(false);
  const [adIndex, setAdIndex] = useState(false);
  const [renewing, setRenewing] = useState(false);
  const [businessExpiring, setBusinessExpiring] = useState(false);
  const [demoModalOpen, setDemoModalOpen] = useState(false);

  const closeModal = (e) => {
    setModalOpen(false);
  };

  const escFunction = (event) => {
    if (event.keyCode === 27) {
      setModalOpen(false);
      const body = document.querySelector('body');
      body.style.overflow = 'auto';
    }
  };

  const handleClickOutside = e => {
    setModalOpen(false);
    const body = document.querySelector('body');
    body.style.overflow = 'auto';
  };

  const renewAdModal = (ad, index) => {
    setModalOpen(true);
    setAdRenew(ad);
    setAdIndex(index);
  };

  const renewAd = async (e, fromModal = true, ad, id) => {
    e.preventDefault();
    if (lc_data.is_demo) {
      setDemoModalOpen(true);
      return false;
    }

    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };

    let index;
    let formData;
    if (fromModal) {
      index = adIndex;
      formData = new FormData(e.target);
      if (adRenew && adRenew.id > 0) {
        formData.append('ad', adRenew.id);
      }
      setRenewing(adRenew.id);
    }
    if (!fromModal) {
      index = id;
      formData = {
        ad: ad.id,
      };
      setRenewing(ad.id);
    }
    await axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url: lc_data.purchase_ad_renewal,
      data: formData,
    }).then(response => {
      if (response.data.success) {
        setModalOpen(false);
        business.expiring[index].expires_human = response.data.expires_human;
        business.expiring[index].remaining = response.data.remaining;
        business.expiring[index].percentage = response.data.percentage;
        business.ads[index].expires_human = response.data.expires_human;
        business.ads[index].remaining = response.data.remaining;
        business.ads[index].percentage = response.data.percentage;
        if (response.data.package) {
          map(business.active_packages, p => {
            if (parseInt(p.id, 10) === response.data.package) {
              p.products_count = parseInt(p.products_count, 10) + 1;
            }
          });
        }
        dispatch(actions.setBusiness(business));
        toast(response.data.message, {
          position: toast.POSITION.BOTTOM_RIGHT,
          autoClose: 3000,
        });
      }
      if (response.data.error) {
        toast.error(response.data.message, {
          position: toast.POSITION.BOTTOM_RIGHT,
          autoClose: 3000,
        });
      }
      setRenewing(false);
    });
  };

  useEffect(() => {
    document.addEventListener('keydown', escFunction, false);

    if (business && Object.keys(business.expiring).length > 0) {
      const expiring = orderBy(business.expiring, ['expires'], ['asc']).slice(0, 4);
      setBusinessExpiring(expiring);
    }

    return () => document.removeEventListener('keydown', escFunction, false);
  }, []);

  return (
    <div
      className="dashboard-widget--expiring-products flex flex-col mb-20 p-20 bg-white rounded shadow-theme w-full sm:w-1/2 xl:w-full">

      <div className="dashboard-widget--header flex justify-between items-center mb-20 px-10">
        <div className="flex-center">
          <ReactSVG
            src={`${lc_data.dir}dist/${icon}`}
            className="mr-8 w-16 h-16 fill-black"
          />
          <h6 className="font-semibold text-lg">{lc_data.jst[315]}</h6>
        </div>
        <div className="flex-center p-2 w-20 h-20 bg-grey-100 rounded">{Object.keys(businessExpiring).length}</div>
      </div>

      {!businessExpiring && lc_data.jst[654]}
      {businessExpiring &&
      <div className="-mb-6">
        <Scrollbars style={{ zIndex: 20 }} autoHide={false} autoHeight
                    autoHeightMin={business.expiring.length >= 2 ? 240 : 102}
                    renderTrackHorizontal={props => <div {...props} className="hidden"/>}
                    renderThumbHorizontal={props => <div {...props} className="hidden"/>}
                    renderTrackVertical={props => <div {...props}
                                                       className="track--vertical top-0 right-0 bottom-0 w-2"/>}
                    renderThumbVertical={props => <div {...props}
                                                       className="thumb--vertical bg-grey-600 rounded opacity-25"/>}>
          {map(businessExpiring, (ad, id) => {
            return (
              <article key={id} className="flex mb-6 p-16 bg-grey-100 rounded">
                {ad.thumbnail &&
                <NavLink to={`${lc_data.site_url}${lc_data.myaccount}ad/${ad.id}`}>
                  <figure className="relative flex mr-10 rounded overflow-hidden"
                          style={{ width: '120px', minHeight: '75px' }}>
                    <img
                      src={ad.thumbnail}
                      alt={ad.post_title}
                      className="absolute top-0 left-0 w-full h-full object-cover"
                    />
                  </figure>
                </NavLink>
                }
                <div className="flex flex-col w-full">
                  <h6 className="text-base"><NavLink
                    to={`${lc_data.site_url}${lc_data.myaccount}ad/${ad.id}`}>{he.decode(ad.title)}</NavLink></h6>

                  <div className="flex justify-between mt-6">

                    <div className="font-light text-grey-500">
                      {lc_data.jst[171]}
                      <span className="ml-4 font-semibold text-red-500">{ad.expires_human}</span>
                    </div>

                    {business.options.enable_packages && (!renewing || renewing !== ad.id) &&
                    <button type="button" className="flex-center font-semibold text-base text-blue-700"
                            onClick={() => renewAdModal(ad, id)}
                    >
                      {lc_data.jst[167]}
                      <ReactSVG
                        src={`${lc_data.dir}dist/${icon}`}
                        className="ml-4 w-14 h-14 fill-icon-contact"
                      />
                    </button>
                    }
                    {!business.options.enable_packages && (!renewing || renewing !== ad.id) &&
                    <button type="button" className="flex-center font-semibold text-base text-blue-700"
                            onClick={(e) => renewAd(e, false, ad, id)}
                    >
                      {lc_data.jst[167]}
                      <ReactSVG
                        src={`${lc_data.dir}dist/${icon}`}
                        className="ml-4 w-14 h-14 fill-icon-contact"
                      />
                    </button>
                    }
                    {renewing && renewing === ad.id &&
                    <ReactSVG
                      src={`${lc_data.dir}dist/${loaderBlue}`}
                      className="absolute right-20"
                      style={{ zoom: 0.6 }}
                    />
                    }
                  </div>

                </div>
              </article>
            );
          })}
        </Scrollbars>
      </div>
      }

      {modalOpen &&
      <div
        key={1}
        className="modal--wrapper modal-send-message fixed top-0 left-0 flex justify-center w-full h-full overflow-y-auto"
      >
        <Modal
          isLogged={lc_data.logged_in}
          open={modalOpen}
          title={lc_data.jst[575]}
          handleClickOutside={handleClickOutside}
          closeModal={closeModal}
        >
          <form className="" onSubmit={(e) => renewAd(e)}>
            <div className="flex flex-col p-30">
              {business.options.enable_packages && business.active_packages.length > 0 &&
              <div className="flex flex-col">
                <label htmlFor="package" className="package ml-10 mb-4">{lc_data.jst[318]}</label>
                <select name="package" id="package"
                        className={`lisfinity-field flex p-10 h-44 w-full border border-grey-300 rounded font-semibold cursor-pointer outline-none ${selectActive ? 'bg-transparent' : 'bg-grey-100'}`}
                >
                  {map(business.active_packages, p_package => (
                    <option key={p_package.id}
                            value={p_package.id}>{`${p_package.title} (${sprintf(lc_data.jst[596], p_package.remaining)})`}</option>
                  ))}
                </select>
                <span className="description mt-2 ml-10 text-sm text-grey-700">{lc_data.jst[577]}</span>
              </div>
              }
              {business.options.enable_packages && business.active_packages.length > 0 &&
              <div className="flex">
                <button
                  type="submit"
                  className="flex items-center mt-16 py-8 px-24 bg-blue-700 hover:bg-blue-800 rounded font-bold text-white"
                  disabled={renewing}
                >
                  <Fragment>
                    {!renewing && lc_data.jst[575]}
                    {renewing &&
                    <ReactSVG
                      src={`${lc_data.dir}dist/${loader}`}
                      className="relative h-44"
                      style={{ zoom: 0.5 }}
                    />
                    }
                  </Fragment>
                </button>
              </div>
              }
              {business.options.enable_packages && business.active_packages.length === 0 &&
              <div className="flex flex-col">
                <p>{lc_data.jst[578]}</p>
                <div>
                  <NavLink
                    to={`${lc_data.site_url}${lc_data.myaccount}packages`}
                    className="inline-flex items-center mt-16 py-8 px-24 bg-green-700 hover:bg-green-800 rounded font-bold text-white"
                  >
                    {lc_data.jst[576]}
                  </NavLink>
                </div>
              </div>
              }
            </div>
          </form>
        </Modal>
      </div>
      }

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

export default ExpiringAds;
