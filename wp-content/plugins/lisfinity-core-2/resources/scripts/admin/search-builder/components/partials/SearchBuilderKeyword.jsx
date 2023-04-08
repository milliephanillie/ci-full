/* global lc_data, React */

import { __, sprintf } from '@wordpress/i18n';
import { useEffect, useRef, useState } from '@wordpress/element';
import { map, filter } from 'lodash';
import { Fragment } from 'react';
import Modal from '../../../taxonomies/components/Modal';

const SearchBuilderKeyword = (props) => {
  const [options, setOptions] = useState(props.options);
  const [suggestions, setSuggestions] = useState(false);
  const [author, setAuthor] = useState(false);
  const [ads, setAds] = useState(false);
  const [modalOpen, setModalOpen] = useState(false);
  const [selectAllCheck, setSelectAllCheck] = useState(false);
  const { fields, groups } = props.fields;
  const taxonomies = props.fields.terms;

  const escFunction = (event) => {
    if (event.keyCode === 27) {
      setModalOpen(false);
      const body = document.querySelector('body');
      body.style.overflow = 'auto';
    }
  }

  const closeModal = () => {
    setModalOpen(false);
    const body = document.querySelector('body');
    body.style.overflow = 'auto';
  }

  const handleClickOutside = e => {
    setModalOpen(false);
    const body = document.querySelector('body');
    body.style.overflow = 'auto';
  }

  const selectedAllDefaultChecked = () => {
    if (groups && groups.length > 0) {
      setSelectAllCheck(options && options['category-types'] && options['category-types'].length === groups.length);
    } else {
      let count = taxonomies && Object.keys(taxonomies).length;
      const ha = filter(taxonomies, tax => tax.options === '');
      count = count - Object.keys(ha).length;

      setSelectAllCheck(options && options['category-types'] && options['category-types'].length === count);
    }
  }

  useEffect(() => {
    selectedAllDefaultChecked();
  }, [options]);

  useEffect(() => {
    document.addEventListener('keydown', escFunction, false);

    const newOptions = options;
    if (!newOptions['category-types']) {
      newOptions['category-types'] = [];
    }
    if (!Array.isArray(newOptions['category-types'])) {
      newOptions['category-types'] = newOptions['category-types'].split(',');
    }

    selectedAllDefaultChecked();

    if (!options) {
      newOptions.titles = true;
      setOptions({ ...options, ...newOptions });
      props.handleKeyword(options);
    }

    setSuggestions(options && options['suggestions']);
    setAuthor(options && options['author']);
    setAds(options && options['ads']);

    return () => document.removeEventListener('keydown', escFunction, false);
  }, []);

  const handleChange = (e, type, name, parentName = false, newOption = true) => {

    const newOptions = options;

    if (type === 'categories') {
      if (e.checked) {
        newOptions['category-types'].push(name);
      } else {
        newOptions['category-types'].forEach((cat, index) => {
          if (cat === name) {
            newOptions['category-types'].splice(index, 1);
          }
        })
      }
    }

    if (type === 'suggestion') {
      if (e.checked) {
        if (newOption) {
          newOptions[name] = true;
        }
        if (parentName) {
          newOptions[parentName] = name;
        }
      } else {
        newOptions[name] = false;
      }
    }

    if (type === 'text') {
      newOptions[name] = e.value;
    }

    setOptions({ ...options, ...newOptions });
    props.handleKeyword(options);
  }

  const handleSelectAll = (allSelected) => {
    const newOptions = options;
    const items = document.querySelectorAll('.category-type');
    if (allSelected) {
      newOptions['category-types'] = [];
      items.forEach(item => {
        item.checked = false;
      });
    } else {
      items.forEach(item => {
        const name = item.dataset.name;
        if (!newOptions['category-types'].includes(name)) {
          newOptions['category-types'].push(name);
          item.checked = true;
        }
      })
    }

    setOptions({ ...options, ...newOptions });
    props.handleKeyword(options);
  }

  return (
    <div>

      <div className="-mt-20 p-20 bg-white border-t border-grey-100">
        <p>{lc_data.jst[14]}</p>

        <div className="mt-10">

          <div>
            <label htmlFor="keywordTitle" className="font-semibold">
              <input
                type="checkbox"
                id="keywordTitle"
                className="grey"
                onClick={(e) => handleChange(e.target, 'suggestion', 'titles')}
                defaultChecked={options && options['titles']}
              />
              {lc_data.jst[15]}
            </label>
          </div>

          <div className="mt-4">
            <label htmlFor="keywordContent" className="font-semibold">
              <input
                type="checkbox"
                id="keywordContent"
                className="grey"
                onClick={(e) => handleChange(e.target, 'suggestion', 'descriptions')}
                defaultChecked={options && options['descriptions']}
              />
              {lc_data.jst[16]}
            </label>
          </div>

          <div className="mt-4">
            <label htmlFor="keywordId" className="font-semibold">
              <input
                type="checkbox"
                id="keywordId"
                className="grey"
                onClick={(e) => handleChange(e.target, 'suggestion', 'ids')}
                defaultChecked={options && options['ads']}
              />
              {lc_data.jst[17]}
            </label>
          </div>

          <div className="mt-4">
            <label htmlFor="keywordSuggestions" className="font-semibold">
              <input
                type="checkbox"
                id="keywordSuggestions"
                className="grey"
                onClick={(e) => {
                  setSuggestions(!suggestions);
                  handleChange(e.target, 'suggestion', 'suggestions');
                }}
                defaultChecked={options && options['suggestions']}
              />
              {lc_data.jst[18]}
            </label>
          </div>
          {suggestions &&
          <div className="relative flex items-center mt-4" style={{ left: 24 }}>
            <label htmlFor="keywordSuggestionsLimit" className="font-semibold">
              Visible suggestions:
              <input
                type="number"
                min={1}
                id="keywordSuggestionsLimit"
                className="grey"
                onChange={(e) => {
                  handleChange(e.target, 'text', 'suggestions-limit');
                }}
                defaultValue={(options && options['suggestions-limit']) || 6}
              />
            </label>
          </div>
          }

        </div>

      </div>

      {suggestions &&
      <div>

        <div className="flex flex-wrap items-center justify-between mt-1 py-10 p-20 bg-white">

          <div>
            <label htmlFor="suggestionCategory" className="font-semibold">
              <input
                type="checkbox"
                id="suggestionCategory"
                className="grey"
                onClick={(e) => handleChange(e.target, 'suggestion', 'category')}
                defaultChecked={options && options['category']}
              />
              {lc_data.jst[19]}
            </label>
          </div>

          {options && options['category'] &&
          <div>
            <button
              type="button"
              className="text-sm text-blue-700 hover:underline"
              onClick={() => {
                const body = document.querySelector('body');
                body.style.overflow = 'hidden';
                setModalOpen(true);
              }}
            >
              {lc_data.jst[20]}
            </button>
          </div>
          }

        </div>

        <div className="flex flex-wrap mt-1 py-10 p-20 bg-white">
          <div className="w-full">
            <label htmlFor="suggestionAuthor" className="font-semibold">
              <input
                type="checkbox"
                id="suggestionAuthor"
                className="grey"
                onClick={(e) => {
                  setAuthor(!author);
                  handleChange(e.target, 'suggestion', 'author');
                }}
                defaultChecked={options && options['author']}
              />
              {lc_data.jst[21]}
            </label>
          </div>

          {author &&
          <Fragment>
            <div className="mt-6 w-1/2">
              <label htmlFor="suggestionAuthorPremium" className="font-semibold">
                <input
                  type="radio"
                  id="suggestionAuthorPremium"
                  className="grey"
                  name="keywordOptions[author-type]"
                  onClick={(e) => handleChange(e.target, 'suggestion', 'author-premium', 'author-type', false)}
                  defaultChecked={options && options['author-type'] === 'author-premium'}
                  value="author-premium"
                />
                {lc_data.jst[22]}
              </label>
            </div>
            <div className="mt-6 w-1/2">
              <label htmlFor="suggestionAuthorSimple" className="font-semibold">
                <input
                  type="radio"
                  id="suggestionAuthorSimple"
                  className="grey"
                  name="keywordOptions[author-type]"
                  onClick={(e) => handleChange(e.target, 'suggestion', 'author-simple', 'author-type', false)}
                  defaultChecked={options && options['author-type'] === 'author-simple'}
                  value="author-simple"
                />
                {lc_data.jst[23]}
              </label>
            </div>
          </Fragment>
          }
        </div>

        <div className="flex flex-wrap mt-1 py-10 p-20 bg-white">
          <div className="mb-6 w-full">
            <label htmlFor="suggestionAd" className="font-semibold">
              <input
                type="checkbox"
                id="suggestionAd"
                className="grey"
                onClick={(e) => {
                  setAds(!ads);
                  handleChange(e.target, 'suggestion', 'ads');
                }}
                defaultChecked={options && options['ads']}
              />
              {lc_data.jst[24]}
            </label>
          </div>

          {ads &&
          <Fragment>
            <div className="w-1/2">
              <label htmlFor="suggestionAdsPromoted" className="font-semibold">
                <input
                  type="radio"
                  id="suggestionAdsPromoted"
                  className="grey"
                  name="keywordOptions[ads-type]"
                  onClick={(e) => handleChange(e.target, 'suggestion', 'ads-premium', 'ads-type', false)}
                  defaultChecked={options && options['ads-type'] === 'ads-premium'}
                  value="ads-premium"
                />
                {lc_data.jst[25]}
              </label>
            </div>
            <div className="w-1/2">
              <label htmlFor="suggestionAdsSimple" className="font-semibold">
                <input
                  type="radio"
                  id="suggestionAdsSimple"
                  className="grey"
                  name="keywordOptions[ads-type]"
                  onClick={(e) => handleChange(e.target, 'suggestion', 'ads-simple', 'ads-type', false)}
                  defaultChecked={options && options['ads-type'] === 'ads-simple'}
                  value="ads-simple"
                />
                {lc_data.jst[26]}
              </label>
            </div>
          </Fragment>
          }
        </div>

      </div>
      }

      {suggestions && options && options['category'] && modalOpen &&
      <div
        className="modal--wrapper fixed top-0 left-0 flex justify-center w-full h-full overflow-y-auto"
      >
        <Modal classes="form-update-option"
               closeModal={closeModal}
               title={groups && groups.length > 0 ? lc_data.jst[27] : lc_data.jst[28]}
               open={modalOpen}
               handleClickOutside={(e) => handleClickOutside(e)}
        >
          <div className="flex flex-wrap">
            {groups && groups.length &&
            <div className="flex flex-col mb-20 w-full">
              <p>{lc_data.jst[29]}</p>
              <div className="flex mt-6">
                <div>
                  <label htmlFor="selectAllCategories" className="font-bold">
                    <input
                      type="checkbox"
                      id="selectAllCategories"
                      className="grey"
                      defaultChecked={selectAllCheck}
                      onClick={() => handleSelectAll(selectAllCheck)}
                    />
                    {selectAllCheck ?
                      lc_data.jst[30]
                      :
                      lc_data.jst[31]
                    }
                  </label>
                </div>
              </div>
            </div>
            }
            {(!groups || groups.length === 0) &&
            <div className="flex flex-wrap">
              <div className="flex flex-col mb-20 w-full">
                <b className="mb-6">{lc_data.jst[549]}</b>
                <a href={`${lc_data.admin_url}admin.php?page=custom-fields`}
                   className="button button-primary py-4 text-center">{lc_data.jst[550]}</a>
              </div>
            </div>
            }
            {groups && groups.length > 0 && groups.map((group, index) => {
              return (
                <div key={index} className="mb-2 w-1/3">
                  <label htmlFor={`category-${group.slug}`} className="font-semibold">
                    <input
                      type="checkbox"
                      id={`category-${group.slug}`}
                      data-name={group.slug}
                      className="grey category-type"
                      onClick={(e) => handleChange(e.target, 'categories', group.slug, 'category-types', false)}
                      defaultChecked={options && options['category-types'] && options['category-types'].includes(group.slug)}
                    />
                    {group.single_name}
                  </label>
                </div>
              )
            })}
            {groups && groups.length === 0 && taxonomies && map(taxonomies, (group, name) => {
              return (
                '' !== group.options &&
                <div key={name} className="mb-2 w-1/3">
                  <label htmlFor={`category-${group.options.slug}`} className="font-semibold">
                    <input
                      type="checkbox"
                      id={`category-${group.options.slug}`}
                      data-name={group.options.slug}
                      className="grey category-type"
                      onClick={(e) => handleChange(e.target, 'categories', group.options.slug, 'category-types', false)}
                      defaultChecked={options && options['category-types'] && options['category-types'].includes(group.options.slug)}
                    />
                    {group.options.single_name}
                  </label>
                </div>
              )
            })}
          </div>
        </Modal>
      </div>
      }

    </div>
  )
}

export default SearchBuilderKeyword;
