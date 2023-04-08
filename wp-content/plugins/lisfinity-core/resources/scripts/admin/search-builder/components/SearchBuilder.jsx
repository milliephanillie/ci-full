/* global lc_data, React */
/**
 * External dependencies.
 */
import { useEffect, useState } from '@wordpress/element';
import { BrowserRouter as Router, Route } from 'react-router-dom';

/**
 * Internal dependencies.
 */
import SearchBuilderRouting from './SearchBuilderRouting';

const SearchBuilder = (props) => {
  const [loading, setLoading] = useState(false);

  return (
    <Router>
      {!loading && <Route path={lc_data.site_url} component={SearchBuilderRouting}/>}
    </Router>
  );
};

export default SearchBuilder;
