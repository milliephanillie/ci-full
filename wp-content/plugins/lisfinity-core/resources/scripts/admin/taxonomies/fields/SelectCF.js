/**
 * External dependencies.
 */
import { Component } from '@wordpress/element';
import { map } from 'lodash';
import ReactSVG from 'react-svg';
import QuestionIcon from '../../../../images/icons/question-circle.svg';
import { sprintf } from '@wordpress/i18n';

class SelectCF extends Component {
  constructor(props) {
    super(props);
    this.state = { value: '', active: false };

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
    this.props.handleChange(e);
  }

  changeText(e) {
    this.props.handleChange(e.target.value, `${this.props.id}|custom`, this.props.taxonomy.field_group, 'input');
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
      parent,
      handleChange,
      firstEmpty,
      error,
      data,
    } = this.props;

    return (
      options.length > 0 && display &&
      <div
        className={`form-field flex flex-col w-full ${classes}`}>
        <div className="field--top flex justify-between">
          <label htmlFor={id} className="field--label flex items-center mb-6 text-sm text-grey-500">
            {label}
            {description &&
            <ReactSVG src={`${lc_data.dir}dist/${QuestionIcon}`} data-tip={description}
                      className="relative left-6 w-14 h-14 fill-blue-700" style={{ top: '-8px' }}/>
            }
          </label>
          {error &&
          <div className="field--error text-sm text-red-700 w-2/3 text-right">{error}</div>}
        </div>
        <select
          id={id}
          name={name}
          value={value}
          className={`lisfinity-field flex mb-20 py-10 px-20 h-44 w-full border border-grey-300 rounded font-semibold cursor-pointer outline-none ${this.state.active ? 'bg-transparent' : 'bg-grey-100'}`}
          data-parent={parent}
          onChange={e => this.handleChange(e)}
          onFocus={() => this.setState({ active: true })}
          onBlur={() => this.setState({ active: false })}
        >
          {firstEmpty && <option value="">{lc_data.jst[102]}</option>}
          {map(options, (item, index) => (
            <option key={index} value={item.slug} dangerouslySetInnerHTML={{
              __html: item.single_name ? item.single_name : item.name,
            }}/>
          ))}
        </select>
        <span className="description">{description}</span>
        {data?.[name] === 'custom' &&
        <div className="select--custom mb-20">
          <label htmlFor={id}
                 className="field--label flex items-center mb-6 text-sm text-grey-500">{sprintf(lc_data.jst[730], this.props.taxonomy.single_name)}</label>
          <input type="text" id={`${id}-custom`} name={`${name}|custom`}
                 className="w-full h-44 bg-grey-100 border border-grey-300 rounded px-20"
                 onChange={e => this.changeText(e)}
                 defaultValue={data?.[`${name}|custom`]}
                 autoComplete="off"
          />
        </div>
        }
      </div>
    );
  }

}

export default SelectCF;
