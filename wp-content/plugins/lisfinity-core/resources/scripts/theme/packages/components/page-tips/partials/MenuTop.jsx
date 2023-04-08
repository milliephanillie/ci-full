/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect, useRef } from 'react';
import { map } from 'lodash';
import ReactSVG from 'react-svg';
import ScrollContainer from 'react-indiana-drag-scroll';

/**
 * Internal dependencies
 */
import shiftRightIcon from '../../../../../../images/icons/shift-right.svg';
import { findDOMNode } from '@wordpress/element';
import { sideScroll } from '../../../../vendor/functions';

const MenuTop = (props) => {
  const { tips } = props;
  const [active, setActive] = useState('all');
  const toScroll = useRef(null);

  function getIds() {
    const ids = [];
    map(tips, (tip, index) => {
      ids.push(tip.category);
    });

    return ids;
  }

  function goTo(id) {
    const el = document.getElementById(id);
    window.scrollTo({
      top: el.offsetTop + 150,
      behavior: 'smooth',
    });
  }

  function isInViewport(element, offset = 0) {
    const windowHeight = window.innerHeight;
    const gridBottom = windowHeight * .1;
    const { top } = element.getBoundingClientRect();
    return (top + offset) >= 0 && (top - offset) <= gridBottom;
  }

  useEffect(() => {
    const ids = getIds();
    window.onscroll = () => {
      ids.forEach(id => {
        const el = document.getElementById(id);
        if (isInViewport(el, 150)) {
          setActive(id);
        }
      });
    };
  })

  function scrollLeft(e) {
    const container = findDOMNode(toScroll.current);
    sideScroll(container, 'right', '25', 100, 10);
  }

  return (
    <div className="flex flex-end py-16 px-20 bg-grey-100 rounded w-full overflow-hidden">
      <ScrollContainer ref={toScroll}>
        <ul className="flex items-center -mx-4">
          {map(tips, (tip, index) => {
            return (
              <li key={index}>
                <button
                  type="button"
                  className={`flex py-4 px-16 rounded font-light whitespace-no-wrap ${tip.category === active ? 'bg-yellow-300' : ''}`}
                  onClick={() => goTo(tip.category)}
                >
                  {tip.name}
                </button>
              </li>
            );
          })}
        </ul>
      </ScrollContainer>
      <button type="button" className="ml-auto pl-20" onClick={(e) => scrollLeft(e)}>
        <ReactSVG
          src={`${lc_data.dir}dist/${shiftRightIcon}`}
          className={`ml-10 w-24 h-24 fill-filter-icon`}
        />
      </button>
    </div>
  );
}

export default MenuTop;
