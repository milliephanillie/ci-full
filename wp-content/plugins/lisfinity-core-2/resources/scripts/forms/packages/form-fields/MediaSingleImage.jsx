/* global lc_data, React */
/**
 * External dependencies.
 */
import { sprintf } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';
import * as actions from '../../../dashboard/packages/store/actions';
import MediaLibrary from '../components/MediaLibrary';
import ReactSVG from 'react-svg';
import QuestionIcon from '../../../../images/icons/question-circle.svg';
import ReactTooltip from 'react-tooltip';
import UploadIcon from '../../../../images/icons/upload.svg';
import axios from 'axios';
import { useDispatch, useSelector } from 'react-redux';
import ModalDemo from '../../../theme/packages/components/modal/ModalDemo';

const MediaSingleImage = (props) => {
  const store = useSelector(state => state);
  const data = props?.dataType === 'profile' ? { ...store.profile } : { ...store.formData };
  const dispatch = useDispatch();
  const {
    name,
    value,
    field,
    buttonLabel,
    mediaLibraryButtonLabel,
    mediaLibraryTitle,
    attachmentsData,
    selectedItem,
    notice,
    media,
    label,
    description,
    payment_package,
    onChange,
  } = props;
  const [image, setImage] = useState(false);
  const [modalOpen, setModalOpen] = useState(false);

  const handleSelect = (attachments) => {
    setImage(attachments[0]);
    if (field?.field_type === 'no_save') {
      return;
    }
    if (onChange && !props.defaultSave) {
      onChange(attachments[0], name);
    } else {
      if (attachments[0]?.id) {
        data[name] = attachments[0]?.id;
      }
      if (attachments[0]?.url) {
        data[`${name}_url`] = attachments[0]?.url;
      }
      dispatch(actions.updateFormData(data));
    }
  };

  useEffect(() => {
    if (null === data[name] || '' === data[name] || undefined === data[name]) {
      return;
    }
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    let id = (field.post_thumbnail && store.business.business.data.thumbnail_id) || data[name];
    if (undefined === id) {
      id = field.defaultValue;
      if (undefined === id || 0 === id) {
        return;
      }
    }
    const url = `${lc_data.wp_resturl}wp/v2/media/${id}`;
    axios({
      credentials: 'same-origin',
      headers,
      method: 'get',
      url,
    }).then(response => {
      const newData = { ...data };
      setImage(response.data);
      if (field?.field_type === 'no_save') {
        return;
      }
      newData[`${name}_url`] = response.data.source_url;
      dispatch(actions.updateFormData(newData));
      if (onChange) {
        onChange(newData[name], response.data);
      }
    });
  }, [data[name]]);
  return (
    <div className="flex flex-col mb-20 w-full">
      <div className="relative flex mb-10">
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
      </div>
      <MediaLibrary
        onSelect={handleSelect}
        multiple={field.multiple}
        title={mediaLibraryTitle}
        buttonLabel={mediaLibraryButtonLabel}
        typeFilter={field.type_filter}
      >
        {
          ({ openMediaBrowser }) => {
            return (
              <div className="flex flex-wrap">

                <button
                  type="button"
                  onClick={() => {
                    if (lc_data.is_demo) {
                      setModalOpen(true);
                      return false;
                    } else {
                      openMediaBrowser();
                    }
                  }}
                  className="flex justify-between items-center py-8 px-20 w-full bg-grey-100 border border-grey-300 rounded font-semibold text-grey-700"
                >
                  <span>
                  {lc_data.jst[359]}
                  </span>
                  <ReactSVG src={`${lc_data.dir}dist/${UploadIcon}`}
                            className="relative w-14 h-14 fill-grey-700"/>
                </button>

                {image && !field.no_preview &&
                <div className="mt-10 overflow-hidden" style={{
                  width: 192,
                  height: 192,
                }}>
                  <figure onClick={() => {
                    if (lc_data.is_demo) {
                      setModalOpen(true);
                      return false;
                    } else {
                      openMediaBrowser();
                    }
                  }}
                          className="figure--uploader relative h-192 w-full rounded overflow-hidden cursor-pointer z-10">
                    <img src={image.url || image.source_url} alt={lc_data.jst[360]}
                         className="absolute top-0 left-0 w-full h-full object-cover"
                    />
                    <span className="overlay--close absolute top-0 left-0 w-full h-full z-40">
                    <button onClick={() => {
                      setImage(false);
                      data[name] = '';
                      data[`${name}_url`] = '';
                      dispatch(actions.updateFormData(data));
                    }}
                            className="absolute top-4 right-6 font-bold text-white">X</button>
                    </span>
                  </figure>
                  <input
                    type="hidden"
                    name={name}
                    value={image.id}
                    readOnly
                  />
                </div>
                }

              </div>
            );
          }
        }
      </MediaLibrary>
      <ModalDemo
        isLogged={lc_data.logged_in}
        open={modalOpen}
        closeModal={() => setModalOpen(false)}
        title={lc_data.jst[606]}
      >
        <div className="font-semibold text-lg text-grey-700" dangerouslySetInnerHTML={{
          __html: lc_data.jst[607],
        }}
        />
      </ModalDemo>
    </div>
  );
};

export default MediaSingleImage;
