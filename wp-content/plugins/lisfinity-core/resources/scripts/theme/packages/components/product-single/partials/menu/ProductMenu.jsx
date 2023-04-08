/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect } from 'react';
import { map, isEmpty } from 'lodash';
import { __ } from '@wordpress/i18n';
import { useDispatch, useSelector } from 'react-redux';
import { isInViewport } from '../../../../../vendor/functions';
import { productMenuActive } from '../../../../store/actions';

/**
 * Internal dependencies
 */

function ProductMenu(props) {
  const { product } = props;
  const [active, setActive] = useState('basic');
  const data = useSelector(state => state);
  const dispatch = useDispatch();

  function getIds() {
    const ids = [];
    map(product.groups && product.groups.groups && product.groups.groups, (group, name) => {
      if (data.specificationMenu.includes(group.slug)) {
        ids.push(group.slug);
      }
    });

    return ids;
  }

  function goTo(id) {
    const el = document.getElementById(id);
    window.scrollTo({
      top: el.offsetTop,
      behavior: 'smooth',
    });
  }

  useEffect(() => {
    window.addEventListener('scroll', handleScroll);

    return window.removeEventListener('scroll', handleScroll);
  });

  const handleScroll = () => {
    const ids = getIds();
    ids.forEach(id => {
      const el = document.getElementById(id);
      if (isInViewport(el, 200)) {
        setActive(id);
        dispatch(productMenuActive(id));
      }
    });
  };

  return (
    <div className="flex flex-end py-16 px-14 bg-grey-100 rounded min-w-192">
      <ul className="-mb-4">
        {map(product.groups.groups, (group, name) => {
          const groupName = group.name;
          return (
            <li key={group.slug} className="mb-4">
              <a
                href="#"
                className={`flex py-4 px-10 rounded font-light ${group.slug === data.productMenuActive ? 'bg-yellow-300' : ''}`}
                onClick={(e) => {
                  e.preventDefault();
                  goTo(group.slug);
                }}
              >
                {groupName}
              </a>
            </li>
          );
        })}
      </ul>
    </div>
  );
}

export default ProductMenu;
