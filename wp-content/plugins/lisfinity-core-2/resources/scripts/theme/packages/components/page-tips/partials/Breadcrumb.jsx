/* global lc_data, React */
/**
 * External dependencies.
 */
import { Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import homeIcon from '../../../../../../images/icons/home.svg';

/**
 * Internal dependencies
 */

function Breadcrumb(props) {
  const { product, title } = props;

  return (
    <Fragment>
      <nav className="search--breadcrumb">
        <ul className="flex items-center -mx-4">
          <li className="flex px-2">
            <ReactSVG
              src={`${lc_data.dir}dist/${homeIcon}`}
              className="relative mr-8 w-16 h-16 fill-icon-home"
            />
            <a href={lc_data.site_url} className="text-grey-900">{lc_data.jst[467]}</a>
          </li>
          <li className="px-4">
            <span className="mr-4">/</span>
            <span
              className="font-semibold text-grey-1100">{title}</span>
          </li>
        </ul>
      </nav>
    </Fragment>
  );
}

export default Breadcrumb;
