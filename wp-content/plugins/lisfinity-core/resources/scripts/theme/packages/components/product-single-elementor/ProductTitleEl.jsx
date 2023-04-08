/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import he from 'he';
import { useEffect, useState } from 'react';

const ProductTitleEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const { product, options } = data;
  const [title, setTitle] = useState('Listing Title');

  useEffect(() => {
    if (product?.post_title) {
      setTitle(product.post_title);
    }
  }, [product]);

  return (
    <h1 className="-mb-10 font-bold text-5xl text-grey-1000 leading-snug"
        dangerouslySetInnerHTML={{ __html: he.decode(title) }}/>
  );
};

export default ProductTitleEl;
