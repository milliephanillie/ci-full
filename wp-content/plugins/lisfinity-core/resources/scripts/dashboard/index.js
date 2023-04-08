/**
 * dependencies.
 */
import { render } from '@wordpress/element';
import Dashboard from './packages/components/Dashboard';
import configureStore from './packages/store/configureStore';
import { Provider } from 'react-redux';

const store = configureStore();
const dashboard = document.getElementById('page-dashboard');

// hook on dashboard product page.
if (dashboard) {
  if (!lc_data.user_has_business) {
    window.location.href = `${lc_data.url}?e=no-business`;
  }
  render(
    <Provider store={store}>
      <Dashboard/>
    </Provider>,
    dashboard,
  );
}
