/* global React, lc_data */
import React, { useState, useLayoutEffect, useRef, Fragment } from 'react';
import { FixedSizeList, areEqual } from 'react-window';
import { DragDropContext, Droppable, Draggable } from 'react-beautiful-dnd';
import { reorderList } from './reorder';
import { map, filter, isEmpty } from 'lodash';
import axios from 'axios';
import { useEffect } from '@wordpress/element';
import { produce } from 'immer';
import { toast, ToastContainer } from 'react-toastify';
import ReactSVG from 'react-svg';
import PencilIcon from '../../../../images/icons/pencil.svg';
import TrashIcon from '../../../../images/icons/trash.svg';
import ImportIcon from '../../../../images/icons/download.svg';
import ModalNew from '../../../theme/packages/components/modal/ModalNew';
import { sprintf } from '@wordpress/i18n';
import FileField from '../fields/File';
import SelectField from '../fields/SelectField';
import TextField from '../fields/TextField';
import apiFetch from '@wordpress/api-fetch';
import SaveIcon from '../../../../images/icons/save.svg';
import PlusIcon from '../../../../images/icons/plus.svg';
import Spinner from '../../../../images/icons/spinner.svg';
import he from 'he';
import TaxonomyImport from './TaxonomyImport';
import { isLodash } from '../../../theme/vendor/functions';

const getParent = (taxonomies, taxonomy) => {
  const parent = taxonomies[taxonomy.parent];
  if (parent) {
    return parent;
  }

  return false;
};

const getParents = (taxonomies, taxonomy, parents) => {
  parents = parents || [];
  if (!taxonomy) {
    return false;
  }
  const parent = getParent(taxonomies, taxonomy);
  if (parent) {
    parents.push(parent);
    getParents(taxonomies, parent, parents);
  }

  return parents;
};

function getStyle({ draggableStyle, virtualStyle, isDragging }) {
  // If you don't want any spacing between your items
  // then you could just return this.
  // I do a little bit of magic to have some nice visual space
  // between the row items
  const combined = {
    ...virtualStyle,
    ...draggableStyle
  };

  // Being lazy: this is defined in our css file
  const grid = 8;

  // when dragging we want to use the draggable style for placement, otherwise use the virtual style
  const result = {
    ...combined,
    height: isDragging ? combined.height : combined.height - grid,
    left: isDragging ? combined.left : combined.left + grid,
    width: isDragging
      ? draggableStyle.width
      : `calc(${combined.width} - ${grid * 2}px)`,
    marginBottom: grid
  };

  return result;
}

function Item({ provided, item, taxonomy, terms, allTerms, style, isDragging, handleTermModal }) {
  return (
    <div
      {...provided.draggableProps}
      {...provided.dragHandleProps}
      ref={provided.innerRef}
      style={getStyle({
        draggableStyle: provided.draggableProps.style,
        virtualStyle: style,
        isDragging
      })}
      className={`item flex flex-wrap p-20 bg-white rounded font-bold ${isDragging ? 'is-dragging' : ''}`}
    >
      <div className="relative flex w-full no-scroll">
        {item?.name &&
        <div className="relative inline-block">
          {item?.meta?.premium === 'yes' &&
          <span className="absolute text-sm text-red-600" style={{ right: -6, top: -4 }}>*</span>}
          <span>
          {he.decode(item.name)}
          </span>
        </div>}
        <button
          type="button"
          className="term--edit flex ml-auto no-scroll"
          onClick={(e, item) => handleTermModal(item, taxonomy)}
        >
          <ReactSVG
            src={`${lc_data.dir}dist/${PencilIcon}`}
            className="relative w-14 h-14 fill-grey-900 cursor-pointer pointer-events-none no-scroll"
          />
        </button>

      </div>
      {terms && (terms[item?.parent]) &&
      <div className="item__content mt-4 font-normal text-grey-400 text-sm capitalize">
        {`${terms[item?.parent]?.taxonomy && he.decode(terms[item?.parent]?.taxonomy.replace('-', '-'))}: ${terms[item?.parent]?.name && he.decode(terms[item?.parent]?.name)}`}
      </div>
      }
    </div>
  );
}

// Recommended react-window performance optimisation: memoize the row render function
// Things are still pretty fast without this, but I am a sucker for making things faster
const Row = React.memo(function Row(props) {
  const { data, index, style } = props;
  const { taxonomy, handleTermModal } = data;
  const item = data.terms[index];

  // We are rendering an extra item for the placeholder
  if (!item) {
    return null;
  }

  return (
    <Draggable draggableId={`${data.taxonomy.slug}-${item.term_id}`} index={index} key={item.term_id}>
      {provided => <Item provided={provided} item={item} style={style} taxonomy={taxonomy} terms={data.allTerms}
                         handleTermModal={(e) => handleTermModal(item, taxonomy)}/>}
    </Draggable>
  );
}, areEqual);

const ItemList = React.memo(function ItemList({ taxonomy, terms, index, handleTermModal }) {
  const taxTerms = taxonomy.term_ids && taxonomy.term_ids.map(termId => terms[termId.replace(`${taxonomy.slug}-`, '')]);
  const listRef = useRef();
  useLayoutEffect(() => {
    const list = listRef.current;
    if (list) {
      list.scrollTo(0);
    }
  }, [index]);

  return taxTerms && (
    <Droppable
      droppableId={taxonomy.slug}
      mode="virtual"
      renderClone={(provided, snapshot, rubric) => {
        const item = taxTerms[rubric.source.index];
        return (
          <Item
            provided={provided}
            isDragging={snapshot.isDragging}
            item={item}
            taxonomy={taxonomy}
            terms={terms}
            handleTermModal={(e) => handleTermModal(item, taxonomy)}
          />
        );
      }}
    >
      {(provided, snapshot) => {
        // Add an extra item to our list to make space for a dragging item
        // Usually the DroppableProvided.placeholder does this, but that won't
        // work in a virtual list
        const itemCount = snapshot.isUsingPlaceholder
          ? taxonomy.term_ids.length + 1
          : taxonomy.term_ids.length;

        return (
          <FixedSizeList
            height={700}
            itemCount={itemCount}
            itemSize={80}
            width={300}
            outerRef={provided.innerRef}
            itemData={{ taxonomy, terms: taxTerms, handleTermModal, allTerms: terms, }}
            className="task-list"
            ref={listRef}
          >
            {Row}
          </FixedSizeList>
        );
      }}
    </Droppable>
  );
});

const Column = React.memo(function Column({
  taxonomies,
  taxonomy,
  terms,
  index,
  handleModal,
  handleTaxonomyDelete,
  handleTermModal,
  handleIeModal,
}) {
  const parents = getParents(taxonomies, taxonomy);
  const margin = parents && parents.length > 0 ? parents.length * 30 : 0;
  return (
    taxonomy &&
    <Draggable draggableId={taxonomy.slug} index={index}>
      {(provided, snapshot) => (
        <div
          className="column px-4"
          {...provided.draggableProps}
          ref={provided.innerRef}
        >
          <div className="flex"
               style={{
                 marginTop: margin,
               }}
          >
            <h3 className="relative mb-10 px-10 font-semibold text-lg no-scroll" {...provided.dragHandleProps}>
              {taxonomy?.premium === 'yes' &&
              <span className="absolute text-sm text-red-600" style={{ right: 4, top: -4 }}>*</span>}
              <span className="no-scroll">{taxonomy.plural_name}</span>
            </h3>
            <div className="actions flex self-start ml-auto pr-8">
              <button
                type="button"
                onClick={e => handleModal(e, taxonomy, index)}
                className="taxonomy--edit mr-10"
              >
                <ReactSVG
                  src={`${lc_data.dir}dist/${PencilIcon}`}
                  className="relative w-16 h-16 fill-grey-900 cursor-pointer pointer-events-none"
                />
              </button>
              <button
                type="button"
                onClick={e => handleIeModal(e)}
                className="taxonomy--import mr-10"
              >
                <ReactSVG
                  src={`${lc_data.dir}dist/${ImportIcon}`}
                  className="relative w-16 h-16 fill-grey-900 cursor-pointer pointer-events-none"
                />
              </button>
              <button
                type="button"
                onClick={e => handleTaxonomyDelete(e, taxonomy)}
                className="taxonomy--remove"
              >
                <ReactSVG
                  src={`${lc_data.dir}dist/${TrashIcon}`}
                  className="relative w-16 h-16 fill-grey-900 cursor-pointer pointer-events-none"
                />
              </button>
            </div>
          </div>

          {parents &&
          <div className="taxonomy--parents flex flex-wrap -mt-6 mb-10">
            {map(parents, (parent, index) => (
              <span key={index} className="font-normal text-grey-400"><span
                className="mx-4">/</span>{parent.single_name}</span>
            ))}
          </div>
          }

          <div className={`column--inner py-10 px-2 rounded ${snapshot.isDragging ? 'is-dragging' : ''}`}>
            <button
              type="button"
              className="term--edit flex justify-center items-center py-6 mx-10 mb-10 bg-grey-300 rounded font-bold text-grey-900 hover:bg-grey-400"
              onClick={e => handleTermModal(false, taxonomy)}
              style={{
                width: '94%',
              }}
            >
              <ReactSVG
                src={`${lc_data.dir}dist/${PlusIcon}`}
                className="relative mr-8 w-14 h-14 fill-grey-900 pointer-events-none"
              />
              {lc_data.jst[99]}
            </button>
            <ItemList taxonomy={taxonomy} terms={terms} index={index} handleTermModal={handleTermModal}/>
          </div>
        </div>
      )}
    </Draggable>
  );
});

let isDown = false;
let startX;
let scrollLeft;
const Taxonomies = (props) => {
  if ( isLodash() ) {
    _.noConflict();
  }
  const [group, setGroup] = useState(document.getElementById('field_group').value || '');
  const [state, setState] = useState({ taxonomiesOrder: [], taxonomies: {}, terms: {} });
  const [terms, setTerms] = useState(terms);
  const [modalOpen, setModalOpen] = useState(false);
  const [termModalOpen, setTermModalOpen] = useState(false);
  const [editData, setEditData] = useState(null);
  const [taxonomyData, setTaxonomyData] = useState({});
  const [fields, setFields] = useState({});
  const [termFields, setTermFields] = useState({});
  const [updateMode, setUpdateMode] = useState(null);
  const [termTaxonomy, setTermTaxonomy] = useState(null);
  const [termsData, setTermsData] = useState({});
  const [newTermData, setNewTermData] = useState({});
  const [submitting, setSubmitting] = useState(false);
  const [updating, setUpdating] = useState(false);
  const [notice, setNotice] = useState(null);
  const [ieModal, setIeModal] = useState(false);

  const wrapper = useRef(null);

  const handleIeModal = (e, taxonomy) => {
    setEditData(taxonomy);
    setIeModal(!ieModal);
  };

  const organizeTaxonomies = (taxonomies) => {
    const taxes = {};
    const order = [];
    taxonomies && map(taxonomies, taxonomy => {
      taxes[taxonomy.slug] = taxonomy;
      taxes[taxonomy.slug].key = taxonomy.slug;
      order.push(taxonomy.slug);
    });
    return { taxonomies: taxes, taxonomiesOrder: order };
  };

  const getTerms = async () => {
    const headers = { 'X-WP-Nonce': lc_data.nonce };
    const terms = await axios({
      url: `${lc_data.terms_by_group}/${group}`,
      method: 'GET',
      credentials: 'same-origin',
      headers,
    }).then(response => {
      const taxes = organizeTaxonomies(props.taxonomies[group]);
      const newState = produce(state, draft => {
        draft.taxonomies = taxes.taxonomies;
        draft.taxonomiesOrder = taxes.taxonomiesOrder;
        draft.terms = response.data;
        setTermsData(draft.terms);
        setTaxonomyData(draft.taxonomies);
      });
      setState(newState);
    });
  };

  const fetchFields = async () => {
    await apiFetch({ path: lc_data.taxonomy_fields }).then((fields) => {
      setFields(fields);
    });
    await apiFetch({ path: lc_data.term_fields }).then((fields) => {
      setTermFields(fields);
    });
  };

  const mouseDown = (e) => {
    const classes = e.target.classList;
    if (classes.contains('builder--wrapper') || classes.contains('item') || classes.contains('no-scroll')
    ) {
      isDown = false;
    } else {
      isDown = true;
      wrapper.current.classList.add('active');
      startX = e.pageX - wrapper.current.offsetLeft;
      scrollLeft = wrapper.current.scrollLeft;
    }
  };

  const mouseLeave = () => {
    isDown = false;
    wrapper.current.classList.remove('active');
  };

  const mouseUp = () => {
    isDown = false;
    wrapper.current.classList.remove('active');
  };

  const mouseMove = (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - wrapper.current.offsetLeft;
    const walk = (x - startX) * 3; //scroll-fast
    wrapper.current.scrollLeft = scrollLeft - walk;
  };

  useEffect(() => {
    if (wrapper.current) {
      wrapper.current.addEventListener('mousedown', mouseDown);
      wrapper.current.addEventListener('mouseleave', mouseLeave);
      wrapper.current.addEventListener('mouseup', mouseUp);
      wrapper.current.addEventListener('mousemove', mouseMove);
    }

    fetchFields();

    return () => {
      if (wrapper.current) {
        wrapper.current.removeEventListener('mousedown', mouseDown);
        wrapper.current.removeEventListener('mouseleave', mouseLeave);
        wrapper.current.removeEventListener('mouseup', mouseUp);
        wrapper.current.removeEventListener('mousemove', mouseMove);
      }
    };
  }, []);

  useEffect(() => {
    getTerms();
  }, [props.taxonomies]);

  const updateTaxonomiesOrder = (order) => {
    setUpdating(true);
    const headers = { 'X-WP-Nonce': lc_data.nonce };
    axios({
      url: lc_data.taxonomy_options_edit,
      method: 'POST',
      credentials: 'same-origin',
      headers,
      data: {
        action: 'update_order',
        order,
        group,
      }
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

  const updateTermsOrder = (taxonomy, term_ids) => {
    setUpdating(true);
    const headers = { 'X-WP-Nonce': lc_data.nonce };
    axios({
      url: lc_data.terms_edit,
      method: 'POST',
      credentials: 'same-origin',
      headers,
      data: {
        action: 'update_order',
        taxonomy,
        term_ids,
        group,
      }
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

  function onDragEnd(result) {
    if (!result.destination) {
      return;
    }

    if (result.source.droppableId !== result.destination.droppableId) {
      return;
    }

    if (result.type === 'column') {
      // if the list is scrolled it looks like there is some strangeness going on
      // with react-window. It looks to be scrolling back to scroll: 0
      // I should log an issue with the project
      const columnOrder = reorderList(
        state.taxonomiesOrder,
        result.source.index,
        result.destination.index
      );

      updateTaxonomiesOrder(columnOrder);
      setState({
        ...state,
        taxonomiesOrder: columnOrder,
      });
      return;
    }

    // reordering in same list
    if (result.source.droppableId === result.destination.droppableId) {
      const column = state.taxonomies[result.source.droppableId];
      const items = reorderList(
        column.term_ids,
        result.source.index,
        result.destination.index
      );

      // updating column entry
      const newState = {
        ...state,
        taxonomies: {
          ...state.taxonomies,
          [result.source.droppableId]: {
            ...column,
            term_ids: items,
          }
        }
      };

      updateTermsOrder(result.source.droppableId, items);
      setState(newState);
    }
  }

  const onDragStart = (start, provided) => {
  };

  const handleModal = (e, taxonomy, index) => {
    setUpdateMode('edit_taxonomy');
    setModalOpen(taxonomy.slug);
    setEditData(taxonomy);
  };

  const handleTermModal = (term, taxonomy) => {
    setTermTaxonomy(taxonomy);
    setTermModalOpen(true);
    if (term) {
      setEditData(term);
      setUpdateMode('edit_term');
    } else {
      setNewTermData({});
      setEditData({});
      setUpdateMode('new_term');
    }
  };

  const handleChange = (name, value, type) => {
    setNotice(null);
    if ('taxonomy' === type) {
      const newData = produce(taxonomyData, draft => {
        draft[editData.slug][name] = value;
      });
      setTaxonomyData(newData);
    }
    if ('term' === type) {
      // editing term.
      if (updateMode === 'edit_term') {
        const newData = produce(editData, draft => {
          if (draft[name] || draft[name] === 0) {
            draft[name] = value;
          } else if (draft[name] === '') {
            draft[name] = value;
          } else if (draft.meta[name]) {
            draft.meta[name] = value;
          } else {
            draft.meta[name] = value;
          }
        });
        setEditData(newData);
      } else {
        // submitting a new term.
        const data = { ...newTermData };
        data[name] = value;
        setNewTermData(data);
      }
    }
  };

  const submitTerm = () => {
    setSubmitting(true);
    const headers = { 'X-WP-Nonce': lc_data.nonce };

    const formData = { ...newTermData };
    formData['field_group'] = group;
    formData['taxonomy-slug'] = termTaxonomy?.slug || '';
    formData['parentTaxonomy'] = termTaxonomy?.parent || '';

    axios({
      url: lc_data.term_store,
      method: 'POST',
      credentials: 'same-origin',
      headers,
      data: formData,
    }).then(response => {
      if (response.data.data.success) {
        toast(response.data.data.message, {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
        });
        const newState = { ...state };
        const newTerm = response.data.data.term;
        const termName = newTerm.term_id;
        newState.terms = { ...newState.terms, ...{ [termName]: newTerm } };
        newState.taxonomies[termTaxonomy.slug].term_ids.push(`${termTaxonomy.slug}-${termName}`);
        setTermsData(newState.terms);
        setState(newState);
        setTermModalOpen(false);
      }
      if (response.data.data.error) {
        setNotice(response.data.data.message);
        toast.error(response.data.data.message, {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
        });
      }
      setSubmitting(false);
    });
  };

  const updateTerm = () => {
    setSubmitting(true);
    const headers = { 'X-WP-Nonce': lc_data.nonce };

    const formData = editData;

    axios({
      url: `${lc_data.term_edit}/${formData.term_id}`,
      method: 'POST',
      credentials: 'same-origin',
      headers,
      data: {
        data: formData,
        group,
        parentTaxonomy: termTaxonomy?.parent || '',
      },
    }).then(response => {
      if (response.data.data.success) {
        toast(response.data.data.message, {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
        });
        const newState = { ...state };
        const term = response.data.data.term;
        newState.terms[term.term_id] = {};
        newState.terms[term.term_id] = term;
        if (response.data.data?.term_ids) {
          newState.taxonomies[term.taxonomy].term_ids = response.data.data.term_ids;
        }
        setTermsData(newState.terms);
        setState(newState);
        setTermModalOpen(false);
      }
      if (response.data.data.error) {
        setNotice(response.data.data.message);
        toast.error(response.data.data.message, {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
        });
      }
      setSubmitting(false);
    });
  };

  const deleteTerm = () => {
    if (!confirm(lc_data.jst[96])) {
      return false;
    }
    setUpdating(true);
    const headers = { 'X-WP-Nonce': lc_data.nonce };

    const formData = { ...editData };

    axios({
      url: lc_data.term_remove,
      method: 'POST',
      credentials: 'same-origin',
      headers,
      data: {
        data: formData,
        group,
      },
    }).then(response => {
      if (response.data.data.success) {
        toast(response.data.data.message, {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
        });
        const newState = { ...state };
        newState.taxonomies[editData.taxonomy].term_ids.splice(response.data.data.term_key, 1);
        if (response.data.data.child_terms) {
          map(response.data.data.child_terms, (option, term_id) => {
            const childTaxonomy = option.replace(`-${term_id}`, '');
            map(newState.taxonomies[childTaxonomy].term_ids, (value, index) => {
              if (value === option) {
                newState.taxonomies[childTaxonomy].term_ids.splice(index, 1);
              }
            });
          });
        }
        setTermsData(newState.terms);
        setState(newState);
        setTermModalOpen(false);
      }
      if (response.data.data.error) {
        setNotice(response.data.data.message);
        toast.error(response.data.data.message, {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
        });
      }
      setUpdating(false);
    });
  };

  const updateTaxonomy = () => {
    setSubmitting(true);
    const headers = { 'X-WP-Nonce': lc_data.nonce };

    const formData = taxonomyData[editData.slug];

    axios({
      url: lc_data.taxonomy_options_edit,
      method: 'POST',
      credentials: 'same-origin',
      headers,
      data: {
        action: 'update_option',
        data: formData,
        old_slug: editData.slug,
      }
    }).then(response => {
      if (response.data.success) {
        toast(response.data.data.message, {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
        });
        const newState = { ...state };
        delete newState.taxonomies[editData.slug];
        newState.taxonomies[response.data.data.slug] = {};
        newState.taxonomies[response.data.data.slug] = response.data.data.taxonomy;
        newState.taxonomiesOrder.splice(response.data.data.order, 1, response.data.data.slug);

        setTaxonomyData(newState.taxonomies);
        setState(newState);
        setModalOpen(false);
      }
      if (response.data.data.error) {
        setNotice(response.data.data.message);
        toast.error(response.data.data.message, {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
        });
      }
      setSubmitting(false);
    });
  };

  const handleTaxonomyDelete = (e, taxonomy) => {
    if (!confirm(lc_data.jst[86])) {
      return false;
    }
    setUpdating(true);
    const headers = { 'X-WP-Nonce': lc_data.nonce };

    axios({
      url: lc_data.taxonomy_options_delete,
      method: 'POST',
      credentials: 'same-origin',
      headers,
      data: {
        fields: taxonomy,
        group: group,
      },
    }).then(response => {
      if (response.data.success) {
        toast(response.data.data.message, {
          position: toast.POSITION.TOP_RIGHT,
          autoClose: 3000,
        });
        const newState = { ...state };
        delete newState.taxonomies[taxonomy.slug];
        newState.taxonomiesOrder.splice(response.data.data.order, 1);

        if (isEmpty(newState.taxonomiesOrder)) {
          props.emptyTaxonomies(true);
          props.taxonomies[group] = newState.taxonomies;
        }

        setTaxonomyData(newState.taxonomies);
        setState(newState);
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

  const showField = (value, name, type) => {
    switch (value.type) {
      case 'file':
        return <FileField
          key={value.key}
          value={editData && editData[name] ? editData[name] : (editData?.meta ? editData.meta[name] : '')}
          name={name}
          buttonLabel={value.label || lc_data.jst[69]}
          mediaLibraryButtonLabel={value.label || lc_data.jst[69]}
          mediaLibraryTitle={value.label || lc_data.jst[69]}
          field={value}
          handleChange={(name, value) => handleChange(name, value, type)}
        />;
      case 'select':
        let choices = value.choices;
        if ((updateMode === 'new_term' || updateMode === 'edit_term') && 'parent' === name) {
          const parent = filter(state.taxonomies, { 'slug': termTaxonomy.parent });
          const parentTerms = parent[0] && filter(state.terms, { 'taxonomy': parent[0].slug });
          if (parentTerms && parentTerms.length > 0) {
            choices = [];
            choices.push({ key: 0, value: lc_data.jst[97] });
            map(parentTerms, term => {
              if (state.taxonomies[parent[0].slug].term_ids.includes(`${term.taxonomy}-${term.term_id}`)) {
                choices.push({ key: term.term_id, value: term.name });
              }
            });
          }
        }
        if (updateMode === 'edit_taxonomy' && 'parent' === name) {
          choices = [{ key: '', value: lc_data.jst[97] }];
          state.taxonomies && map(state.taxonomies, tax => {
              if (tax && editData && editData.slug !== tax.slug && editData.slug !== tax.parent) {
                choices.push({ key: tax.slug, value: tax.single_name });
              }
            }
          );
        }
        let display = true;
        if (editData && value?.conditional) {
          if (value.conditional[1] === 'IN') {
            //display = value.conditional[2].includes(editData.type);
          }
        }
        return <SelectField
          display={display}
          key={value.key}
          id={name}
          name={name}
          label={value.label}
          value={editData && editData[name] ? editData[name] : (editData?.meta?.[name] ? editData.meta[name] : '')}
          description={value.description}
          options={choices}
          field={value}
          taxonomy={editData?.slug || ''}
          handleChange={(name, value) => handleChange(name, value, type)}
        />;
      default: {
        return <TextField
          key={value.key}
          id={name}
          name={name}
          label={value.label}
          value={editData ? editData[name] : ''}
          description={value.description}
          field={value}
          handleChange={(name, value) => handleChange(name, value, type)}
        />;
      }
    }
  };

  return (
    <DragDropContext onDragEnd={onDragEnd} onDragStart={onDragStart}>
      <div className="relative flex builder--wrapper overflow-y-auto"
           ref={wrapper}
      >
        {(updating || submitting || props.taxSubmitting) &&
        <span className="absolute top-0 left-0 w-full h-full bg-grey-100 opacity-50 cursor-default z-10"></span>}
        <Droppable
          droppableId="all-droppables"
          direction="horizontal"
          type="column"
        >
          {provided => (
            <div
              className="flex columns -mx-4"
              {...provided.droppableProps}
              ref={provided.innerRef}
            >
              {state.taxonomiesOrder.map((columnId, index) => (
                <Column
                  key={columnId}
                  taxonomies={state.taxonomies}
                  taxonomy={state.taxonomies[columnId]}
                  terms={state.terms}
                  index={index}
                  handleModal={e => handleModal(e, state.taxonomies[columnId], index)}
                  handleTaxonomyDelete={(e, taxonomy) => handleTaxonomyDelete(e, taxonomy)}
                  handleTermModal={handleTermModal}
                  handleIeModal={(e) => handleIeModal(e, state.taxonomies[columnId])}
                />
              ))}
              {provided.placeholder}
            </div>
          )}
        </Droppable>
      </div>
      <ModalNew
        open={modalOpen}
        closeModal={() => setModalOpen(false)}
        title={sprintf(lc_data.jst[76], editData?.single_name || '')}
      >
        <div className="flex flex-wrap justify-between">
          {map(fields.general, (value, name) => (
            showField(value, name, 'taxonomy')
          ))}
          <div className="flex justify-end w-full">
            <button
              className="relative flex justify-center items-center py-12 px-24 h-44 bg-blue-700 hover:bg-blue-800 rounded font-bold text-base text-white"
              onClick={updateTaxonomy}
              disabled={submitting}
            >
              {submitting &&
              <Fragment>
                <ReactSVG
                  src={`${lc_data.dir}dist/${Spinner}`}
                  className="absolute"
                  style={{ zoom: .2 }}
                />
                <span className="opacity-0">{lc_data.jst[52]}</span>
              </Fragment>
              }
              {!submitting &&
              <Fragment>
                <ReactSVG
                  src={`${lc_data.dir}dist/${SaveIcon}`}
                  className="relative mr-8 w-20 h-20 fill-white"
                />
                {lc_data.jst[52]}
              </Fragment>
              }
            </button>
          </div>
        </div>
        {notice &&
        <div className="response response--error flex items-center">
          <span className="text-base text-red-700">{notice}</span>
        </div>
        }
      </ModalNew>
      <ModalNew
        open={termModalOpen}
        closeModal={() => setTermModalOpen(false)}
        title={sprintf(lc_data.jst[76], editData?.single_name || '')}
      >
        <div className="flex flex-wrap justify-between">
          {map(termFields.general, (value, name) => (
            showField(value, name, 'term')
          ))}
          <div className="flex justify-end w-full">
            <button
              className="relative flex justify-center items-center py-12 px-24 h-44 bg-blue-700 hover:bg-blue-800 rounded font-bold text-base text-white"
              onClick={() => {
                if (updateMode === 'edit_term') {
                  updateTerm();
                } else {
                  submitTerm();
                }
              }}
              disabled={submitting}
            >
              {submitting &&
              <Fragment>
                <ReactSVG
                  src={`${lc_data.dir}dist/${Spinner}`}
                  className="absolute"
                  style={{ zoom: .2 }}
                />
                <span className="opacity-0">{lc_data.jst[52]}</span>
              </Fragment>
              }
              {!submitting &&
              <Fragment>
                <ReactSVG
                  src={`${lc_data.dir}dist/${SaveIcon}`}
                  className="relative mr-8 w-20 h-20 fill-white"
                />
                {lc_data.jst[52]}
              </Fragment>
              }
            </button>
            {updateMode === 'edit_term' &&
            <button
              type="button"
              className="term--delete flex justify-center items-center ml-10 py-12 px-24 h-44 bg-red-700 hover:bg-red-800 rounded font-bold text-base text-white"
              onClick={e => deleteTerm()}
            >
              <ReactSVG
                src={`${lc_data.dir}dist/${TrashIcon}`}
                className="relative w-20 h-20 fill-white"
              />
            </button>
            }
          </div>
        </div>
        {notice &&
        <div className="response response--error flex items-center">
          <span className="text-base text-red-700">{notice && he.decode(notice)}</span>
        </div>
        }
      </ModalNew>
      {ieModal &&
      <ModalNew
        closeModal={() => setIeModal(false)}
        title={lc_data.jst[13]}
        open={ieModal}
      >
        <div className="flex flex-wrap justify-between">
          <TaxonomyImport taxonomy={editData} taxonomies={state.taxonomies} terms={state.terms}/>
        </div>
      </ModalNew>
      }
      <ToastContainer/>
    </DragDropContext>
  );
};

export default Taxonomies;

