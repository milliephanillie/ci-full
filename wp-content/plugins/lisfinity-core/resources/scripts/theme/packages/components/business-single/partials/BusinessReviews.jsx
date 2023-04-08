/* global lc_data, React */

import { map } from 'lodash';
import ReactSVG from 'react-svg';
import starIcon from '../../../../../../images/icons/star.svg';
import axios from 'axios';
import { useState, useEffect } from '@wordpress/element';
import Pagination from '../../partials/TestimonialsPagination';
import QuoteIcon from '../../../../../../images/icons/quotation.svg';
import LoaderBusinessReviews from '../../business-archive/LoaderBusinessReviews';

const BusinessReviews = (props) => {
  const { business } = props;
  const [loading, setLoading] = useState(true);
  const [allData, setAllData] = useState(false);
  const [testimonials, setTestimonials] = useState(false);
  const [page, setPage] = useState(1);

  useEffect(() => {
    setLoading(true);
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    let url = `${lc_data.get_reviews}/${business.premium_profile.ID}`;
    if (page !== 1) {
      url = `${url}?offset=${page}`;
    }
    axios({
      credentials: 'same-origin',
      headers,
      method: 'get',
      url,
    }).then(data => {
      setTestimonials(data.data.comments);
      setAllData(data.data);
      setLoading(false);
    });
  }, [page]);

  return (
    <section className="testimonials pt-30 pb-86">
      <div className="container">
        {loading && <LoaderBusinessReviews/>}
        {!loading &&
        <div className="row -mb-30">
          {map(testimonials, testimonial => {
            return (
              <div key={testimonial.comment_ID} className="mb-30 px-col w-full sm:w-1/2 bg:w-1/3">
                <article className="flex flex-col p-30 bg-white rounded shadow-theme">

                  <div
                    className="testimonial--content relative flex font-light italic text-lg text-grey-1000 leading-relaxed">
                    <ReactSVG src={`${lc_data.dir}dist/${QuoteIcon}`}
                              className="absolute top-0 w-24 h-24 fill-grey-500"/>
                    <div style={{ textIndent: '34px' }}>{testimonial.comment_content}</div>
                  </div>

                  <div className="testimonial--author flex justify-between items-center mt-20">

                    <div className="flex items-center">
                      {testimonial.thumbnail &&
                      <figure
                        className="relative mr-10 border-2 border-grey-200 rounded-full overflow-hidden"
                        style={{ width: '40px', height: '40px' }}
                      >
                        <img src={testimonial.thumbnail} alt={testimonial.title}
                             className="absolute top-0 left-0 w-full h-full object-cover"/>
                      </figure>
                      }
                      <div className="testimonial--author flex flex-col">
                        <span className="text-sm text-grey-500 leading-none">{testimonial.date_year}</span>
                        <span>{testimonial.title}</span>
                      </div>
                    </div>

                    <div className="lisfinity-product--info flex-center">
                      <span className="flex-center min-w-32 h-32 rounded-full bg-yellow-300">
                      <ReactSVG
                        src={`${lc_data.dir}dist/${starIcon}`}
                        className="w-14 h-14 fill-product-star-icon"
                      />
                      </span>
                      <span className="ml-6 text-sm text-grey-500">{testimonial.rating}</span>
                    </div>

                  </div>

                </article>
              </div>
            );
          })}
        </div>
        }

        {!loading &&
        <div>
          <Pagination results={allData} handlePagination={(offset) => setPage(offset)}/>
        </div>
        }

      </div>
    </section>
  );
};

export default BusinessReviews;
