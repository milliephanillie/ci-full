import { combineReducers } from '@wordpress/data';
import { map } from 'lodash';

export default combineReducers({
  fields,
  fieldGroups,
  fieldsByGroup,
  formData,
  formErrors,
  paymentPackage,
  costs,
  customFields,
});
