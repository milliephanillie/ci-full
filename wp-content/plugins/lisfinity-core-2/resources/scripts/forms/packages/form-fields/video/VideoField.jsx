/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component, Fragment, createRef } from '@wordpress/element';
import { compose, withState } from '@wordpress/compose';
import { __, sprintf } from '@wordpress/i18n';
import { withEffects, toProps } from 'refract-callbag';
import of from 'callbag-of';
import {
  map,
  pipe,
  merge
} from 'callbag-basics';
import { get, debounce, isEmpty } from 'lodash';
import VideoPreview from './VideoPreview';
import produce from 'immer';
import Sortable from '../../components/sortable/sortable';
import ReactSVG from 'react-svg';
import PlusIcon from '../../../../../images/icons/plus.svg';
import Modal from '../../../../theme/packages/components/modal/Modal';
import ReactTooltip from 'react-tooltip';
import QuestionIcon from '../../../../../images/icons/question-circle.svg';
import CloseIcon from '../../../../../images/icons/close.svg';
import { formatMoney } from '../../../../theme/vendor/functions';
import * as actions from '../../../../dashboard/packages/store/actions';
import { connect } from 'react-redux';
import ModalDemo from '../../../../theme/packages/components/modal/ModalDemo';

class VideoField extends Component {
  constructor(props) {
    super(props);
    this.state = {
      value: this.props.value,
      modalOpen: false,
      demoModalOpen: false,
    };

    this.handleVideoModal = this.handleVideoModal.bind(this);
    this.handleVideoFetch = this.handleVideoFetch.bind(this);
    this.submitVideo = this.submitVideo.bind(this);
    this.closeModal = this.closeModal.bind(this);
    this.escFunction = this.escFunction.bind(this);
  }

  /**
   * Keeps references to the DOM node.
   *
   * @type {Object}
   */
  node = createRef();
  attachmentsList = createRef();

  /**
   * Component has been mounted
   * --------------------------
   */
  componentDidMount() {
    document.addEventListener('keydown', this.escFunction, false);

    const { formData, field, value, videos, payment_package } = this.props;

    const i = setInterval(() => {
      if (this.node.current.getBoundingClientRect().width > 0) {
        clearInterval(i);

        this.handleSearch(value);
      }
    }, 100);

    const promotion = get(payment_package.promotions, 'video');

    if (!isEmpty(promotion)) {
      const free = this.props.isEdit && value.length > promotion.value ? value.length : promotion.value;
      promotion.value = free;
      formData['addon-video'] = promotion;
      this.props.dispatch(actions.updateFormData(formData));
      this.props.setState(prevState => ({
        videos: {
          ...prevState.videos,
          ['limit']: field.limit,
          ['free']: free,
          ['price']: promotion.price,
        }
      }));
    }
  }

  /**
   * Component will be unmounted
   * ---------------------------
   */
  componentWillUnmount() {
    document.removeEventListener('keydown', this.escFunction, false);
  }

  /**
   * Open modal for adding a new video
   * ---------------------------------
   */
  handleVideoModal() {
    if (lc_data.is_demo) {
      this.setState({ demoModalOpen: true });
      return false;
    }
    const { setState, embedCode } = this.props;
    const { modalOpen } = this.state;
    setState({ notice: '' });
    setState({ embedCode: '' });
    this.setState({ modalOpen: true });
  }

  handleClickOutside = () => {
    this.setState({ modalOpen: false });
    const body = document.querySelector('body');
    body.style.overflow = 'auto';
  };

  /**
   * Handle video searching
   * ----------------------
   *
   * @type {debounced}
   */
  handleSearch = debounce((value) => {
    const {
      isLoading,
      setState,
      onFetchEmbedCode,
    } = this.props;

    if (isLoading) {
      return;
    }

    setState({
      embedCode: '',
      error: '',
      notice: '',
    });

    if (isEmpty(value)) {
      return;
    }

    setState({
      isLoading: true
    });

    onFetchEmbedCode(value);
  }, 200);

  /**
   * Fetch video from the given link
   * -------------------------------
   *
   * @param value
   * @returns {Promise<void>}
   */
  handleVideoFetch = async (value) => {
    this.handleSearch(value);
  };

  /**
   * On click escape close the modal
   * -------------------------------
   *
   * @param event
   */
  escFunction(event) {
    if (event.keyCode === 27) {
      this.setState({ modalOpen: false });
    }
  }

  /**
   * Close modal on click
   * -------------------
   */
  closeModal() {
    this.setState({ modalOpen: false });
  }

  /**
   * Submit Video handler
   * --------------------
   */
  submitVideo() {
    const { id, onChange, value, videoData, setState, videos, name, costs, dispatch } = this.props;
    this.setState({ modalOpen: false });

    onChange(id, [...value, ...[videoData]]);

    // calculate the additional costs.
    let cost = videos.price;
    let allVideos = value.length + 1;
    dispatch(actions.updateCosts({}));
    if (allVideos >= videos.free) {
      cost = (allVideos - videos.free) * videos.price;
      setState(prevState => ({
        videos: {
          ...prevState.videos,
          ['cost']: cost,
        }
      }));
      if (!costs['media']) {
        costs['media'] = {};
      }
      if (!costs['total']) {
        costs['total'] = {};
        if (!costs['total']['media']) {
          costs['total']['media'] = 0;
        }
      }
      if (costs['media'] && costs['media'][name] && costs['media'][name].cost && costs['media'][name].cost > 0) {
        costs['total']['media'] -= costs['media'][name].cost;
      }
      costs['media'][name] = { cost };
      costs['total']['media'] += cost;
      costs['final'] = (costs.total.media) + (costs['total']['promo'] || 0) || 0;
      dispatch(actions.updateCosts(costs));
    }
  }

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
      setState,
      videos,
      name,
      costs,
      dispatch,
    } = this.props;

    onChange(id, produce(value, (draft) => {
      draft.splice(index, 1);
    }));
    // calculate the additional costs.
    const values = value.length - 1;
    let cost = videos.price;
    if (values >= videos.free) {
      cost = (values - videos.free) * videos.price;
      setState(prevState => ({
        videos: {
          ...prevState.videos,
          ['cost']: cost,
        }
      }));
      const newCosts = { ...costs };
      newCosts['media'][name] = { cost };
      newCosts['total']['media'] -= videos.price;
      newCosts['final'] = (costs.total.media) + (costs['total']['promo'] || 0) || 0;
      dispatch(actions.updateCosts(newCosts));
    }
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
      user,
      type,
      id,
      name,
      field,
      value,
      notice,
      embedCode,
      embedType,
      provider,
      isLoading,
      description,
      label,
      videos,
      payment_package,
    } = this.props;
    const { modalOpen } = this.state;

    const freeCount = parseInt(videos.free, 10) < value.length ? parseInt(videos.free, 10) : value.length;
    const toPayCount = value.length - parseInt(videos.free, 10);
    return [
      <Fragment key={0}>
        <div className="flex flex-col mb-20">
          <div className="relative flex mb-10">
          <span
            className="text-sm text-grey-500"
            dangerouslySetInnerHTML={{
              __html: sprintf(lc_data.jst[375], `<span class="font-semibold text-grey-1000">${label}</span>`)
            }}></span>
            {description &&
            <ReactSVG src={`${lc_data.dir}dist/${QuestionIcon}`} data-tip={description}
                      className="relative left-6 w-14 h-14 fill-blue-700" style={{ top: '-8px' }}/>}
            {description && <ReactTooltip/>}
          </div>
          <Sortable
            items={value}
            forwardedRef={this.attachmentsList}
            options={{
              handle: '.video-sort-handle',
              forcePlaceholderSize: true
            }}
            onUpdate={this.handleSort}
          >
            <div className="flex flex-wrap p-20 bg-grey-100 border border-grey-300 rounded" ref={this.node}>
              <div className={`flex flex-wrap w-full ${this.attachmentsList.length > 0 ? '-mx-4' : ''}`}>

                <div className="px-4 w-1/6">
                  <button
                    type="button"
                    className="flex-center p-30 bg-white rounded shadow-theme hover:shadow-lg"
                    onClick={this.handleVideoModal}
                  >
                    <ReactSVG src={`${lc_data.dir}dist/${PlusIcon}`}
                              className="relative w-30 h-30 fill-grey-700"/>
                  </button>
                </div>

                {value &&
                <div className="flex flex-wrap -mb-10 w-5/6" ref={this.attachmentsList}>
                  {value.map((video, index) => {
                    return (
                      <div className="mb-10 px-4 w-1/5" key={index}>
                        <div
                          className={`video-sort-handle flex-center relative border-2 rounded overflow-hidden cursor-pointer ${(index + 1) > videos.free ? 'border-red-600' : 'border-green-700'}`}
                          style={{ height: '91px' }}
                        >
                          <button
                            type="button"
                            className="absolute top-10 right-10 z-2"
                            onClick={() => this.handleAttachmentRemove(index)}
                          >
                            <ReactSVG src={`${lc_data.dir}dist/${CloseIcon}`} data-tip={description}
                                      className="w-10 h-10 fill-white"/>
                          </button>
                          <span className="absolute top-0 left-0 w-full h-full bg-black opacity-25 z-1"></span>
                          <img
                            src={video.thumbnail}
                            className="absolute top-0 left-0 w-full h-full object-cover"
                            alt="image"
                          />
                          <span
                            className="absolute bottom-4 right-10 text-sm text-white z-1">
                                    {payment_package && (index + 1) > videos.free
                                      ?
                                      <span
                                        className="price"
                                        dangerouslySetInnerHTML={{
                                          __html: sprintf(payment_package.price_format, payment_package.currency, formatMoney(videos.price, payment_package.decimals, payment_package.decimal_separator, payment_package.thousand_separator))
                                        }}></span>
                                      :
                                      lc_data.jst[352]
                                    }
                                  </span>
                          <input
                            type="hidden"
                            name={`${name}[${index}]`}
                            value={video.url}
                            readOnly
                          />
                        </div>
                      </div>
                    );
                  })}
                </div>
                }
              </div>
            </div>
          </Sortable>
        </div>

        {videos &&
        <div className="flex justify-between items-center mt-20 text-sm text-grey-500">

          <div
            className="flex"
            dangerouslySetInnerHTML={{
              __html: sprintf(lc_data.jst[353],
                `<span class="mr-4 font-bold text-grey-900">${(videos.limit - value.length)}</span>`, field.labels[1])
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
            {videos && payment_package &&
            <div
              className="ml-20 py-6 px-10 bg-green-100 border border-green-300 rounded font-bold text-green-800"
              dangerouslySetInnerHTML={{
                __html: sprintf(payment_package.price_format, payment_package.currency, formatMoney(videos.cost, payment_package.decimals, payment_package.decimal_separator, payment_package.thousand_separator))
              }}></div>
            }
          </div>
        </div>}
      </Fragment>
      ,
      modalOpen &&
      <div
        key={1}
        className="modal--wrapper modal-send-message fixed top-0 left-0 flex justify-center w-full h-full overflow-y-auto"
      >
        <Modal
          key={2}
          open={modalOpen}
          title={lc_data.jst[376]}
          handleClickOutside={this.handleClickOutside}
          closeModal={() => this.setState({ modalOpen: false })}
        >
          <div className="new-video flex flex-col p-40 w-full">
            <div className="video-wrapper flex flex-col">
              <div className="field--top relative flex justify-between">
                <label htmlFor="findVideo" className="field--label mb-4 text-sm text-grey-500">
                  {lc_data.jst[377]}
                </label>
                {notice &&
                <div className="response response--error flex absolute right-0 text-sm text-red-700">
                  <span className="text-sm">{notice}</span>
                </div>
                }
              </div>

              <div
                className="field--wrapper relative flex items-center h-44 p-14 bg-grey-100 border border-grey-300 rounded">
                <input type="text" id="findVideo" className="w-full bg-transparent"
                       onChange={e => this.handleVideoFetch(e.target.value)}
                       autoComplete="off"
                />
              </div>

              {embedCode &&
              <button
                type="button"
                className="flex-center my-10 py-10 p-20 bg-blue-700 rounded shadow font-semibold text-base text-white hover:bg-blue-800"
                onClick={this.submitVideo}
              >
                {lc_data.jst[378]}
              </button>
              }

            </div>
            {isLoading && <div className="loading mt-3">Loading...</div>}
            {embedCode &&
            <div className="video-preview flex w-full mt-3 rounded overflow-hidden">
              <VideoPreview
                html={embedCode}
                type={embedType}
                provider={provider}
              />
            </div>
            }
          </div>
        </Modal>
      </div>,
      <ModalDemo
        key={10}
        isLogged={lc_data.logged_in}
        open={this.state.demoModalOpen}
        closeModal={() => this.setState({ demoModalOpen: false })}
        title={lc_data.jst[606]}
      >
        <div className="font-semibold text-lg text-grey-700" dangerouslySetInnerHTML={{
          __html: lc_data.jst[607],
        }}
        />
      </ModalDemo>,
    ];
  }
}

/**
 * The function that controls the stream of side-effects.
 *
 * @param  {Object} component
 * @return {Object}
 */
function aperture(component) {
  const [fetchEmbedCode$, fetchEmbedCode] = component.useEvent('fetchEmbedCode');

  const fetchEmbedCodeProps$ = pipe(
    of({
      onFetchEmbedCode: fetchEmbedCode
    }),
    map(toProps)
  );

  const fetchEmbedCodeEffect$ = pipe(
    fetchEmbedCode$,
    map((payload) => ({
      type: 'FETCH_EMBED_CODE',
      payload: payload
    }))
  );

  return merge(fetchEmbedCodeProps$, fetchEmbedCodeEffect$);
}

/**
 * The function that causes the side effects.
 *
 * @param  {Object} props
 * @return {Function}
 */
function handler(props) {
  return function (effect) {
    const { payload, type } = effect;

    switch (type) {
      case 'FETCH_EMBED_CODE':
        // eslint-disable-next-line
        let request = $.get(window.wpApiSettings.root + 'oembed/1.0/proxy', {
          url: payload,
          _wpnonce: window.wpApiSettings.nonce
        });

        request.done((response) => {
          props.setState({
            videoData: {
              url: payload,
              title: response.title,
              thumbnail: response.thumbnail_url,
              provider: response.provider_name,
            },
            embedCode: response.html,
            embedType: response.type,
            provider: response.provider_name,
            isLoading: false
          });
        });

        request.fail(() => {
          props.setState({
            notice: lc_data.jst[379],
            error: lc_data.jst[379],
            isLoading: false
          });
        });

        break;
    }
  };
}

const applyWithState = withState({
  notice: '',
  videos: { // todo change values to dynamic ones
    limit: 16,
    free: 2,
    price: 0.5,
    cost: 0,
  },
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

export default enhance(VideoField);
