/* global lc_data, React */
/**
 * External dependencies.
 */
import Pagination from 'react-js-pagination';
import { useDispatch, useSelector } from 'react-redux';
import { fetchPosts } from '../page-search/store/actions';
import { updateSearchData } from '../../store/actions';

const PaginationA = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();

  const handlePagination = (page) => {
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

  return (
    <div className="mb-20 sm:pb-0">
      <nav className="pagination pagination-search-content flex-center mt-20 sm:mt-44">

        <Pagination
          activePage={parseInt(props.page, 10)}
          itemsCountPerPage={parseInt(props.options?.listings_per_page, 10) || 12}
          totalItemsCount={parseInt(props.items, 10)}
          pageRangeDisplayed={8}
          onChange={(e) => handlePagination(e)}
          innerClass="flex list-reset"
          linkClass="flex-center default-page w-30 h-30 text-grey-900 cursor-pointer"
          activeLinkClass="flex-center active-page w-30 h-30 bg-white rounded shadow-theme text-grey-900"
          disabledClass="flex-center opacity-25 cursor-default"
          hideFirstLastPages={true}
        />

      </nav>
    </div>
  );
};

export default PaginationA;
