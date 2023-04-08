/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import { useState } from '@wordpress/element';

const PackagesSubscription = (props) => {
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { business, menuOpen, options } = data;
  const { packages } = business;
  const [fetching, setFetching] = useState(null);
  const [modalOpen, setModalOpen] = useState(false);
  
  const handleDays = (e) => {
    
  }

  return (
    <!-- Price Package | Qty -->
    <div className="flex items-center mt-20 -mb-20">
      <label htmlFor={`qty-${product.ID}`}
             className="font-light text-sm text-grey-900">{lc_data.jst[145]}</label>
      <div className="mx-8 p-4 bg-grey-100 border border-grey-300 rounded" style="width: 54px;">
        <input id={`qty-${product.ID}`} type="number" min="1" className="w-full bg-transparent"
               onChange={handleDays}
               value="1"/>
      </div>
    </div>
  );
};

export default PackagesSubscription;
