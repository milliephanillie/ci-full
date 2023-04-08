/* global lc_data, React */

import { map } from 'lodash';
import { sprintf } from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import starIcon from '../../../../../images/icons/star.svg';
import mapMarkerIcon from '../../../../../images/icons/map-marker.svg';
import { Fragment } from 'react';
import LoaderBusinessArchiveQuery from './LoaderBusinessArchiveQuery';

const BusinessArchiveQuery = (props) => {
  const { data, options, type } = props;

  return (
    <Fragment>
      {props.loading && <LoaderBusinessArchiveQuery promoted={type === 'premium'}/>}
      {!props.loading &&
      <section className="vendors">
        <div className="flex vendors--title items-baseline">
          {
            type === 'premium' &&
            <h5>{lc_data.jst[561]}</h5>
          }
          {
            type === 'default' &&
            <Fragment>
              <h5 className="mr-10 leading-none">{lc_data.jst[562]}</h5>
              <span className="text-sm text-grey-500">{sprintf(lc_data.jst[563], data.found_posts)}</span>
            </Fragment>
          }
        </div>

        {data.query &&
        <div className="vendors--query flex flex-wrap mt-20 mb-10 -mx-col">
          {map(data.query, (vendor, id) => {
            return (
              <div key={id} className="mb-20 px-col w-full sm:w-1/2 lg:w-1/3">
                <article
                  className="vendor flex flex-wrap sm:flex-no-wrap items-center p-20 bg-white rounded shadow-theme">
                  {vendor?.thumbnail &&
                  <figure
                    className="profile--thumbnail flex-center mr-20 p-10 border-grey-100 rounded-2xl overflow-hidden bg:mb-20 lg:mb-0"
                    style={{ height: '84px', width: '90px', borderWidth: '6px' }}
                  >
                    <a href={vendor.url}>
                      <img src={vendor.thumbnail} alt={vendor.title}/>
                    </a>
                  </figure>
                  }

                  <div className="vendor--content">
                    <h6 className="text-lg">
                      <a href={vendor.url}>
                        {vendor.title}
                      </a>
                    </h6>

                    <div className="flex items-center mt-10">
                      <div className="lisfinity-product--info flex-center mr-22">
                          <span className="flex-center min-w-32 h-32 rounded-full bg-yellow-300">
                           <ReactSVG
                             src={`${lc_data.dir}dist/${starIcon}`}
                             className="w-14 h-14 fill-product-star-icon"
                           />
                          </span>
                        <span className="ml-6 text-sm text-grey-600">{vendor.rating}</span>
                      </div>

                      {vendor.location.address &&
                      <div className="lisfinity-product--info flex-center">
                        <span className="flex-center min-w-32 h-32 rounded-full bg-cyan-300">
                          <ReactSVG
                            src={`${lc_data.dir}dist/${mapMarkerIcon}`}
                            className="w-14 h-14 fill-product-place-icon"
                          />
                        </span>
                        <span className="ml-6 text-sm text-grey-600">{vendor.location.address}</span>
                      </div>
                      }
                    </div>
                  </div>

                </article>
              </div>
            );
          })}
        </div>
        }
      </section>
      }
    </Fragment>
  );
};

export default BusinessArchiveQuery;
