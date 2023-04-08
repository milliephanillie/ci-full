/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component, useEffect, useState } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import axios from 'axios';
import TextField from '../fields/TextField';
import SelectField from '../fields/SelectField';
import { map, filter, isNumber, isEmpty } from 'lodash';
import { __, sprintf } from '@wordpress/i18n';
import Modal from './Modal';
import Group from './Group';
import Textarea from '../fields/Textarea';
import FileField from '../fields/File';
import React, { Fragment } from 'react';
import ReactSVG from 'react-svg';
import PlusIcon from '../../../../images/icons/plus.svg';
import SaveIcon from '../../../../images/icons/save.svg';
import LoaderIconWhite from '../../../../images/icons/loader-rings-white.svg';
import { Scrollbars } from 'react-custom-scrollbars';
import { toast, ToastContainer } from 'react-toastify';
import GroupExport from './GroupExport';
import GroupImport from './GroupImport';
import LoaderFieldsBuilder from '../../loaders/LoaderFieldsBuilder';
import { DragDropContext, Draggable, Droppable } from 'react-beautiful-dnd';
import { reorderList } from './reorder';
import Licence from './Licence';
import { isLodash } from '../../../theme/vendor/functions';

const Groups = (props) => {
  if ( isLodash() ) {
    _.noConflict();
  }
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [modalOpen, setModalOpen] = useState(false);
  const [fields, setFields] = useState([]);
  const [groups, setGroups] = useState([]);
  const [taxonomies, setTaxonomies] = useState([]);
  const [groupEdit, setGroupEdit] = useState([]);
  const [predefined, setPredefined] = useState(false);
  const [notice, setNotice] = useState('');
  const [ieModal, setIeModal] = useState(false);
  const [updating, setUpdating] = useState(false);
  const [key, setKey] = useState(false);

  useEffect(() => {
    document.addEventListener('keydown', escFunction, false);

    fetchGroupFields();
    fetchGroups();
    fetchTaxonomies();

    if (lc_data?.key) {
      if (lc_data.key.is_valid && !isEmpty(lc_data.l) && lc_data.key.license_key === lc_data.l && lc_data.key.domain === lc_data.domain && isNumber(lc_data.key.next_request)) {
        setKey(true);
      }
    }

    return () => document.removeEventListener('keydown', escFunction, false);
  }, []);

  const handleClickOutside = (e, type) => {
    const body = document.querySelector('body');
    if (body.classList.contains('modal-open')) {
      return false;
    }
    if (type === 'ie') {
      setIeModal(false);
    } else {
      setModalOpen(false);
    }
    body.style.overflow = 'auto';
  };

  const escFunction = (event) => {
    if (event.keyCode === 27) {
      setModalOpen(false);
      setIeModal(false);
      const body = document.querySelector('body');
      body.style.overflow = 'auto';
    }
  };

  const fetchTaxonomies = () => {
    apiFetch({ path: lc_data.taxonomy_options }).then((taxonomies) => {
      setTaxonomies(taxonomies);
      setLoading(false);
    });
  };

  const fetchGroupFields = () => {
    apiFetch({ path: lc_data.group_fields }).then((fields) => {
      setFields(fields);
    });
  };

  const fetchGroups = () => {
    apiFetch({ path: lc_data.groups }).then((groups) => {
      setGroups(groups);
      setLoading(false);
      setSubmitting(false);
    });
  };

  const showField = (value, name, predefined = false) => {
    switch (value.type) {
      case 'file':
        return <FileField
          key={value.key}
          value={predefined ? groupEdit[name] : ''}
          name={name}
          buttonLabel={value.label || lc_data.jst[69]}
          mediaLibraryButtonLabel={value.label || lc_data.jst[69]}
          mediaLibraryTitle={value.label || lc_data.jst[69]}
          field={value}
          handleChange={() => ''}
        />;
      case 'select':
        return <SelectField
          display
          key={value.key}
          id={name}
          name={name}
          classes="crb-select2"
          label={value.label}
          value={predefined ? groupEdit[name] : ''}
          description={value.description}
          options={value.choices}
          multiselect={value.multiselect}
          field={value}
          handleChange={() => ''}
        />;
      case 'textarea':
        return <Textarea
          key={value.key}
          id={name}
          name={name}
          label={value.label}
          value={predefined ? groupEdit[name] : ''}
          description={value.description}
          field={value}
          handleChange={() => ''}
        />;
      default: {
        return <TextField
          key={value.key}
          id={name}
          name={name}
          label={value.label}
          value={predefined ? decodeURIComponent(groupEdit[name]) : ''}
          description={value.description}
          field={value}
          handleChange={() => ''}
        />;
      }
    }
  };

  const handleModal = (e, group, isPredefined) => {
    setModalOpen(!modalOpen);

    if (modalOpen) {
      const body = document.querySelector('body');
      body.style.overflow = 'hidden';
    }

    setGroupEdit(group);
    setPredefined(isPredefined);
    setNotice('');
  };

  const handleGroupSubmit = (e, edit) => {
    e.preventDefault();
    setLoading(true);
    setSubmitting(true);
    const data = new FormData(e.target);

    const headers = new Headers();
    let url = lc_data.group_store;
    headers.append('X-WP-Nonce', lc_data.nonce);
    if (edit) {
      url = lc_data.group_edit;
    }
    fetch(url, {
      method: 'POST',
      credentials: 'same-origin',
      headers,
      body: data,
    }).then(response => response.json().then(json => {
      if (json.data.success) {
        fetchGroups();
        setModalOpen(false);
        setIeModal(false);
        toast(json.data.message, {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
        });
      }
      if (json.data.error) {
        toast.error(json.data.message, {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
        });
      }
      setSubmitting(false);
      setLoading(false);
    }));
  };

  const handleGroupDelete = (e, order) => {
    e.preventDefault();

    if (!confirm(lc_data.jst[86])) {
      return false;
    }

    setSubmitting(true);

    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    axios({
      url: lc_data.group_delete,
      method: 'POST',
      credentials: 'same-origin',
      headers,
      data: {
        order,
      },
    }).then(response => {
      fetchGroups();
      toast(lc_data.jst[87], {
        position: toast.POSITION.BOTTOM_RIGHT,
        autoClose: 3000,
      });
    });

  };

  const groupToUpdate = () => {
    let groupIndex = 0;
    filter(groups, (group, index) => {
      if (group.slug === groupEdit.slug) {
        groupIndex = index;
      }
    });

    return groupIndex;
  };

  const closeModal = () => {
    setModalOpen(false);
    setIeModal(false);
    const body = document.querySelector('body');
    body.style.overflow = 'auto';
  };

  const onDragStart = () => {

  };

  const onDragEnd = (result) => {
    const reorder = reorderList(
      groups,
      result.source.index,
      result.destination.index
    );

    updateOrder(reorder);

    setGroups(reorder);
  };

  const updateOrder = (groups) => {
    setUpdating(true);
    const headers = { 'X-WP-Nonce': lc_data.nonce };
    axios({
      url: lc_data.group_edit_order,
      method: 'POST',
      credentials: 'same-origin',
      headers,
      data: groups,
    }).then(response => {
      if (response.data.success) {
        toast(response.data.data.message, {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
        });
      }
      if (response.data.error) {
        setNotice(response.data.data.message);
        toast.error(response.data.data.message, {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
        });
      }
      setUpdating(false);
    });
  };

  return (
    <Fragment>
      <section className="dashboard flex flex-col py-60 font-sans">

        <div className="flex flex-wrap">
          <h1 className="font-sans font-bold text-4xl">{lc_data.jst[88]}</h1>
        </div>
        <p className="mt-10 text-base text-grey-700" dangerouslySetInnerHTML={{
          __html: sprintf(lc_data.jst[89],
            `<a href="https://pebas.gitbook.io/lisfinity-documentation/fields-builder" target="_blank" class="text-blue-700 underline">${lc_data.jst[90]}</a>`),
        }}></p>

        {loading && <LoaderFieldsBuilder/>}

        {!loading &&
        <Fragment>
          {key &&
          <div className="mt-30">
            <button
              type="button"
              onClick={(e, group, predefined) => handleModal(e, [], false)}
              className="flex justify-center items-center py-12 px-24 bg-blue-700 hover:bg-blue-800 rounded font-bold text-base text-white"
            >
              <ReactSVG
                src={`${lc_data.dir}dist/${PlusIcon}`}
                className="relative mr-8 w-20 h-20 fill-white"
              />
              <span>{lc_data.jst[91]}</span>
            </button>
          </div>
          }

          {!key && <Licence/>}

          {key &&
          <div className="cf-groups mt-40 p-30 bg-grey-200 rounded">
            <div className="cf-groups--head flex items-end justify-between mb-20 px-4">
              <span className="pr-10 w-1/4 font-semibold text-grey-1000">{lc_data.jst[58]}</span>
              <span className="pr-10 w-2/4 font-semibold text-grey-1000">{lc_data.jst[28]}</span>
              <span className="w-1/4 text-sm text-right font-semibold text-grey-1000">{__('', 'lisfinity-core')}</span>
              <div className="relative wrap">
                <button
                  type="button"
                  onClick={() => setIeModal(true)}
                  className="button button-primary"
                  style={{
                    fontSize: '10px',
                    fontWeight: 700,
                  }}
                >
                  <span>{lc_data.jst[3]}</span>
                </button>

              </div>
            </div>

            <Scrollbars style={{ zIndex: 20 }} autoHide={false} autoHeight autoHeightMin={780}
                        renderTrackHorizontal={props => <div {...props} className="hidden"/>}
                        renderThumbHorizontal={props => <div {...props} className="hidden"/>}
                        renderTrackVertical={props => <div {...props}
                                                           className="track--vertical top-0 right-0 bottom-0 w-2"/>}
                        renderThumbVertical={props => <div {...props}
                                                           className="thumb--vertical bg-grey-600 rounded opacity-25"/>}>
              <Group
                key={2}
                group={false}
                taxonomies={taxonomies}
                provided={{}}
              />
              {groups &&
              <DragDropContext onDragEnd={onDragEnd} onDragStart={onDragStart}>
                <div className="relative flex builder--wrapper">
                  {updating &&
                  <span
                    className="absolute top-0 left-0 w-full h-full bg-grey-100 opacity-50 cursor-default z-10"></span>}
                  <Droppable
                    droppableId="categories-droppables"
                    direction="vertical"
                    type="column"
                  >
                    {provided => (
                      <div
                        className="w-full"
                        {...provided.droppableProps}
                        ref={provided.innerRef}
                      >
                        {map(groups, (group, index) => {
                          return (
                            <Draggable key={group.slug} draggableId={group.slug} index={index}>
                              {(provided, snapshot) => (
                                <div
                                  className="column"
                                  {...provided.draggableProps}
                                  ref={provided.innerRef}
                                >
                                  <Group
                                    group={group}
                                    taxonomies={taxonomies}
                                    handleModal={handleModal}
                                    handleGroupDelete={e => handleGroupDelete(e, index)}
                                    provided={{ ...provided }}
                                  />
                                </div>
                              )}
                            </Draggable>
                          );
                        })}
                        {provided.placeholder}
                      </div>
                    )}
                  </Droppable>
                </div>
              </DragDropContext>
              }
            </Scrollbars>

          </div>
          }
        </Fragment>
        }

        {modalOpen &&
        <div
          className="modal--wrapper fixed top-0 left-0 flex justify-center w-full h-full overflow-y-auto"
        >
          <Modal classes="form-update-group"
                 closeModal={closeModal}
                 title={groupEdit.length ? sprintf(lc_data.jst[92], groupEdit.plural_name) : lc_data.jst[93]}
                 open={modalOpen}
                 handleClickOutside={(e) => handleClickOutside(e, 'group')}
          >
            <form className="form-create-group" onSubmit={(e) => handleGroupSubmit(e, predefined)}>
              {predefined ?
                <div className="hidden">
                  <input type="hidden" name="element-to-update" value={groupToUpdate()}/>
                  <input type="hidden" name="element-old-slug" value={groupEdit.slug}/>
                </div>
                : ''}
              {fields.general &&
              <div className="flex flex-wrap justify-between">
                {map(fields.general, (value, name) => (
                  showField(value, name, predefined)
                ))}
              </div>}
              <div className={`flex mt-20 ${notice ? 'justify-between' : 'justify-end'}`}>

                {notice &&
                <div className="response response--error flex items-center">
                  <span className="text-base text-red-700">{notice}</span>
                </div>
                }

                <button type="submit"
                        className="flex justify-center items-center py-12 px-24 h-44 bg-blue-700 hover:bg-blue-800 rounded font-bold text-base text-white"
                        disabled={submitting}
                >
                  {submitting &&
                  <ReactSVG
                    src={`${lc_data.dir}dist/${LoaderIconWhite}`}
                    className="relative"
                    style={{ top: '-12px', zoom: 1 }}
                  />
                  }
                  {!submitting &&
                  <Fragment>
                    <ReactSVG
                      src={`${lc_data.dir}dist/${SaveIcon}`}
                      className="relative mr-8 w-20 h-20 fill-white"
                    />
                    {predefined ?
                      sprintf(lc_data.jst[52], groupEdit.single_name)
                      :
                      lc_data.jst[94]
                    }
                  </Fragment>}
                </button>
              </div>
            </form>
          </Modal>
        </div>
        }
        {ieModal &&
        <div
          className="modal--wrapper fixed top-0 left-0 flex justify-center w-full h-full overflow-y-auto"
        >
          <Modal classes="form-import-export"
                 closeModal={closeModal}
                 title={lc_data.jst[13]}
                 open={ieModal}
                 handleClickOutside={(e) => handleClickOutside(e, 'ie')}
          >
            <div className="flex flex-wrap justify-between">
              <GroupExport/>
              <GroupImport/>
            </div>
          </Modal>
        </div>
        }
      </section>
      <ToastContainer/>
    </Fragment>
  );
};

export default Groups;
