/* global lc_data */
/**
 * The external dependencies.
 */

export default () => {
  return new Promise((resolve, reject) => {
    const headers = new Headers();
    headers.append('X-WP-Nonce', lc_data.nonce);
    const result = fetch(`${lc_data.wp_resturl}wp/v2/users/me`, {
      credentials: 'same-origin',
      headers,
    });

    result.then((response) => {
      resolve(response.json());
    });
  });
};
