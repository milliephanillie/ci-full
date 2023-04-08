/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component, Fragment, createRef, useEffect, useState, useRef } from '@wordpress/element';
import { map, filter, isEmpty, get } from 'lodash';
import { __, sprintf } from '@wordpress/i18n';
import axios from 'axios';
import ReactSVG from 'react-svg';
import SaveIcon from '../../../../../images/icons/save.svg';
import Sortable from '../sortable/sortableSingle';
import PencilIcon from '../../../../../images/icons/pencil.svg';
import TrashIcon from '../../../../../images/icons/trash.svg';
import PlusIcon from '../../../../../images/icons/plus.svg';
import Modal from '../../../taxonomies/components/Modal';
import KeyboardIcon from '../../../../../images/icons/keyboard.svg';
import { toast } from 'react-toastify';
import LoaderIcon from '../../../../../images/icons/loader-rings.svg';
import ScrollContainer from 'react-indiana-drag-scroll';

const SearchSingle = (props) => {
  const { options } = props;
  const [submitting, setSubmitting] = useState(false);
  const [groups, setGroups] = useState(false);
  const [chosenGroup, setChosenGroup] = useState(false);
  const [taxonomies, setTaxonomies] = useState({});
  const [sections, setSections] = useState({});
  const [modalOpen, setModalOpen] = useState(false);
  const [modalEdit, setModalEdit] = useState(false);
  const wrapper = useRef(null);
  const items = useRef(null);
  const toScroll = useRef(null);

  useEffect(() => {
    document.addEventListener('keydown', escFunction, false);

    const headers = { 'X-WP-Nonce': lc_data.nonce };
    axios({
      url: lc_data.groups,
      credentials: 'same-origin',
      headers,
    }).then(groups => {
      const allGroups = [
        {
          single_name: 'Common', plural_name: 'Commons', slug: 'common',
        }
      ];
      map(groups.data, group => {
        allGroups.push(group);
      });
      setChosenGroup(allGroups[0].slug);
      setGroups(allGroups);
      setTaxonomies(filter(options.taxonomy, (o) => o.field_group === 'common' || o.field_group === allGroups[0].slug));
      getSections(allGroups[0].slug);
    });

    return () => document.removeEventListener('keydown', escFunction, false);
  }, []);

  const getSections = (niche) => {
    const headers = { 'X-WP-Nonce': lc_data.nonce };
    axios({
      url: lc_data.single_builder_options,
      credentials: 'same-origin',
      headers,
    }).then(sections => {
      setSections(sections.data);
    });
  };

  const handleGroupClick = (group) => {
    setChosenGroup(group);
    setTaxonomies(filter(options.taxonomy, (o) => o.field_group === 'common' || o.field_group === group));
  };

  const handleSort = (e, ui) => {
  };

  const handleStop = (e, ui) => {
  };

  const handleClickOutside = () => {
    setModalOpen(false);
  };

  const handleModalClose = e => {
    setModalOpen(false);
  };

  const escFunction = (event) => {
    if (event.keyCode === 27) {
      setModalOpen(false);
    }
  };

  const editGroup = (order, name) => {
    setModalOpen(true);
    setModalEdit(name);
  };

  const handleGroupSubmit = (e) => {
    e.preventDefault();
    setSubmitting(true);
    const formData = new FormData(e.target);
    formData.append('niche', chosenGroup);
    const headers = { 'X-WP-Nonce': lc_data.nonce };
    axios({
      url: lc_data.single_builder_add_group,
      credentials: 'same-origin',
      data: formData,
      method: 'POST',
      headers,
    }).then(json => {
      const message = !isEmpty(modalEdit) ? lc_data.jst[45] : json.data.message;
      toast(message, {
        position: toast.POSITION.BOTTOM_RIGHT,
        autoClose: 3000,
      });
      setModalOpen(false);
      setModalEdit(false);
      setSubmitting(false);
      getSections(chosenGroup);
    });
  };

  const handleGroupDelete = (e, index) => {
    e.preventDefault();
    if (!confirm(lc_data.jst[65])) {
      return false;
    }
    setSubmitting(true);
    const headers = { 'X-WP-Nonce': lc_data.nonce };
    axios({
      url: lc_data.single_builder_delete_group,
      credentials: 'same-origin',
      data: {
        order: index,
        niche: chosenGroup,
      },
      method: 'POST',
      headers,
    }).then(json => {
      toast(json.data.message, {
        position: toast.POSITION.BOTTOM_RIGHT,
        autoClose: 3000,
      });
      setModalOpen(false);
      setModalEdit(false);
      setSubmitting(false);
      getSections(chosenGroup);
    });
  };

  const changeFieldGroup = (group, slug) => {
    setSubmitting(true);
    const headers = { 'X-WP-Nonce': lc_data.nonce };
    axios({
      url: lc_data.single_builder_change_group,
      credentials: 'same-origin',
      data: {
        niche: chosenGroup,
        group,
        taxonomy: slug,
      },
      method: 'POST',
      headers,
    }).then(json => {
      toast(json.data.message, {
        position: toast.POSITION.BOTTOM_RIGHT,
        autoClose: 3000,
      });
      setSubmitting(false);
      getSections(chosenGroup);
    });
  };

  return (
    <div className="search-builder--page py-40 px-20 bg-grey-200 sm:p-40">
      <div>

        <div className="flex flex-col justify-between mb-20 sm:flex-row">
          <div>
            <h2 className="mb-10 font-bold text-4xl">{lc_data.jst[66]}</h2>
            <p
              className="text-lg">{lc_data.jst[67]}</p>
            <span
              className="text-md text-grey-500">{lc_data.jst[583]}</span>
          </div>

        </div>

        <div className="niche-groups my-30">
          <div
            className="niche-groups--tabs flex items-center px-20 bg-white rounded shadow__big overflow-x-hidden">
            <p className="mr-10 font-bold">{lc_data.jst[53]}</p>
            <ScrollContainer ref={toScroll}>
              <ul className="flex items-center">
                {map(groups, (group, index) => {
                  return (
                    <li key={index} className="mb-0 whitespace-no-wrap">
                      <button
                        className={`group py-16 px-20 font-light text-grey-1100 focus:outline-none focus:shadow-none ${chosenGroup === group.slug ? 'bg-blue-200' : ''}`}
                        data-group={group.slug}
                        onClick={() => handleGroupClick(group.slug)}
                      >
                        {group.single_name}
                      </button>
                    </li>
                  );
                })}
              </ul>
            </ScrollContainer>
          </div>
        </div>

        <div className="sb-groups relative flex flex-no-wrap -mx-col overflow-y-auto"
             ref={wrapper}
        >
          {submitting &&
          <div
            className="absolute top-0 left-0 flex justify-center items-center flex-col w-full h-full z-10 cursor-default">
            <span className="absolute top-0 left-0 flex-center w-full h-full bg-grey-200 z-10 opacity-75">
            </span>
            <div className="relative flex justify-center items-center flex-col h-full z-20">
              <ReactSVG
                src={`${lc_data.dir}dist/${LoaderIcon}`}
                className="relative"
                style={{ zoom: 1 }}
              />
              <p className="mt-20 text-lg text-grey-900">{lc_data.jst[556]}</p>
            </div>
          </div>
          }

          <div className="search-builder--group px-col w-1/2">
            <h4 className="mb-10 font-bold text-grey-1000">{lc_data.jst[54]}</h4>

            <div
              className="single--taxonomies"
            >
              {!isEmpty(taxonomies) && map(taxonomies, (tax, index) => (
                <div key={index}
                     className={`flex flex-col mb-10 py-16 px-20 bg-white rounded cursor-pointer`}
                >
                  <div className="flex justify-between items-center">
                    <div>
                      {tax.single_name}
                      <span className="taxonomy--type text-grey-500"> / {tax.type}</span>
                    </div>
                    {sections[chosenGroup] &&
                    <div>
                      <label htmlFor={tax.slug}>{tax.name}</label>
                      <select
                        id={tax.slug}
                        defaultValue={sections[chosenGroup].fields && sections[chosenGroup].fields[tax.slug] || ''}
                        onChange={(e) => changeFieldGroup(e.target.value, tax.slug)}
                        className="flex py-4 px-10 bg-grey-100 border border-grey-200 rounded font-semibold"
                        style={{ height: 'auto', paddingTop: '4px', paddingBottom: '4px' }}
                      >
                        <option value="">{lc_data.jst[68]}</option>
                        {get(sections, chosenGroup) && map(sections[chosenGroup].groups, (group, index) => (
                          <option key={index} value={group.slug}>{group.name}</option>
                        ))}
                      </select>
                    </div>
                    }
                  </div>
                </div>
              ))}
            </div>
          </div>

          <div className="search-builder--group px-col">
            <h4 className="mb-10 font-bold text-grey-1000">{lc_data.jst[27]}</h4>
            <Sortable
              forwardedRef={items}
              onUpdate={handleSort()}
              onStop={handleStop()}
            >
              <div className="groups flex flex-col" ref={items} data-niche={chosenGroup}>
                {get(sections, chosenGroup) && map(sections[chosenGroup].groups, (group, index) => {
                  return (
                    <div key={index} data-order={index}
                         className="group group--sort group--handle flex justify-between items-center mb-10 py-16 px-20 bg-white rounded shadow cursor-pointer focus:cursor-grabbing">
                      <span>{group.name}</span>
                      <div className="group--actions">
                        <button type="button" onClick={() => editGroup(index, group.name)} className="mr-10">
                          <ReactSVG
                            src={`${lc_data.dir}dist/${PencilIcon}`}
                            className="relative w-14 h-14 fill-grey-900 cursor-pointer pointer-events-none"
                          />
                        </button>
                        <button type="button" onClick={e => handleGroupDelete(e, index)}>
                          <ReactSVG
                            src={`${lc_data.dir}dist/${TrashIcon}`}
                            className="relative w-14 h-14 fill-grey-900 cursor-pointer pointer-events-none"
                          />
                        </button>
                      </div>
                    </div>
                  );
                })}
              </div>
            </Sortable>
            <button
              type="submit"
              className="flex justify-center items-center py-12 px-30 h-44 bg-blue-700 hover:bg-blue-800 rounded font-bold text-base text-white"
              onClick={() => setModalOpen(true)}
            >
              <ReactSVG
                src={`${lc_data.dir}dist/${PlusIcon}`}
                className="relative mr-8 w-20 h-20 fill-white"
              />
              {lc_data.jst[56]}
            </button>
          </div>

          {modalOpen &&
          <div
            key={3}
            className="modal--wrapper fixed top-0 left-0 flex justify-center w-full h-full overflow-y-auto"
          >
            <Modal classes="form-update-option"
                   closeModal={handleModalClose}
                   title={lc_data.jst[57]}
                   open={modalOpen}
                   handleClickOutside={(e) => handleClickOutside(e)}
            >
              <form className="form--group form-fetch"
                    onSubmit={handleGroupSubmit}>
                <div className="mb-20 w-full bg:w-1/2">
                  <label htmlFor="newGroup"
                         className="lisfinity-label flex mb-10">{lc_data.jst[58]}</label>
                  <div className="flex items-center px-16 bg-grey-100 border border-grey-200 rounded">
                    <ReactSVG
                      src={`${lc_data.dir}dist/${KeyboardIcon}`}
                      className="relative mr-8 w-18 h-18 fill-grey-700"
                    />
                    <input
                      id="newGroup"
                      type="text"
                      name="new_group"
                      defaultValue={!isEmpty(modalEdit) ? modalEdit : ''}
                      className="py-12 w-full font-semibold bg-transparent border-0 text-grey-700"
                      autoComplete="off"
                      autoFocus
                    />
                  </div>
                  <div className="description mt-6 text-grey-700 leading-snug"
                       style={{ fontSize: '11px' }}>{lc_data.jst[32]}</div>
                </div>
                {!isEmpty(modalEdit) && <input type="hidden" name="old_group" value={modalEdit}/>}
                <button
                  type="submit"
                  className="flex justify-center items-center py-12 px-30 h-44 bg-blue-700 hover:bg-blue-800 rounded font-bold text-base text-white"
                >
                  <ReactSVG
                    src={`${lc_data.dir}dist/${SaveIcon}`}
                    className="relative mr-8 w-20 h-20 fill-white"
                  />
                  {lc_data.jst[34]}
                </button>
              </form>
            </Modal>
          </div>
          }

        </div>
      </div>
    </div>
  );

};

export default SearchSingle;
