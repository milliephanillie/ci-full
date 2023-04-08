/* global React */
/**
 * External dependencies.
 */
import * as actions from '../../../../dashboard/packages/store/actions';
import { Component, useEffect, useState } from '@wordpress/element';
import { map, isEmpty, isString } from 'lodash';
import Input from '../Input';
import { useDispatch, useSelector } from 'react-redux';
import produce from 'immer';
import { __ } from '@wordpress/i18n';
import Date from '../Date';
import { Fragment } from 'react';
import ReactSVG from 'react-svg';
import PlusIcon from '../../../../../images/icons/plus.svg';
import MinusIcon from '../../../../../images/icons/trash.svg';
import CloseIcon from '../../../../../images/icons/close.svg';

const Complex = (props) => {
  const store = useSelector(state => state);
  const data = store.formData;
  const dispatch = useDispatch();
  const {
    display,
    type,
    id,
    name,
    description,
    label,
    handleChange,
    fields,
    error,
    attributes,
    options,
    field,
  } = props;
  const [value, setValue] = useState(null);

  useEffect(() => {
    const values = [];
    const obj = {};
    map(fields, (field, name) => {
      obj[name] = '';
      values[0] = obj;
    });
    const elValue = isEmpty(data[props.name]) ? values : data[props.name];
    setValue(elValue);
  }, []);

  const handleFieldChange = (e, name, group, type, index) => {
    const elValue = isString(e) ? e : e.target.value;
    value[index][name] = elValue;
    setValue(value);
    const nextData = produce(data, (draft) => {
      draft[group] = value;
    });
    dispatch(actions.updateFormData(nextData));
  };

  const handleRemoveGroup = (e, name, group, index) => {
    const nextValue = produce(value, (draft) => {
      draft.splice(index, 1);
    });
    setValue(nextValue);
    const nextData = produce(data, (draft) => {
      draft[group] = nextValue;
    });
    dispatch(actions.updateFormData(nextData));
  };

  const handleAddGroup = (e, name, index) => {
    const obj = {};
    obj[name] = '';
    const nextValue = produce(value, (draft) => {
      draft.push(obj);
    });
    setValue(nextValue);
  };

  const customChange = (e, name, id, group, index) => {
    const nextValue = produce(value, (draft) => {
      if (!value[index][name]) {
        value[index][name] = [];
      }
      if (value[index][name].includes(id)) {
        draft[index][name].splice(id);
      } else {
        draft[index][name].push(id);
      }
    });
    setValue(nextValue);
    const nextData = produce(data, (draft) => {
      draft[group] = nextValue;
    });
    dispatch(actions.updateFormData(nextData));
  };

  const clearDate = (e, name, group, type, index) => {
    const nextValue = produce(value, (draft) => {
      draft[index][name] = '';
    });
    setValue(nextValue);
    const nextData = produce(data, (draft) => {
      draft[group] = nextValue;
    });
    dispatch(actions.updateFormData(nextData));
  };

  const showField = (field, name, group, index) => {
    const display = field.conditional ? data[field.conditional[0]] === field.conditional[1] : true;
    switch (field.type) {
      case 'title':
        return (
          <h5 className="font-semibold">{field.label}</h5>
        );
      case 'date':
        return (
          <div key={field.key} className="relative">
            <Date
              display={display}
              key={field.key}
              id={`${group}[${name}][${index}]`}
              name={name}
              label={field.label}
              handleChange={e => handleFieldChange(e, name, group, field.type, index)}
              value={!isEmpty(value[index][name]) ? value[index][name] : ''}
              attributes={field.attributes}
              description={field.description}
              options={field.options}
              additional={field.additional}
            />
            {value[index][name] !== '' &&
            <button type="button" onClick={e => clearDate(e, name, group, field.type, index)}
                    className="absolute top-12"
                    style={{ top: '37px', right: '34px' }}>
              <ReactSVG src={`${lc_data.dir}dist/${CloseIcon}`}
                        className="relative w-14 h-14 fill-grey-700"/>
            </button>}
          </div>
        );
      case 'custom':
        return (
          <div key={name} className="flex sm:justify-end">
            {options?.phone_apps && options.phone_apps.includes('viber') &&
            <div className="flex-center ml-10">
              <label htmlFor={`viber[${index}]`} className="text-grey-500">
                <input
                  id={`viber[${index}]`}
                  type="checkbox"
                  value="viber"
                  onChange={e => customChange(e, name, 'viber', group, index)}
                  checked={value[index][name] && value[index][name].includes('viber')}
                  className="relative top-0 mr-3"
                />
                {lc_data.jst[367]}
              </label>
            </div>
            }
            {options?.phone_apps && options.phone_apps.includes('whatsapp') &&
            <div className="flex-center ml-10">
              <label htmlFor={`whatsapp[${index}]`} className="text-grey-500">
                <input
                  id={`whatsapp[${index}]`}
                  type="checkbox"
                  value="whatsapp"
                  onChange={e => customChange(e, name, 'whatsapp', group, index)}
                  checked={value[index][name] && value[index][name].includes('whatsapp')}
                  className="relative top-0 mr-3"
                />
                {lc_data.jst[368]}
              </label>
            </div>
            }
            {options?.phone_apps && options.phone_apps.includes('skype') &&
            <div className="flex-center ml-10">
              <label htmlFor={`skype[${index}]`} className="text-grey-500">
                <input
                  id={`skype[${index}]`}
                  type="checkbox"
                  value="skype"
                  onChange={e => customChange(e, name, 'skype', group, index)}
                  checked={value[index][name] && value[index][name].includes('skype')}
                  className="relative top-0 mr-3"
                />
                {lc_data.jst[369]}
              </label>
            </div>
            }

          </div>
        );
      default:
        return <Input
          display={display}
          key={name}
          id={`${name}[${index}]`}
          name={name}
          label={field.label}
          handleChange={e => handleFieldChange(e, name, group, field.type, index)}
          value={!isEmpty(value[index][name]) ? value[index][name] : ''}
          additional={field.additional}
        />;
    }
  };

  return (
    display &&
    <div
      className={`repeater-group flex flex-col mb-20 ${field.style === 'dark' ? 'mb-0 p-20 bg-grey-100 rounded' : ''}`}>
      {map(value, (group, index) => {
        let count = 0;
        const style = {};
        style.top = '32px';
        if (field.style !== 'dark') {
          style.right = '-32px';
        } else {
          style.right = '0';
        }
        return (
          <div key={index} className="relative flex flex-wrap w-90% sm:w-full">
            {label && index === 0 && <div className="flex w-full mb-20 font-semibold text-grey-1100">{label}</div>}
            <div className={`flex flex-wrap justify-between`}>
              {map(fields, (field, fieldName) => {
                count += 1;
                return (
                  <div key={fieldName}
                       className={`flex ${field.type === 'date' ? 'justify-between' : 'flex-col w-full'}`}>
                    {showField(field, fieldName, name, index, type)}
                  </div>
                );
              })}
            </div>
            <div className="action absolute ml-10"
                 style={style}>
              {(count) === Object.keys(fields).length ? index === 0
                ?
                <button
                  type="button"
                  onClick={e => handleAddGroup(e, name, value.length)}
                >
                  <ReactSVG src={`${lc_data.dir}dist/${PlusIcon}`}
                            className="relative w-20 h-20 fill-green-700"/>
                </button>
                :
                <button
                  type="button"
                  className="material-icons-outlined"
                  onClick={e => handleRemoveGroup(e, `${name}[${index}]`, name, index)}
                >
                  <ReactSVG src={`${lc_data.dir}dist/${MinusIcon}`}
                            className="relative w-20 h-20 fill-red-700"/>
                </button>
                : ''
              }
            </div>
          </div>
        );
      })
      }
    </div>
  );
};

export default Complex;
