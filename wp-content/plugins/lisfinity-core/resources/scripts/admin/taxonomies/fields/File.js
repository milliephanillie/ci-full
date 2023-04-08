/**
 * External dependencies.
 */
import { Component, useEffect, useState } from '@wordpress/element';
import MediaLibrary from '../components/MediaLibrary';
import apiFetch from '@wordpress/api-fetch';
import ReactSVG from 'react-svg';
import PlusIcon from '../../../../images/icons/plus.svg';

const FileField = (props) => {
  const {
    value,
    field,
    name,
    buttonLabel,
    mediaLibraryButtonLabel,
    mediaLibraryTitle,
    handleChange,
  } = props;
  const [data, setData] = useState({});

  useEffect(() => {
    getValue();
  }, []);

  const getValue = () => {
    if (value) {
      return new Promise((resolve, reject) => {
        const url = `${lc_data['attachment_data']}/?type=image&value=${value}`;
        const request = apiFetch({ path: url },
        ).then(handleFileDataChange);

        request.then(response => {
          resolve(response);
        });
      });
    }
  };

  useEffect(() => {
    handleChange(name, data.id || '');
  }, [data]);

  const getThumb = () => {
    if (data.sizes) {
      const size = data.sizes.thumbnail || data.sizes.full;

      if (size) {
        return size.url;
      }
    }

    if (data.thumb_url && data.thumb_url.indexOf('/media/default.png') !== -1) {
      return data.file_url;
    }

    return data.thumb_url;
  };

  const getFileName = () => {
    return data.filename || data.file_name;
  };

  const handleFileDataChange = (data) => {
    setData(data);
  };

  const handleClear = () => {
    handleFileDataChange({});
  };

  const handleSelect = (files) => {
    const [file] = files;

    handleFileDataChange(file);
  };

  return (
    <MediaLibrary
      onSelect={handleSelect}
      multiple={false}
      title={mediaLibraryTitle}
      buttonLabel={mediaLibraryButtonLabel}
      typeFilter={field.type_filter}
    >
      {
        ({ openMediaBrowser }) => {
          return <div
            className={`media--wrapper mb-20 ${field.additional && field.additional.class && field.additional.class}`}>
            <input
              type="hidden"
              name={name}
              value={data.id || ''}
              readOnly
            />

            <p className="mb-10">{buttonLabel}{field && field.required &&
            <span className="text-sm text-red-600 leading-none">*</span>}</p>

            {(!!data.id) && (
              <div className="media--content">
                <div className="media--preview relative h-128 rounded overflow-hidden">
                  <img src={getThumb()}
                       className={`media--file-image absolute top-0 left-0 w-full h-full ${getFileName().indexOf('.svg') !== -1 || getFileName().indexOf('.png') ? 'object-contain' : 'object-cover'}`}/>

                  <button type="button"
                          className="media--file-remove absolute flex justify-center items-center top-3 right-3 w-30 h-30 bg-white rounded dashicons-before dashicons-no-alt"
                          onClick={handleClear}></button>
                </div>

                <span className="media--file-name" title={getFileName()}>
										{getFileName()}
									</span>
              </div>
            )}

            {!data.id &&
            <button type="button"
                    className="media--file-add flex justify-center items-center w-full h-128 border-4 border-dashed border-grey-300"
                    onClick={openMediaBrowser}>
              <ReactSVG
                src={`${lc_data.dir}dist/${PlusIcon}`}
                className="relative w-40 h-40 fill-grey-300"
              />
            </button>
            }
          </div>;
        }
      }
    </MediaLibrary>
  );

};

export default FileField;
