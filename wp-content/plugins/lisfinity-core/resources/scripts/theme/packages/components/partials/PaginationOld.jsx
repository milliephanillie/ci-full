/* global lc_data, React */
/**
 * External dependencies.
 */
import queryString from 'query-string';
import ReactSVG from 'react-svg';
import arrowRight from '../../../../../images/icons/arrow-right.svg';
import arrowLeft from '../../../../../images/icons/arrow-left.svg';
import { useDispatch, useSelector } from 'react-redux';
import { useState, useEffect } from 'react';
import { fetchPosts } from '../page-search/store/actions';
import { updateSearchData } from '../../store/actions';

const PaginationFirstLast = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const [results, setResults] = useState({});
  const [pages, setPages] = useState([]);

  const handlePagination = (e, page) => {
    const sData = data.searchData;
    sData['offset'] = page;
    dispatch(fetchPosts(sData, true));
    dispatch(updateSearchData(sData));

    // scroll to top of the products.
    const el = document.getElementById('lisfinityProducts');
    if (el && props.type !== 'business') {
      if (props.type === 'elementor' && props.scrollbar.current) {
        props.scrollbar.current.scrollTop(el.offsetTop - 100);
      }
      if (props.type !== 'elementor' && props.scrollbar.current) {
        props.scrollbar.scrollTop(el.offsetTop - 100);
      }
    }
    const breadcrumb = document.getElementsByClassName('page-search-listings');
    if (breadcrumb[0]) {
      const top = breadcrumb[0].offsetTop;
      window.scrollTo({ top, behavior: 'smooth' });
    }
  };

  useEffect(() => {
    setResults(data?.postsByUrl?.RECEIVE_POSTS?.items);
    let links = {};

    if (results.max_num_pages > 8) {
      for (let i = 1; i <= 3; i += 1) {
        links[i] = i;
      }
      const end = results.max_num_pages - 2;
      for (let i = 1; i <= 3; i += 1) {
        links[i] = i;
      }
    } else {
      for (let i = 1; i <= results.max_num_pages; i += 1) {
        links[i] = i;
      }
      setPages(links);
    }
  }, [data]);

  let url = queryString.parse(location.search);
  return (
    results &&
    <div className="mb-20 sm:pb-0">

      <nav className="pagination--simple flex-center mt-10 sm:mt-24">
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

    </div>
  );
};

export default PaginationFirstLast;
