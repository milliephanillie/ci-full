/* global lc_data, React */
/**
 * External dependencies.
 */
import { Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import queryString from 'query-string';
import { map } from 'lodash';
import ReactSVG from 'react-svg';

/**
 * Internal dependencies
 */
import arrowRight from '../../../../../images/icons/arrow-right.svg';
import arrowLeft from '../../../../../images/icons/arrow-left.svg';

const Pagination = (props) => {
  const { results, handlePagination } = props;

  let url = queryString.parse(location.search);
  let links = {};
  for (let i = 1; i <= results.max_num_pages; i += 1) {
    links[i] = i;
  }
  return (
    <Fragment>

      <nav className="pagination flex-center mt-44">
        <ul className="flex list-reset">
          {map(links, (link, index) => {
            url.page = index;
            return <li key={link}>
              {index == results.page
                ?
                <span className="flex-center w-30 h-30 bg-white rounded shadow-theme text-grey-900">{link}</span>
                :
                <button
                  type="button"
                  className="flex-center w-30 h-30 text-grey-900 cursor-pointer"
                  onClick={e => handlePagination(e, index)}
                >
                  {link}
                </button>
              }
            </li>;
          })}
        </ul>
      </nav>

      <nav className="pagination--simple flex-center mt-24">
        <ul className="flex list-reset">
          <li className="mr-12">
            {results.page != 1 ?
              <button className="flex-center text-grey-900" onClick={e => handlePagination(e, 1)}>
                <ReactSVG
                  src={`${lc_data.dir}dist/${arrowLeft}`}
                  className={`mr-8 min-w-16 min-h-16 fill-current-color`}
                />
                {lc_data.jst[479]}
              </button>
              :
              <span className="flex-center opacity-25">
                  <ReactSVG
                    src={`${lc_data.dir}dist/${arrowLeft}`}
                    className={`mr-8 min-w-16 min-h-16 fill-current-color`}
                  />
                {lc_data.jst[479]}
                </span>
            }
          </li>
          <li className="ml-12">
            {results.page != results.max_num_pages ?
              <button className="flex-center text-grey-900"
                      onClick={e => handlePagination(e, results.max_num_pages)}>
                {lc_data.jst[480]}
                <ReactSVG
                  src={`${lc_data.dir}dist/${arrowRight}`}
                  className={`ml-8 min-w-16 min-h-16 fill-current-color`}
                />
              </button>
              :
              <span className="flex-center opacity-25">
                  {lc_data.jst[480]}
                <ReactSVG
                  src={`${lc_data.dir}dist/${arrowRight}`}
                  className={`ml-8 min-w-16 min-h-16 fill-current-color`}
                />
                </span>
            }
          </li>
        </ul>
      </nav>

    </Fragment>
  );
};

export default Pagination;
