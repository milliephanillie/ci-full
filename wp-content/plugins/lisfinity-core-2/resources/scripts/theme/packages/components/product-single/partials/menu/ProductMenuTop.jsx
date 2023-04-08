/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect, useRef } from 'react';
import { map, isEmpty } from 'lodash';
import { __ } from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import ScrollContainer from 'react-indiana-drag-scroll';

/**
 * Internal dependencies
 */
import shiftRightIcon from '../../../../../../../images/icons/shift-right.svg';
import { useDispatch, useSelector } from 'react-redux';
import { findDOMNode } from 'react-dom';
import { isInViewport, sideScroll } from '../../../../../vendor/functions';
import { productMenuActive } from '../../../../store/actions';

function ProductMenuTop(props) {
  const { product } = props;
  const [active, setActive] = useState('basic');
  const [showScrollBtn, setShowScrollBtn] = useState(false);
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const toScroll = useRef(null);

  function getIds() {
    const ids = [];
    map(product.groups, (group, name) => {
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

  function scrollLeft() {
    const container = findDOMNode(toScroll.current);
    sideScroll(container, 'right', '25', 100, 10);
  }

  useEffect(() => {
    const menuContainer = document.querySelector('.menu-container');
    const scrollItems = document.querySelectorAll('.scroll-item');
    if (scrollItems && menuContainer) {
      const menuContainerWidth = menuContainer.offsetWidth;
      let scrollContainerWidth = 0;
      scrollItems.forEach(item => {
        scrollContainerWidth += item.offsetWidth;
      });
      if (scrollContainerWidth > menuContainerWidth) {
        setShowScrollBtn(true);
      }
    }

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
    <div className="menu-container flex flex-end py-16 px-20 bg-grey-100 rounded w-full overflow-hidden">
      <ScrollContainer ref={toScroll}>
        <ul className="flex items-center -mx-4">
          {map(product.groups.groups, (group, name) => {
            const groupName = group.name;
            return (
              <li key={group.slug} className="scroll-item">
                <a
                  href="#"
                  className={`flex py-4 px-16 rounded font-light whitespace-no-wrap ${group.slug === data.productMenuActive ? 'bg-yellow-300' : ''}`}
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
      </ScrollContainer>
      {showScrollBtn &&
      <button type="button" className="ml-auto pl-20" onClick={() => scrollLeft()}>
        <ReactSVG
          src={`${lc_data.dir}dist/${shiftRightIcon}`}
          className={`ml-10 w-24 h-24 fill-filter-icon`}
        />
      </button>
      }
    </div>
  );
}

export default ProductMenuTop;
