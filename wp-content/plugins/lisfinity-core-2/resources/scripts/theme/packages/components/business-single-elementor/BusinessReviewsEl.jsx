/* global lc_data, React */

import {map} from 'lodash';
import ReactSVG from 'react-svg';
import starIcon from '../../../../../images/icons/star.svg';
import axios from 'axios';
import {useState, useEffect, createRef} from '@wordpress/element';
import Pagination from '../../components/partials/TestimonialsPagination';
import QuoteIcon from '../../../../../images/icons/quotation.svg';
import LoaderBusinessReviews from '../business-archive/LoaderBusinessReviews';

const BusinessReviewsEl = () => {
  const [loading, setLoading] = useState(true);
  const [allData, setAllData] = useState(false);
  const [testimonials, setTestimonials] = useState(false);
  const [page, setPage] = useState(1);
  const [options, setOptions] = useState({});
  let wrapper = null;
  let el = createRef();

  useEffect(() => {
    wrapper = el.current.closest('.elementor-business-reviews');
    if (wrapper && wrapper.dataset.settings) {
      const settings = JSON.parse(wrapper.dataset.settings);
      setOptions(settings);
    }
  }, []);

  useEffect(() => {
    setLoading(true);
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    let url = `${lc_data.get_reviews}/${lc_data.current_business_id}`;
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
    <section className="testimonials pt-30 pb-86" ref={el}>
      <div className="container">
        {loading && <LoaderBusinessReviews/>}
        {!loading &&
        <div className="row -mb-30">
          {map(testimonials, testimonial => {
            return (
              <div key={testimonial.comment_ID} className="mb-30 px-col w-full sm:w-1/2 bg:w-1/3">
                <article className="flex flex-col p-30 bg-white rounded shadow-theme testimonial">

                  <div
                    className="testimonial--content relative flex font-light italic text-lg text-grey-1000 leading-relaxed">
                    {!options?.selected_icon?.value &&
                    <ReactSVG src={`${lc_data.dir}dist/${QuoteIcon}`}
                              className="absolute testimonial-icon top-0 w-24 h-24 fill-grey-500"/>}
                    {options?.selected_icon?.value && options?.selected_icon?.library !== 'svg' &&
                    <i
                      className={`absolute top-0 testimonial-icon w-24 h-24 fill-grey-500 ${options.selected_icon.value}`}></i>
                    }
                    {options?.selected_icon?.value?.url && options?.selected_icon?.library === 'svg' &&
                    <ReactSVG
                      src={`${options.selected_icon.value.url}`}
                      className={`absolute testimonial-icon top-0 w-24 h-24 fill-grey-500`}
                    />
                    }
                    <div className="testimonial--text" style={{textIndent: '34px'}}>{testimonial.comment_content}</div>
                  </div>

                  <div className="testimonial--author testimonial--author-wrapper flex justify-between items-center mt-20">

                    <div className="flex items-center">
                      {testimonial.thumbnail &&
                      <figure
                        className="relative mr-10 border-2 border-grey-200 testimonial--author-img rounded-full overflow-hidden"
                      >
                        <img src={testimonial.thumbnail} alt={testimonial.title}
                             className="absolute top-0 left-0 w-full h-full object-cover"/>
                      </figure>
                      }
                      <div className="testimonial--author testimonial--author-content relative flex flex-col">
                        <span
                          className="text-sm text-grey-500 testimonial--author-year leading-none">{testimonial.date_year}</span>
                        <span className="testimonial--author-name">{testimonial.title}</span>
                      </div>
                    </div>

                    <div className="lisfinity-product--info relative flex-center">
                      <span className="flex-center min-w-32 h-32 rounded-full bg-yellow-300">
                        {!options?.selected_icon_footer?.value &&
                        <ReactSVG
                          src={`${lc_data.dir}dist/${starIcon}`}
                          className="w-14 h-14 fill-product-star-icon testimonial-icon-footer"
                        />}
                        {options?.selected_icon_footer?.value && options?.selected_icon_footer?.library !== 'svg' &&
                          <i className={`testimonial-icon-footer w-14 h-14 fill-grey-500 ${options.selected_icon_footer.value}`}></i>
                        }
                        {options?.selected_icon_footer?.value?.url && options?.selected_icon_footer?.library === 'svg' &&
                          <ReactSVG
                          src={`${options.selected_icon_footer.value.url}`}
                          className={`testimonial-icon-footer w-14 h-14 fill-grey-500`}
                          />
                        }
                          </span>
                          <span className="ml-6 text-sm lisfinity-product--info-text text-grey-500">{testimonial.rating}</span>
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

                          export default BusinessReviewsEl;
