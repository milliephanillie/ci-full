/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import { Fragment, useState } from '@wordpress/element';
import axios from 'axios';
import CloseIcon from '../../../../../../../images/icons/close.svg';
import { isEmpty, map } from 'lodash';
import ModalDemo from '../../../../../../theme/packages/components/modal/ModalDemo';
import ReactSVG from 'react-svg';
import produce from 'immer';
import LoaderDashboardBookmarks from '../../../../../../theme/packages/components/loaders/LoaderDashboardBookmarks';
import * as actions from '../../../../store/actions';
import LoaderIcon from '../../../../../../../images/icons/loader-rings-white.svg';
import SaveIcon from '../../../../../../../images/icons/save.svg';

const MyEmailSubscriptions = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { loading, business } = data;
  const { subscriptions, subscription_details } = business;
  const [modalOpen, setModalOpen] = useState(false);
  const [fetching, setFetching] = useState(false);
  const [subs, setSubs] = useState(subscriptions);

  const handleChange = (value, name) => {
    const new_subs = { ...subs };
    new_subs[name] = value;
    setSubs(new_subs);
  };

  const saveSubscriptions = async (subscriptions) => {
    if (lc_data.is_demo) {
      setModalOpen(true);
      return false;
    }
    setFetching(true);

    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };

    await axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url: `${lc_data.user_action}/subscribe`,
      data: {
        subscriptions,
      },
    }).then(response => {
      setFetching(false);
      const new_business = { ...business };
      new_business.subscriptions = response.data.subscriptions;
      dispatch(actions.setBusiness(new_business));
    });
  };

  return (
    <section className="dashboard-account flex flex-col -mb-10 p-30 bg-white rounded shadow-theme">
      <div className="subscriptions flex flex-wrap">
        {!loading && isEmpty(subs) && <p className="mb-20">{lc_data.jst[737]}</p>}
        {!loading &&
        <Fragment>
          <h3 className="mb-20 font-bold w-full">{lc_data.jst[736]}</h3>
          {!isEmpty(subs) && map(subs, (value, name) => {
            return (
              <div key={name} className="subscription mb-10 px-10 w-full sm:w-1/2 xl:w-1/4">
                <div>
                  <input
                    type="checkbox"
                    id={name}
                    className="relative top-1"
                    onChange={(e) => handleChange(e.target.checked, name)}
                    defaultChecked={value}
                  />
                  <label htmlFor={name} className="ml-4">{subscription_details[name]?.title}</label>
                </div>
                <small className="inline-block mt-6 leading-2 text-grey-500">{subscription_details[name]?.description}</small>
              </div>
            );
          })}

          <div className="flex w-full">
            <button
              type="submit"
              className="flex items-center mt-20 py-12 px-24 bg-blue-700 hover:bg-blue-800 rounded font-bold text-white e:default"
              onClick={() => saveSubscriptions(subs)}
              disabled={fetching}
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
        </Fragment>
        }

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

      </div>
    </section>
  );
};

export default MyEmailSubscriptions;
