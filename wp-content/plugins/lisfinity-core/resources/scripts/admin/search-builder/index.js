/**
 * External dependencies.
 */
// import './taxonomies/vendor';
// import './taxonomies/store';
// import './taxonomies/fields';
import { render } from '@wordpress/element';

/**
 * Internal dependencies.
 */
import SearchBuilder from './components/SearchBuilder';

/**
 * Prevent the conflicts with WordPress's Underscore lib.
 */
if (!window.lodash) {
  _.noConflict();
}

const searchBuilder = document.getElementById('lisfinity-search-builder');

if (searchBuilder) {
  render(
    <SearchBuilder/>,
    searchBuilder,
  );
}
