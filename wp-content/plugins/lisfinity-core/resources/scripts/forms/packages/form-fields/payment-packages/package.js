/* global lc_data, React */
/**
 * External dependencies.
 */
import store from '../../../index';
import * as actions from '../../store/actions';
import { Component, Fragment, createRef } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';
import { map, get, omit, isEmpty } from 'lodash';

class Package extends Component {
  constructor(props) {
    super(props);
    this.state = {
      value: this.props.value,
      products: [],
      addAllDisabled: false,
    };
  }

  /**
   * Component has been mounted
   * --------------------------
   *
   * @returns {Promise<any>}
   */
  componentWillMount() {
    const url = `${lc_data.packages}/${[this.props.field.product]}`;
    fetch(url).then(json => json.json()).then(products => this.setState({ products }));
  }

  componentDidMount() {
    const submitBtn = document.getElementById('btn-submit');
    submitBtn.style.display = 'none';
  }

  handleClick = (e) => {
    const data = store.getState().formData;
    const { name } = this.props;
    const id = e.target.dataset.id;
    const submitBtn = document.getElementById('btn-submit');
    data['package_submit'] = true;
    data[name] = id;

    store.dispatch(actions.updateFormData(data));

    // trigger form submission handler.
    submitBtn.click();
  }

  /**
   * Render the component.
   *
   * @return {Object}
   */
  render() {
    const { products, addAllDisabled, value, active } = this.state;
    const { name } = this.props;
    const data = store.getState().formData;

    return [
      products &&
      <div key={0} className="packages">
        {map(products, (product, index) => {
          return (
            <div key={index} className="package">
              <h4>{product.post_title}</h4>
              <div className="price-wrapper">
                {product.meta['sale-price'] ?
                  <p className="price price-old line-through">{product.meta.price}</p>
                  :
                  <p className="price">{product.meta.price}</p>
                }
                {product.meta['sale-price'] && <p className="price-sale">{product.meta['sale-price']}</p>}
              </div>
              <div className="duration-wrapper">
                {product.meta.duration && <p className="duration">{product.package.duration}</p>}
              </div>
              {product.package.features &&
              <div>
                <span>{lc_data.jst[370]}</span>
                <ul>
                  {map(product.package.features, (feature, key) => {
                    return <li key={key}>{feature['package-feature']}</li>;
                  })}
                </ul>
              </div>
              }
              {product.package.promotions &&
              <div>
                <span>{lc_data.jst[371]}</span>
                <ul>
                  {map(product.package.promotions, (promotion, key) => {
                    return <li key={key}>
                      <span>{promotion['package-promotions-product']}</span>
                      <span>{promotion['package-promotions-value']}</span>
                    </li>;
                  })}
                </ul>
              </div>
              }
              <button type="button" data-id={product.ID} onClick={this.handleClick}>
                {lc_data.jst[111]}
              </button>
            </div>
          );
        })}
      </div>,
    ];
  }
}

export default Package;
