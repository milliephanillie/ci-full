/**
 * External dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import { Fragment, useRef, useState } from '@wordpress/element';
import ImageUploading from 'react-images-uploading';
import { useEffect } from 'react';
import { sprintf } from '@wordpress/i18n';
import ReactSVG from 'react-svg';
import QuestionIcon from '../../../../images/icons/question-circle.svg';
import ReactTooltip from 'react-tooltip';
import PlusIcon from '../../../../images/icons/plus.svg';
import CloseIcon from '../../../../images/icons/close.svg';
import { addProps, formatMoney } from '../../../theme/vendor/functions';
import * as actions from '../../../dashboard/packages/store/actions';
import { get } from 'lodash';
import Sortable from '../components/sortable/sortable';

const Gallery = (props) => {
  const {
    allData,
    user,
    type,
    id,
    name,
    field,
    label,
    description,
    payment_package,
    error,
    display,
    notice,
  } = props;
  const [formData, setFormData] = useState(props.value);
  const data = useSelector(state => state);
  const dispatch = useDispatch();
  let { costs } = data;
  const [images, setImages] = useState(data?.formData[name] || []);
  const [promotion, setPromotion] = useState(get(payment_package.promotions, 'image') || false);
  const [freeCount, setFreeCount] = useState(0);
  const [payCount, setPayCount] = useState(0);
  const [media, setMedia] = useState({
    limit: field.limit || 16,
    free: field.free || 4,
    price: promotion?.price || 0.25,
  });
  const attachments = useRef();

  const onChange = (imageList, addUpdateIndex) => {
    const form = data.formData;
    // data for submit
    setImages(imageList);
    form[name] = imageList;
    dispatch(actions.updateFormData(form));
  };

  useEffect(() => {
    setImages(data?.formData[name]);
  }, [data?.formData[name]]);

  useEffect(() => {
    const { formData } = data;
    let addonsToPay = 0;
    if (!promotion) {
      setPromotion(get(payment_package.promotions, 'image'));
    }
    formData['addon-image'] = promotion;
    addonsToPay += parseInt(formData?.['needs-payment']?.image, 10) || 0;

    if (promotion) {
      let free = props.isEdit && images.length > promotion.value ? images.length : promotion.value;
      free = formData?.free_images || free;
      if (!formData?.free_images) {
        formData.free_images = free;
      }
      promotion.value = parseInt(free, 10) - addonsToPay;
      setMedia({
        limit: field.limit || 16,
        free: free - addonsToPay,
        price: promotion?.price || 0.25,
      });
    }

    dispatch(actions.updateFormData(formData));
  }, []);

  useEffect(() => {
    if (!images) {
      return;
    }
    let cost = media.price;
    let calc = { ...costs };
    if (!costs?.total?.media) {
      addProps(calc, ['total', 'media'], 0);
    }
    if (!costs?.media?.[name]) {
      addProps(calc, ['media', name], 0);
    }

    calc.total.media -= calc.total.media;
    calc['final'] = (calc['total']['promo'] || 0) - (calc.total.media) || 0;
    if (images.length >= media.free) {
      cost = (images.length - media.free) * media.price;

      calc.total.media += cost;
      calc.media[name] = cost;
      calc['final'] = (calc.total.media) + (calc['total']['promo'] || 0) || 0;
    } else {
      calc.media[name] = 0;
    }
    dispatch(actions.updateCosts(calc));

    setFreeCount(parseInt(media.free, 10) < images.length ? parseInt(media.free, 10) : images.length);
    setPayCount(images.length - parseInt(media.free, 10));
  }, [images]);

  return (
    <div className="flex flex-col w-full">
      <div className="mb-40">
        <div className="flex flex-col mb-20">
          <div className="relative flex mb-10">

            { console.log("we are here inside the gallery") }
            { console.log(data) }
          <span
            className="text-sm text-grey-500"
            dangerouslySetInnerHTML={{
              __html: sprintf(lc_data.jst[351], `<span class="font-semibold text-grey-1000">${label}</span>`, field.size_limit || '2mb')
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
            items={images}
            forwardedRef={attachments}
            options={{
              handle: '.gallery-sort-handle',
              forcePlaceholderSize: true
            }}
            onUpdate={(attachments) => onChange(attachments)}
          >
            <ImageUploading
              multiple={field?.multiple || false}
              value={images}
              onChange={onChange}
              maxNumber={field?.limit || 16}
              dataURLKey="data_url"
            >
              {({
                imageList,
                onImageUpload,
                onImageRemoveAll,
                onImageUpdate,
                onImageRemove,
                isDragging,
                dragProps,
              }) => (
                // write your building UI
                <Fragment>
                  <div className="flex flex-wrap p-20 bg-grey-100 border border-grey-300 rounded">
                    <div
                      className={`dashboard--gallery flex flex-wrap w-full ${imageList?.length > 0 ? '-mx-4' : ''}`}>
                      <div className="gallery--button px-4"
                           style={{
                             width: 100
                           }}
                      >
                        <button
                          type="button"
                          style={isDragging ? { color: 'red' } : undefined}
                          onClick={() => {
                            if (lc_data.is_demo) {

                            } else {
                              onImageUpload();
                            }
                          }}
                          className="flex-center p-30 bg-white rounded shadow-theme hover:shadow-lg"
                          {...dragProps}
                        >
                          <ReactSVG src={`${lc_data.dir}dist/${PlusIcon}`}
                                    className="relative w-30 h-30 fill-grey-700"/>
                        </button>
                      </div>
                      <div className="gallery-items flex flex-wrap -mb-10" ref={attachments}
                           style={{ width: 'calc(100% - 100px)' }}
                      >
                        {imageList.map((image, index) => (
                          <div key={index} className="image-item mb-10 px-4">
                            <div
                              className={`gallery-sort-handle relative border rounded overflow-hidden cursor-pointer ${(index + 1) > media.free ? 'border-red-600' : 'border-green-700'}`}
                              style={{ height: 91 }}
                            >
                              <button
                                type="button"
                                className="absolute top-10 right-10 z-1"
                                onClick={() => onImageRemove(index)}
                              >
                                <ReactSVG src={`${lc_data.dir}dist/${CloseIcon}`} data-tip={description}
                                          className="w-10 h-10 fill-white"/>
                              </button>
                              <span className="absolute top-0 left-0 w-full h-full bg-black opacity-25"></span>
                              <img src={image['data_url']} alt="" width="100"/>

                              <span
                                className="absolute bottom-4 right-10 text-sm text-white z-1">
                                    {payment_package && (index + 1) > freeCount
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
                              <input
                                type="hidden"
                                name={`${name}[${index}]`}
                                value={image['data_url']}
                                readOnly
                              />
                            </div>
                          </div>
                        ))}
                      </div>
                    </div>
                  </div>

                  {media && images &&
                  <div className="flex justify-between items-center mt-20 text-sm text-grey-500">

                    <div
                      className="flex"
                      dangerouslySetInnerHTML={{
                        __html: sprintf(lc_data.jst[353],
                          `<span class="mr-4 font-bold text-grey-900">${(media.limit - images.length)}</span>`, field.labels[1])
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
                      {payCount > 0 &&
                      <div className="ml-4">
                        <span>{lc_data.jst[356]}</span>
                        {payCount === 1 ?
                          <span
                            className="ml-4 font-bold text-red-600">{payCount} {sprintf(lc_data.jst[357], field.labels[0])}</span>
                          :
                          <span
                            className="ml-4 font-bold text-red-600">{payCount} {sprintf(lc_data.jst[357], field.labels[1])}</span>
                        }
                      </div>
                      }
                      {costs && payment_package &&
                      <div
                        className="ml-20 py-6 px-10 bg-green-100 border border-green-300 rounded font-bold text-green-800"
                        dangerouslySetInnerHTML={{
                          __html: sprintf(payment_package.price_format, payment_package.currency, formatMoney(costs?.media?.[name] || 0, payment_package.decimals, payment_package.decimal_separator, payment_package.thousand_separator))
                        }}></div>
                      }
                    </div>
                  </div>
                  }
                </Fragment>
              )}
            </ImageUploading>
          </Sortable>
        </div>
      </div>
    </div>
  );
};

export default Gallery;
