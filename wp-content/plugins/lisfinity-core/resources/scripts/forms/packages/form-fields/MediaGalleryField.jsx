/* global lc_data, React */
/**
 * External dependencies.
 */
import produce from 'immer';
import { withEffects } from 'refract-callbag';
import { map, pipe } from 'callbag-basics';
import { __, _n, sprintf } from '@wordpress/i18n';
import { Component, createRef } from '@wordpress/element';
import { withState, compose } from '@wordpress/compose';
import { get, isEmpty } from 'lodash';

/**
 * Internal dependencies.
 */
import * as actions from '../../../dashboard/packages/store/actions';
import MediaLibrary from '../components/MediaLibrary';
import Sortable from '../components/sortable/sortable';
import fetchAttachmentsData from '../../utils/fetch-attachments-data';
import getCurrentUser from '../../utils/get-current-user';
import ReactSVG from 'react-svg';
import QuestionIcon from '../../../../images/icons/question-circle.svg';
import PlusIcon from '../../../../images/icons/plus.svg';
import CloseIcon from '../../../../images/icons/close.svg';
import ReactTooltip from 'react-tooltip';
import { Fragment } from 'react';
import { formatMoney } from '../../../theme/vendor/functions';
import { connect } from 'react-redux';
import ModalDemo from '../../../theme/packages/components/modal/ModalDemo';

class MediaGalleryField extends Component {
  constructor(props) {
    super(props);
    this.state = {
      modalOpen: false,
    };
  }

  /**
   * Keeps reference to the list that contains selected attachments.
   *
   * @type {Object}
   */
  attachmentsList = createRef();

  /**
   * Handles the file selection.
   *
   * @param  {Object} attachments
   * @return {void}
   */
  handleSelect = (attachments) => {
    const {
      id,
      onChange,
      setState,
      value,
      media,
      field,
      name,
      dispatch,
    } = this.props;
    let { costs } = this.props;

    if (costs === 0) {
      costs = {};
    }


    // calculate the maximum number of media and prepare for storing in the state.
    const newMedia = attachments.map((attachment) => attachment.id);
    const values = [...value, ...newMedia];
    if (values.length > media.limit) {
      if (media.limit === 1) {
        setState({ notice: sprintf(lc_data.jst[358], media.limit, field.labels[0]) });
      } else {
        setState({ notice: sprintf(lc_data.jst[358], media.limit, field.labels[1]) });
      }
      return;
    }

    onChange(id, values);

    const allAttachments = [...this.props.attachmentsData, ...attachments];
    setState({
      attachmentsData: allAttachments,
    });
    setState({ notice: '' });

    // calculate the additional costs.
    let cost = media.price;
    dispatch(actions.updateCosts({}));
    if (values.length >= media.free) {
      cost = (values.length - media.free) * media.price;
      setState(prevState => ({
        media: {
          ...prevState.media,
          ['cost']: cost,
        }
      }));
      if (!costs['media']) {
        costs['media'] = {};
      }
      if (!costs['total']) {
        costs['total'] = {};
      }
      if (!costs['total']['media']) {
        costs['total']['media'] = 0;
      }

      if (costs['media'] && costs['media'][name] && costs['media'][name].cost && costs['media'][name].cost > 0) {
        costs['total']['media'] -= costs['media'][name].cost;
      }
      costs['media'][name] = { cost };
      costs['total']['media'] += cost;
      costs['final'] = (costs.total.media) + (costs['total']['promo'] || 0) || 0;
      dispatch(actions.updateCosts(costs));
    }
  };

  /**
   * Handles the removal of an item.
   *
   * @param  {number} index
   * @return {void}
   */
  handleAttachmentRemove = (index) => {
    const {
      id,
      value,
      onChange,
      media,
      setState,
      name,
      costs,
      dispatch,
    } = this.props;

    if (!confirm(lc_data.jst[589])) {
      return false;
    }

    onChange(id, produce(value, (draft) => {
      draft.splice(index, 1);
    }));

    // calculate the additional costs.
    let newCost = { ...costs, ...{} };
    const values = value.length - 1;
    let cost = media.price;


    if (values >= media.free) {
      cost = (values - media.free) * media.price;
      setState(prevState => ({
        media: {
          ...prevState.media,
          ['cost']: cost,
        }
      }));

      newCost['media'][name] = { cost };
      newCost['total']['media'] -= media.price;


      newCost['final'] = (costs.total.media) + (costs['total']['promo'] || 0) || 0;
    }
    dispatch(actions.updateCosts(newCost));
  };

  /**
   * Handles the media item selection.
   *
   * @param  {number} itemId
   * @return {void}
   */
  handleAttachmentSelect = (itemId) => {
    const { setState } = this.props;

    setState(({ selectedItem }) => ({
      selectedItem: selectedItem !== itemId ? itemId : null
    }));
  };

  /**
   * Handles sorting of attachments.
   *
   * @param  {Object[]} attachments
   * @return {void}
   */
  handleSort = (attachments) => {
    const { id, onChange } = this.props;

    onChange(id, attachments);
  };

  /**
   * Render the component.
   *
   * @return {Object}
   */
  render() {
    const {
      name,
      value,
      field,
      buttonLabel,
      formData,
      mediaLibraryButtonLabel,
      mediaLibraryTitle,
      attachmentsData,
      selectedItem,
      notice,
      media,
      label,
      description,
      payment_package,
      error,
      product,
      costs
    } = this.props;

    const freeCount = parseInt(media.free, 10) < value.length ? parseInt(media.free, 10) : value.length;
    const toPayCount = value.length - parseInt(media.free, 10);

    return (
      <div className="flex flex-col mb-20">
        <div className="relative flex mb-10">
          <span
            className="text-sm text-grey-500"
            dangerouslySetInnerHTML={{
              __html: sprintf(name === '_product_image_gallery' ? lc_data.jst[351] : lc_data.jst[739], `<span class="font-semibold text-grey-1000">${label}</span>`, field.size_limit || '2mb')
            }}></span>
          {description &&
          <ReactSVG src={`${lc_data.dir}dist/${QuestionIcon}`} data-tip={description}
                    className="relative left-6 w-14 h-14 fill-blue-700" style={{ top: '-8px' }}/>}
          {description && <ReactTooltip/>}
          {notice &&
          <div className="response response--error flex absolute right-0 text-sm text-red-700">
            <span className="text-sm">{notice}</span>
          </div>
          }
          {error &&
          <div className="response response--error flex absolute right-0 text-sm text-red-700">
            <span className="text-sm">{error}</span>
          </div>
          }
        </div>
        <Sortable
          items={value}
          forwardedRef={this.attachmentsList}
          options={{
            handle: '.gallery-sort-handle',
            forcePlaceholderSize: true
          }}
          onUpdate={this.handleSort}
        >
          <MediaLibrary
            onSelect={this.handleSelect}
            multiple={field.multiple}
            title={mediaLibraryTitle}
            buttonLabel={mediaLibraryButtonLabel}
            typeFilter={field.type_filter}
          >
            {
              ({ openMediaBrowser }) => {
                return (
                  <Fragment>
                    <div className="flex flex-wrap p-20 bg-grey-100 border border-grey-300 rounded">

                      <div
                        className={`dashboard--gallery flex flex-wrap w-full ${this.attachmentsList.length > 0 ? '-mx-4' : ''}`}>
                        <div className="gallery--button px-4 mb-0 xl:mb-20 w-1/6">
                          <button
                            type="button"
                            className="flex-center p-30 bg-white rounded shadow-theme hover:shadow-lg"
                            onClick={() => {
                              if (lc_data.is_demo) {
                                this.setState({ modalOpen: true });
                                return false;
                              } else {
                                openMediaBrowser();
                              }
                            }}
                          >
                            <ReactSVG src={`${lc_data.dir}dist/${PlusIcon}`}
                                      className="relative w-30 h-30 fill-grey-700"/>
                          </button>
                        </div>

                        <div className="gallery-items flex flex-wrap -mb-10 w-5/6" ref={this.attachmentsList}>
                          {value.map((id, index) => { // eslint-disable-line no-shadow
                            let attachment = false;
                            if (typeof id === 'object') {
                              attachment = attachmentsData.find((attachmentData) => attachmentData.id = parseInt(id.file, 10));
                            } else {
                              attachment = attachmentsData.find((attachmentData) => attachmentData.id === parseInt(id, 10));
                            }
                            const className = ['cf-media-gallery__item list-reset'];

                            if (attachment) {
                              className.push(`cf-media-gallery__item--${attachment.type}`);
                            }

                            if (selectedItem === index) {
                              className.push('cf-media-gallery__item--selected');
                            }

                            if (!attachment) {
                              return null;
                            }

                            return (
                              <div className={`gallery--box mb-10 px-4 ${className.join(' ')}`} key={index}
                                   onClick={() => this.handleAttachmentSelect(index)}
                              >
                                <div
                                  className={`gallery-sort-handle flex-center relative border-2 rounded overflow-hidden cursor-pointer ${(index + 1) > media.free ? 'border-red-600' : 'border-green-700'}`}
                                  style={{ height: '91px' }}
                                >
                                  <button
                                    type="button"
                                    className="absolute top-10 right-10 z-1"
                                    onClick={() => this.handleAttachmentRemove(index)}
                                  >
                                    <ReactSVG src={`${lc_data.dir}dist/${CloseIcon}`} data-tip={description}
                                              className="w-10 h-10 fill-white"/>
                                  </button>
                                  <span className="absolute top-0 left-0 w-full h-full bg-black opacity-25"></span>
                                  <div
                                    className="cf-media-gallery__item-preview absolute top-0 left-0 flex-center w-full h-full">
                                    {
                                      attachment.type === 'image'
                                        ? (
                                          <img
                                            className="cf-media-gallery__item-thumb absolute top-0 left-0 w-full h-full object-cover"
                                            src={attachment.url}
                                          />
                                        )
                                        : (
                                          <img
                                            className="cf-media-gallery__item-icon"
                                            src={attachment.icon}
                                          />
                                        )
                                    }
                                  </div>
                                  <span
                                    className="absolute bottom-4 right-10 text-sm text-white z-1">
                                    {payment_package && (index + 1) > media.free
                                      ?
                                      <span
                                        className="price"
                                        dangerouslySetInnerHTML={{
                                          __html: sprintf(payment_package.price_format, payment_package.currency, formatMoney(media.price, payment_package.decimals, payment_package.decimal_separator, payment_package.thousand_separator))
                                        }}></span>
                                      :
                                      lc_data.jst[352]
                                    }
                                  </span>
                                </div>

                                <input
                                  type="hidden"
                                  name={`${name}[${index}]`}
                                  value={typeof id === 'object' ? id.file : id}
                                  readOnly
                                />
                              </div>
                            );
                          })}
                        </div>
                      </div>
                    </div>

                    {media &&
                    <div className="flex justify-between items-center mt-20 text-sm text-grey-500">

                      <div
                        className="flex"
                        dangerouslySetInnerHTML={{
                          __html: sprintf(lc_data.jst[353],
                            `<span class="mr-4 font-bold text-grey-900">${(media.limit - value.length)}</span>`, field.labels[1])
                        }}>
                      </div>

                      <div className="flex items-center">
                        {freeCount > 0 &&
                        <div>
                          <span>{lc_data.jst[354]}</span>
                          {freeCount === 1 ?
                            <span
                              className="ml-4 font-bold text-green-800">{freeCount} {sprintf(lc_data.jst[355], field.labels[0])}</span>
                            :
                            <span
                              className="ml-4 font-bold text-green-800">{freeCount} {sprintf(lc_data.jst[355], field.labels[1])}</span>
                          }
                        </div>
                        }
                        {toPayCount > 0 &&
                        <div className="ml-4">
                          <span>{lc_data.jst[356]}</span>
                          {toPayCount === 1 ?
                            <span
                              className="ml-4 font-bold text-red-600">{toPayCount} {sprintf(lc_data.jst[357], field.labels[0])}</span>
                            :
                            <span
                              className="ml-4 font-bold text-red-600">{toPayCount} {sprintf(lc_data.jst[357], field.labels[1])}</span>
                          }
                        </div>
                        }
                        {media && payment_package &&
                        <div
                          className="ml-20 py-6 px-10 bg-green-100 border border-green-300 rounded font-bold text-green-800"
                          dangerouslySetInnerHTML={{
                            __html: sprintf(payment_package.price_format, payment_package.currency, formatMoney(!isEmpty(costs?.media) ? costs?.media['_product_image_gallery']?.['cost'] : media.cost, payment_package.decimals, payment_package.decimal_separator, payment_package.thousand_separator))
                          }}></div>
                        }
                      </div>

                    </div>
                    }
                  </Fragment>
                );
              }
            }
          </MediaLibrary>
        </Sortable>
        <ModalDemo
          isLogged={lc_data.logged_in}
          open={this.state.modalOpen}
          closeModal={() => this.setState({ modalOpen: false })}
          title={lc_data.jst[606]}
        >
          <div className="font-semibold text-lg text-grey-700" dangerouslySetInnerHTML={{
            __html: lc_data.jst[607],
          }}
          />
        </ModalDemo>
      </div>
    );
  }
}

function aperture(component) {
  const mount$ = component.mount;

  return pipe(mount$, map(() => ({
    type: 'COMPONENT_MOUNTED'
  })));
}

function handler(props) {
  return function (effect) {
    switch (effect.type) {
      case 'COMPONENT_MOUNTED':
        const { dispatch, formData, field, payment_package, value, setState, media, name } = props;
        let { costs } = props;

          console.log("here are inside of the media gallery checking for the payment package")
          console.log(payment_package)

        if (payment_package) {
          let promotion = {};
          let addonsToPay = 0;
          if (field.store_as === 'image') {
            promotion = get(payment_package.promotions, 'image');
            formData['addon-image'] = promotion;
            addonsToPay += parseInt(formData?.['needs-payment']?.image, 10) || 0;
          } else {
            promotion = get(payment_package.promotions, 'docs');
            formData['addon-docs'] = promotion;
            addonsToPay += parseInt(formData?.['needs-payment']?.docs, 10) || 0;
          }

          if (promotion) {
            const free = props.isEdit && value.length > promotion.value ? value.length : promotion.value;
            promotion.value = parseInt(free, 10) - addonsToPay;
            setState(prevState => ({
              media: {
                ...prevState.media,
                ['limit']: field.limit,
                ['free']: free - addonsToPay,
                ['price']: promotion.price,
              }
            }));

            dispatch(actions.updateFormData(formData));

            let cost = promotion.price;
            if (value.length >= promotion.value) {
              cost = (value.length - promotion.value) * parseFloat(promotion.price);
              setState(prevState => ({
                media: {
                  ...prevState.media,
                  ['cost']: cost,
                }
              }));

              if (!costs) {
                costs = {};
              }
              if (!costs['media']) {
                costs['media'] = {};
              }
              if (!costs['total']) {
                costs['total'] = {};
              }
              if (!costs['total']['media']) {
                costs['total']['media'] = 0;
              }
              if (costs['media'] && costs['media'][name] && costs['media'][name].cost && costs['media'][name].cost > 0) {
                costs['total']['media'] -= costs['media'][name].cost;
              }
              costs['media'][name] = { cost };
              costs['total']['media'] += cost;
              costs['final'] = (costs.total.media) + (costs['total']['promo'] || 0) || 0;
            }
          }
        }

        let valuesToGet = value;
        if (field.store_as !== 'image') {
          valuesToGet = value.map(val => val.file);
        }

        fetchAttachmentsData(valuesToGet, lc_data.current_user_id).then((attachmentsData) => {
          setState({
            attachmentsData: [...props.attachmentsData, ...attachmentsData.data]
          });

          if (costs) {
            dispatch(actions.updateCosts(costs));
          }
        });

        break;
    }
  };
}

const applyWithState = withState({
  notice: '',
  media: {
    limit: 16,
    free: 1,
    price: 0,
    cost: 0,
  },
  attachmentsData: [],
  selectedItem: null
});

const applyWithEffects = withEffects(aperture, { handler });

function mapStateToProps(state) {
  const { fields, formErrors, formData, costs } = state;
  return {
    fields,
    formErrors,
    formData,
    costs,
  };
}

const enhance = compose(
  applyWithState,
  applyWithEffects,
  connect(mapStateToProps),
);

export default enhance(MediaGalleryField);
