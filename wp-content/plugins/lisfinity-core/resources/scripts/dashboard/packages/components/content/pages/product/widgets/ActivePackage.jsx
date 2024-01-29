/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import ReactSVG from 'react-svg';
import { __, _n, sprintf } from '@wordpress/i18n';
import { map, isEmpty } from 'lodash';
import icon from '../../../../../../../../images/icons/package.svg';
import ReloadIcon from '../../../../../../../../images/icons/reload.svg';
import CalendarIcon from '../../../../../../../../images/icons/calendar.svg';
import Modal from '../../../../../../../theme/packages/components/modal/Modal';
import { Fragment, useCallback, useEffect, useState } from '@wordpress/element';
import cx from 'classnames';

const ActivePackage = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { business, menuOpen, product, paymentPackage  } = data;
  const { productId } = props;
  const [modalOpen, setModalOpen] = useState(false);
  const [effectivePackage, setEffectivePackage] = useState(product?.package || paymentPackage?.payment_package || {});

  // useEffect to update effectivePackage whenever product or paymentPackage changes
  useEffect(() => {
    if (product?.package) {
      setEffectivePackage(product.package);
    } else if (paymentPackage?.payment_package) {
      setEffectivePackage(paymentPackage.payment_package);
    }
  }, [product, paymentPackage]);

  const closeModal = (e) => {
    setModalOpen(false);
  };

  const escFunction = useCallback((event) => {
    if (event.keyCode === 27) {
      setModalOpen(false);
      const body = document.querySelector('body');
      body.style.overflow = 'auto';
    }
  });

  const handleClickOutside = e => {
    setModalOpen(false);
    const body = document.querySelector('body');
    body.style.overflow = 'auto';
  };

  useEffect(() => {
    document.addEventListener('keydown', escFunction, false);

    return () => document.removeEventListener('keydown', escFunction, false);
  }, [escFunction]);

  const timelineClass = cx({
    'bg-green-700': product.percentage < 35,
    'bg-yellow-700': product.percentage >= 35 && product.percentage < 75,
    'bg-red-700': product.percentage >= 75,
  });

  return (
    <div
      className="dashboard-widget--active-package flex flex-col mb-20 p-20 bg-white rounded shadow-theme w-full sm:w-1/2 xl:w-full">

      {paymentPackage?.product?.is_expired &&
          <div className={"is-expired-sidebar"}>
            <article className="flex mb-10 p-30 bg-yellow-100 rounded">
            <h6>This package is expired. To renew it, resubmit the listing.</h6>
            </article>
          </div>}

      {!paymentPackage?.product?.is_expired && (product?.title || paymentPackage?.payment_package?.title) &&
          <div>
          <div className="dashboard-widget--header flex justify-between items-center mb-20 px-10">
            <div className="flex-center">
              <ReactSVG
                  src={`${lc_data.dir}dist/${icon}`}
                  className="mr-8 w-16 h-16 fill-black"
              />
              <h6 className="font-semibold text-lg">{lc_data.jst[209]}</h6>
            </div>
          </div>

        <article className="flex mb-10 p-30 bg-yellow-100 rounded">
        <div className="flex flex-col w-full">

        <div className="promotion--top flex justify-between mb-10">

        <div className="flex-center">
        <ReactSVG src={`${lc_data.dir}dist/${CalendarIcon}`}
        className="relative mr-8 w-16 h-16 fill-grey-700" style={{ top: '-2px' }}/>
        <div className="font-light text-grey-900">
      {lc_data.jst[210]}
        <span className="ml-6 font-semibold text-grey-1000">
      {product.submitted_human}
        </span>
        </div>
        </div>

        <button
        type="button"
        className="set-modal-open flex-center font-semibold text-base text-blue-700"
        onClick={() => setModalOpen(true)}
        >
      {lc_data.jst[211]}
        <ReactSVG
        src={`${lc_data.dir}dist/${ReloadIcon}`}
        className="ml-4 w-14 h-14 fill-icon-contact"
        />
        </button>

      {modalOpen && effectivePackage &&
        <div
        key={1}
        className="modal--wrapper modal-send-message fixed top-0 left-0 flex justify-center w-full h-full overflow-y-auto"
        >
        <Modal
        isLogged={lc_data.logged_in}
        open={modalOpen}
        title={lc_data.jst[593]}
        handleClickOutside={handleClickOutside}
        closeModal={closeModal}
        >
        <div className="p-40">
        <div className="packages-active--wrapper">

        <div
        className="packages-active--header items-center justify-between mb-20 py-20 px-20 bg-white">
        <div className="header--action w-2/5 font-semibold text-lg text-grey-900">
      {lc_data.jst[122]}
        </div>
        <div className="header--action w-1/5 font-semibold text-lg text-grey-900">
      {lc_data.jst[24]}
        </div>
        <div className="header--action w-1/5 font-semibold text-lg text-grey-900">
      {lc_data.jst[127]}
        </div>
        <div className="header--action w-1/5 font-semibold text-lg text-grey-900">
      {lc_data.jst[123]}
        </div>
        </div>

        <div className="packages-active--content">
        <article key={effectivePackage.id}
        className="flex flex-wrap items-center px-20 bg-white rounded">

        <div className="flex flex-col w-full md:w-2/5 package--width__title">
        <h6 className="active-package-title font-semibold text-3xl">{effectivePackage.title}</h6>
        <div className="timeline relative mt-16 w-2/3 h-10 bg-grey-300 rounded overflow-hidden">
        <div
        className={`timeline--line absolute top-0 left-0 h-10 ${timelineClass}`}
        style={{ width: `${effectivePackage.percentage}%` }}
        ></div>
        </div>
        <span
        className="mt-20 font-semibold text-sm">{sprintf(_n(lc_data.jst[125], lc_data.jst[126], effectivePackage.remaining, 'lisfinity-core'), effectivePackage.remaining)}</span>
        </div>

        <div className="mt-20 w-full md:mt-0 md:w-1/5">
        <span className="text-grey-500">{effectivePackage.products_count}</span>
        <span className="mx-2 text-grey-900">/</span>
        <span className="font-semibold text-grey-900">{effectivePackage.products_limit}</span>
        <span className="package--mobile-info ml-2 text-grey-500">{lc_data.jst[24]}</span>
        </div>

        <div className="mt-20 w-full md:mt-0 md:w-1/5">
      {effectivePackage.products_duration}
        <span className="package--mobile-info ml-2 text-grey-500">{lc_data.jst[127]}</span>
        </div>

        <div className="flex flex-col mt-20 w-full md:mt-0 md:w-1/5">
        <Fragment>
      {product && effectivePackage && effectivePackage.promotion && !isEmpty(effectivePackage.promotion.addon) && map(effectivePackage.promotion.addon, (addon, i) =>
        <div key={i} className="package--promotion mb-12">
        <span>{addon.value}</span>
        <span className="ml-4">{lc_data.jst[128]}</span>
        <span className="ml-4 text-grey-700">{addon.title}</span>
        </div>
        )}
      {product && effectivePackage && effectivePackage.promotion && isEmpty(effectivePackage.promotion.addon) &&
        <div>
        <span className="text-grey-700">{lc_data.jst[129]}</span>
        </div>
      }
        </Fragment>
        </div>

        </article>
                    </div>
                  </div>
                </div>
              </Modal>
            </div>
            }

          </div>

          <div>
            <h5 className="mb-4 font-bold" dangerouslySetInnerHTML={{
              __html: effectivePackage?.title || '',
            }}/>
          </div>

        </div>
      </article>

          </div>}

    </div>
  );
};

export default ActivePackage;
