/* global lc_data, React */
/**
 * Dependencies
 */
import { Fragment } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { map } from 'lodash';
import ReactSVG from 'react-svg';
import arrowRight from '../../../../../../../images/icons/arrow-right.svg';
import arrowLeft from '../../../../../../../images/icons/arrow-left.svg';
import { useEffect, useState } from '@wordpress/element';
import * as actions from '../../../../store/actions';
import Pagination from 'react-js-pagination';

const ProductsPaginationFirstLast = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { business, menuOpen } = data;
  let pagination = business.ads_pagination;
  const [page, setPage] = useState(1);
  const [maxPages, setMaxPages] = useState(pagination.maxPages);
  const [links, setLinks] = useState({});

  useEffect(() => {
    let linkArray = [];
    for (let i = 1; i <= pagination.maxPages; i += 1) {
      linkArray[i] = i;
    }
    setLinks(linkArray);
  }, []);

  useEffect(() => {
    pagination = business.ads_pagination;
    let linkArray = [];
    for (let i = 1; i <= business.ads_pagination.maxPages; i += 1) {
      linkArray[i] = i;
    }
    setLinks(linkArray);
  }, [business.ads_pagination.maxPages]);

  useEffect(() => {
    pagination.page = page;
    dispatch(actions.setBusiness(business))
  }, [page]);

  useEffect(() => {
    setPage(page);
  }, [business.ads_pagination.page]);

  return (
    pagination.maxPages > 1 &&
    <Fragment>

      <nav className="pagination--simple flex-center mt-24">
        <ul className="flex list-reset">
          <li className="mr-12">
            {business.ads_pagination.page !== 1 ?
              <button className="flex-center text-grey-900" onClick={() => setPage(1)}>
                <ReactSVG
                  src={`${lc_data.dir}dist/${arrowLeft}`}
                  className={`mr-8 min-w-16 min-h-16 fill-current-color`}
                />
                {lc_data.jst[[479]]}
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
            {business.ads_pagination.page !== maxPages ?
              <button className="flex-center text-grey-900" onClick={() => setPage(maxPages)}>
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
}

export default ProductsPaginationFirstLast;
