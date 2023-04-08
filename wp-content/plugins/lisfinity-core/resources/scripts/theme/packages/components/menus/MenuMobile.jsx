/* global lc_data, React */
/**
 * Dependencies.
 */
import { useEffect, useRef, useState } from '@wordpress/element';
import axios from 'axios';
import { Fragment } from 'react';
import { map, filter, isEmpty } from 'lodash';
import ReactSVG from 'react-svg';
import HeaderNotificationsWrapper from '../../../../dashboard/packages/components/header/HeaderNotificationsWrapper';
import HeaderCompareWrapper from '../../../../dashboard/packages/components/header/HeaderCompareWrapper';
import CloseIcon from '../../../../../images/icons/close.svg';
import BackIcon from '../../../../../images/icons/chevron-left.svg';
import PlusIcon from '../../../../../images/icons/plus.svg';
import MenuIcon from '../../../../../images/icons/menu.svg';
import BoltIcon from '../../../../../images/icons/bolt-alt.svg';
import CartIcon from '../../../../../images/icons/cart.svg';
import { Scrollbars } from 'react-custom-scrollbars';
import he from 'he';

const MenuMobile = (props) => {
  const [loading, setLoading] = useState(true);
  const [user, setUser] = useState(null);
  const [menuActivated, setMenuActivated] = useState(false);
  const [socials, setSocials] = useState(null);
  const [cart, setCart] = useState(null);
  const [menu, setMenu] = useState(null);
  const [filteredMenu, setFilteredMenu] = useState(null);
  const [showMenu, setShowMenu] = useState(false);
  const [currentMenuItem, setCurrentMenuItem] = useState(null);
  const [menuHeight, setMenuHeight] = useState(999);
  const [options, setOptions] = useState({});
  const navWrapper = useRef(null);
  const menuBottom = useRef(null);
  const menuHeader = useRef(null);
  const menuUl = useRef(null);
  const [currentMenuItemParent, setCurrentMenuItemParent] = useState([]);

  const showMenuHandler = () => {
    if (window.innerWidth < 1030) {
      setMenuActivated(true);
    } else {
      setMenuActivated(false);
    }
  };

  const menuItemsWidth = () => {
    const windowHeight = window.innerHeight;
    const bottomHeight = menuBottom.current.offsetHeight;
    const topHeight = menuHeader.current.offsetHeight;
    const height = windowHeight - bottomHeight - topHeight - 60;
    if (menuUl.current.offsetHeight > height) {
      setMenuHeight(height);
    } else {
      setMenuHeight(menuUl.current.offsetHeight);
    }
  };

  useEffect(() => {
    window.addEventListener('resize', showMenuHandler);
    if (showMenu) {
      window.addEventListener('resize', menuItemsWidth);
    }

    setOptions(JSON.parse(document.getElementById('mobile-menu--wrapper').dataset.options));
    showMenuHandler();

    return () => {
      window.removeEventListener('resize', showMenuHandler);
      window.removeEventListener('resize', menuItemsWidth);
    };
  }, []);

  useEffect(() => {
    if (showMenu) {
      menuItemsWidth();
    }
  }, [showMenu]);

  const hasChildren = (id) => {
    let hasChild = false;
    map(menu, item => {
      if (item.parent === id) {
        hasChild = true;
      }
    });

    return hasChild;
  };

  const getParentItem = (id) => {
    let item = null;
    map(menu, el => {
      if (el.ID === id) {
        item = el;
      }
    });

    return item;
  };

  const getMobileMenu = async () => {
    const data = {};
    const headers = { 'X-WP-Nonce': lc_data.nonce };
    await axios({
      url: lc_data.get_mobile_menu,
      method: 'GET',
      credentials: 'same-origin',
      //headers,
      data,
    }).then(response => {
      setSocials(response.data.socials);
      setUser(response.data.user);
      setCart(response.data.cart);
      setMenu(response.data.menu);
      let menuItemsWithoutParent = filter(response.data.menu, el => el.parent === 0);
      setFilteredMenu(menuItemsWithoutParent);
      setLoading(false);
    });
  };

  useEffect(() => {
    getMobileMenu();
  }, []);

  useEffect(() => {
    if (currentMenuItem) {
      setFilteredMenu(filter(menu, el => el.parent === currentMenuItem.ID));
    } else {
      setFilteredMenu(filter(menu, el => el.parent === 0));
    }
  }, [currentMenuItem]);

  return (
    menuActivated &&
    <Fragment>
      {!loading && !showMenu &&
      <button
        type="button"
        onClick={() => setShowMenu(true)}
      >
        <ReactSVG
          src={`${lc_data.dir}dist/${MenuIcon}`}
          className="relative w-32 h-32 fill-white pointer-events-none"
        />
      </button>
      }
      {!loading && showMenu &&
      <div
        ref={navWrapper}
        className="menu-mobile fixed right-0 bottom-0 flex flex-col py-20 px-20 bg-white"
      >
        <nav>
          <div
            className={`menu-mobile--header flex items-center justify-between mb-10 border-b-2 border-grey-100 ${lc_data.logged_in ? 'pb-10' : 'pb-20'}`}
            ref={menuHeader}
          >
            <button
              type="button"
              onClick={() => setShowMenu(false)}
            >
              <ReactSVG
                src={`${lc_data.dir}dist/${CloseIcon}`}
                className="relative w-20 h-20 fill-grey-1100 pointer-events-none"
              />
            </button>

            {lc_data.logged_in &&
            <div className="flex items-center">
              <a href={user.account_link}>
                <figure className="user--avatar relative w-44 h-40 rounded-xl overflow-hidden">
                  <img
                    src={options?.avatar}
                    alt={user.display_name}
                    className="absolute top-0 left-0 w-full h-full object-cover"
                  />
                </figure>
              </a>
              <HeaderNotificationsWrapper mobile={true}/>
              {options?.enable_compare && <HeaderCompareWrapper mobile={true}/>}
              {options?.enable_cart &&
              <a
                href={cart.url}
                className="relative user--notifications ml-20"
              >
                <ReactSVG
                  src={`${lc_data.dir}dist/${CartIcon}`}
                  className="w-20 h-20 fill-icon-reset pointer-events-none"
                />
                <span
                  className={`cart-count absolute flex-center w-16 h-16 bg-grey-200 rounded text-xs text-grey-1100 pointer-events-none ${cart.count === 0 ? 'hidden' : ''}`}
                  style={{
                    top: '-8px',
                    right: '-10px',
                  }}
                >{cart.count}</span>
              </a>
              }
            </div>
            }
            {!lc_data.logged_in &&
            <a href={user.login_link} className="flex items-center text-blue-700">
              {lc_data.jst[459]}
            </a>
            }
          </div>
          {menu && currentMenuItem &&
          <button
            type="button"
            className="flex items-center mt-14 mb-10 w-full font-semibold text-grey-500"
            onClick={() => setCurrentMenuItem(getParentItem(currentMenuItem.parent))}
          >
            <div className="flex-center mr-10 p-4 bg-grey-200 rounded">
              <ReactSVG
                src={`${lc_data.dir}dist/${BackIcon}`}
                className="relative w-10 h-10 fill-grey-1100 pointer-events-none"
              />
            </div>
            {currentMenuItem.title}
          </button>
          }
          <Scrollbars style={{ zIndex: 20 }} autoHide={false} autoHeight
                      autoHeightMin={menuHeight}
                      renderTrackHorizontal={props => <div {...props} className="hidden"/>}
                      renderThumbHorizontal={props => <div {...props} className="hidden"/>}
                      renderTrackVertical={props => <div {...props}
                                                         className="track--vertical top-86 right-0 bottom-0 w-2"/>}
                      renderThumbVertical={props => <div {...props}
                                                         className="thumb--vertical bg-grey-600 rounded opacity-25"/>}>
            <ul className={`flex flex-col`} ref={menuUl}>
              {menu && map(filteredMenu, (item, index) => {
                const hasChild = hasChildren(item.ID);
                return (
                  <li key={index} className="flex my-8">
                    {hasChild &&
                    <button
                      type="button"
                      id={`menu-item-${item.ID}`}
                      className="flex items-center justify-between w-full font-bold text-grey-900"
                      onClick={() => setCurrentMenuItem(item)}
                    >
                      <span>{item.title}</span>
                      <div className="flex-center p-3 bg-grey-200 rounded">
                        <ReactSVG
                          src={`${lc_data.dir}dist/${PlusIcon}`}
                          className="relative w-12 h-12 fill-grey-1100 pointer-events-none"
                        />
                      </div>
                    </button>
                    }
                    {!hasChild &&
                    <a
                      href={item.permalink}
                      id={`menu-item-${item.ID}`}
                      className="w-full font-bold text-grey-900" dangerouslySetInnerHTML={{ __html: item.title }}/>
                    }
                  </li>
                );
              })}
            </ul>
          </Scrollbars>

        </nav>
        <div className="mobile-menu--bottom mt-auto" ref={menuBottom}>
          <a
            href={lc_data.submit_ad_link}
            className="flex justify-between items-center mb-20 py-3 px-10 w-full bg-transparent rounded border-3 border-blue-700 shadow-theme font-bold text-white"
          >
            <div className="flex flex-col text-left text-blue-700">
              <span className="text-sm">{lc_data.jst[105]}</span>
              <span className="font-bold text-xl">{lc_data.jst[106]}</span>
            </div>
            <ReactSVG
              src={`${lc_data.dir}dist/${BoltIcon}`}
              className="w-20 h-20 fill-blue-700"
            />
          </a>

          {socials && !isEmpty(socials) &&
          <div className="menu-mobile--socials flex flex-wrap mt-20 mb-20">
            <p className="mb-10 w-full">{options.social_text ? options.social_text : lc_data.jst[460]}</p>
            {map(socials, (social, name) => {
              return (
                <a
                  key={name}
                  href={social.url}
                  target="_blank"
                  rel="nofollow"
                  className="flex-center mt-6 mr-8 p-4 border-2 rounded-full"
                  title={name}
                  dangerouslySetInnerHTML={{
                    __html: social.icon,
                  }}
                />
              );
            })}
          </div>
          }
        </div>
      </div>
      }
    </Fragment>
  );

};

export default MenuMobile;
