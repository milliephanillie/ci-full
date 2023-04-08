/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect, useRef } from 'react';
import { map, isEmpty } from 'lodash';
import cx from 'classnames';
import { __ } from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import ScrollContainer from 'react-indiana-drag-scroll';

/**
 * Internal dependencies
 */
import shiftRightIcon from '../../../../../../../images/icons/shift-right.svg';
import ProductPrice from '../content/ProductPrice';
import { useDispatch, useSelector } from 'react-redux';
import { findDOMNode } from 'react-dom';
import { isInViewport, sideScroll } from '../../../../../vendor/functions';
import { dispatch } from '@wordpress/data';
import { productMenuActive } from '../../../../store/actions';

function ProductMenuSticky(props) {
  const { product, currentUser, options } = props;
  const [active, setActive] = useState('basic');
  const [visible, setVisible] = useState(false);
  const [classes, setClasses] = useState('');
  const [menuVisible, setMenuVisible] = useState(false);
  const [showScrollBtn, setShowScrollBtn] = useState(false);
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const toScroll = useRef(null);
  const sticky = useRef(null);

  function getIds() {
    const ids = [];
    map(product.groups.groups, (group, name) => {
      if (data.specificationMenu.includes(group.slug)) {
        ids.push(group.slug);
      }
    });

    return ids;
  }

  function goTo(id) {
    const el = document.getElementById(id);
    if (window.innerWidth <= 960) {
      window.scrollTo({
        top: el.offsetTop,
        behavior: 'smooth',
      });
    } else {
      window.scrollTo({
        top: el.offsetTop,
        behavior: 'smooth',
      });
    }
  }

  function scrollLeft() {
    const container = findDOMNode(toScroll.current);
    sideScroll(container, 'right', '25', 100, 10);
  }

  const defineClasses = () => {
    const stickyClasses = cx({
      'md:flex md:flex-no-wrap': product.product_meta.price_type === 'auction',
      'flex flex-no-wrap': product.product_meta.price_type !== 'auction',
    });
    setClasses(stickyClasses);
    if (window.outerWidth < 1620) {
      setMenuVisible(true);
    } else {
      setMenuVisible(false);
    }
  };

  useEffect(() => {
    defineClasses();
    window.addEventListener('scroll', handleScroll);
    window.addEventListener('resize', defineClasses);
    return () => {
      window.removeEventListener('resize', defineClasses);
      window.removeEventListener('scroll', handleScroll);
    };
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
    if (pageYOffset > 266) {
      setVisible(true);
    } else {
      setVisible(false);
    }
    const container = document.querySelector('.product--single-content-wrapper');
    const menu = document.querySelector('.product--menu__aside');
    const pos = container.getBoundingClientRect();
    if (menu && container) {
      if ((window.scrollY + menu.offsetHeight) >= pos.height) {
        menu.classList.add('menu-stop');
      } else {
        menu.classList.remove('menu-stop');
      }
    }
  };

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
  });

  return (
    visible &&
    <div
      className="menu-container flex flex-col w-full rounded-b shadow-single-menu overflow-hidden"
      ref={sticky}
    >
      {menuVisible &&
      <div className="flex flex-end py-16 px-20 bg-grey-100 w-full overflow-hidden">
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
      }

      <div className={`flex flex-wrap justify-between items-center p-20 bg-white rounded-b`}>
        <div
          className={`flex w-full sm:w-1/2 ${classes} ${'auction' === product.product_meta.price_type ? 'product-auction' : ''}`}>
          {product.thumbnail &&
          <figure
            className="product--figure relative flex rounded overflow-hidden mr-10 sm:mr-0 min-w-40 w-40 h-40 sm:min-w-75 sm:w-75 sm:h-56">
            <img
              className="absolute top-0 left-0 w-full h-full object-cover rounded"
              src={product.thumbnail.url ? product.thumbnail.url : options.fallback_image}
              alt={product.post_title}
            />
          </figure>}
          <h6
            className="product--title relative sm:top-0 pl-0 sm:pl-20 font-bold text-grey-1000 text-base sm:text-lg">{product.post_title}</h6>
        </div>
        <div className={`product--price w-full sm:w-auto`}>
          <ProductPrice product={product} currentUser={currentUser} options={options} fromSticky/>
        </div>
      </div>

    </div>
  );
}

export default ProductMenuSticky;
