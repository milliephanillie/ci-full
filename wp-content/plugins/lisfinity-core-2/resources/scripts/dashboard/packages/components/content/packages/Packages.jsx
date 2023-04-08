/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import { map, sortBy, isEmpty } from 'lodash';
import { Fragment, useRef } from 'react';
import PackagesActive from './PackagesActive';
import { useState } from '@wordpress/element';
import ModalDemo from '../../../../../theme/packages/components/modal/ModalDemo';
import PackageSubscription from './PackageSubscription';

const Packages = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const {business, menuOpen, options} = data;
  const {packages} = business;
  const [fetching, setFetching] = useState(null);
  const [modalOpen, setModalOpen] = useState(false);
  const days = useRef(null);

  const changePrice = (selected, product) => {
    product.packages_discounts.map(discount => {
      if (discount.listings === selected.label) {
        let array = price;
        array.push({price: discount.price, id: product.ID});
        setPrice([...array]);
      }
    })
  }

  return (
    <Fragment>
      {!packages &&
      <h3
        className="mr-10 w-full bg:w-2/3 font-bold text-grey-400"
      >
        {lc_data.jst[112]}
      </h3>
      }
      {packages && business.active_packages && <PackagesActive/>}
      <section className="packages mb-128">
        <div className="flex mb-20 w-full">
          {isEmpty(business.active_packages) &&
          <h3
            className="mr-10 font-bold text-grey-400">{lc_data.jst[113]}</h3>
          }
          {packages &&
          <h3 className="font-bold">{lc_data.jst[114]}</h3>
          }
        </div>
        <div className="flex flex-wrap -mx-8 -mb-20">
          {packages &&
          map(sortBy(packages, options?.sorting_pricing_packages || 'price'), product => {
            const footnotes = [];
            return <PackageSubscription key={product.ID} product={product} footnotes={footnotes} />;
          })}
        </div>
      </section>
      <ModalDemo
        isLogged={lc_data.logged_in}
        open={modalOpen}
        closeModal={() => setModalOpen(false)}
        title={lc_data.jst[606]}
      >
        <div className="font-semibold text-lg text-grey-700" dangerouslySetInnerHTML={{
          __html: lc_data.jst[607],
        }}
        />
      </ModalDemo>
    </Fragment>
  );
};

export default Packages;
