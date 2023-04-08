/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component } from '@wordpress/element';
import { connect } from 'react-redux';
import { sprintf } from '@wordpress/i18n';
import { map, isEmpty } from 'lodash';
import ReactSVG from 'react-svg';
import homeIcon from '../../../../../../images/icons/home.svg';

class Breadcrumb extends Component {
  constructor(props) {
    super(props);
    this.state = {
      loading: false,
      mobile: false,
    };
  }

  componentDidMount() {
    this.showMenu();
    window.addEventListener('resize', this.showMenu);
  }

  componentWillUnmount() {
    window.removeEventListener('resize', this.showMenu);
  }

  showMenu = () => {
    if (window.innerWidth <= 640) {
      this.setState({ mobile: true });
    } else {
      this.setState({ mobile: false });
    }
  };

  /**
   * Renders the component.
   *
   * @return {Object}
   */
  render() {
    const { loading } = this.state;
    const { results, isFetching } = this.props;
    const data = this.props.searchDataChosen;
    let name = '';
    if (!isEmpty(data) && data['category-type'] !== 'common') {
      map(this.props.fieldOptions?.groups, group => {
        if (group.slug === data['category-type']) {
          name = group.plural_name;
        }
      });
    } else {
      name = lc_data.jst[400];
    }

    return (
      name &&
      <div className="flex flex-wrap mt-0 -mt-16 mb-10 sm:justify-between items-center">
        <nav className="search--breadcrumb mb-6 mr-20">
          <ul className="flex items-center -mx-4">
            <li className="hidden sm:flex items-center px-2 whitespace-no-wrap">
              <ReactSVG
                src={`${lc_data.dir}dist/${homeIcon}`}
                className="injectable mr-8 w-16 h-16 fill-icon-home"
              />
              <a href={lc_data.site_url} className="text-grey-900">{lc_data.jst[467]}</a>
            </li>
            {
              <li className="flex px-4 whitespace-no-wrap">
                <span className="hidden sm:block mr-4">/</span>
                {this.state.mobile &&
                <span
                  className="font-semibold text-grey-1100">{results?.found_posts || 0}</span>
                }
                {!this.state.mobile &&
                <span
                  className="font-semibold text-grey-1100">{sprintf(lc_data.jst[476], results?.found_posts || 0)}</span>
                }
              </li>
            }
            {this.props.options.has_groups &&
            <li className="-ml-2 px-4 whitespace-no-wrap">
              <span className="mr-4 text-grey-700">{this.state.mobile ? '/' : lc_data.jst[399]}</span>
              <span
                className="inline-block font-semibold text-grey-1100 capitalize"
              >{name}</span>
            </li>
            }
          </ul>
        </nav>
        {
          results && results.max_num_pages && results.max_num_pages > 1 &&
          <div
            className="page--information self-start ml-auto font-semibold text-grey-700"
            dangerouslySetInnerHTML={{
              __html: this.state.mobile ? sprintf(lc_data.jst[630], results.page, results.max_num_pages) : sprintf(lc_data.jst[551], results.page, results.max_num_pages),
            }}
          >
          </div>
        }
      </div>
    );
  }
}

function mapStateToProps(state) {
  const { postsByUrl, searchData, searchDataChosen, fieldOptions } = state;
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
    searchData,
    searchDataChosen,
    fieldOptions,
  };
}

export default connect(mapStateToProps)(Breadcrumb);
