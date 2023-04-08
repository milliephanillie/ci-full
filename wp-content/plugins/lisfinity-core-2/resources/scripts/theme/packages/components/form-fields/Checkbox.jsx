/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { map } from 'lodash';

class Checkbox extends Component {
  constructor(props) {
    super(props);
    this.state = { checked: this.props.checked };

    this.handleChange = this.handleChange.bind(this);
  }

  /**
   * Handles the change of the input.
   *
   * @param  {Object} e
   * @return {void}
   */
  handleChange(e) {
    const { checked } = this.state;
    this.setState({ checked: !!checked });
  }

  /**
   * Renders the component.
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
      handleChange,
      checked,
    } = this.props;
    return (
      <input
        type="checkbox"
        id={id}
        name={name}
        className="filters--checkbox"
        onChange={handleChange}
        checked={checked || false}
        value={value}
      />
    );
  }
}

export default Checkbox;
