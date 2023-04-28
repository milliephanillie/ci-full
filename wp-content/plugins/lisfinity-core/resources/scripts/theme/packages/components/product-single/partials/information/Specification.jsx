/* global lc_data, React */
/**
 * External dependencies.
 */
import * as actions from '../../../../store/actions';
import {useState, useEffect, Fragment} from 'react';
import cx from 'classnames';
import {sprintf, __} from '@wordpress/i18n';
import {isEmpty, map, get} from 'lodash';
import {useDispatch, useSelector} from 'react-redux';

/**
 * Internal dependencies.
 */

const colored = [0, 1, 4, 5, 8, 9, 12, 13, 16, 17, 20, 21, 24, 25, 28, 29, 32, 33, 36, 37, 40, 41, 44, 45, 48, 49, 52, 53, 56, 57, 60, 61, 64, 67, 70, 71, 74, 75, 78, 79, 82, 83, 86, 87, 90, 91, 94, 95, 98, 99];

const Specification = (props) => {
  const {product} = props;
  const [smallScreen, setSmallScreen] = useState(false);
  const [usedTaxonomies, setUsedTaxonomies] = useState({});
  const [otherUsedTaxonomies] = useState({});
  const dispatch = useDispatch();
  const data = useSelector(state => state);
  const otherTaxonomies = [];
  const notUsedTaxonomies = [];
  let otherCount = 0;

  const isSmallScreen = () => {
    if (window.innerWidth < 640) {
      setSmallScreen(true);
    } else {
      setSmallScreen(false);
    }
  };

  useEffect(() => {
    isSmallScreen();
    window.addEventListener('resize', isSmallScreen);
    return () => window.removeEventListener('resize', isSmallScreen);
  });

  useEffect(() => {
    const groupTaxonomies = {};
    product && product.groups && product.groups.fields &&
    map(product.groups.fields, (taxonomyGroup, taxonomy) => {
      if ('' !== taxonomyGroup) {
        if (groupTaxonomies[taxonomyGroup]) {
          groupTaxonomies[taxonomyGroup].push(taxonomy);
        } else {
          groupTaxonomies[taxonomyGroup] = [];
        }
        const tax = get(product.taxonomies, taxonomy);
        if (tax) {
          if (usedTaxonomies[taxonomyGroup]) {
            usedTaxonomies[taxonomyGroup].push(tax);
          } else {
            usedTaxonomies[taxonomyGroup] = [];
          }
        }
      }
    });

    if (!isEmpty(groupTaxonomies)) {
      dispatch(actions.specificationMenu(Object.keys(groupTaxonomies)));
    }
  }, []);

  const usedTerms = [];
  return (
    <div className="mt-30">
      {product && product.groups && product.groups.groups && product.groups.groups.length > 0 && map(product.groups.groups, (group, index) => {
        let count = 0;
        let groups = {
          'concrete-equipment-year': 'general-details',
          'concrete-equipment-make': 'general-details',
          'concrete-equipment-model': 'general-details',
          'concrete-equipment-condition': 'general-details',
        };

        return Object.keys(usedTaxonomies).includes(group.slug) && (
          <div id={group.slug} key={group.slug} className="mt-20 sm:mt-48">
            <h5
              className="mb-20 font-bold text-grey-1000">{group.slug === '' ? lc_data.jts[505] : group.name}</h5>
            <div className="flex flex-wrap justify-between grid-me">
              {map(groups, (taxonomyGroup, taxonomy) => {
                const tax = get(product.taxonomies, taxonomy);
                const prefix = get(product.taxonomy_options, [taxonomy, 'prefix']);
                const suffix = get(product.taxonomy_options, [taxonomy, 'suffix']);
                const elClasses = cx({
                  'flex justify-between px-10 py-10 sm:mt-0': tax,
                  'w-full sm:w-100%': tax && tax.type !== 'checkbox',
                  'bg-grey-100': tax && tax.type !== 'checkbox' && (count % 2 === 0),
                  'flex-col -mx-8 px-0 w-full': tax && tax.type === 'checkbox',
                });
                if (group.slug === taxonomyGroup && !otherTaxonomies.includes(taxonomy)) {
                  otherTaxonomies.push(taxonomy);

                  if (tax) {
                    count += 1;
                  }

                }
                return (
                  tax && group.slug === taxonomyGroup &&
                  <div key={taxonomy} className={elClasses}>
                    {tax && tax.type !== 'checkbox' &&
                    <Fragment>
                      <span
                        className="font-light text-grey-700">{sprintf(__('%s:', 'lisfinity-core'), tax.single_name)}</span>
                      <span
                        className="font-semibold text-right text-grey-1000"
                        dangerouslySetInnerHTML={{__html: `${prefix ? prefix : ''}${(tax.term)}${suffix ? suffix : ''}`}}></span>
                    </Fragment>
                    }
                    {tax && tax.type === 'checkbox' &&
                    <div className="mb-0 sm:mb-20">
                      <h6
                        className="flex mb-10 font-bold text-base text-grey-1000">{product.taxonomies[taxonomy].single_name}</h6>
                      <div className="flex flex-wrap -mb-10 w-full">
                        {map(tax.term, (term, termIndex) => {
                          if (!usedTerms.includes(term)) {
                            usedTerms.push(term);
                            count = 0;
                            return (
                              <span key={termIndex} className="mb-8 w-full font-light text-grey-1000 sm:w-1/3"
                                    dangerouslySetInnerHTML={{
                                      __html: term,
                                    }}>
                            </span>
                            );
                          }
                        })}
                      </div>
                    </div>
                    }
                  </div>
                );
              })}
            </div>
          </div>
        );
      })}
    </div>
  );
};

export default Specification;
