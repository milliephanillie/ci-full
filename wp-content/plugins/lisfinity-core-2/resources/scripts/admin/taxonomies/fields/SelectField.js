/**
 * External dependencies.
 */
import { Component, Fragment } from '@wordpress/element';
import { map } from 'lodash';

class SelectField extends Component {
  constructor(props) {
    super(props);
    this.state = { value: this.props.value };

    this.handleChange = this.handleChange.bind(this);
  }

  /**
   * Handles the change of the input.
   *
   * @param  {Object} e
   * @return {void}
   */
  handleChange(e) {
    this.setState({ value: e.target.value });
  }

  /**
   * Render the component.
   *
   * @return {Object}
   */
  render() {
    const {
      type,
      id,
      name,
      description,
      label,
      options,
      value,
      classes,
      display,
      multiselect,
      field,
      handleChange,
    } = this.props;

    return (
      options.length !== 0 && display &&
      <div
        className={`flex flex-col mb-20 z-10 ${classes} ${field.additional && field.additional.class && field.additional.class}`}>
        <label htmlFor={id} className="lisfinity-label mb-10">{label}{field && field.required &&
        <span className="text-sm text-red-600 leading-none">*</span>}</label>
        <select
          id={id}
          name={name}
          defaultValue={value}
          className="lisfinity-select py-10 bg-grey-100 border-grey-200 rounded"
          onChange={(e) => handleChange(name, e.target.value)}
          multiple={multiselect}
        >
          {map(options, (label, value) => {
              return (
                <Fragment key={!label.key ? label : label.key}>
                  {value !== this.props.taxonomy && !label.value &&
                  <option value={value}>
                    {label}
                  </option>
                  }
                  {label.value &&
                  <option value={label.key}>{label.value}</option>
                  }
                </Fragment>
              );
            }
          )}
        </select>
        <span className="text-sm mt-6 text-grey-700 leading-snug">{description}</span>
      </div>
    );
  }
}

export default SelectField;
