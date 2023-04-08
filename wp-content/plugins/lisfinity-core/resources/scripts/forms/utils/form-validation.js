/* global lc_data */
/**
 * The external dependencies.
 */
import store from '../index';
import * as actions from '../../dashboard/packages/store/actions';
import { sprintf, __ } from '@wordpress/i18n';
import { map, isEmpty } from 'lodash';

export default (step, props, dispatch) => {
  const { fields, customFields } = props;
  const data = props.formData;
  dispatch(actions.updateFormErrors({}));
  let errors = props.formErrors;
  errors = {};
  map(fields[step.id], (field, name) => {
    if (field.required) {
      // if has attributes to check for.
      if (field.required) {
        if (field?.attributes?.type === 'number') {
          if (Array.isArray(field.conditional[1])) {
            if (data[field.conditional[0]].includes(field.conditional[1])) {
              if (data[name] < field.attributes.min) {
                errors[name] = sprintf(lc_data.jst[381], field.label, field.attributes.min);
              }
              if (data[name] > field.attributes.max) {
                errors[name] = sprintf(lc_data.jst[382], field.label, field.attributes.max);
              }
            }
          } else {
            if (data[field.conditional[0]] === field.conditional[1]) {
              if (data[name] < field.attributes.min) {
                errors[name] = sprintf(lc_data.jst[381], field.label, field.attributes.min);
              }
              if (data[name] > field.attributes.max) {
                errors[name] = sprintf(lc_data.jst[382], field.label, field.attributes.max);
              }
            }
          }
        }
      }
      // if checkbox hasn't been checked.
      if (field.type === 'checkbox') {
        if (data[name] !== true) {
          errors[name] = sprintf(lc_data.jst[383], name);
        }
      }
      // if location is not set.
      if (field.type === 'location') {
        map(customFields, (customField, fieldName) => {
          map(customField, (field, name) => {
            if (field.type === 'location' && field.submission_required && field.submission_required === 'yes') {
              if (fieldName !== 'xloc') {
                if (isEmpty(data[fieldName][field.slug])) {
                  errors[field.slug] = sprintf(lc_data.jst[385], field.single_name);
                }
              }
            }
          });

        });
        if (isEmpty(`location[${data[name]}]`)) {
          errors[name] = sprintf(lc_data.jst[384], field.label);
        }
      }
      // if the required fields are empty.
      if (['text', 'rich-text', 'select', 'media', 'complex'].includes(field.type)) {
        if (field.type === 'rich-text' && !isEmpty(field?.options?.bad_words)) {
          let badWords = field?.options?.bad_words.split(/,/).map(function (item) {
            return item.trim();
          });
          if (!isEmpty(badWords)) {
            let wordArray = data[name].split(/\s+/);
            wordArray[0] = wordArray[0].replace('<p>', '');
            wordArray[wordArray.length - 1] = wordArray[wordArray.length - 1].replace('<\/p>', '');
            let includedBadWords = [];
            wordArray.filter(el => {
              badWords.map(element => {
                if (el.includes(element)) {
                  includedBadWords.push(element);
                }
              });
            });
            if (!isEmpty(includedBadWords)) {
              errors[name] = sprintf(lc_data.jst[744], includedBadWords);
            }
          }
        }

        if (field.conditional && Array.isArray(field.conditional[1])) {
          if (data[field.conditional[0]].includes(field.conditional[1])) {
            if (isEmpty(data[name])) {
              errors[name] = sprintf(lc_data.jst[385], field.label);
            }
          }
        } else if (field.conditional && !Array.isArray(field.conditional[1])) {
          if (data[field.conditional[0]] === field.conditional[1]) {
            if (isEmpty(data[name])) {
              errors[name] = sprintf(lc_data.jst[385], field.label);
            }
          }
        } else {
          if (field.required && isEmpty(data[name])) {
            errors[name] = sprintf(lc_data.jst[385], field.label);
          }
        }
      }
    }
    if (field.type === 'taxonomies') {
      map(customFields, (customField, fieldName) => {
        if (fieldName === props?.formData?.cf_category) {
          map(customField, (field, name) => {
            if (field.submission_required && field.submission_required === 'yes' && field.type !== 'location') {
              if (isEmpty(data[fieldName][field.slug])) {
                errors[field.slug] = sprintf(lc_data.jst[385], field.single_name);
              }
            }
          });
        }
      });
    }
  });

  return errors;
};
