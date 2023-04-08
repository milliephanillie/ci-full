/**
 * External dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import * as actions from '../../../dashboard/packages/store/actions';
import { useState } from '@wordpress/element';
import MediaGalleryField from './MediaGalleryField';
import VideoField from './video/VideoField';
import { useEffect } from 'react';

const Media = (props) => {
  const [value, setValue] = useState([]);
  const [formData, setFormData] = useState(props.value);
  const data = useSelector(state => state);
  const dispatch = useDispatch();

  const onChange = (id, value) => {
    data.formData[id] = value;
    setValue(value);

    dispatch(actions.updateFormData(data.formData));
  };

  const updateState = (data) => {
    setFormData(data);
  };

  useEffect(() => {
    setValue(props.value);
  }, [props.value]);

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
  } = props;


  return (
    <div className="flex flex-col w-full">
      {undefined !== field.type_filter && display &&
      <div className="mb-40">
        <MediaGalleryField
          id={id}
          name={name}
          field={field}
          costs={data.costs}
          formData={data.formData}
          value={value}
          buttonLabel={field.props.button_label}
          mediaLibraryButtonLabel={field.props.media_button_label}
          mediaLibraryTitle={field.props.media_title}
          setState={updateState}
          onChange={onChange}
          label={label}
          description={description}
          payment_package={payment_package}
          attachmentsData={allData[name] || []}
          dispatch={dispatch}
          isEdit={props.isEdit}
          error={error}
        />
      </div>}
      {undefined === field.type_filter &&
      <div className="mb-40">
        <VideoField
          id={id}
          name={name}
          field={field}
          value={value}
          setState={updateState}
          onChange={onChange}
          label={label}
          description={description}
          payment_package={payment_package}
          formData={data.formData}
          dispatch={dispatch}
          isEdit={props.isEdit}
          error={error}
        />
      </div>}
    </div>
  );
};

export default Media;
