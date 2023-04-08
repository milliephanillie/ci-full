/* global lc_data */
/**
 * The external dependencies.
 */
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';

export default (option) => {
  // eslint-disable-next-line
  const path = `${lc_data.option}/${option}`;
  return apiFetch({ path }).then(response => response)
    .catch(() => __('An error occurred while trying to fetch the option.', 'lisfinity-core'));
};
