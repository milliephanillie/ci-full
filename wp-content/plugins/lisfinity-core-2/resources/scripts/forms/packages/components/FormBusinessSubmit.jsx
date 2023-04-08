/* global lc_data, React */
import * as actions from '../../../dashboard/packages/store/actions';
import { useDispatch, useSelector } from 'react-redux';
import { useEffect, useState } from '@wordpress/element';
import axios from 'axios';
import { map, isEmpty, isString } from 'lodash';
import PaymentPackage from '../form-fields/payment-packages';
import Media from '../form-fields/Media';
import CustomFields from '../form-fields/CustomFields';
import RichTextField from '../form-fields/RichText';
import Checkbox from '../form-fields/Checkbox';
import SelectField from '../form-fields/select';
import Complex from '../form-fields/complex';
import Location from '../form-fields/Location';
import Date from '../form-fields/Date';
import WorkingHours from '../form-fields/WorkingHours';
import Costs from '../form-fields/Costs';
import Input from '../form-fields/Input';
import validation from '../../utils/form-validation';
import jsonForm from '../../utils/build-form-data';
import { produce } from 'immer';
import MediaSingleImage from '../form-fields/MediaSingleImage';
import LoaderAuth from '../../../theme/packages/components/loaders/LoaderAuth';
import { toast } from 'react-toastify';
import ModalDemo from '../../../theme/packages/components/modal/ModalDemo';

/**
 * External dependencies.
 */

const FormBusinessSubmit = (props) => {
  const store = useSelector(state => state);
  const data = store.formData;
  const dispatch = useDispatch();
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [formFields, setFormFields] = useState({});
  const [fieldGroups, setFieldGroups] = useState({});
  let [errors, setErrors] = useState({});
  const [featuredImage, setFeaturedImage] = useState(false);
  const [message, setMessage] = useState('');
  const [modalOpen, setModalOpen] = useState(false);

  useEffect(() => {
    // set default data.
    const newData = store.business.business.edit_data;
    dispatch(actions.updateFormData(newData));
    getFormFields();
  }, []);

  const getFormFields = async () => {
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const url = lc_data.business_fields;
    await axios({
      credentials: 'same-origin',
      headers,
      method: 'get',
      url,
    }).then(response => {
      setFormFields(response.data);
      organizeFieldGroups(response.data);
      dispatch(actions.setupSubmitFields(response.data));
      setLoading(false);
    });
  };

  const organizeFieldGroups = (formFields) => {
    const groups = [];
    map(formFields, (fields, name) => {
      groups.push(name);
    });
    setFieldGroups(groups);
  };

  const showField = (field, name, group) => {
    let display = true;
    if (field.conditional) {
      if (isString[data[field.conditional[1]]]) {
        display = field.conditional[1] === data[field.conditional[0]];
      } else {
        display = undefined !== data[field.conditional[0]] ? field.conditional[1].includes(data[field.conditional[0]]) : true;
      }
    }
    switch (field.type) {
      case 'promotions':
        return <PaymentPackage
          key={field.key}
          field={field}
          name={name}
          id={name}
          value={isEmpty(data[name]) ? [] : data[name]}
        />;
      case 'media':
        return <Media
          key={field.key}
          allData={data}
          field={field}
          field_type={field.field_type}
          name={name}
          id={name}
          value={isEmpty(data[name]) ? [] : data[name]}
          label={field.label}
          description={field.description}
        />;
      case 'single_image':
        return <MediaSingleImage
          display
          key={field.key}
          allData={data}
          field={field}
          field_type={field.field_type}
          name={name}
          id={name}
          value={isEmpty(data[name]) ? [] : data[name]}
          mediaLibraryTitle={lc_data.jst[69]}
          mediaLibraryButtonLabel={field?.props?.media_button_label}
          label={field.label}
          description={field.description}
          settings={{
            basic: true,
          }}
          defaultSave={field?.default_save || false}
          onChange={e => handleChange(e, name, 'single_image')}
        />;
      case 'taxonomies':
        return <CustomFields
          key={field.key}
          group={group}
          data={data}
        />;
      case 'rich-text':
        return <RichTextField
          key={field.key}
          id={name}
          name={name}
          field={field.options}
          visible
          handleChange={(e) => handleChange(e, name, field.type)}
          value={data[name]}
          attributes={field.attributes}
          label={field.label}
          description={field.description}
          error={errors}
          additional={field.additional}
        />;
      case 'checkbox':
        return <Checkbox
          display={display}
          key={field.key}
          id={name}
          name={name}
          label={field.label}
          handleChange={(e) => handleChange(e, name, field.type)}
          value={data[name]}
          checked={data[name]}
          description={field.description}
          attributes={field.attributes}
          additional={field.additional}
          error={errors}
        />;
      case 'select':
        return <SelectField
          display={display}
          key={field.key}
          id={name}
          label={field.label}
          handleChange={(e) => handleChange(e, name, field.type)}
          value={data[name] ? data[name] : field.options[0]}
          options={field.options}
          attributes={field.attributes}
          description={field.description}
          multiselect={field.multiselect || false}
          error={errors}
          additional={field.additional}
        />;
      case 'complex':
        return <Complex
          display={display}
          key={field.key}
          id={name}
          name={name}
          label={field.label}
          value={data[name]}
          fields={field.fields}
          field={field}
          attributes={field.attributes}
          description={field.description}
          error={errors}
          options={props.options}
        />;
      case 'location':
        return <Location
          display={display}
          key={field.key}
          id={name}
          name={name}
          label={field.label}
          value={data[name]}
          fields={field.fields}
          props={field.props}
          field={field}
        />;
      case 'date':
        return <Date
          display={display}
          key={field.key}
          id={name}
          name={name}
          label={field.label}
          handleChange={e => handleChange(e, name, field.type)}
          value={data[name]}
          attributes={field.attributes}
          description={field.description}
          error={errors}
          options={field.options}
          additional={field.additional}
        />;
      case 'costs':
        return <Costs
          display={display}
          key={field.key}
          type={field.field_type}
          name={name}
          label={field.label}
          calculation={field.calculation}
        />;
      case 'title':
        return (
          <h6 key={field.key} className="flex mb-20 w-full font-semibold">{field.label}</h6>
        );
      case 'working_hours':
        return data[name] &&
          <WorkingHours
            display={display}
            key={field.key}
            id={name}
            name={name}
            label={field.label}
            handleChange={e => handleChange(e, name, field.type)}
            value={data[name]}
            attributes={field.attributes}
            description={field.description}
            error={errors}
            options={field.options}
            additional={field.additional}
          />;
      default:
        return <Input
          display={display}
          key={field.key}
          id={name}
          name={name}
          label={field.label}
          handleChange={e => handleChange(e, name, field.type)}
          value={data[name]}
          attributes={field.attributes}
          description={field.description}
          error={errors}
          additional={field.additional}
        />;
    }
  };

  const handleChange = (e, name, type) => {
    const newData = { ...data };
    if (type === 'single_image') {
      if (e?.id) {
        newData[name] = e.id;
      }
      if (e?.source_url) {
        newData[`${name}_url`] = e.source_url;
        if (name === '_featured_image') {
          setFeaturedImage(newData['_featured_image_url']);
        }
      }
    } else if (type === 'working_hours') {
      newData[name] = e;
    } else if (type !== 'single_image') {
      let elValue = isString(e) ? e : e.target.value;
      if (type === 'checkbox' || type === 'radio') {
        elValue = e.target.checked;
      }
      newData[name] = elValue;
    }
    dispatch(actions.updateFormData(newData));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (lc_data.is_demo) {
      setModalOpen(true);
      return false;
    }
    // validate form
    const step = {
      id: 'basic',
    };

    let validationData = {
      fields: store.fields,
    };
    errors = validation(step, validationData, dispatch);
    setErrors(errors);

    if (!isEmpty(errors)) {
      setErrors(errors);
      dispatch(actions.updateFormErrors(errors));
      return false;
    }

    // submit form
    setSaving(true);
    let url = lc_data.product_submit;
    let headers = {
      'X-WP-Nonce': lc_data.nonce,
    };

    const newData = produce(data, draft => {
      draft.action = 'edit';
      draft.id = store.business.business.ID;
      draft.post_type = 'premium_profile';
    });
    const formData = jsonForm(newData);

    axios({
      credentials: 'same-origin',
      method: 'post',
      url,
      data: formData,
      headers,
    }).then(response => {
      setSaving(false);
      setMessage(response.data.message);
      toast(response.data.message, {
        position: toast.POSITION.BOTTOM_RIGHT,
        autoClose: 3000,
      });
    });
  };

  useEffect(() => {
    if ('' !== message) {
      setTimeout(() => {
        setMessage('');
      }, 3000);
    }
  }, [message]);

  return (
    !isEmpty(data) &&
    <div className="form--business relative py-40 px-20 sm:px-60 bg-white rounded shadow-xl w-full">
      {saving &&
      <span className="absolute top-0 left-0 w-full h-full bg-grey-100 opacity-75 z-9 business--overlay"></span>}

      <div className="flex items-center">
        {(featuredImage || data?.['_featured_image_url']) &&
        <figure
          className="flex-center relative mr-20 border-4 border-grey-100 overflow-hidden"
          style={{
            width: '75px',
            height: '60px',
            borderRadius: '10px',
          }}
        >
          <img src={featuredImage || data?.['_featured_image_url']} alt={store.business.business.post_title}
               className="absolute top-0 left-0 w-full h-full object-contain"/>
        </figure>}
        <h3 className="font-bold">{store.business.business.post_title}</h3>
      </div>

      {loading && <LoaderAuth/>}

      {!loading && map(fieldGroups, (group, index) => {
        return (
          <div key={index} className="relative">
            <div className="form--wrapper flex flex-wrap w-full">
              <div className="form--product flex flex-wrap py-30 w-full">

                {map(store.fields[group], (field, name) => (
                  showField(field, name, group)
                ))}

                <div className="form-business--submit flex flex-col items-end mt-0 sm:mt-40 w-full">
                  <button
                    type="submit"
                    id="btn-submit"
                    className="flex justify-between items-center px-40 py-16 bg-blue-700 shadow-md rounded font-bold text-white hover:bg-blue-800"
                    onClick={e => handleSubmit(e)}
                    disabled={loading}
                  >
                    {lc_data.jst[52]}
                  </button>
                </div>

              </div>
            </div>
          </div>
        );
      })}
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

export default FormBusinessSubmit;
