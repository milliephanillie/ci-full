/* global lc_data, React */
/**
 * Dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import { useState, useEffect, useRef } from '@wordpress/element';
import { sprintf, __ } from '@wordpress/i18n';
import cx from 'classnames';
import ReactSVG from 'react-svg';
import ProductPrice from './ProductPrice';
import HammerIcon from '../../../../../../../images/icons/construction-hammer.svg';
import EnvelopeIcon from '../../../../../../../images/icons/envelope.svg';
import BaloonIcon from '../../../../../../../images/icons/baloon.svg';
import EyeIcon from '../../../../../../../images/icons/eye.svg';
import ArrowRightIcon from '../../../../../../../images/icons/arrow-right.svg';
import PencilIcon from '../../../../../../../images/icons/pencil.svg';
import TrashIcon from '../../../../../../../images/icons/trash.svg';
import { NavLink } from 'react-router-dom';
import { Fragment } from 'react';
import he from 'he';
import * as actions from '../../../../store/actions';

const Product = (props) => {
  const { product } = props;
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const { business, menuOpen, options } = data;
  const productBox = useRef(null);
  const [figureHeight, setFigureHeight] = useState(150);
  const [smallScreen, setSmallScreen] = useState(false);

  const timelineClass = cx({
    'bg-green-500': product.percentage < 40,
    'bg-yellow-500': product.percentage >= 40 && product.percentage < 80,
    'bg-red-500': product.percentage >= 80,
  });

  const smallScreenCalc = () => {
    if (window.innerWidth < 780) {
      setSmallScreen(true);
    } else {
      setSmallScreen(false);
    }
  };

  useEffect(() => {
    const figureHeightSize = () => {
      const rect = productBox.current.getBoundingClientRect();
      if (window.innerWidth > 1030) {
        setFigureHeight(rect.height);
      } else {
        setFigureHeight(180);
      }
    };
    smallScreenCalc();
    figureHeightSize();
    window.addEventListener('resize', smallScreenCalc);
    window.addEventListener('resize', figureHeightSize);

    return () => {
      removeEventListener('resize', smallScreenCalc);
      removeEventListener('resize', figureHeightSize);
    };
  }, []);

  const productClass = cx({
    'opacity-50': !product.is_active || product.status === 'sold',
  });

  return (
    <div className={`product flex flex-wrap mb-20 bg-white rounded shadow-theme overflow-auto ${productClass}`}
         ref={productBox}>

      {(product.thumbnail || options?.fallback_image) &&
      <figure className="product--figure relative mr-0" style={{ height: `${figureHeight}px` }}>
        <NavLink to={`${lc_data.site_url}${lc_data.myaccount}ad/${product.id}`}>
          <img
            src={product.thumbnail ? product.thumbnail : options?.fallback_image}
            alt={product.title}
            className="absolute top-0 left-0 w-full h-full object-cover"
          />
        </NavLink>
      </figure>
      }

      {product.title &&
      <div className="relative product--title flex flex-col justify-center py-20 px-30">

        {(product.status === 'pending' || product.status === 'sold' || product.status === 'trash') &&
        <div className={`product--status mb-6 ${product.status}`}>
          {product.status === 'pending' && <span
            className="py-2 px-12 bg-red-500 rounded text-sm text-white"
            style={{ fontSize: '11px' }}
          >{lc_data.jst[223]}</span>}
          {product.status === 'sold' && <span
            style={{ fontSize: '11px' }}
            className="py-2 px-12 bg-red-500 rounded text-sm text-white">{lc_data.jst[224]}</span>}
          {product.status === 'trash' && <span
            style={{ fontSize: '11px' }}
            className="py-2 px-12 bg-yellow-500 rounded text-sm text-white">{lc_data.jst[695]}</span>}
        </div>
        }

        <h6 className="font-bold">
          <NavLink
            to={`${lc_data.site_url}${lc_data.myaccount}ad/${product.id}`}><span>{he.decode(product?.title)}</span></NavLink>
        </h6>

        <div className="flex flex-wrap mt-10 text-sm text-grey-500 whitespace-nowrap">
          {lc_data.jst[170]}
          <span
            className="ml-3 font-semibold text-grey-1100">{sprintf(lc_data.jst[153], product.submitted_human)}</span>
          <span className="mx-3">-</span>
          {product.is_active
            ?
            <Fragment>
              {lc_data.jst[171]}
              <span className="ml-3 font-semibold text-grey-1100">{product.expires_human}</span>
            </Fragment>
            :
            <Fragment>
              {lc_data.jst[172]}
              <span className="ml-3 font-semibold text-grey-1100">{product.expires_date}</span>
            </Fragment>
          }
        </div>

        <div className="timeline relative mt-8 w-full h-3 bg-grey-100 rounded overflow-hidden">
          <div
            className={`timeline--line absolute top-0 left-0 h-3 ${timelineClass}`}
            style={{ width: `${product.percentage}%` }}
          ></div>
        </div>

        {!!smallScreen &&
        <a href={product.permalink} className="flex mt-20 text-sm text-grey-500">
          {lc_data.jst[222]}
          <ReactSVG
            src={`${lc_data.dir}dist/${ArrowRightIcon}`}
            className="ml-4 w-16 h-16 fill-grey-700"
          />
        </a>
        }

      </div>
      }

      {!smallScreen &&
      <Fragment>
        <div className="product--price-table flex items-center py-20 px-30 w-2/16">
          <ProductPrice product={product}/>
        </div>

        {product.agent &&
        <div className="product--agent flex items-center py-20 px-30 w-3/16">
          <figure className="relative flex min-w-40 h-40 border-4 border-grey-100 rounded-full overflow-hidden">
            <img src={product.agent.avatar} alt={product.agent.display_name}
                 className="absolute top-0 left-0 w-full h-full object-cover"/>
          </figure>
          <p className="flex ml-14 font-semibold text-grey-900">
            {product.agent.first_name && product.agent.last_name
              ?
              sprintf('%s %s', product.agent.first_name, product.agent.last_name)
              :
              product.agent.display_name
            }
          </p>
        </div>
        }

        <div className="product--notifications flex items-center py-20 px-30 w-4/16">
          {
            (options && options.disable_bidding && !options.site_hide_bidding && product.bids) &&
            <NavLink to={`${lc_data.site_url}${lc_data.myaccount}ad/${product.id}/bids`}
                     className="flex flex-col py-8 px-10 w-1/3 bg-grey-100 rounded">
              <div className="flex items-center">
                <ReactSVG
                  src={`${lc_data.dir}dist/${HammerIcon}`}
                  className="mr-6 w-14 h-14 fill-grey-500"
                />
                <span className="font-semibold text-grey-900">{Object.keys(product.bids).length}</span>
              </div>
              <span className="text-sm text-grey-500 leading-none">{lc_data.jst[131]}</span>
            </NavLink>}
          {options && options.messenger && product.messages &&
          <NavLink to={`${lc_data.site_url}${lc_data.myaccount}ad/${product.id}/messages`}
                   className="flex flex-col ml-3 py-8 px-10 w-1/3 bg-grey-100 rounded">
            <div className="flex items-center">
              <ReactSVG
                src={`${lc_data.dir}dist/${EnvelopeIcon}`}
                className="mr-6 w-14 h-14 fill-grey-500"
              />
              <span className="font-semibold text-grey-900">{Object.keys(product.messages).length}</span>
            </div>
            <span className="text-sm text-grey-500 leading-none">{lc_data.jst[132]}</span>
          </NavLink>
          }
          {options && options.promotions && product.promotions &&
          <NavLink to={`${lc_data.site_url}${lc_data.myaccount}ad/${product.id}/promotions`}
                   className="flex flex-col ml-3 py-8 px-10 w-1/3 bg-grey-100 rounded">
            <div className="flex items-center">
              <ReactSVG
                src={`${lc_data.dir}dist/${BaloonIcon}`}
                className="mr-6 w-14 h-14 fill-grey-500"
              />
              <span className="font-semibold text-grey-900">{Object.keys(product.promotions).length}</span>
            </div>
            <span className="text-sm text-grey-500 leading-none">{lc_data.jst[177]}</span>
          </NavLink>
          }
        </div>

        <div className="product--actions relative flex items-center py-20 px-30 w-1/16">

          <a href={product.permalink} className="absolute top-0 lg:top-20 right-60 flex-center text-sm text-grey-500">
            {lc_data.jst[222]}
            <ReactSVG
              src={`${lc_data.dir}dist/${ArrowRightIcon}`}
              className="ml-4 w-16 h-16 fill-grey-700"
            />
          </a>

          <NavLink
            title={lc_data.jst[764]}
            to={`${lc_data.site_url}${lc_data.myaccount}ad/${product.id}`}
            className="flex-center w-1/3 md:w-40 md:min-w-40 h-40 bg-yellow-300 rounded md:rounded-full">
            <ReactSVG
              src={`${lc_data.dir}dist/${EyeIcon}`}
              className="w-16 h-16 fill-grey-900"
            />
          </NavLink>

          <NavLink
            title={lc_data.jst[164]}
            to={`${lc_data.site_url}${lc_data.myaccount}edit/${product.id}`}
            className="flex-center ml-10 w-1/3 md:w-40 md:min-w-40 h-40 bg-green-300 rounded md:rounded-full">
            <ReactSVG
              src={`${lc_data.dir}dist/${PencilIcon}`}
              className="w-16 h-16 fill-grey-900"
            />
          </NavLink>

          <button
            id={`delete-${product.id}`}
            title={lc_data.jst[168]}
            onClick={props.deleteProduct}
            className="flex-center w-1/3 md:w-40 md:min-w-40 ml-10 h-40 bg-red-200 rounded md:rounded-full">
            <ReactSVG
              src={`${lc_data.dir}dist/${TrashIcon}`}
              className="w-16 h-16 fill-grey-900"
            />
          </button>

        </div>
      </Fragment>
      }

    </div>
  );
};

export default Product;
