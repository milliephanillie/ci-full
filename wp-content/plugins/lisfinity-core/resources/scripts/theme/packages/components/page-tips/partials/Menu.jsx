/* global lc_data, React */
/**
 * External dependencies.
 */
import { useEffect, useState } from '@wordpress/element';
import { map } from 'lodash';

/**
 * Internal dependencies
 */

function Menu(props) {
  const { tips } = props;
  const [active, setActive] = useState('all');

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
  });

  return (
    <div className="flex flex-end py-16 px-14 bg-grey-100 rounded min-w-192 w-full">
      <ul className="-mb-4">
        {map(tips, (tip, index) => {
          return (
            <li key={tip.category} className="mb-4">
              <button
                type="button"
                className={`flex py-4 px-10 rounded font-light ${tip.category === active ? 'bg-yellow-300' : ''}`}
                onClick={() => goTo(tip.category)}
              >
                {tip.name}
              </button>
            </li>
          );
        })}
      </ul>
    </div>
  );
}

export default Menu;
