/* global lc_data, React */
/**
 * Dependencies.
 */
import Pagination from 'react-js-pagination';
import { useDispatch, useSelector } from 'react-redux';
import { useState } from '@wordpress/element';
import * as actions from '../../../../store/actions';
import { useEffect } from 'react';

const ProductsPagination = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { business } = data;
  let pagination = business.ads_pagination;
  const [page, setPage] = useState(1);

  const handlePagination = (page) => {
    setPage(page);
    pagination.page = page;
    dispatch(actions.setBusiness(business));
  };

  useEffect(() => {
    setPage(business?.ads_pagination?.page || 1);
  }, [business]);

  return (
    <div className="mb-20 sm:pb-0">
      <nav className="pagination pagination-search-content flex-center mt-20 sm:mt-44">

        <Pagination
          activePage={parseInt(page, 10)}
          itemsCountPerPage={pagination.per_page || 12}
          totalItemsCount={business.ads.length || 0}
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

export default ProductsPagination;
