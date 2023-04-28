/* global lc_data, React */
/**
 * External dependencies.
 */
import {useState, useEffect, Fragment} from 'react';
import ReactSVG from 'react-svg';

/**
 * Internal dependencies.
 */
import UsersIcon from '../../../../../../../images/icons/users.svg';

const Visits = (props) => {
  const {product, currentUser, settings} = props;
  const [visits, setVisits] = useState(product.views);
  let icon = null;
  let svg = null;
  let actionVisitsIndex = null;

  actionVisitsIndex = settings?.actions && settings?.actions.findIndex(action => action.actions === 'visits');

  if (settings?.actions[actionVisitsIndex].selected_icon_action !== null && settings?.actions[actionVisitsIndex].selected_icon_action) {
    typeof settings.actions[actionVisitsIndex].selected_icon_action['value'] === 'string' ? icon = settings.actions[actionVisitsIndex].selected_icon_action['value'] : svg = settings.actions[actionVisitsIndex].selected_icon_action['value']['url'];
  }

  return (
    <div className={`product--action text-base`}
         style={{
           display: 'flex',
           justifyContent: 'center',
           alignItems: 'center'
         }}
    >
      {'Views ' + visits}
    </div>
  );
};

export default Visits;
