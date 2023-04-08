/**
 * Dependencies.
 */
import { useEffect, useState } from '@wordpress/element';
import { map, filter, sortBy } from 'lodash';
import dragula from 'react-dragula';
import autoScroll from 'dom-autoscroller';
import ReactSVG from 'react-svg';
import MoveIcon from '../../../../images/icons/move.svg';
import PencilIcon from '../../../../images/icons/pencil.svg';
import PlusIcon from '../../../../images/icons/plus.svg';
import QuestionIcon from '../../../../images/icons/question-circle.svg';
import ReactTooltip from 'react-tooltip';
import { Fragment } from 'react';
import LoaderIcon from '../../../../images/icons/loader-rings.svg';
import StarIcon from '../../../../images/icons/star-filled.svg';
import StarEmptyIcon from '../../../../images/icons/star.svg';
import { useDispatch, useSelector } from 'react-redux';

let containers = [];
let drake;
const dragulaOptions = {
  accepts: (el, target) => {
    if (el.dataset.taxonomy !== target.dataset.taxonomy) {
      return false;
    }

    return true;
  },
};

/**
 * Terms class
 * -----------
 */
const Terms = (props) => {
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  const [loading, setLoading] = useState(false);
  const [childTerms, setChildTerms] = useState([]);
  const [loadedOnce, setLoadedOnce] = useState(false);
  const [termHeight, setTermHeight] = useState('100%');
  const [termMaxHeight, setTermMaxHeight] = useState('100%');
  const [clicked, setClicked] = useState(false);
  const [parent, setParent] = useState(props.termParent);
  const [terms, setTerms] = useState(props.terms);
  const [maxPages, setMaxPages] = useState(0);
  const [page, setPage] = useState(0);
  const [termsLimit, setTermsLimit] = useState(data.themeOptions.terms_limit);

  const {
    taxonomyToEdit,
    termParent,
    taxonomySlug,
    handleTermParentChange,
    termLoadedOnce,
    changingParent,
  } = props;

  const setTermsHeight = () => {
    const screen = window.innerHeight;
    if (termsLimit < 20) {
      setTermHeight(termsLimit * 50);
    } else {
      setTermHeight((screen / 100) * 50);
    }
    setTermMaxHeight((screen / 100) * 50);
  };

  useEffect(() => {
    if (data.themeOptions.hide_child_terms) {
      const newTerms = filter(props.terms, term => term.parent === 0);
      setMaxPages(Math.ceil(newTerms.length / termsLimit));
      setTerms(newTerms);
    } else {
      setMaxPages(Math.ceil(props.terms.length / termsLimit));
      setTerms(sortBy(props.terms, term => term.parent !== 0 || term.parent !== termParent).splice(0, termsLimit));
    }
  }, [props.terms]);

  useEffect(() => {
    if (termParent) {
      const newTerms = filter(props.terms, term => term.parent === 0 || term.parent === termParent);
      setMaxPages(Math.ceil(newTerms.length / termsLimit));
      setTerms(newTerms.splice(page * termsLimit, termsLimit).splice(0, termsLimit));
    } else {
      setPage(0);
      setMaxPages(Math.ceil(props.terms.length / termsLimit));
      //setTerms(sortBy(props.terms, term => term.parent !== termParent));
      setTerms(filter(props.terms, term => term.parent === 0).splice(0, termsLimit));
    }
  }, [termParent]);

  useEffect(() => {
    setTermsHeight();

    document.addEventListener('resize', setTermsHeight);

    drake = dragula(containers, dragulaOptions);
    setLoadedOnce(true);
    const scroll = autoScroll(
      containers,
      {
        margin: 5,
        maxSpeed: 5,
        scrollWhenOutside: true,
        autoScroll: function () {
          return this.down && drake.dragging;
        }
      });

    updateOrder();

    return () => document.removeEventListener('resize', setTermsHeight);

  }, []);

  useEffect(() => {
    if (props.termParent !== parent) {
      setParent(props.termParent);
    }
  });

  useEffect(() => {
    if (termParent) {
      setTerms(filter(props.terms, term => term.parent === 0 || term.parent === termParent).splice(page * termsLimit, termsLimit));
    } else {
      setTerms(sortBy(props.terms, term => term.parent !== 0 || term.parent !== termParent).splice(page * termsLimit, termsLimit));
    }
  }, [page]);

  /**
   * Update the order of the terms
   * -----------------------------
   */
  const updateOrder = () => {
    let order = [];
    drake.on('cancel', (el, container, source) => {
      order = [];
    });
    drake.on('drag', (el, source) => {
      map(source.childNodes, item => {
        if (item.className !== undefined && item.className.includes('term')) {
          order.push(item.dataset.order);
        }
      });
    });
    drake.on('drop', (el, target, source, sibling) => {
      setLoading(true);
      const elementsOrder = [];
      let count = 0;
      map(target.childNodes, (item, index) => {
        if (item.className !== undefined && item.className.includes('term')) {
          elementsOrder.push(item.dataset.term);

          item.dataset.order = order[count];
          count++;
        }
      });

      const headers = new Headers();
      const formData = new FormData();
      headers.append('X-WP-Nonce', lc_data.nonce);
      formData.append('action', 'update_order');
      formData.append('terms', elementsOrder);
      formData.append('positions', order);
      fetch(lc_data.terms_edit, {
        method: 'POST',
        credentials: 'same-origin',
        headers,
        body: formData,
      }).then(response => response.json().then(json => {
        setLoading(false);
      }));
      order = [];
    });
  };

  const dragulaDecorator = (componentBackingInstance) => {
    if (componentBackingInstance) {
      containers.push(componentBackingInstance);
    }
  };

  const pagination = () => {
    let pagination = [];
    if (maxPages > 1) {
      for (let i = 1; i <= maxPages; i += 1) {
        pagination.push(<button key={i} type="button"
                                className={`terms--pagination-item flex justify-center items-center h-20 rounded mt-2 mx-1 text-sm ${i === (page + 1) || ((page + 1) === 0 && i === 1) ? 'bg-blue-700 text-white' : 'bg-grey-100'}`}
                                style={{
                                  width: '9.15%',
                                }}
                                onClick={() => setPage(i - 1)}>{i}</button>);
      }
    }

    return pagination;
  };

  return (
    props.terms &&
    <div className="terms-wrapper">

      {props.taxonomy.type !== 'input' ?
        <button
          type="button"
          className="term--edit flex items-center mb-20 font-bold text-blue-700"
          data-taxonomy-slug={taxonomySlug}
          onClick={e => props.handleTermModal(e, false, taxonomySlug)}
          disabled={loading}
        >
          <ReactSVG
            src={`${lc_data.dir}dist/${PlusIcon}`}
            className="relative mr-8 w-14 h-14 fill-blue-700 pointer-events-none"
          />
          {lc_data.jst[99]}
        </button>
        :
        <div className="flex">
          <div className="relative text-grey-500">
            {lc_data.jst[100]}
            <ReactSVG src={`${lc_data.dir}dist/${QuestionIcon}`}
                      data-tip={lc_data.jst[101]}
                      className="absolute w-14 h-14 fill-blue-700" style={{ top: '-8px', right: '-12px' }}/>
          </div>
          <ReactTooltip/>
        </div>
      }
      {data.themeOptions.hide_child_terms && props.isChild &&
      <div className="-mt-14 mb-14 text-sm text-grey-500" style={{
        fontSize: 11,
      }} dangerouslySetInnerHTML={{ __html: lc_data.jst[643] }}/>
      }

      <div
        ref={dragulaDecorator}
        className="relative terms overflow-y-auto"
        data-taxonomy={taxonomySlug}
        style={{ height: props.taxonomy.type !== 'input' ? termHeight : 'auto', maxHeight: termMaxHeight }}
      >
        {loading && <span className="absolute top-0 left-0 w-full h-full bg-grey-100 opacity-25 z-10"></span>}
        {map(terms, (term, index) => {
          const termClass = props.newTerm === term.term_id ? 'bg-blue-200' : (termParent === undefined || termParent === 0 || taxonomyToEdit !== term.taxonomy ? 'default' : (taxonomyToEdit === term.taxonomy && termParent === term.parent ? 'bg-blue-200' : 'bg-grey-100'));
          const termParentClass = termParent === term.term_id ? 'bg-blue-200' : '';
          const termLoadingClass = loading ? 'opacity-50 pointer-events-none' : '';
          return taxonomySlug === term.taxonomy &&
            <div key={index}
                 data-order={term.meta.order}
                 data-term={term.term_id}
                 data-taxonomy={taxonomySlug}
                 className={`term flex items-center bg-grey-100 shadow--terms py-10 px-10 mb-10 rounded ${termLoadingClass} ${termParentClass} ${termClass} hover:opacity-100`}
            >
              <div className="term-handle cursor-pointer">
                <ReactSVG
                  src={`${lc_data.dir}dist/${MoveIcon}`}
                  className="relative mr-8 w-12 h-12 fill-grey-900 pointer-events-none"
                />
              </div>
              <button
                type="button"
                className="term--title flex items-center"
                onClick={e => {
                  setClicked(!clicked);
                  props.handleChildTerms(e, term.term_id, term.taxonomy);
                }}
              >
                <h4 className="term--name font-bold text-left">
                  <span dangerouslySetInnerHTML={{
                    __html: term.name,
                  }}></span>
                  <span className="term--taxonomy mt-1 leading-none font-normal text-sm text-grey-500">
                  <span className="mx-1 font-normal text-sm text-gray-grey-400">/</span>
                    {term.parent !== 0
                      ?
                      term.meta.parent_name
                      : filter(props.allTerms, { 'parent': term.term_id }).length
                    }
                </span>
                </h4>
              </button>
              <div className="term--actions flex ml-auto">
                {taxonomyToEdit === term.taxonomy ?
                  <button
                    type="button"
                    className="term--edit flex"
                    data-term-id={term.term_id}
                    data-taxonomy-slug={taxonomySlug}
                    onClick={e => handleTermParentChange(e, term.term_id, termParent)}
                  >
                    <Fragment>
                      {changingParent === term.term_id &&
                      <ReactSVG
                        src={`${lc_data.dir}dist/${LoaderIcon}`}
                        className="relative"
                        style={{ zoom: 0.4 }}
                      />}
                      {changingParent !== term.term_id && taxonomyToEdit === term.taxonomy && termParent === term.parent &&
                      <ReactSVG
                        src={`${lc_data.dir}dist/${StarIcon}`}
                        className="relative w-14 h-14 fill-green-700 cursor-pointer pointer-events-none"
                      />
                      }
                      {changingParent !== term.term_id && (taxonomyToEdit !== term.taxonomy || termParent !== term.parent) &&
                      <ReactSVG
                        src={`${lc_data.dir}dist/${StarEmptyIcon}`}
                        className="relative w-14 h-14 fill-grey-700 cursor-pointer pointer-events-none"
                      />
                      }
                    </Fragment>
                  </button>
                  : ''
                }
                {taxonomyToEdit !== term.taxonomy &&
                <button
                  type="button"
                  className="term--edit flex"
                  data-term-id={term.term_id}
                  data-taxonomy-slug={taxonomySlug}
                  onClick={e => props.handleTermModal(e, term.term_id, taxonomySlug, true)}
                >
                  <ReactSVG
                    src={`${lc_data.dir}dist/${PencilIcon}`}
                    className="relative w-14 h-14 fill-grey-900 cursor-pointer pointer-events-none"
                  />
                </button>
                }
              </div>
            </div>;
        })}
      </div>
      {maxPages > 1 && (!props.childTermsActive || taxonomyToEdit === taxonomySlug) &&
      <div className="terms--pagination flex flex-wrap items-center mt-20" data-taxonomy={taxonomySlug}>
        <div className="flex mb-4 font-semibold w-full">{lc_data.jst[644]}</div>
        {pagination()}
      </div>
      }
    </div>
  );
};

export default Terms;
