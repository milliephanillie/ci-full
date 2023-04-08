import axios from 'axios';

/**
 * The external dependencies.
 */
export default (attachments, user = false) => {
  return new Promise((resolve, reject) => {
    // eslint-disable-next-line
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const url = lc_data.query_attachments;
    let data = {
      post__in: attachments,
      posts_per_page: -1,
      //author: user,
    };
    let result = axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url,
      data,
    });

    result.then((response) => {
      resolve(response.data);
    }).catch((error) => reject(lc_data.jst[380]));
  });
};
