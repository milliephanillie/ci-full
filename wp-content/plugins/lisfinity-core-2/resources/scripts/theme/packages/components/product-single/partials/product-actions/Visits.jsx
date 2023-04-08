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
    <div className={`product--action text-base elementor-repeater-item-${props.elementId}`}
         style={{
           display: 'flex',
           justifyContent: 'center',
           alignItems: 'center'
         }}
    >
      {
        settings?.actions[actionVisitsIndex].remove_icon_action === '' &&
        <Fragment>
          {(icon === null && svg === null || '' == icon) &&
          <ReactSVG
            src={`${lc_data.dir}dist/${UsersIcon}`}
            className="mr-4 product-icon w-16 h-16 fill-field-icon"
          />
          }

          {
            svg && settings?.actions[actionVisitsIndex].place_icon_action !== '' &&
            <img src={svg} alt="visits-icon"
                 className="w-20 h-20 mr-8 product-icon fill-icon-reset pointer-events-none"/>
          }
          {
            settings?.actions[actionVisitsIndex].place_icon_action !== '' && icon &&
            <i className={`${icon} product-icon`}
               aria-hidden="true"
            ></i>
          }
        </Fragment>
      }

      {visits}
      {settings?.actions[actionVisitsIndex].action_text !== '' &&
      <span className="ml-3">{settings?.actions[actionVisitsIndex].action_text}</span>
      }
    </div>
  );
};

export default Visits;
