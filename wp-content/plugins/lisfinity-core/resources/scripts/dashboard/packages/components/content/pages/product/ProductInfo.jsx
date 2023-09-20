/* global lc_data, React */
/**
 * Dependencies.
 */
import { NavLink, Redirect } from 'react-router-dom';
import { useDispatch, useSelector } from 'react-redux';
import { useState, useEffect, useRef } from '@wordpress/element';
import { sprintf } from '@wordpress/i18n';
import { map } from 'lodash';
import ReactSVG from 'react-svg';
import ProductPrice from '../products/ProductPrice';
import PencilIcon from '../../../../../../../images/icons/pencil.svg';
import CircleMinusIcon from '../../../../../../../images/icons/circle-minus.svg';
import DollarIcon from '../../../../../../../images/icons/dollar.svg';
import ReloadIcon from '../../../../../../../images/icons/reload.svg';
import cx from 'classnames';
import { Fragment } from 'react';
import axios from 'axios';
import * as actions from '../../../../store/actions';
import { toast } from 'react-toastify';
import Modal from '../../../../../../theme/packages/components/modal/Modal';
import loader from '../../../../../../../images/icons/loader-rings-white.svg';
import loaderDefault from '../../../../../../../images/icons/loader-rings.svg';
import ModalDemo from '../../../../../../theme/packages/components/modal/ModalDemo';
import he from 'he';

const ProductInfo = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { loading, business, product } = data;
  const content = useRef(null);
  const [figureHeight, setFigureHeight] = useState(180);
  const [redirect, setRedirect] = useState(false);
  const [status, setStatus] = useState(product.status);
  const [modalOpen, setModalOpen] = useState(false);
  const [selectActive, setSelectActive] = useState(false);
  const [adRenew, setAdRenew] = useState(false);
  const [adIndex, setAdIndex] = useState(false);
  const [renewing, setRenewing] = useState(false);
  const [demoModalOpen, setDemoModalOpen] = useState(false);

  const timelineClass = cx({
    'bg-green-500': product.percentage < 40,
    'bg-yellow-500': product.percentage >= 40 && product.percentage < 80,
    'bg-red-500': product.percentage >= 80,
  });

  useEffect(() => {
    const figureHeight = () => {
      if (window.innerWidth > 1080) {
        const rect = content.current.getBoundingClientRect();
        setFigureHeight(rect.height);
      } else {
        setFigureHeight(310);
      }
    };

    figureHeight();
    window.addEventListener('load', figureHeight);
    window.addEventListener('resize', figureHeight);

    return () => {
      removeEventListener('load', figureHeight);
      removeEventListener('resize', figureHeight);
    };
  }, []);

  // useEffect to listen for changes to `redirect`
  useEffect(() => {
    console.log(`redirect has changed to: ${redirect}`);
  }, [redirect]);  // The effect depends on `redirect`, so it runs whenever `redirect` changes

  const deleteProduct = (id) => {
    if (!confirm(lc_data.jst[173])) {
      return false;
    }
    if (lc_data.is_demo) {
      setDemoModalOpen(true);
      return false;
    }
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const url = `${lc_data.product_action}/delete`;
    let data = {
      product_id: id,
      redirect: true,
    };
    axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url,
      data,
    }).then(data => {
      if (data.data.success) {
        window.location.href = "/my-account/ads";
      }
    });
  };

  useEffect(() => {
    setRedirect(false);
  }, [redirect]);

  const markAsSold = (id) => {
    if (!confirm(lc_data.jst[174])) {
      return false;
    }
    if (lc_data.is_demo) {
      setDemoModalOpen(true);
      return false;
    }
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const url = `${lc_data.product_action}/mark-sold`;
    let data = {
      product_id: id,
      status: product.status === 'publish' ? 'sold' : 'publish',
    };
    axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url,
      data,
    }).then(data => {
      if (data.data.success) {
        setStatus(data.data.status);
        product.status = data.data.status;
        dispatch(actions.setProduct(product));
        toast(data.data.message, {
          position: toast.POSITION.BOTTOM_RIGHT,
          autoClose: 3000,
        });
      }
    });
  };

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

  const findAdIndex = (ad) => {
    let index = 0;
    map(business.ads, (product, i) => {
      if (product.id === parseInt(ad.id, 10)) {
        index = i;
      }
    });

    return index;
  };

  const renewAdModal = (ad) => {
    setModalOpen(true);
    setAdRenew(ad);
    setAdIndex(findAdIndex(ad));
  };

  const singleRenewAd = async (e) => {
    e.preventDefault();
    setRenewing(true);

    const url = ci_data.ci_rapid_renew

    const headers = {
      'X-WP-Nonce': lc_data.nonce,
      'Content-Type': 'application/json',  // Important when sending JSON data
    };

    const formData = new FormData(e.target);
    const package_id = formData.get("package");

    const data = {
      product_id: product.id,
      package_id: package_id,
      exact_package: true
    };

    await axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url: url,
      data: JSON.stringify(data),  // Convert the data object to a JSON string
    }).then(response => {
      if (response.data.permalink) {
        // Handle success case
        window.location.href = response.data.permalink;
      } else {
        window.location.reload(false);
      }
    }).catch(error => {
      console.error("An error occurred:", error);
    });
  }

  const renewAd = async (e, fromModal = true, ad, id) => {
    e.preventDefault();
    if (lc_data.is_demo) {
      setDemoModalOpen(true);
      return false;
    }
    setRenewing(true);

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
    }
    if (!fromModal) {
      index = id;
      formData = {
        ad: ad.id,
      };
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
        if (business?.expiring[index]) {
          business.expiring[index].expires_human = response.data.expires_human;
          business.expiring[index].remaining = response.data.remaining;
          business.expiring[index].percentage = response.data.percentage;
        }
        business.ads[index].expires_human = response.data.expires_human;
        business.ads[index].remaining = response.data.remaining;
        business.ads[index].percentage = response.data.percentage;
        product.percentage = response.data.percentage;
        product.expires_human = response.data.expires_human;
        if (response.data.package) {
          map(business.available_packages, p => {
            if (parseInt(p.id, 10) === response.data.package) {
              p.products_count = parseInt(p.products_count, 10) + 1;
            }
          });
        }
        dispatch(actions.setBusiness(business));
        dispatch(actions.setProduct(product));
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

    return document.removeEventListener('keydown', escFunction, false);
  }, []);

  const soldIconBgClass = cx({
    'bg-red-200': status === 'sold',
    'bg-green-200': status === 'publish' || status === 'pending',
  });
  const soldIconClass = cx({
    'fill-red-700': status === 'sold',
    'fill-green-700': status === 'publish' || status === 'pending',
  });
  const soldClass = cx({
    'bg-red-200': status === 'sold',
    'bg-green-200': status === 'publish' || status === 'pending',
  });

  return (
    product &&
    <div>

      {status === 'pending' &&
      <div className="product--notice mb-30 p-20 bg-red-500 rounded shadow-theme font-semibold text-white">
        {lc_data.jst[163]}
      </div>
      }

      <section className="flex flex-wrap mb-30 p-30 bg-white rounded shadow-theme overflow-hidden">

        {product.thumbnail &&
        <figure className="flex relative w-full xl:w-3/12 rounded overflow-hidden sm:flex"
                style={{ height: `${figureHeight}px`, marginBottom: figureHeight === 310 ? '30px' : '0' }}>
          <img src={product.thumbnail} alt={product.title}
               className="absolute top-0 left-0 w-full h-full object-cover"/>
        </figure>}

        <div className="flex flex-col p-10 xl:pl-20 w-full xl:w-9/12 overflow-hidden" ref={content}>

          <div className="product-single--top flex flex-wrap justify-between items-center -mr-20">

            <div className="mb-10">
              <ProductPrice style="single" product={product}/>
            </div>

            <div className="product-single--actions flex flex-wrap items-center">

              <NavLink to={`${lc_data.site_url}${lc_data.myaccount}edit/${product.id}`}
                       className="product-single--action flex-center sm:mr-20 mb-10 w-full sm:w-auto bg-blue-200 rounded sm:bg-transparent">
                <div className="flex-center mr-10 w-30 h-30 rounded-full bg-blue-200">
                  <ReactSVG
                    src={`${lc_data.dir}dist/${PencilIcon}`}
                    className="w-16 h-16 fill-blue-700"
                  />
                </div>
                <span className="text-sm text-grey-500">{lc_data.jst[164]}</span>
              </NavLink>

              {status !== 'pending' && data?.options?.product_mark_as_sold &&
              <button onClick={() => markAsSold(product.id)} type="button"
                      className={`product-single--action flex-center sm:mr-20 mb-10 w-full sm:w-auto ${soldClass} rounded sm:bg-transparent`}>
                <div className={`flex-center mr-10 w-30 h-30 rounded-full ${soldIconBgClass}`}>
                  <ReactSVG
                    src={`${lc_data.dir}dist/${DollarIcon}`}
                    className={`w-16 h-16 ${soldIconClass}`}
                  />
                </div>
                {status === 'sold' &&
                <span className="text-sm text-grey-500">{lc_data.jst[165]}</span>}
                {status !== 'sold' &&
                <span className="text-sm text-grey-500">{lc_data.jst[166]}</span>}
              </button>
              }

              <button type="button"
                      className="product-single--action flex-center sm:mr-20 mb-10 w-full sm:w-auto bg-grey-100 rounded sm:bg-transparent"
                      onClick={(e) => business.options.enable_packages ? renewAdModal(product) : renewAd(e, false, product, findAdIndex(product))}
              >

                <div className="flex-center mr-10 w-30 h-30 rounded-full bg-grey-100">
                  {!renewing &&
                  <ReactSVG
                    src={`${lc_data.dir}dist/${ReloadIcon}`}
                    className="w-16 h-16 fill-grey-700"
                  />
                  }
                  {renewing &&
                  <ReactSVG
                    src={`${lc_data.dir}dist/${loaderDefault}`}
                    className="relative"
                    style={{
                      zoom: 0.4,
                    }}
                  />
                  }
                </div>
                {!renewing && <span className="text-sm text-grey-500">{lc_data.jst[167]}</span>}
                {renewing && <span className="text-sm text-grey-500">{lc_data.jst[579]}</span>}
              </button>

              <button onClick={() => deleteProduct(product.id)} type="button"
                      className="product-single--action flex-center sm:mr-20 mb-10 w-full sm:w-auto bg-orange-100 rounded sm:bg-transparent">
                <div className="flex-center mr-10 w-30 h-30 rounded-full bg-orange-100">
                  <ReactSVG
                    src={`${lc_data.dir}dist/${CircleMinusIcon}`}
                    className="w-16 h-16 fill-orange-700"
                  />
                </div>
                <span className="text-sm text-grey-500">{lc_data.jst[168]}</span>
              </button>

            </div>

          </div>

          <div className="product-single--title mt-10">
            <h1 className="font-bold text-3xl sm:text-5xl">{he.decode(product.title)}</h1>
          </div>

          <div className="product-single--meta flex flex-wrap justify-between items-end">

            <div className="flex items-center mt-20 ">
              <figure className="mr-10 w-40 h-40 border-4 border-grey-100 rounded-full overflow-hidden">
                <img src={product.agent.avatar} alt={product.agent.display_name}/>
              </figure>
              <div className="flex flex-col">
                <span className="text-sm text-grey-500">{lc_data.jst[169]}</span>
                <p className="flex font-bold text-grey-1100">
                  {product.agent.first_name && product.agent.last_name
                    ?
                    sprintf('%s %s', product.agent.first_name, product.agent.last_name)
                    :
                    product.agent.display_name
                  }
                </p>
              </div>
            </div>

            <div className="product-single--timeline w-full xl:w-7/16 mt-20">

              <div className="timeline relative w-full h-10 bg-grey-200 rounded overflow-hidden">
                <div
                  className={`timeline--line absolute top-0 left-0 h-10 ${timelineClass}`}
                  style={{ width: `${product.percentage}%` }}
                ></div>
              </div>
              <div className="flex flex-wrap mt-10 text-sm text-grey-500">
                {lc_data.jst[170]}
                <span
                  className="ml-3 font-bold text-13 text-grey-1100">{sprintf(lc_data.jst[153], product.submitted_human)}</span>
                <span className="mx-3">-</span>
                {product.is_active
                  ?
                  <Fragment>
                    {lc_data.jst[171]}
                    <span className="ml-3 font-bold text-13 text-grey-1100">{product.expires_human}</span>
                  </Fragment>
                  :
                  <Fragment>
                    {lc_data.jst[172]}
                    <span className="ml-3 font-bold 13 text-grey-1100">{product.expires_date}</span>
                  </Fragment>
                }
              </div>

            </div>

          </div>

        </div>

        {redirect &&
        <Redirect to={`${lc_data.site_url}${lc_data.myaccount}ads`}/>
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
            <form className="" onSubmit={(e) => singleRenewAd(e)}>
              <div className="flex flex-col p-30">
                {business.options.enable_packages && business.available_packages.length > 0 &&
                <div className="flex flex-col">
                  <label htmlFor="package" className="package ml-10 mb-4">{lc_data.jst[318]}</label>
                  <select name="package" id="package"
                          className={`lisfinity-field flex p-10 h-44 w-full border border-grey-300 rounded font-semibold cursor-pointer outline-none ${selectActive ? 'bg-transparent' : 'bg-grey-100'}`}
                  >
                    {map(business.available_packages, p_package => (
                      <option key={p_package.id} value={p_package.id}>{p_package.title}</option>
                    ))}
                  </select>
                  <span className="description mt-2 ml-10 text-sm text-grey-700">{lc_data.jst[577]}</span>
                </div>
                }
                {business.options.enable_packages && business.available_packages.length > 0 &&
                <div className="flex">
                  <button
                    type="submit"
                    className="flex items-center mt-16 py-8 px-24 bg-blue-700 hover:bg-blue-800 rounded font-bold text-white"
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
                {business.options.enable_packages && business.available_packages.length === 0 &&
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
      </section>
    </div>
  );
};

export default ProductInfo;
