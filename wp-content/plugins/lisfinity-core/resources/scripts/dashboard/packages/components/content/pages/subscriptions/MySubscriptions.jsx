/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import * as actions from '../../../../store/actions';
import { useEffect } from '@wordpress/element';
import { isEmpty } from 'lodash';
import MySubscriptionsTabs from './MySubscriptionsTabs';

const MySubscriptions = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { loading, business, menuOpen, profile } = data;

  const getProfile = () => {
    const response = actions.fetchData(lc_data.get_wc_profile, '');
    response.then((result) => {
      dispatch(actions.setProfile(result.data));
    });
  }

  useEffect(() => {
    if (isEmpty(profile)) {
      getProfile();
    }
  }, []);

  return (
    !loading &&
    <section>
      <MySubscriptionsTabs/>
    </section>
  );
};

export default MySubscriptions;
