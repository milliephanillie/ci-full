/* global lc_data, React */
/**
 * Dependencies.
 */
import { useEffect, useState } from '@wordpress/element';
import axios from 'axios';
import { Fragment } from 'react';
import Breadcrumb from './partials/Breadcrumb';
import Menu from './partials/Menu';
import { map, isEmpty } from 'lodash';
import MenuTop from './partials/MenuTop';
import MenuTopSticky from './partials/MenuTopSticky';

const PageTips = (props) => {
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(null);
  const [tips, setTips] = useState(null);
  const [menuType, setMenuType] = useState('side');

  const showMenu = () => {
    if (window.innerWidth > 1620) {
      setMenuType('side')
    } else {
      setMenuType('top')
    }
  }

  useEffect(() => {
    window.addEventListener('resize', showMenu);

    showMenu();

    return () => window.removeEventListener('resize', showMenu);
  })

  useEffect(() => {
    const data = {};
    const headers = { 'X-WP-Nonce': lc_data.nonce };
    axios({
      url: lc_data.get_tips,
      method: 'GET',
      credentials: 'same-origin',
      headers,
      data,
    }).then(response => {
      setPage(response.data.page);
      setTips(response.data.page.tips);
      setLoading(false);
      const oldLoader = document.getElementById('loader');
      if (oldLoader) {
        oldLoader.classList.add('fade-out');
        setTimeout(() => {
          oldLoader.remove();
        }, 200);
      }
    });
  }, []);

  return (
    <Fragment>
      {!loading &&
      <div className="container py-40">
        <div className="page-title mb-36 sm:mb-60 px-10 xs:px-20 sm:px-60 bg:px-0">
          <h1 className="mb-10">{page.title}</h1>
          <Breadcrumb title={page.title}/>
        </div>
        {!loading && isEmpty(tips) &&
        <div
          className="flex items-center  mt-20 mb-40 font-semibold text-xl text-grey-500">{lc_data.jst[573]}</div>
        }

        <div className="relative container flex flex-wrap px-10 xs:px-20 sm:px-60 bg:px-0">
          {menuType === 'side' && !isEmpty(tips) &&
          <aside className="menu__aside flex fixed justify-end">
            <Menu tips={tips}/>
          </aside>
          }
          {menuType === 'top' && !isEmpty(tips) &&
          <aside className="flex mt-5 mb-36 w-full">
            <MenuTop tips={tips}/>
          </aside>
          }
          {menuType === 'top' &&
          <aside
            className={`product--menu-sticky flex fixed left-0 container px-0 z-10 ${lc_data.logged_in ? 'mt-0 sm:mt-30 sm:top-2' : 'top-0 mt-0'}`}
            style={{
              top: lc_data.logged_in ? '44px' : '0',
              zIndex: 999,
            }}
          >
            <MenuTopSticky tips={tips}/>
          </aside>
          }

          <div className="page-tips--content relative flex flex-col ml-auto">
            {tips && map(tips, (tip, index) => {
              return (
                <article key={index} id={tip.category} className="mb-60">
                  <h3 className="mb-30 font-bold">{tip.name}</h3>
                  {tip.tips &&
                  <ol className="list-decimal pl-col">
                    {map(tip.tips, (t, count) => {
                      return (
                        <li key={count} className="mb-14 font-light text-lg leading-relaxed">{t}</li>
                      )
                    })}
                  </ol>
                  }
                </article>
              )
            })}
          </div>
        </div>

      </div>
      }
    </Fragment>
  )

}

export default PageTips;
