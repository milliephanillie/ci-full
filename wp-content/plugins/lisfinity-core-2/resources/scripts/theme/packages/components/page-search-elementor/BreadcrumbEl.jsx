/* global lc_data, React */
/**
 * External dependencies.
 */
import { useEffect, useRef, useState } from '@wordpress/element';
import { useDispatch, useSelector } from 'react-redux';
import { sprintf } from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import homeIcon from '../../../../../images/icons/home.svg';
import { isEmpty } from 'lodash';

const BreadcrumbEl = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const { searchData } = data;
  const { options } = searchData;
  const [items, setItems] = useState({});
  const [mobile, setMobile] = useState(false);
  const [settings, setSettings] = useState({});
  const wrapper = useRef(null);

  useEffect(() => {
    window.addEventListener('resize', showMenu);

    showMenu();

    return () => window.addEventListener('resize', showMenu);
  }, []);

  useEffect(() => {
    const el = wrapper.current && wrapper.current.closest('.page-search-breadcrumbs');

    if (el) {
      const settingsData = JSON.parse(el.dataset.options);
      setSettings(settingsData);
    }
  }, [data]);

  useEffect(() => {
    setItems(data.postsByUrl.RECEIVE_POSTS?.items || {});
  }, [data.postsByUrl]);

  const showMenu = () => {
    if (window.innerWidth <= 640) {
      setMobile(true);
    } else {
      setMobile(false);
    }
  };

  return (
    <div className="flex flex-wrap sm:justify-between items-center" ref={wrapper}>
      <nav className="search--breadcrumb">
        <ul className="flex items-center -mx-4">
          <li className="hidden sm:flex items-center px-2 whitespace-no-wrap breadcrumb__home">
            {(!settings?.home_icon || settings?.home_icon.value === '') &&
            <ReactSVG
              src={`${lc_data.dir}dist/${homeIcon}`}
              className="injectable mr-8 w-16 h-16 fill-icon-home"
            />
            }
            {settings?.home_icon?.value && settings?.home_icon?.library !== 'svg' &&
            <i className={`mr-8 ${settings.home_icon.value} fill-icon-home`}></i>
            }
            {settings?.home_icon?.value.url && settings?.home_icon?.library === 'svg' &&
            <ReactSVG
              src={`${settings.home_icon.value.url}`}
              className={`mr-8 w-16 h-16 fill-icon-home`}
            />
            }
            <a href={lc_data.site_url} className="text-grey-900">{lc_data.jst[467]}</a>
          </li>
          {
            items &&
            <li className="flex px-4 whitespace-no-wrap breadcrumb__home">
              <span className="hidden sm:block mr-4">/</span>
              {mobile &&
              <span
                className="font-semibold text-grey-1100">{items.found_posts}</span>
              }
              {!mobile &&
              <span
                className="font-semibold text-grey-1100">{sprintf(lc_data.jst[476], items.found_posts)}</span>
              }
            </li>
          }
          {options?.has_groups && data['category-type'] &&
          <li className="-ml-2 px-4 whitespace-no-wrap breadcrumb__home">
            <span className="mr-4 text-grey-700">{mobile ? '/' : lc_data.jst[399]}</span>
            <span
              className="inline-block font-semibold text-grey-1100 capitalize"
            >
                  {
                    data['category-type'] === 'common'
                      ? lc_data.jst[400]
                      : decodeURIComponent(data['category-type'].replace(/-/, ' '))
                  }
                </span>
          </li>
          }
        </ul>
      </nav>
      {
        items && items.max_num_pages && items.max_num_pages > 1 &&
        <div
          className="page--information self-start ml-auto font-semibold text-grey-700"
          dangerouslySetInnerHTML={{
            __html: mobile ? sprintf(lc_data.jst[630], items.page, items.max_num_pages) : sprintf(lc_data.jst[551], items.page, items.max_num_pages),
          }}
        >
        </div>
      }
    </div>
  );
};

export default BreadcrumbEl;
