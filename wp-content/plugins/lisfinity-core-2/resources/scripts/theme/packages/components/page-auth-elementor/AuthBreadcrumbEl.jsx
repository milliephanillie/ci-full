/* global lc_data, React */
/**
 * External dependencies.
 */
import {Fragment, useEffect, useRef, useState} from '@wordpress/element';
import { useDispatch, useSelector } from 'react-redux';
import ReactSVG from 'react-svg';
import homeIcon from '../../../../../images/icons/home.svg';
import queryString from 'query-string';
import he from "he";

const AuthBreadcrumbEl = (props) => {
  const data = useSelector(state => state);
  const [options, setOptions] = useState(false);
  const wrapper = useRef(null);
  const params = queryString.parse(location.search);
  const [settings, setSettings] = useState({});
  const [loading, setLoading] = useState(true);

  const fetchOptions = () => {
    setLoading(true);
    fetch(`${lc_data.auth_options}/?page=${lc_data.page_id}`).then(json => json.json()).then(options => {
      setOptions(options);
      setLoading(false);
    });
  };

  useEffect(() => {
    fetchOptions();
  }, []);

  useEffect(() => {
    const el = wrapper.current && wrapper.current.closest('.elementor-auth-breadcrumbs');

    if (el) {
      const settingsData = JSON.parse(el.dataset.settings);
      setSettings(settingsData);
    }
  }, [data]);


  return (
    <Fragment>
      <nav className="search--breadcrumb flex" ref={wrapper}>
        <ul className="flex items-center -mx-4">
          <li className="flex items-center px-2 text-grey-500">
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
            <a href={options.site_url || '/'} className="font-semibold text-grey-500">{lc_data.jst[467]}</a>
            <span className="ml-4">/</span>
          </li>
          <li className="px-4 text-grey-900 active-link">
            <span
              className="font-semibold">{options.title}</span>
          </li>
        </ul>
      </nav>
    </Fragment>
  );
};

export default AuthBreadcrumbEl;
