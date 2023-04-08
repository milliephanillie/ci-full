/* global lc_data, React */

import { useDispatch, useSelector } from 'react-redux';
import { Fragment, useEffect, useState } from '@wordpress/element';
import axios from 'axios';
import ModalDemo from '../../../../../../theme/packages/components/modal/ModalDemo';
import ReactSVG from 'react-svg';
import LoaderIcon from '../../../../../../../images/icons/loader-rings-white.svg';
import SaveIcon from '../../../../../../../images/icons/save.svg';
import StripeIcon from '../../../../../../../images/icons/stripe.svg';
import { toast } from 'react-toastify';
import queryString from 'query-string';
import { isEmpty } from 'lodash';

const EarningsSettings = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { business, menuOpen } = data;
  const premium = business.business.premium;
  const product = business.business.premium_product;
  const [modalOpen, setModalOpen] = useState(false);
  const [fetching, setFetching] = useState(false);
  const [gateway, setGateway] = useState('');
  const [paypal, setPaypal] = useState('');
  const [stripe, setStripe] = useState('');
  const [bank, setBank] = useState({});
  const [params, setParams] = useState(queryString.parse(location.search));

  useEffect(() => {
    if (business.business.vendor.gateway) {
      setGateway(business.business.vendor.gateway);
    }
    if (business.business.vendor.paypal) {
      setPaypal(business.business.vendor.paypal);
    }
    if (business.business.vendor.bank) {
      setBank(business.business.vendor.bank);
    }
    if (params.code) {
      setStripe(params.code);
    }
  }, []);

  const handleGateway = async () => {
    if (lc_data.is_demo) {
      setModalOpen(true);
      return false;
    }
    setFetching(true);

    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };

    const formData = {
      business_id: business.business.ID,
      gateway,
    };
    if ('paypal' === gateway) {
      formData.paypal = paypal;
    }

    if ('bank' === gateway) {
      formData.bank = bank;
    }

    await axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url: lc_data.payouts_settings,
      data: formData,
    }).then(response => {
      if (response.data.error) {
        toast.error(response.data.message, {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
        });
      }
      if (response.data.success) {
        toast(response.data.message, {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
        });
      }
      setFetching(false);
    });
  };

  const handleStripeAccount = async () => {
    setFetching(true);
    if (lc_data.is_demo) {
      setModalOpen(true);
      return false;
    }

    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };

    await axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url: lc_data.stripe_create_account,
      data: {
        stripe,
      },
    }).then(response => {
      if (response.data.error) {
        setStripe('');
        setParams({});
        business.business.vendor.stripe_connected = false;
        toast.error(response.data.message, {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
        });
      }
      if (response.data.success) {
        setGateway('stripe');
        business.business.vendor.stripe_connected = true;
        toast(response.data.message, {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
        });
      }
      setFetching(false);
    });
  };

  return (
    <div className="payouts">

      <div className="payouts--settings p-20 bg-white rounded shadow-theme animate-up">
        <div className="w-full pt-10 px-20">
          <h3 className="mb-20 font-bold">{lc_data.jst[674]}</h3>
        </div>

        <div className="payouts--settings-content mt-20 px-20 pb-20">

          <div className="payouts--gateways">

            {data.options.stripe_connect_enabled &&
              <div className="stripe mb-20 pb-20 border-b border-grey-100">
                <h5 className="mb-4 font-semibold text-xl">{lc_data.jst[671]}</h5>
                {!business.business.vendor.stripe_connected && !params.code &&
                  <div className="form-field">
                    <label htmlFor="stripe" className="flex mb-4">
                      {lc_data.jst[675]}
                      <span className="relative text-sm text-red-700" style={{ top: '-2px', left: '1px' }}>*</span>
                    </label>
                    <a
                      href={!lc_data.is_demo ? `https://connect.stripe.com/express/oauth/authorize?response_type=code&client_id=${data.options.stripe_connect_ca}&scope=read_write&redirect_uri=${data.options.stripe_connect_redirect_uri}` : 'javascript:'}
                      className="inline-flex items-center py-12 px-24 bg-blue-700 hover:bg-blue-800 rounded font-bold text-white e:default"
                      onClick={e => handleStripeAccount(e)}
                      style={{
                        backgroundColor: '#6772e5',
                      }}
                    >
                      <ReactSVG
                        src={`${lc_data.dir}dist/${StripeIcon}`}
                        className="relative mr-8 w-14 h-14 fill-white pointer-events-none"
                        style={{
                          position: 'relative',
                          top: -3,
                        }}
                      />
                      {lc_data.jst[676]}
                    </a>
                    <small className="description flex mt-6 pl-10 text-sm text-grey-500">{lc_data.jst[677]}</small>
                  </div>
                }
                {!business.business.vendor.stripe_connected && params.code &&
                  <div className="form-field">
                    <label htmlFor="stripe" className="flex mb-4">
                      {lc_data.jst[678]}
                      <span className="relative text-sm text-red-700" style={{ top: '-2px', left: '1px' }}>*</span>
                    </label>
                    <button
                      type="button"
                      className="flex items-center py-12 px-24 bg-blue-700 hover:bg-blue-800 rounded font-bold text-white e:default"
                      disabled={fetching}
                      onClick={e => handleStripeAccount(e)}
                      style={{
                        backgroundColor: '#6772e5',
                      }}
                    >
                      <Fragment>
                        {fetching &&
                          <ReactSVG
                            src={`${lc_data.dir}dist/${LoaderIcon}`}
                            className="relative mr-12 w-14 h-14"
                            style={{
                              zoom: 0.6,
                              left: '-23px',
                              top: '-15px',
                            }}
                          />
                        }
                        {!fetching &&
                          <ReactSVG
                            src={`${lc_data.dir}dist/${StripeIcon}`}
                            className="relative mr-8 w-14 h-14 fill-white pointer-events-none"
                            style={{
                              position: 'relative',
                              top: -3,
                            }}
                          />
                        }
                      </Fragment>
                      {lc_data.jst[678]}
                    </button>
                    <small className="description flex mt-6 pl-10 text-sm text-grey-500">{lc_data.jst[677]}</small>
                  </div>
                }
                {business.business.vendor.stripe_connected &&
                  <div className="inline-block py-10 px-20 rounded font-semibold text-white"
                       style={{
                         backgroundColor: '#6772e5',
                       }}
                  >{lc_data.jst[679]}</div>
                }
              </div>
            }

            <div className="form-field mb-20">
              <label htmlFor="paymentGateway" className="flex mb-4">
                {lc_data.jst[680]}
                <span className="relative text-sm text-red-700" style={{ top: '-2px', left: '1px' }}>*</span>
              </label>
              <select name="gateway" id="paymentGateway"
                      className="flex lisfinity-field py-10 px-20 w-full h-44 xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
                      value={gateway}
                      onChange={e => setGateway(e.target.value)}>
                <option value="">{lc_data.jst[44]}</option>
                <option value="paypal">{lc_data.jst[670]}</option>
                <option value="bank">{lc_data.jst[786]}</option>
              </select>
              <small className="description mt-6 pl-10 text-sm text-grey-500">{lc_data.jst[672]}</small>
            </div>

            {'paypal' === gateway &&
              <div className="paypal">
                <h5 className="mb-4 font-semibold text-xl">{lc_data.jst[681]}</h5>
                <div className="form-field">
                  <label htmlFor="paypal" className="flex mb-4">
                    {lc_data.jst[673]}
                    <span className="relative text-sm text-red-700" style={{ top: '-2px', left: '1px' }}>*</span>
                  </label>
                  <input type="email" id="paypal"
                         defaultValue={paypal}
                         className="flex py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
                         onChange={e => setPaypal(e.target.value)}/>
                  <small className="description mt-6 pl-10 text-sm text-grey-500">{lc_data.jst[682]}</small>
                </div>
              </div>
            }

            {'bank' === gateway &&
              <div className="paypal">
                <h5 className="mb-4 font-semibold text-xl">{lc_data.jst[787]}</h5>

                <div className="form-field">
                  <label htmlFor="account_name" className="flex mb-4">
                    {lc_data.jst[788]}
                    <span className="relative text-sm text-red-700" style={{ top: '-2px', left: '1px' }}>*</span>
                  </label>
                  <input type="account_name" id="account_name"
                         defaultValue={bank?.account_name || ''}
                         className="flex py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
                         onChange={e => {
                           let details = { ...bank };
                           details.account_name = e.target.value;
                           setBank(details);
                         }}/>
                </div>

                <div className="form-field flex flex-col mt-10">
                  <label htmlFor="account_number" className="flex mb-4">
                    {lc_data.jst[789]}
                    <span className="relative text-sm text-red-700" style={{ top: '-2px', left: '1px' }}>*</span>
                  </label>
                  <input type="account_number" id="account_number"
                         defaultValue={bank?.account_number || ''}
                         className="flex py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
                         onChange={e => {
                           let details = { ...bank };
                           details.account_number = e.target.value;
                           setBank(details);
                         }}/>
                </div>

                <div className="form-field flex flex-col mt-10">
                  <label htmlFor="bank_name" className="flex mb-4">
                    {lc_data.jst[790]}
                    <span className="relative text-sm text-red-700" style={{ top: '-2px', left: '1px' }}>*</span>
                  </label>
                  <input type="bank_name" id="bank_name"
                         defaultValue={bank?.bank_name || ''}
                         className="flex py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
                         onChange={e => {
                           let details = { ...bank };
                           details.bank_name = e.target.value;
                           setBank(details);
                         }}/>
                </div>

                <div className="form-field flex flex-col mt-10">
                  <label htmlFor="routing_number" className="flex mb-4">
                    {lc_data.jst[791]}
                    <span className="relative text-sm text-red-700" style={{ top: '-2px', left: '1px' }}>*</span>
                  </label>
                  <input type="routing_number" id="routing_number"
                         defaultValue={bank?.routing_number || ''}
                         className="flex py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
                         onChange={e => {
                           let details = { ...bank };
                           details.routing_number = e.target.value;
                           setBank(details);
                         }}/>
                </div>

                <div className="form-field flex flex-col mt-10">
                  <label htmlFor="iban" className="flex mb-4">
                    {lc_data.jst[792]}
                    <span className="relative text-sm text-red-700" style={{ top: '-2px', left: '1px' }}>*</span>
                  </label>
                  <input type="iban" id="iban"
                         defaultValue={bank?.iban || ''}
                         className="flex py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
                         onChange={e => {
                           let details = { ...bank };
                           details.iban = e.target.value;
                           setBank(details);
                         }}/>
                </div>

                <div className="form-field flex flex-col mt-10">
                  <label htmlFor="swift" className="flex mb-4">
                    {lc_data.jst[793]}
                    <span className="relative text-sm text-red-700" style={{ top: '-2px', left: '1px' }}>*</span>
                  </label>
                  <input type="swift" id="swift"
                         defaultValue={bank?.swift || ''}
                         className="flex py-10 px-20 w-full xxl:w-90% bg-grey-100 border border-grey-200 rounded font-bold"
                         onChange={e => {
                           let details = { ...bank };
                           details.swift = e.target.value;
                           setBank(details);
                         }}/>
                </div>

              </div>
            }

            <button
              type="submit"
              className="flex items-center mt-20 py-12 px-24 bg-blue-700 hover:bg-blue-800 rounded font-bold text-white e:default"
              disabled={fetching}
              onClick={e => {
                handleGateway();
              }}
            >
              <Fragment>
                {fetching &&
                  <ReactSVG
                    src={`${lc_data.dir}dist/${LoaderIcon}`}
                    className="relative mr-12 w-14 h-14"
                    style={{
                      zoom: 0.6,
                      left: '-23px',
                      top: '-15px',
                    }}
                  />
                }
                {!fetching &&
                  <ReactSVG
                    src={`${lc_data.dir}dist/${SaveIcon}`}
                    className="relative mr-8 w-14 h-14 fill-white pointer-events-none"
                  />
                }
              </Fragment>
              {lc_data.jst[52]}
            </button>

          </div>
        </div>
      </div>

      {modalOpen &&
        <ModalDemo
          isLogged={lc_data.logged_in}
          open={modalOpen}
          closeModal={() => setModalOpen(false)}
          title={lc_data.jst[606]}
        >
          <div className="font-semibold text-lg text-grey-700" dangerouslySetInnerHTML={{
            __html: lc_data.jst[607],
          }}
          />
        </ModalDemo>
      }

    </div>
  );
};

export default EarningsSettings;
