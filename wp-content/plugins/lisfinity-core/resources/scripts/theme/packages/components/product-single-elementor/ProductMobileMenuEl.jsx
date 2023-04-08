/* global lc_data, React */
/**
 * Dependencies.
 */
import {createRef, Fragment, useEffect, useRef, useState} from '@wordpress/element';
import {useDispatch, useSelector} from 'react-redux';
import ReactSVG from "react-svg";
import {map} from "lodash";
import shiftRightIcon from "../../../../../images/icons/shift-right.svg";
import {findDOMNode} from "react-dom";
import {isInViewport, sideScroll} from "../../../vendor/functions";
import {productMenuActive} from "../../store/actions";
import ScrollContainer from 'react-indiana-drag-scroll';
import queryString from "query-string";

/**
 * Internal dependencies
 */

const ProductMobileMenuEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const {product} = data;
  const [active, setActive] = useState('basic');
  const [showScrollBtn, setShowScrollBtn] = useState(false);
  const toScroll = useRef(null);
  const [showMenuTop, setShowMenuTop] = useState('');
  const [elSettings, setElSettings] = useState({});
  const [params, setParams] = useState({});
  let wrapper = null;


  let el = createRef();

  useEffect(() => {
    wrapper = el.current.closest('.elementor-product-mobile-menu');
    if (wrapper && wrapper.dataset.settings) {
      const settings = JSON.parse(wrapper.dataset.settings);
      setElSettings(settings);
    }
  }, []);


  function getIds() {
    const ids = [];

    product?.groups && product?.groups?.groups &&
    map(product.groups.groups, (group, name) => {
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
    window.addEventListener('scroll', handleScroll);
    window.addEventListener('resize', resizeHandler);
    resizeHandler();
    return () => {
      window.removeEventListener('scroll', handleScroll);
      window.removeEventListener('resize', resizeHandler);
    };
  }, []);

  useEffect(() => {
    setParams(queryString.parse(location.search));
  }, []);

  const resizeHandler = () => {
    const menuContainer = document.querySelector('.menu-container');
    const scrollItems = document.querySelectorAll('.scroll-item');

    if (scrollItems && menuContainer) {
      const menuContainerWidth = menuContainer.offsetWidth;
      let scrollContainerWidth = 0;
      scrollItems.forEach(item => {
        scrollContainerWidth += item.offsetWidth;
      });

      if (window.innerWidth < 1620) {
        setShowMenuTop('top');
      } else {
        setShowMenuTop('');
      }

      if (scrollContainerWidth > menuContainerWidth) {
        setShowScrollBtn(true);
      } else {
        setShowScrollBtn(false);
      }
    }
  }


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

  let icon = null;
  let svg = null;

  if (elSettings?.icon_url !== null && elSettings?.icon_url) {
    typeof elSettings.icon_url['value'] === 'string' ? icon = elSettings.icon_url['value'] : svg = elSettings.icon_url['value']['url'];
  }
  return (
    <div
      className="menu-container"
      ref={el}>
      {('top' === showMenuTop || params['elementor-preview']) &&
      <div
        className="wrapper-menu-mobile flex flex-end py-16 px-20 bg-grey-100 rounded w-full overflow-hidden">
        <ScrollContainer ref={toScroll}>
          <ul className="flex items-center -mx-4">
            {map(product?.groups?.groups, (group, name) => {
              const groupName = group.name;
              return (
                <li key={group.slug} className="scroll-item">
                  <a
                    href="#"
                    className={`flex py-4 px-16 rounded menu-mobile-link font-light whitespace-no-wrap ${group.slug === data.productMenuActive ? 'bg-yellow-300' : ''}`}
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
          {(icon === null && svg === null || "" == icon) &&
          <ReactSVG
            src={`${lc_data.dir}dist/${shiftRightIcon}`}
            className={`ml-10 w-24 h-24 fill-filter-icon scroll-button-icon`}
          />}
          {
            svg && elSettings.place_icon !== '' &&
            <img src={svg} alt="cart-icon"
                 className="ml-10 w-24 h-24 fill-filter-icon scroll-button-icon"/>
          }
          {
            elSettings.place_icon !== '' && icon &&
            <i className={`${icon} ml-10 w-24 h-24 fill-filter-icon scroll-button-icon`} style={{
              display: 'flex',
              alignSelf: 'center'
            }}
               aria-hidden="true"
            ></i>
          }
        </button>
        }
      </div>
      }
    </div>
  );
};

export default ProductMobileMenuEl;
