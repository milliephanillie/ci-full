// eslint-disable-next-line no-unused-vars
import config from '@config';
import '@styles/admin';
import './vendor/*.js';
import Choices from 'choices.js';
import queryString from 'query-string';
import axios from 'axios';
// import 'airbnb-browser-shims'; // Uncomment if needed

// Your code goes here ...
document.addEventListener('DOMContentLoaded', () => {
  const choices = document.querySelectorAll('.crb-select2 select');
  if (choices.length !== 0) {
    choices.forEach((choice) => {
      const options = choice.dataset.select ? JSON.parse(choice.dataset.select) : {};
      options.removeItemButton = true;
      new Choices(choice, options); // eslint-disable-line no-new
    });
  }

  const params = queryString.parse(location.search);
  if (params.page === 'lisfinity-elements') {
    location.href = location.origin + '/wp-admin/edit.php?post_type=lisfinity_header';
  }

});
