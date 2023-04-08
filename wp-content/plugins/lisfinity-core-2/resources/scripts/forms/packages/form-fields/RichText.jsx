/**
 * External dependencies.
 */
import { Component } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';
import { isString, template, isEmpty } from 'lodash';
import cx from 'classnames';
import ReactSVG from 'react-svg';
import QuestionIcon from '../../../../images/icons/question-circle.svg';
import { Fragment } from 'react';
import Textarea from 'react-textarea-autosize';

class RichTextField extends Component {
  /**
   * Define the project base properties
   *
   * @return {void}
   */
  constructor(props) {
    super(props);

    this.state = {
      value: this.props.value,
      charCount: 1,
    }

    this.node = null;
    this.editor = null;
    this.cancelResizeObserver = null;
  }

  /**
   * Lifecycle hook.
   *
   * @return {void}
   */
  componentDidMount() {
    if (this.props.visible) {
      this.timer = setTimeout(this.initEditor, 250);
    }
  }

  /**
   * Lifecycle hook.
   *
   * @return {void}
   */
  componentWillUnmount() {
    clearTimeout(this.timer);

    this.destroyEditor();
  }

  /**
   * Handles the change of the input.
   *
   * @param  {Object|string} eventOrValue
   * @return {void}
   */
  handleChange = (eventOrValue) => {
    const { id, onChange } = this.props;

    const elValue = isString(eventOrValue) ? eventOrValue : eventOrValue.target.value
    this.setState({ value: elValue });
  }

  /**
   * Renders the component.
   *
   * @return {Object}
   */
  render() {
    const { charCount } = this.state;
    const {
      id,
      name,
      field,
      attributes,
      error,
      placeholder,
      description,
      label,
      additional,
    } = this.props;

    const classes = [
      'carbon-wysiwyg',
      'wp-editor-wrap',
      { 'tmce-active': field.rich_editing },
      { 'html-active': !field.rich_editing }
    ];

    const mediaButtonsHTML = field.media_buttons
      ? template(field.media_buttons)({ id })
      : null;

    const len = null !== this.editor ? this.editor.getContent().replace(/(<([^>]+)>)/ig, "").length : 0;
    return (
      <div className={`${additional && additional.class ? additional.class : 'mb-40 w-full'}`}>
        <div className="field--top flex justify-between">
          <label htmlFor={id} className="field--label flex items-center mb-6 text-sm text-grey-500">
            {label}
            {description &&
            <ReactSVG src={`${lc_data.dir}dist/${QuestionIcon}`} data-tip={description}
                      className="relative left-6 w-14 h-14 fill-blue-700" style={{ top: '-8px' }}/>
            }
          </label>
          {error && error[name] &&
          <div className="field--error text-sm text-red-700 w-2/3 text-right">{error[name]}</div>}
        </div>

        <div
          id={`wp-${id}-wrap`}
          className={cx(classes)}
          ref={(node) => this.editorNode = node}
        >
          {field.media_buttons && (
            <div id={`wp-${id}-media-buttons`} className="hide-if-no-js wp-media-buttons">
              <span dangerouslySetInnerHTML={{ __html: mediaButtonsHTML }}></span>
            </div>
          )}

          {field.rich_editing && !field.hide_buttons && (
            <div className="wp-editor-tabs">
              <button type="button" id={`${id}-tmce`} className="wp-switch-editor switch-tmce" data-wp-editor-id={id}>
                {lc_data.jst[361]}
              </button>

              <button type="button" id={`${id}-html`} className="wp-switch-editor switch-html" data-wp-editor-id={id}>
                {lc_data.jst[362]}
              </button>
            </div>
          )}

          <div id={`wp-${id}-editor-container`} className="relative wp-editor-container">
					<textarea
            style={{ width: '100%' }}
            className="w-full min-h-40 bg-grey-100 font-semibold text-grey-900 outline-none"
            id={id}
            name={name}
            value={this.props.value || this.state.value}
            onChange={this.props.handleChange}
            {...attributes}
          />
            {field.max_chars >= len &&
            <span className="description absolute bottom-0 right-20 font-light text-sm"
                  dangerouslySetInnerHTML={{ __html: sprintf(lc_data.jst[196], field.max_chars - len) }}></span>}
            {field.max_chars < len &&
            <span
              className="description absolute bottom-0 right-20 font-light text-sm text-red-700">{lc_data.jst[197]}</span>}
          </div>
        </div>
      </div>
    );
  }

  /**
   * Initialize the WYSIWYG editor.
   *
   * @return {void}
   */
  initEditor = () => {
    const { id, field, attributes } = this.props;

    if (field.rich_editing) {
      const editorSetup = (editor) => {
        this.editor = editor;

        editor.onKeyDown.add((ed, e) => {
          const content = editor.getContent().replace(/(<([^>]+)>)/ig, "");
          const max = field.max_chars;
          const len = content.length !== 0 ? content.length : 1;

          if (len > max) {
            if (e.keyCode !== 8 && e.keyCode !== 46) {
              e.preventDefault();
            } else {
              this.setState({ charCount: len });
            }
          } else {
            this.setState({ charCount: len });
          }
        });

        editor.on('blur Change', () => {
          editor.save();

          this.props.handleChange(editor.getContent());
        });
      };
      let options = { ...window.tinyMCEPreInit.mceInit.lisfinity_settings, ...field };
      const editorOptions = {
        ...options,
        selector: `#${id}`,
        setup: editorSetup,
      };

      window.tinymce.init(editorOptions);
    }

    const quickTagsOptions = {
      ...window.tinyMCEPreInit,
      id
    };

    window.quicktags(quickTagsOptions);

    // Force the initialization of the quick tags.
    window.QTags._buttonsInit();
  }

  /**
   * Destroy the instance of the WYSIWYG editor.
   *
   * @return {void}
   */
  destroyEditor() {
    if (this.editor) {
      this.editor.remove();

      this.node = null;
      this.editor = null;
    }

    if(!isEmpty(this.props.id)) {
      delete window.QTags.instances[this.props.id];
    }
  }
}

export default RichTextField;
