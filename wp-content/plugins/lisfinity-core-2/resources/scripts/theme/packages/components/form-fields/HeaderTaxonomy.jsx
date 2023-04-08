/* global lc_data, React */
/**
 * External dependencies.
 */
import { connect } from 'react-redux';
import { Component } from '@wordpress/element';
import { map, get, filter } from 'lodash';
import { fetchPosts } from '../page-search/store/actions';
import apiFetch from '@wordpress/api-fetch';
import Select from 'react-select';

class HeaderTaxonomy extends Component {
  constructor(props) {
    super(props);
    this.state = {
      loading: false,
      value: '',
      results: {},
      chosen: {},
      terms: {},
      fields: {},
      options: {},
      request: {},
    };
  }

  componentDidMount() {
    this.fetchTaxonomies();
    this.getSettings();

    let options = document.getElementById('header-taxonomy')?.dataset?.options;
    if (options) {
      options = JSON.parse(options);
      this.setState({ options });
    }

    if (lc_data?.request) {
      this.setState({ request: lc_data.request });
    }

  }

  getSettings() {
    apiFetch({ path: `${lc_data.search_builder_fields}/home` }).then(fields => {
      this.setState({ fields });
    });
  }

  /**
   * Get all available terms for the given taxonomies
   * ------------------------------------------------
   */
  fetchTaxonomies() {
    const { fields, data } = this.state;
    const taxonomy = document.getElementById('header-taxonomy').dataset.taxonomy;
    const url = `${lc_data.terms_get}/${taxonomy}`;
    this.setState({ terms: {} });
    apiFetch({ path: url }).then(terms => {
      this.setState({ terms });
    });
  }

  /**
   * Control the change of the taxonomy select
   * -----------------------------------------
   *
   * @param selectedOption
   * @param taxonomy
   * @param name
   */
  handleChange = (selectedOption, taxonomy, name) => {
    const { chosen } = this.state;
    const selected = selectedOption ? selectedOption.value : '';
    const { terms, searchPage, dispatch } = this.props;
    chosen[taxonomy] = selected;
    this.setState({ chosen });
    const data = this.props.searchData;
    data[name] = selected;

    if (lc_data.is_search) {
      dispatch(fetchPosts(data));
    } else {
      let link = `${lc_data.page_search}?${name}=${selected}`;
      if (this.state.options?.custom_category_pages) {
        link = `${lc_data.site_url}${taxonomy}/${selected}`;
      }
      window.location.href = link;
    }
  };

  /**
   * Renders the component.
   *
   * @return {Object}
   */
  render() {
    const { loading, value, results, chosen, terms, fields } = this.state;
    const data = this.props.searchData;
    const taxonomy = document.getElementById('header-taxonomy').dataset.taxonomy;
    const icon = document.getElementById('header-taxonomy').dataset.icon;
    const placeholder = get(fields, 'placeholder');
    const label = get(fields, 'label');
    const options = [];
    map(filter(terms, (t) => t.taxonomy === taxonomy), term => {
      options.push({ value: term.slug, label: term.name });
    });
    let chosenIndex = false;
    map(options, (option, index) => {
      if (option.value === data[`tax[${taxonomy}]`] || option.value === this.state?.request?.[1]) {
        chosenIndex = index;
      }
    });
    const customStyles = {
      control: () => ({}),
    };

    return [
      loading && <div key={0} className="loading">{lc_data.jst[401]}</div>,
      !loading && (
        <div key={1} className="select-transparent flex items-center">
          {icon &&
          <img src={icon} alt={taxonomy} width={16} height={16}
               className="taxonomy-icon injectable fill-red-700 mr-10"/>}
          <Select
            className="header-select w-128 cursor-pointer"
            placeholder={placeholder ? placeholder[taxonomy] : lc_data.jst[411]}
            onChange={selectedOption => this.handleChange(selectedOption, taxonomy, `tax[${taxonomy}]`)}
            value={options[chosenIndex]}
            options={options}
            isClearable
            styles={customStyles}
            isRtl={lc_data.rtl}
          />
        </div>
      ),
    ];
  }
}

function mapStateToProps(state) {
  const { postsByUrl, searchData } = state;
  const {
    isFetching, lastUpdated, items: results, url,
  } = postsByUrl.RECEIVE_POSTS || {
    isFetching: true,
    results: {},
    url: '',
  };
  return {
    results,
    isFetching,
    lastUpdated,
    url,
    searchData,
  };
}

export default connect(mapStateToProps)(HeaderTaxonomy);
