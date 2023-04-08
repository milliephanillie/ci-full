/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import { useState } from '@wordpress/element';
import axios from 'axios';
import CloseIcon from '../../../../../../../images/icons/close.svg';
import { isEmpty, map } from 'lodash';
import ModalDemo from '../../../../../../theme/packages/components/modal/ModalDemo';
import ReactSVG from 'react-svg';
import produce from 'immer';
import LoaderDashboardBookmarks from '../../../../../../theme/packages/components/loaders/LoaderDashboardBookmarks';
import TrashIcon from '../../../../../../../images/icons/trash.svg';
import { setBusiness } from '../../../../store/actions';

const AgentsList = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { loading, business } = data;
  let { agents } = business;
  const [errors, setErrors] = useState(null);
  const [fetching, setFetching] = useState(false);
  const [modalOpen, setModalOpen] = useState(false);

  const deleteAgent = async (id, user_id) => {
    if (!confirm(lc_data.jst[637])) {
      return false;
    }
    if (lc_data.is_demo) {
      setModalOpen(true);
      return false;
    }
    setFetching(true);

    delete business.agents[id];
    dispatch(setBusiness(business));

    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };

    await axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url: `${lc_data.agent_action}/delete`,
      data: {
        id,
        user_id,
      },
    }).then(response => {
      setFetching(false);
    });
  };

  return (
    <section className="dashboard-account flex flex-col p-30 bg-white rounded shadow-theme">
      <div className="agents flex flex-wrap">

        <div className="flex w-full font-semibold">
          <div className="w-1/4">{lc_data.jst[782]}</div>
          <div className="w-1/4">{lc_data.jst[783]}</div>
          <div className="w-1/4">{lc_data.jst[784]}</div>
          <div className="ml-auto">{lc_data.jst[785]}</div>
        </div>
        {isEmpty(business?.agents) && <div className="flex mt-10">No agents found.</div>}
        {!isEmpty(business?.agents) && map(business.agents, (agent, key) => {
          return (
            <div key={key} className="flex justify-between mt-10 w-full">
              <div className="w-1/4">{agent.first_name}</div>
              <div className="w-1/4">{agent.last_name}</div>
              <div className="w-1/4">{agent.email}</div>
              <button
                className="ml-auto"
                onClick={() => deleteAgent(key, agent.user_id)}
              >
                <ReactSVG
                  src={`${lc_data.dir}dist/${TrashIcon}`}
                  className="w-16 h-16 fill-red-700"
                />
              </button>
            </div>
          );
        })}

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

export default AgentsList;
