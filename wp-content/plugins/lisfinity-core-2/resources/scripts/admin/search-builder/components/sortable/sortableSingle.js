/**
 * External dependencies.
 */
import produce from 'immer';
import { Component, Children } from '@wordpress/element';
import { toast } from 'react-toastify';

class Sortable extends Component {
  /**
   * Lifecycle hook.
   *
   * @return {void}
   */
  componentDidMount() {
    const { options, forwardedRef } = this.props;

    window.jQuery(forwardedRef.current).sortable({
      ...options,
      start: this.handleStart,
      update: this.handleUpdate,
      stop: this.handleStop
    });
  }

  /**
   * Lifecycle hook.
   *
   * @return {void}
   */
  componentWillUnmount() {
    const { forwardedRef } = this.props;
    const $element = window.jQuery(forwardedRef.current);
    const instance = $element.sortable('instance');

    if (instance) {
      $element.sortable('destroy');
    }
  }

  /**
   * Handles the `start` event.
   *
   * @param  {Object} e
   * @param  {Object} ui
   * @return {void}
   */
  handleStart = (e, ui) => {
    const { onStart } = this.props;

    if (onStart) {
      onStart(e, ui);
    }

    ui.item.data('index', ui.item.index());
  }

  /**
   * Handles the `update` event.
   *
   * @param  {Object} e
   * @param  {Object} ui
   * @return {void}
   */
  handleUpdate = (e, ui) => {
    const {
      forwardedRef,
      onUpdate
    } = this.props;

    const items = document.querySelectorAll('.group--sort');
    const order = [];
    if (items) {
      items.forEach(item => {
        order.push(item.dataset.order);
      })
    }
    const niche = document.querySelector('.groups.ui-sortable');

    const headers = new Headers();
    const formData = new FormData();
    formData.append('order', order);
    formData.append('niche', niche.dataset.niche);
    let url = lc_data.single_builder_order_group;
    headers.append('X-WP-Nonce', lc_data.nonce);
    fetch(url, {
      method: 'POST',
      credentials: 'same-origin',
      headers,
      body: formData,
    }).then(response => response.json().then(json => {
        toast(json.message, {
          position: toast.POSITION.BOTTOM_RIGHT,
          autoClose: 3000,
        });
      }
    ));
  }

  /**
   * Handles the `stop` event.
   *
   * @param  {Object} e
   * @param  {Object} ui
   * @return {void}
   */
  handleStop = (e, ui) => {
    const { onStop } = this.props;

    if (onStop) {
      onStop(e, ui);
    }
  }

  /**
   * Renders the component.
   *
   * @return {Object}
   */
  render() {
    return Children.only(this.props.children);
  }
}

export default Sortable;
