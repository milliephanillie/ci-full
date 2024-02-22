/* global lc_data, React */
/**
 * External dependencies.
 */
import { connect } from 'react-redux';
import * as actions from '../../store/actions';
import { fetchPosts, updateFieldOptions } from './store/actions';
import { BrowserRouter as Router } from 'react-router-dom';
import { Component } from '@wordpress/element';
import { map, isEmpty } from 'lodash';
import queryString from 'query-string';
import SearchDefault from './SearchDefault';
import SearchDetailed from './SearchDetailed';
import apiFetch from '@wordpress/api-fetch';
import { setIsDetailed, updateSearchData, updateSearchDataChosen } from '../../store/actions';

class PageSearch extends Component {
  constructor(props) {
    super(props);
    this.state = {
      loading: false,
      detailed: false,
      reloadMap: true,
      options: {},
    };
  }

  /**
   * Before a component has been mounted
   * -----------------------------------
   */
  componentDidMount() {
    const { detailed } = this.state;
    const { dispatch } = this.props;

    const id = document.getElementById('page-search');
    let options = {};
    if (id) {
      options = JSON.parse(id.dataset.options);
      this.setState({ options });
    }

    const parsed = queryString.parse(location.search);
    const data = {};
    if (parsed.t) {
      data['category-type'] = parsed.t;
    } else if (options?.type) {
      data['category-type'] = options.type;
    } else {
      data['category-type'] = 'common';
    }

    // update search data with url query params.
    map(parsed, (param, name) => {
      if (!parsed.t && !isEmpty(param)) {
        data[name] = param;
      }
    });

    console.log("parsed")
    console.log(parsed)
    dispatch(updateSearchData(data));
    dispatch(updateSearchDataChosen(data));
    this.fetchSearchFields();
    this.fetchCategoryContent(data);
    dispatch(setIsDetailed(parsed && parsed.p === 'detailed'));
  }

  /**
   * Load required search fields
   * ---------------------------
   */
  fetchSearchFields = async () => {
    const { fields } = this.state;
    const { type, dispatch, fieldOptions } = this.props;
    const id = document.getElementById('page-search');
    let options = {};
    if (id) {
      options = JSON.parse(id.dataset.options);
      this.setState({ options });
    }
    const parsed = queryString.parse(location.search);
    const data = {};
    if (parsed.t) {
      data['category-type'] = parsed.t;
    } else if (options?.type) {
      data['category-type'] = options.type;
    } else {
      data['category-type'] = 'common';
    }
    map(parsed, (value, key) => !isEmpty(value) ? data[key] = value : '');
    this.setState({ loading: true });
    if (!fieldOptions || isEmpty(fieldOptions)) {
      await apiFetch({ path: `${lc_data.search_builder_fields}/sidebar` }).then(result => {
        dispatch(updateFieldOptions(result));
        dispatch(fetchPosts(data));

        this.setState({ loading: false });
      });
    }
  };

  fetchCategoryContent = async (data) => {
    this.setState({ categoryContent: null });

    const currentQueryString = window.location.search;

    let url = ci_data.ci_category_content + currentQueryString;

    console.log("fetchCategoryContent");
    console.log(url);
    console.log(data);
    console.log("fetchCategoryContent");

    try {
        const response = await fetch(url, {
            method: "GET",
            headers: {
                "Content-Type": "application/json"
            },
            credentials: "same-origin"
        });

        let data = await response.json(); // 'data' is now the resolved value, not a Promise

        // Directly use 'data' without '.then()'
        this.setState({ categoryContent: data });
        console.log("fetchCategoryContent call");
        console.log(data);
    } catch (error) {
        // Error handling
        console.log(error);
        console.log(error.message);
        console.error(error);
    }
}

  /**
   * Handle change of the view
   * -------------------------
   *
   * @param e
   * @param detailed
   */
  handleChange = (e, detailed, reset = false) => {
    let { dispatch, searchData } = this.props;
    const { reloadMap } = this.state;
    this.setState({ detailed });
    this.setState({ reloadMap: !reloadMap });
    if (!detailed) {
      dispatch(fetchPosts(searchData));
      //dispatch(updateSearchDataChosen(searchData));
    }
    dispatch(setIsDetailed(detailed));
  };

  /**
   * Render the correct search page view
   * -----------------------------------
   *
   * @param detailed
   * @param results
   * @returns {*}
   */
  renderSearch = (detailed, results) => {
    const { loading } = this.state;
    if (detailed && this.state.options.detailed_search !== '0') {
      return <SearchDetailed
        onChange={(e, detailed, reset, data) => this.handleChange(e, detailed || false, reset || false)}
        results={results} options={this.state.options}
        loading={loading}/>;
    } else {
      return <SearchDefault categoryContent={this.state.categoryContent} onChange={e => this.handleChange(e, true)} results={results} options={this.state.options}
                            loading={loading}/>;
    }
  };

  /**
   * Renders the component.
   *
   * @return {Object}
   */
  render() {
    let { loading, detailed } = this.state;
    const { results } = this.props;
    const params = queryString.parse(location.search);
    detailed = params && params.p;
    return [
      <main key={1} className="flex flex-no-wrap bg-grey-100">
        <Router>
          {!isEmpty(this.state.options) && this.renderSearch(detailed, results)}
        </Router>
      </main>,
    ];
  }
}

function mapStateToProps(state) {
  const { postsByUrl, fieldOptions, searchData, isDetailed } = state;
  const {
    isFetching, lastUpdated, items: results,
  } = postsByUrl.RECEIVE_POSTS || {
    isFetching: true,
    results: [],
  };
  return {
    results,
    isFetching,
    lastUpdated,
    fieldOptions,
    searchData,
    isDetailed,
  };
}

export default connect(mapStateToProps)(PageSearch);
