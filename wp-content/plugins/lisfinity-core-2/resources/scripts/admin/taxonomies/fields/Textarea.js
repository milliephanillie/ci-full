/**
 * External dependencies.
 */
import { Component } from '@wordpress/element';
import TextareaField from 'react-textarea-autosize';

class Textarea extends Component {
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
      id,
      name,
      value,
      description,
      label,
      field,
    } = this.props;

    return (
      <div className={`mb-20 ${field.additional && field.additional.class && field.additional.class}`}>
        <label htmlFor={id}
               className="lisfinity-label flex mb-10 text-md">{label}{field && field.required &&
        <span className="text-sm text-red-600 leading-none">*</span>}</label>
        <TextareaField
          id={id}
          name={name}
          value={this.state.value}
          className="lisfinity-field flex px-16 py-12 w-full h-44 border border-grey-200 rounded font-bold text-grey-900"
          onChange={this.handleChange}
          placeholder={field.placeholder && field.placeholder}
          autoComplete="off"
        />
        <div className="description mt-6 text-grey-700 leading-snug" style={{ fontSize: '11px' }}>{description}</div>
      </div>
    );
  }
}

export default Textarea;
