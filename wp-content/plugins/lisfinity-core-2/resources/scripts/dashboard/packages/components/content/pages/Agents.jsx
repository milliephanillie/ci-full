/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import { useEffect } from '@wordpress/element';
import { isEmpty } from 'lodash';
import AgentsTabs from './agents/AgentsTabs';

const Agents = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { loading, business, menuOpen, profile } = data;

  return (
    !loading &&
    <section>
      <AgentsTabs />
    </section>
  );
};

export default Agents;
