/* global lc_data, React */
/**
 * External dependencies.
 */
import * as actions from '../../../dashboard/packages/store/actions';
import { Component, Fragment } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';
import { map, isEmpty, isString, filter, remove, get, sortBy } from 'lodash';
import SelectCF from '../../../admin/taxonomies/fields/SelectCF';
import PlusIcon from '../../../../images/icons/plus.svg';
import MinusIcon from '../../../../images/icons/minus.svg';
import Input from './Input';
import Checkbox from './Checkbox';
import { connect } from 'react-redux';
import LoaderCF from '../../../theme/packages/components/loaders/LoaderCF';
import ReactSVG from 'react-svg';
import { addProps } from '../../../theme/vendor/functions';

class CustomFields extends Component {
  constructor(props) {
    super(props);
    this.state = {
      loading: false,
      fieldsCommon: {},
      groups: this.props.groups,
      fieldsByGroup: {},
      data: {},
      cf_taxonomies: {},
      cf_terms: {},
      taxonomy_slugs: {},
      hidden: true,
    };

    this.categoryField = React.createRef();

  }

  componentDidMount() {
    this.fetchGroups();
  }

  /**
   * Props from HOC received
   * -----------------------
   *
   * @param nextProps
   * @param nextContext
   */
  componentWillReceiveProps(nextProps, nextContext) {
    this.setState({ data: nextProps.data });
  }

  /**
   * Fetch custom fields taxonomy groups
   * -----------------------------------
   */
  fetchGroups() {
    const { dispatch } = this.props;

    this.setState({ fieldsByGroup: this.props.customFields });
    const data = this.props.formData;

    if (!data['cf_loaded']) {
    }
    this.populateCfData();
  }

  /**
   * Populate Custom Fields Taxonomies & Terms
   * -----------------------------------------
   */
  populateCfData() {
    const { dispatch } = this.props;
    const fieldsByGroup = this.props.customFields;
    const data = this.props.formData;

    // populate taxonomies
    let taxonomies = {};
    if (this.props.options.common_first) {
      if (!isEmpty(fieldsByGroup['common'])) {
        taxonomies['common'] = fieldsByGroup['common'];
      }
    }
    if (!isEmpty(fieldsByGroup[data['cf_category']])) {
      taxonomies[data['cf_category']] = fieldsByGroup[data['cf_category']];
    }
    if (!this.props.options.common_first) {
      if (!isEmpty(fieldsByGroup['common'])) {
        taxonomies['common'] = fieldsByGroup['common'];
      }
    }
    this.setState({ cf_taxonomies: taxonomies });

    // populate terms
    let terms = [];
    const locations = [];
    const slugs = {};
    if (!isEmpty(taxonomies)) {
      map(taxonomies, (taxonomy, group) => map(taxonomy, field => terms.push(field.slug)));
      this.setState({ loading: true });
      map(taxonomies, (taxonomy, group) => {
        map(taxonomy, field => {
          slugs[field.slug] = field;
          if (field.type === 'location') {
            locations.push(field.slug);
          }
          if (field.type === 'select' && !locations.includes(field.parent)) {
            const term = filter(this.props.terms, { 'taxonomy': field.slug });
            if (!isEmpty(term)) {
              if (isEmpty(data[group][field.slug]) && field?.first_empty !== 'yes') {
                data[group][field.slug] = term[0].slug;
              }
              dispatch(actions.updateFormData(data));
              this.setState(prevState => ({
                data: {
                  ...prevState.data,
                  [group.name]: term[0].slug,
                }
              }));
            }
          }
        });
      });
      this.setState({ taxonomy_slugs: slugs });
      this.setState({ loading: false });
    }
  }

  /**
   * Store the field value
   * ---------------------
   *
   * @param e
   * @param name
   * @param type
   */
  handleChange = (e, name, type) => {
    const { dispatch } = this.props;
    // handle form data change
    const data = this.props.formData;
    let elValue = isString(e) ? e : e.target.value;

    this.setState(prevState => ({
      data: {
        ...prevState.data,
        [name]: elValue,
      }
    }));
    data[name] = elValue;
    dispatch(actions.updateFormData(data));

    this.calculateTotal();

    // handle custom fields dependency loading
    if (name === 'cf_category') {
      this.populateCfData();
    }
  };

  calculateTotal = () => {
    const { dispatch } = this.props;
    const payment_package = this.props.paymentPackage;
    const data = this.props.formData;

    let categoryIndex = 999;
    if (payment_package?.is_exclusive) {
      !isEmpty(payment_package?.categories) && map(payment_package.categories, (cat, index) => {
        if (data['cf_category'] === cat?.category) {
          categoryIndex = index;
        }
      });
    }

    let price = parseFloat(payment_package?.categories?.[categoryIndex]?.price) || 0;
    if (!data.costs) {
      data.costs = {};
    }
    addProps(data.costs, ['total', 'additional'], price);

    if (data.costs && data.costs.total) {
      let final = (data.costs['total']['media'] || 0) + (data.costs['total']['promo']) || (data.costs['total']['commission']) || 0;
      if (data?.costs['total']?.['additional']) {
        final += data.costs['total']['additional'];
      }
      data.costs['final'] = final;
      dispatch(actions.updateCosts(data.costs));
    }
  };

  /**
   * Handle the change of custom fields
   * ----------------------------------
   *
   * @param e
   * @param name
   * @param group
   * @param type
   */
  handleChangeCF = (e, name, group, type, prefix, suffix) => {
    const { dispatch } = this.props;
    // handle form data change
    const data = this.props.formData;

    let elValue = isString(e) ? e : e.target.value;

    console.log("evalue")
    console.log(elValue)
    console.log(group)

    if (type === 'select') {
      const child = document.querySelectorAll(`[data-parent=${name}]`)[0];
      setTimeout(() => {
        if (child) {
          data[group][child.name] = child.value;
        }
      });
      data[group][name] = `${prefix}${elValue}${suffix}`;

      // handle custom fields dependency loading
    } else if (type === 'checkbox') {
      if (!data[group][name]) {
        data[group][name] = [];
      } else if (typeof data[group][name] === 'string') {
        data[group][name] = [data[group][name]];
      }

      if (data[group][name].includes(elValue)) {
        remove(data[group][name], (el) => el === elValue);
      } else {
        data[group][name].push(elValue);
      }
    } else {
      data[group][name] = `${prefix}${elValue}${suffix}`;
    }
    this.setState(prevState => ({
      data: {
        ...prevState.data,
        [group.name]: elValue,
      }
    }));
    dispatch(actions.updateFormData(data));
  };

  setHidden = (e, key) => {
    if (this.state.hidden === key) {
      this.setState({ hidden: false });
    } else {
      this.setState({ hidden: key });
    }
  };

  /**
   * Render the component.
   *
   * @return {Object}
   */
  render() {
    const { group, formData, error } = this.props;
    const { loading, groups, cf_taxonomies, taxonomy_slugs, key } = this.state;
    const fieldsByGroup = this.props.customFields;
    const cf_terms = this.props.terms;
    let packageCategoryIndex = 999;
    {
      !isEmpty(this.props.paymentPackage?.categories) && map(this.props.paymentPackage.categories, (cat, index) => {
        if (formData['cf_category'] === cat?.category) {
          packageCategoryIndex = index;
        }
      });
    }

    return [
      loading && <LoaderCF key={0}/>,
      !loading && <div key={1} className="flex flex-col w-full">
        <div>
          <SelectCF
            display
            id="cf_category"
            name="cf_category"
            label={lc_data.jst[333]}
            options={groups}
            handleChange={e => this.handleChange(e, 'cf_category', 'select')}
            value={formData['cf_category']}
            ref={this.categoryField}
          />
          {!isEmpty(this.props.paymentPackage?.categories) && this.props.paymentPackage?.is_exclusive ?
            <span className="relative text-sm text-red-600" style={{ top: -15 }}>
            {!isEmpty(this.props.paymentPackage?.categories[packageCategoryIndex]) ? sprintf(lc_data.jst[747], parseFloat(this.props.paymentPackage?.categories[packageCategoryIndex]?.price) || 0, this.props.paymentPackage?.categories[packageCategoryIndex]?.currency) : lc_data.jst[748]}
          </span> : ''}
          {!isEmpty(this.props.paymentPackage?.categories) && !this.props.paymentPackage?.is_exclusive ?
            <span className="relative text-sm text-red-600" style={{ top: -15 }}>
            {!isEmpty(this.props.paymentPackage?.categories[packageCategoryIndex]) ? sprintf(lc_data.jst[747], parseFloat(this.props.paymentPackage?.categories[packageCategoryIndex]?.price) || 0, this.props.paymentPackage?.categories[packageCategoryIndex]?.currency) : ''}
          </span> : ''}
        </div>
        {cf_taxonomies &&
          map(cf_taxonomies, (group, fields) => (
            map(group, (taxonomy, index) => {
              const parentValue = !isEmpty(formData[taxonomy.field_group]) ? formData[taxonomy.field_group][taxonomy.parent] : '';
              let choices = [];
              let prefix = '';
              let suffix = '';
              if (get(taxonomy, 'prefix_taxonomy')) {
                prefix = taxonomy.prefix_taxonomy;
              }
              if (get(taxonomy, 'suffix_taxonomy')) {
                suffix = taxonomy.suffix_taxonomy;
              }

              if (isEmpty(taxonomy.parent) && isEmpty(parentValue)) {
                choices = filter(this.props.terms, { taxonomy: taxonomy.slug });
              } else {
                choices = filter(this.props.terms, {
                  taxonomy: taxonomy.slug,
                  meta: { parent_slug: parentValue || [parentValue] }
                });
              }
              if (isEmpty(choices) && !isEmpty(taxonomy.parent)) {
                choices = filter(this.props.terms, { taxonomy: taxonomy.slug, slug: 'custom' });
              }

              return (
                <div key={index}>
                  {taxonomy.type === 'select' && !isEmpty(choices) &&
                    <SelectCF
                      display
                      name={taxonomy.slug}
                      label={sprintf(lc_data.jst[334], taxonomy.single_name)}
                      options={sortBy(choices, ['name'])}
                      handleChange={(e, name, group, type) => this.handleChangeCF(e, name || taxonomy.slug, group || taxonomy.field_group, type || 'select', prefix, suffix)}
                      value={formData[taxonomy.field_group][taxonomy.slug]}
                      parent={taxonomy.parent}
                      data={formData[taxonomy.field_group]}
                      firstEmpty={taxonomy?.first_empty === 'yes'}
                      taxonomy={taxonomy}
                      error={error && error[taxonomy.slug]}
                    />
                  }
                  {
                      taxonomy.type === 'text' && (
                          <React.Fragment>
                            {
                              // // Log the desired value and return null to avoid rendering anything
                              // (console.log(formData[taxonomy.field_group][taxonomy.slug])?.[0]?.name, null)
                            }
                            <Input
                                display
                                customText={'custom-text-input'}
                                id={taxonomy.slug}
                                name={taxonomy.slug}
                                label={sprintf(lc_data.jst[335], taxonomy.single_name)}
                                handleChange={e => this.handleChangeCF(e, taxonomy.slug, taxonomy.field_group, 'text', prefix, suffix)}
                                value={formData[taxonomy.field_group][taxonomy.slug]}
                                parent={taxonomy.parent}
                                additional={{ class: 'xs:mb-20' }}
                                type="text"
                                errorTaxonomy={error && error[taxonomy.slug]}
                            />
                          </React.Fragment>
                      )
                  }
                  {taxonomy.type === 'input' &&
                    <Input
                      display
                      id={taxonomy.slug}
                      name={taxonomy.slug}
                      label={sprintf(lc_data.jst[335], taxonomy.single_name)}
                      handleChange={e => this.handleChangeCF(e, taxonomy.slug, taxonomy.field_group, 'input', prefix, suffix)}
                      value={formData[taxonomy.field_group][taxonomy.slug]}
                      parent={taxonomy.parent}
                      additional={{ class: 'xs:mb-20' }}
                      type="number"
                      errorTaxonomy={error && error[taxonomy.slug]}
                    />
                  }
                  {taxonomy.type === 'checkbox' &&
                    <Fragment>
                      <div className="flex flex-wrap mb-10">
                        <div className="field--top flex items-center justify-between mb-10 w-full">
                          {this?.props?.options?.is_collapsable &&
                            <button type="button"
                                    className="collapsible w-18 h-18 flex-center mr-10 bg-grey-100 border rounded border-grey-300"
                                    onClick={e => this.setHidden(e, taxonomy.slug)}>
                              {this.state.hidden === taxonomy.slug &&
                                <ReactSVG
                                  src={`${lc_data.dir}dist/${MinusIcon}`}
                                  className="w-14 h-14 fill-grey-700"/>
                              }
                              {(!this.state.hidden || this.state.hidden !== taxonomy.slug) &&
                                <ReactSVG
                                  src={`${lc_data.dir}dist/${PlusIcon}`}
                                  className="w-14 h-14 fill-grey-700"/>
                              }
                            </button>
                          }
                          <h5 className="font-semibold text-grey-1000 w-full">{taxonomy.single_name}</h5>
                          {error && error[taxonomy.slug] &&
                            <div
                              className="field--error text-sm text-red-700 w-2/3 text-right">{error[taxonomy.slug]}</div>}
                        </div>
                        <div
                          className={`${(!this?.props?.options?.is_collapsable || this?.state?.hidden === taxonomy.slug) ? 'flex flex-wrap' : 'hidden'}`}>
                          {map(choices, (term, index) => {
                            return (
                              <div key={index} className="w-1/2 sm:w-1/3 bg:w-1/4">
                                <Checkbox
                                  key={index}
                                  display
                                  id={term.slug}
                                  name={taxonomy.slug}
                                  label={term.name}
                                  handleChange={() => this.handleChangeCF(term.slug, taxonomy.slug, taxonomy.field_group, 'checkbox', prefix, suffix)}
                                  checked={formData && formData[taxonomy.field_group] && formData[taxonomy.field_group][taxonomy.slug] && formData[taxonomy.field_group][taxonomy.slug].includes(term.slug)}
                                />
                              </div>
                            );
                          })
                          }
                        </div>
                      </div>
                    </Fragment>}

                </div>
              );
            })
          ))}
      </div>,
    ];
  }
}

function mapStateToProps(state) {
  const {
    fields,
    formErrors,
    formData,
    costs,
    customFields,
    taxonomies,
    terms
  }

    = state;
  return {
    fields,
    formErrors,
    formData,
    costs,
    customFields,
    taxonomies,
    terms,
  };
}

export default connect(mapStateToProps)(CustomFields);
