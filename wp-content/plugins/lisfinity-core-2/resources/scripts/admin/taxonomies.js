/**
 * Internal dependencies.
 */
import IE_Options from './ie-options/components/IE_Options';

import { render } from '@wordpress/element';
import Dashboard from './taxonomies/components/Dashboard';
import Groups from './taxonomies/components/Groups';
import configureStoreDashboard from './taxonomies/store/configureStore';
import { Provider } from 'react-redux';

const dashboard = document.getElementById('lisfinity-dashboard');
const groups = document.getElementById('lisfinity-cf-groups');
const ieOptions = document.getElementById('carbon_fields_container_importexport');

if (dashboard) {
  const storeDashboard = configureStoreDashboard();
  render(
    <Provider store={storeDashboard}>
      <Dashboard/>
    </Provider>,
    dashboard,
  );
}

if (groups) {
  render(
    <Groups/>,
    groups,
  );
}

if (ieOptions) {
  render(
    <IE_Options/>,
    ieOptions,
  );
}
