/* global lc_data, React */
/**
 * External dependencies.
 */
import { Fragment } from '@wordpress/element';
import ReactSVG from 'react-svg';
import homeIcon from '../../../../../../images/icons/home.svg';
import he from 'he';

/**
 * Internal dependencies
 */

function Breadcrumb(props) {
  const { product, title, options } = props;

  return (
    <Fragment>
      <nav className="search--breadcrumb">
        <ul className="flex items-center -mx-4">
          <li className="flex items-center px-2 text-grey-500">
            <ReactSVG
              src={`${lc_data.dir}dist/${homeIcon}`}
              className="relative mr-8 w-16 h-16 fill-icon-home"
            />
            <a href={options.site_url || '/'} className="font-semibold text-grey-500">{lc_data.jst[467]}</a>
            <span className="ml-4">/</span>
          </li>
          <li className="px-4 text-grey-900">
            <span
              className="font-semibold">{he.decode(title)}</span>
          </li>
        </ul>
      </nav>
    </Fragment>
  );
}

export default Breadcrumb;
