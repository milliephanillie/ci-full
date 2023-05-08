/* global lc_data, React */
/**
 * dependencies.
 */
import { Component, Fragment, createRef } from '@wordpress/element';
import * as actions from '../../../dashboard/packages/store/actions';
import { connect } from 'react-redux';
import apiFetch from '@wordpress/api-fetch';
import { Wizard, Steps, Step } from 'react-albus';
import { map, isEmpty, isString, join } from 'lodash';
import validation from '../../utils/form-validation';
import Input from '../form-fields/Input';
import Description from '../form-fields/Description';
import Costs from '../form-fields/Costs';
import Date from '../form-fields/Date';
import Checkbox from '../form-fields/Checkbox';
import RichTextField from '../form-fields/RichText';
import CustomFields from '../form-fields/CustomFields';
import Media from '../form-fields/Media';
import SelectField from '../form-fields/select';
import Complex from '../form-fields/complex';
import jsonForm from '../../utils/build-form-data';
import Location from '../form-fields/Location';
import PaymentPackage from '../form-fields/payment-packages';
import ChoosePackage from '../form-fields/payment-packages/choosepackage';
import axios from 'axios';
import { NavLink } from 'react-router-dom';
import ReactSVG from 'react-svg';
import ActivePackage from '../../../dashboard/packages/components/content/pages/product/widgets/ActivePackage';
import cx from 'classnames';
import ReplyIcon from '../../../../images/icons/reply.svg';
import ArrowLeftIcon from '../../../../images/icons/arrow-left.svg';
import ArrowRightIcon from '../../../../images/icons/arrow-right.svg';
import LoaderSteps from '../../../theme/packages/components/loaders/LoaderSteps';
import ModalDemo from '../../../theme/packages/components/modal/ModalDemo';
import CostsAdditional from '../form-fields/CostsAdditional';
import QRForm from '../form-fields/QRForm';
import MediaSingleImage from '../form-fields/MediaSingleImage';
import { addProps } from '../../../theme/vendor/functions';
import Gallery from '../form-fields/Gallery';
import Packages from "../../../dashboard/packages/components/content/packages/Packages";

class FormSubmit extends Component {
  constructor(props) {
    super(props);
    this.state = {
      waiting: true,
      loading: true,
      formFields: {},
      fieldGroups: {},
      fieldsCommon: {},
      data: {},
      notice: '',
      errors: {},
      payment_package: false,
      doneSteps: [],
      productEditInfo: false,
      cf_groups: {},
      modalOpen: false,
      stepTitles: {},
    };

    this.handleSubmit = this.handleSubmit.bind(this);
    this.handleChange = this.handleChange.bind(this);
    this.selectFields = [];
  }

  componentWillMount() {
    this.getPackages();
    this.fetchGroups();
  }

  /**
   * Before a component is mounted
   * -----------------------------
   */
  componentDidMount() {
    const { dispatch } = this.props;
    // clear data before populating.
    dispatch(actions.updateFormData({}));
    //this.getPackage();
    this.getProductEditInfo();
  }

  handleFormSubmit = (e, next, step, steps, group) => {
    e.preventDefault();
    if (steps.indexOf(step) < steps.length - 1) {
      this.handleStepsNext(e, next, step, steps, group);
    } else {
      this.handleFormSubmit(e, next, step, steps, group);
    }
    return false;
  };

  getProductInfo = () => {
    this.setState({ loading: true });
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const url = lc_data.product_data;
    let data = {
      id: this.props.match.params.id,
      user_id: lc_data.current_user_id,
    };
    return axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url,
      data,
    });
  };

  getProductEditInfo = () => {
    if (!this.props.edit) {
      this.setState({ loading: false });
      return false;
    }
    const { dispatch } = this.props;
    const response = this.getProductInfo();
    response.then(data => {
      if (data.data) {
        this.setState({ payment_package: data.data.payment_package });
        this.setState({ productEditInfo: data.data.product });
        dispatch(actions.setupPackage(data.data));
      }
      this.setState({ loading: false });
      this.fetchFormFields();
    });
  };

  getPackages = () => {
    if (this.props.edit) {
      this.setState({ loading: false });
      return false;
    }

    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };

    const url = lc_data.packages + '/payment_package';

    const response = axios({
      credentials: 'same-origin',
      headers,
      method: 'get',
      url,
    });

    response.then(data => {
      if(data.data) {
        console.log(data);
        this.setState({packages: data.data})
        this.setState({ loading: false });
        this.setState({ waiting: false });
      }
      this.fetchFormFields();
    });
  }

  getPackage = () => {
    if (this.props.edit) {
      this.setState({ loading: false });
      return false;
    }
    const { dispatch } = this.props;
    const response = this.checkPackage();
    response.then(data => {
      if (data.data) {
        this.setState({ payment_package: data.data });
        dispatch(actions.setupPackage(data.data));
        if (data.data?.limit_reached) {
          const costs = {};
          costs.total = {};
          costs.total.commission = parseFloat(data.data?.commission.price);
          dispatch(actions.updateCosts(costs));
        }
      }
      this.setState({ loading: false });
      this.fetchFormFields();
    });
  };

  checkPackage = async () => {
    this.setState({ loading: true });
    const headers = {
      'X-WP-Nonce': lc_data.nonce,
    };
    const url = lc_data.package_and_promotions;
    let data = {
      id: this.props.match.params.package,
      user_id: lc_data.current_user_id,
    };
    return axios({
      credentials: 'same-origin',
      headers,
      method: 'post',
      url,
      data,
    });
  };

  /**
   * Prepare form fields for submitting
   * ----------------------------------
   *
   * @param formFields
   */
  populateFormFields(formFields) {
    const { formData, dispatch } = this.props;
    const { productEditInfo } = this.state;
    let data = this.props.formData;
    const fields = [];
    map(formFields, (allFields, group) => {
      map(formFields[group], (field, name) => {
        if (field.type === 'location') {
          fields[name] = {};
        } else if (field.type === 'checkbox') {
          fields[name] = false;
        } else if (field.type === 'select') {
          fields[name] = Object.keys(field.options)[0];
        } else if (field.type === 'date') {
          if (field.options.defaultDate) {
            fields[name] = field.options.defaultDate;
          }
        } else {
          fields[name] = field.value;
        }
      });
    });
    data = { ...data, ...fields };

    // if is product edit.
    if (this.props.edit) {
      data = { ...data, ...productEditInfo };
    }

    this.setState({ data });
    dispatch(actions.updateFormData(data));
  }

  /**
   * Prepare fields groups for iterating
   * -----------------------------------
   *
   * @param formFields
   */
  setFieldGroups(formFields) {
    const groups = [];
    map(formFields, (fields, name) => {
      groups.push(name);
    });
    if (this.props.edit && this.props.options.promotions) {
      groups.splice(-1, 1);
    }
    this.setState({ fieldGroups: groups });
  }

  /**
   * Fetch necessary form fields
   * ---------------------------
   */
  fetchFormFields() {
    const { dispatch } = this.props;
    const { productEditInfo } = this.state;
    return new Promise((resolve, reject) => {
      const form = document.getElementById('form-fields');
      const url = form ? lc_data[form.value] : lc_data.product_fields;
      const request = apiFetch({ path: url });

      request.then(response => {
        resolve(response);
        dispatch(actions.setupSubmitFields(response.fields));
        this.setState({ formFields: response.fields });
        const groups = {};
        map(response.fields, (field, name) => {
          groups[name] = field;
          if (name === 'payments' && this.state.payment_package?.free_promotions?.length >= this.props.business?.promotion_products?.length) {
            delete groups[name];
          }
        });
        this.setFieldGroups(groups);

        this.populateFormFields(response.fields);
        this.setState({ stepTitles: response.titles });
        this.setState({ loading: false });
        this.setState({ waiting: false });
      });
    });
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
    const data = this.props.formData;
    let elValue = isString(e) ? e : e.target.value;
    if (type === 'checkbox' || type === 'radio') {
      elValue = e.target.checked;
    }
    this.setState(prevState => ({
      data: {
        ...prevState.data,
        [name]: elValue,
      }
    }));

    if (name === '_price') {
      let percentage = parseFloat(this.state.payment_package?.submit_percentage) || false;
      if (percentage) {
        if (!data.costs) {
          data.costs = {};
        }
        let price = (parseInt(percentage, 10) / 100) * parseFloat(elValue);
        addProps(data.costs, ['total', 'additional'], price);
        data['commission_id'] = this.state.payment_package?.commission?.product || 0;
        data['submission_commission'] = price;
        data['toPay'] = true;
      }
    }

    data[name] = elValue;
    dispatch(actions.updateFormData(data));
  };

  /**
   * Fetch Custom Fields Taxonomies
   * ------------------------------
   */
  fetchGroups() {
    const { dispatch } = this.props;
    let groups = lc_data.get_groups;

    const filteredGroups = map(groups, group => group.slug);
    let grouped = join(filteredGroups, ',');
    if (!isEmpty(groups)) {
      grouped += ',common';
    } else {
      grouped += 'common';
    }

    if (this?.props?.options?.empty_category) {
      let emptyFirstCategory = groups.unshift({ slug: '', single_name: lc_data.jst[333] });
      this.setState({ cf_groups: emptyFirstCategory });
    }
    this.setState({ cf_groups: groups });

    axios({ url: lc_data.taxonomy_group_options, params: { group: grouped } }).then((fields) => {
      dispatch(actions.updateFormCF(fields.data));
      this.populateFormData();
      this.populateCfData();
    });
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

    if (!isEmpty(fieldsByGroup[data['cf_category']])) {
      taxonomies[data['cf_category']] = fieldsByGroup[data['cf_category']];
    }
    if (!isEmpty(fieldsByGroup['common'])) {
      taxonomies['common'] = fieldsByGroup['common'];
    }
    this.setState({ cf_taxonomies: taxonomies });
    map(fieldsByGroup, (tax, group) => taxonomies[group] = tax);
    dispatch(actions.updateFormTaxonomies(taxonomies));

    // populate terms
    let terms = [];
    const locations = [];
    if (!isEmpty(taxonomies)) {
      map(taxonomies, (taxonomy, group) => map(taxonomy, field => terms.push(field.slug)));
      this.setState({ loading: true });
      apiFetch({ path: `${lc_data.terms_get}/all` })
        .then(terms => {
          dispatch(actions.updateFormTerms(terms));
          this.setState({ loading: false });
        });
    }
  }

  /**
   * Populate form data with basic information
   * -----------------------------------------
   *
   */
  populateFormData() {
    const { dispatch } = this.props;
    let data = this.props.formData;
    const { fieldsByGroup, groups } = this.state;

    let fields = [];
    let newFields = [];
    const defaultGroup = !isEmpty(this.state.cf_groups) ? this.state.cf_groups[0].slug : 'common';
    fields['cf_category'] = data['cf_category'] || defaultGroup;
    data = { ...data, ...fields };
    map(this.props.customFields, (group, name) => (
      map(group, fields => {
        if (!data[name]) {
          data[name] = {};
        }
        if (!data[name] || !data[name][fields.slug]) {
          if (!data[name]) {
            data[name] = {};
            data[name][fields.slug] = '';
          } else {
            data[name][fields.slug] = '';
          }
        }
      })
    ));
    dispatch(actions.updateFormData(data));
  }

  handleImageChange = (image, name) => {
    const { dispatch } = this.props;
    let data = this.props.formData;
    if (image) {
      data[name] = image?.id;
      data[`${name}_url`] = image?.url;
      dispatch(actions.updateFormData(data));
    }
  };

  /**
   * Display the field based on type
   * -------------------------------
   *
   * @param field
   * @param name
   * @returns {*}
   */
  showField = (field, name, group) => {

    console.log("amazon payment package")
    console.log(field)
    console.log(group)
    console.log(name)
    const { errors } = this.state;
    const data = this.props.formData;
    let display = true;
    if (field.conditional) {
      if (isString[data[field.conditional[1]]]) {
        display = field.conditional[1] === data[field.conditional[0]];
      } else {
        display = undefined !== data[field.conditional[0]] ? field.conditional[1].includes(data[field.conditional[0]]) : true;
      }
    }
    console.log("we in the packages")
    console.log(this.state.packages);

    const allpacks = this.state.packages.map((item) => {
      console.log("we in the items")
      console.log(item.post_title)
    })
    switch (field.type) {
      case 'packages' :
        return <ChoosePackage
            key={field.key}
            field={field}
            name={name}
            id={name}
            packages={this.state.packages}
            value={isEmpty(data[name]) ? [] : data[name]}
        />;
      case 'promotions':
        return <PaymentPackage
          key={field.key}
          field={field}
          name={name}
          id={name}
          package={this.state.payment_package}
          value={isEmpty(data[name]) ? [] : data[name]}
        />;
      case 'image':
        return <Gallery
          display={display}
          key={field.key}
          allData={data}
          field={field}
          name={name}
          id={name}
          value={isEmpty(data[name]) ? [] : data[name]}
          label={field.label}
          description={field.description}
          payment_package={this.state.payment_package}
          isEdit={this.props.edit}
          error={errors[name]}
        />;
      case 'media':
        return <Media
          key={field.key}
          allData={data}
          field={field}
          field_type={field.field_type}
          name={name}
          id={name}
          value={isEmpty(data[name]) ? [] : data[name]}
          label={field.label}
          description={field.description}
          mediaLibraryTitle={lc_data.jst[69]}
          mediaLibraryButtonLabel={field?.props?.media_button_label}
          payment_package={this.state.payment_package}
          isEdit={this.props.edit}
          display={display}
          error={errors[name]}
        />;
      case 'single_image':
        return <MediaSingleImage
          key={field.key}
          allData={data}
          field={field}
          field_type={field.field_type}
          name={name}
          id={name}
          value={isEmpty(data[name]) ? [] : data[name]}
          mediaLibraryTitle={lc_data.jst[69]}
          mediaLibraryButtonLabel={field?.props?.media_button_label}
          label={field.label}
          description={field.description}
          display={display}
          onChange={(image, name) => this.handleImageChange(image, name)}
        />;
      case 'qr':
        return <QRForm
          key={field.key}
          allData={field}
          name={name}
          id={name}
          value={isEmpty(data[name]) ? [] : data[name]}
          label={field.label}
          isEdit={this.props.edit}
          error={errors[name]}
        />;
      case 'taxonomies':
        return !isEmpty(this.props.terms) &&
          <CustomFields
            key={field.key}
            group={group}
            data={data}
            edit={this.props.edit}
            groups={this.state.cf_groups}
            loading={this.state.loading}
            options={this.props.options}
            error={errors}
            paymentPackage={this.state.payment_package}
          />;
      case 'rich-text':
        return <RichTextField
          key={field.key}
          id={name}
          name={name}
          field={field.options}
          visible
          handleChange={(e) => {
            this.handleChange(e, name, field.type);
          }}
          value={data[name]}
          attributes={field.attributes}
          label={field.label}
          description={field.description}
          error={errors}
          additional={field.additonal}
        />;
      case 'checkbox':
        return <Checkbox
          display={display}
          key={field.key}
          id={name}
          name={name}
          label={field.label}
          handleChange={(e) => this.handleChange(e, name, field.type)}
          value={data[name]}
          checked={field?.force_value ? true : data[name]}
          description={field.description}
          attributes={field.attributes}
          additional={field.additional}
          error={errors}
        />;
      case 'select':
        return <SelectField
          display={display}
          key={field.key}
          id={name}
          label={field.label}
          handleChange={(e) => this.handleChange(e, name, field.type)}
          value={data[name] ? data[name] : field.options[0]}
          options={field.options}
          attributes={field.attributes}
          description={field.description}
          multiselect={field.multiselect || false}
          error={errors}
          additional={field.additional}
        />;
      case 'complex':
        return <Complex
          display={display}
          key={field.key}
          id={name}
          name={name}
          label={field.label}
          value={data[name]}
          fields={field.fields}
          attributes={field.attributes}
          description={field.description}
          error={errors}
        />;
      case 'location':
        return <Location
          display={display}
          key={field.key}
          id={name}
          name={name}
          label={field.label}
          value={data[name]}
          error={errors}
          fields={field.fields}
          props={field.props}
          field={field}
          edit={this.props.edit}
        />;
      case 'date':
        return <Date
          display={display}
          key={field.key}
          id={name}
          name={name}
          label={field.label}
          payment_package={this.state.payment_package}
          handleChange={e => this.handleChange(e, name, field.type)}
          value={data[name]}
          attributes={field.attributes}
          description={field.description}
          error={errors}
          options={field.options}
          additional={field.additional}
        />;
      case 'costs':
        return <Costs
          display={display}
          key={field.key}
          type={field.field_type}
          name={name}
          label={field.label}
          payment_package={this.state.payment_package}
          costs={this.props.costs}
          calculation={field.calculation}
        />;
      case 'costs_additional':
        return <CostsAdditional
          display={this.props.edit}
          key={field.key}
          type={field.field_type}
          name={name}
          label={field.label}
          payment_package={this.state.payment_package}
          costs={this.props.costs}
          calculation={field.calculation}
        />;
      case 'description':
        return <Description
          display={display}
          key={field.key}
          field={field}
          additional={field?.additional || false}
        />;
      default:
        return <Input
          display={display}
          key={field.key}
          id={name}
          name={name}
          label={field.label}
          handleChange={e => this.handleChange(e, name, field.type)}
          value={data[name]}
          attributes={field.attributes}
          description={field.description}
          error={errors}
          additional={field.additional}
        />;
    }
  };

  /**
   * Handle Product Submission
   * -------------------------
   *
   * @param e
   * @param step
   */
  handleSubmit(e, step) {
    e.preventDefault();
    if (lc_data.is_demo) {
      this.setState({ modalOpen: true });
      return false;
    }
    const { dispatch, costs } = this.props;
    const { payment_package } = this.state;
    const finalCost = costs.final || 0;
    const package_id = this.state.payment_package.id;
    // validate form
    const errors = validation(step, this.props, dispatch);
    this.setState({ errors });

    if (!isEmpty(errors)) {
      this.setState({ errors });
      dispatch(actions.updateFormErrors(errors));
      return false;
    }

    // submit form
    const data = this.props.formData;
    if (package_id) {
      data['package'] = package_id;
      if (payment_package?.is_subscription) {
        data['is_subscription'] = payment_package?.is_subscription;
      }
    }
    this.setState({ loading: true });

    if (!isEmpty(payment_package?.categories) && payment_package?.commission?.product) {
      data['additional_payment'] = true;
    }

    const headers = new Headers();
    const formData = jsonForm(data);
    let url = lc_data.product_submit;
    headers.append('X-WP-Nonce', lc_data.nonce);

    formData.append('business', this.props.business.business.ID);

    if (payment_package?.commission?.product && payment_package?.pay_commission) {
      formData.append('pay_commission', true);
      formData.append('commission_id', payment_package.commission.product);
      formData.append('commission_price', payment_package.commission.price);
      formData.append('toPay', true);
    }
    if (this.props.edit) {
      formData.append('action', 'edit');
      formData.append('id', this.props.match.params.id);
    }

    if (finalCost > 0) {
      formData.append('toPay', true);
    }

    fetch(url, {
      method: 'POST',
      credentials: 'same-origin',
      headers,
      body: formData,
    }).then(response => response.json().then(json => {
      this.setState({ loading: false });

      if (json.success) {
        this.props.info.refresh_products = true;
        dispatch(actions.setInfo(this.props.info));
        if (json.permalink) {
          window.location = json.permalink;
        }
        this.setState({ notice: json.message });
      }
    }));
  }

  /**
   * Go to the next step
   * -------------------
   *
   * @param e
   * @param next
   * @param step
   * @param steps
   * @param group
   */
  handleStepsNext = (e, next, step, steps, group) => {
    const { doneSteps, fieldGroups, formErrors, payment_package } = this.state;
    const { dispatch } = this.props;
    const data = this.props.formData;
    const errors = validation(step, this.props, dispatch);
    this.setState({ errors });

    let proceed = true;
    let categoryIndex = 999;
    if (step.id === 'details') {
      if (payment_package?.is_exclusive) {
        !isEmpty(payment_package?.categories) && map(payment_package.categories, (cat, index) => {
          if (data['cf_category'] === cat?.category) {
            categoryIndex = index;
          }
        });
        if (isEmpty(payment_package?.categories[categoryIndex])) {
          proceed = false;
        }
      }

      if (!proceed) {
        return;
      }
      this.calculateTotal();
    }

    if (isEmpty(errors)) {
      const nextGroup = fieldGroups[steps.indexOf(step) + 1];
      if (!doneSteps.includes(nextGroup) && proceed) {
        doneSteps.push(group);
        doneSteps.push(nextGroup);
      }
      next();
    } else {
      this.setState({ errors });
      dispatch(actions.updateFormErrors(errors));
    }
  };

  calculateTotal = () => {
    const { dispatch } = this.props;
    const payment_package = this.state.payment_package;
    const data = this.props.formData;

    let categoryIndex = 999;
    !isEmpty(payment_package?.categories) && map(payment_package.categories, (cat, index) => {
      if (data['cf_category'] === cat?.category) {
        categoryIndex = index;
      }
    });

    let price = parseFloat(payment_package?.categories?.[categoryIndex]?.price) || 0;
    if (!data.costs) {
      data.costs = {};
    }
    addProps(data.costs, ['total', 'additional'], price);

    if (data.costs && data.costs.total) {
      let final = (data.costs['total']['media'] || 0) + (data.costs['total']['promo']) || 0 + (data.costs['total']['commission']) || 0;
      if (data?.costs['total']?.['additional']) {
        final += data.costs['total']['additional'];
      }
      data.costs['final'] = final;
      dispatch(actions.updateCosts(data.costs));
    }
  };

  /**
   * Renders the component.
   *
   * @return {Object}
   */
  render() {
    const {
      waiting,
      loading,
      fieldGroups,
      notice,
      packages,
      payment_package,
      doneSteps,
      productEditInfo,
      product,
      stepTitles,
    } = this.state;
    const { fields, formErrors, formData, costs } = this.props;
    let finalCost = costs.final || 0;

    !isEmpty(payment_package?.categories) && map(payment_package.categories, cat => {
      if (formData?.cf_category === cat?.category) {
        finalCost += parseFloat(cat.price) || finalCost;
      }
    });

    if (costs?.total?.commission) {
      finalCost += costs.total.commission;
    }

    return (
      <Fragment>
        {
          (waiting || loading) &&
          <div key={0} className="relative w-full xl:w-11/16">
            <LoaderSteps/>
          </div>
        }

        {/*{!waiting && !loading && isEmpty(notice) && !this.props.edit &&*/}
        {/*  <div key={1} className="p-30 bg-white rounded shadow-theme">*/}
        {/*    <p*/}
        {/*      className="font-bold text-xl">{lc_data.jst[317]}</p>*/}
        {/*    <NavLink to={`${lc_data.site_url}${lc_data.myaccount}packages/`}*/}
        {/*             className="flex justify-between items-center mt-20 px-20 h-60 bg-orange-600 rounded shadow-theme font-bold text-white hover:bg-orange-700"*/}
        {/*             style={{ width: '225px' }}*/}
        {/*    >*/}
        {/*      <div className="flex flex-col text-left">*/}
        {/*        <span className="text-sm">{lc_data.jst[318]}</span>*/}
        {/*        <span className="font-bold text-xl">{lc_data.jst[319]}</span>*/}
        {/*      </div>*/}
        {/*      <ReactSVG*/}
        {/*        src={`${lc_data.dir}dist/${ReplyIcon}`}*/}
        {/*        className="w-20 h-20 fill-white"*/}
        {/*      />*/}
        {/*    </NavLink>*/}
        {/*  </div>*/}
        {/*}*/}

        { console.log (fieldGroups)}

        {!loading && !isEmpty(fieldGroups) &&
          <div key={1} className="form--wrapper flex flex-wrap flex-wrapper">

            <div className="w-full xl:w-11/16">
              <Wizard render={({ next, previous, step, steps, push, wizard }) => {
                const stepIndex = steps.indexOf(step);
                let percentage = (stepIndex + 1) / steps.length * 100;
                percentage = isEmpty(notice) ? percentage : 100;
                return (
                  <div className="flex flex-wrap">

                    {isEmpty(notice) &&
                      <div className="w-full bg:w-1/4">
                        <nav className="flex flex-col mr-1 bg-grey-200 rounded-l">
                          <ul className="py-30">
                            {map(fieldGroups, (group, index) => {
                              const stepClass = cx({
                                'bg-green-600 border border-green-600 text-white': stepIndex === index || stepIndex > index,
                                'bg-green-300 border border-green-300 text-grey-900': stepIndex + 1 === index,
                                'border border-grey-500 text-grey-900': stepIndex + 1 !== index && stepIndex !== index && stepIndex < index,
                              });
                              const stepBtnClass = cx({
                                'bg-white hover:bg-white': stepIndex === index,
                                'bg-transparent hover:bg-grey-300': stepIndex !== index,
                              });
                              return (
                                <li key={index} className="flex w-full my-10">
                                  <button
                                    className={`flex items-center py-10 px-30 w-full ${stepBtnClass}`}
                                    onClick={() => {
                                      if (doneSteps.includes(group) || this.props.edit) {
                                        let categoryIndex = 999;
                                        if (this.state.payment_package?.is_exclusive) {
                                          !isEmpty(this.state.payment_package?.categories) && map(this.state.payment_package.categories, (cat, index) => {
                                            if (formData['cf_category'] === cat?.category) {
                                              categoryIndex = index;
                                            }
                                          });
                                          if (isEmpty(this.state.payment_package?.categories[categoryIndex])) {
                                            return false;
                                          } else {
                                            push(group);
                                          }
                                        } else {
                                          push(group);
                                        }
                                      }
                                    }}
                                  >
                              <span
                                className={`flex-center mr-20 w-30 h-30 rounded-full ${stepClass}`}>{index + 1}</span>
                                    <span
                                      className="capitalize font-semibold text-lg text-grey-900">{stepTitles[group] || group}</span>
                                  </button>
                                </li>
                              );
                            })}
                          </ul>
                        </nav>
                      </div>}

                    <div className="relative w-full bg:w-3/4 py-40 px-20 sm:px-60 bg-white rounded shadow-theme">

                      <div className="timeline absolute top-0 left-0 w-full h-6 bg-grey-200 rounded-t overflow-hidden">
                        <div
                          className="timeline--line absolute top-0 left-0 h-6 bg-green-600"
                          style={{ width: `${percentage}%` }}
                        ></div>
                      </div>

                      {isEmpty(notice) &&
                        <div className="flex">
                          <span className="text-grey-400">{lc_data.jst[320]}</span>
                          <span className="mx-4 font-semibold text-grey-900">{stepIndex + 1}</span>
                          <span className="text-grey-400">{lc_data.jst[321]}</span>
                          <span className="mx-4 font-semibold text-grey-900">{steps.length}</span>
                        </div>}

                      <Steps key={step.id} step={step.id ? step : undefined}>
                        {map(fieldGroups, (group, index) => {
                          return (
                            <Step key={step.id} id={group}>
                              {isEmpty(notice) &&
                                <form className="form--product flex flex-wrap py-30"
                                      onChange={() => this.setState({ errors: {} })}
                                      onSubmit={(e) => this.handleFormSubmit(e, next, step, steps, group)}
                                >
                                  <h3
                                    className="flex w-full mb-20 capitalize font-bold text-grey-1000">{stepTitles[group] || group}</h3>

                                  { console.log("fields")}{console.log(fields)}{console.log(fields[group])}


                                  <div className="steps--wrapper flex flex-wrap w-full">
                                    {!isEmpty(this.props.formData) && map(fields[group], (field, name) => (
                                      this.showField(field, name, group)
                                    ))}
                                  </div>

                                  <div
                                    className={`steps--actions flex justify-between mt-0 sm:mt-60 w-full ${finalCost > 0 ? 'flex-wrap sm:flex-no-wrap' : ''}`}>
                                    {steps.indexOf(step) === 0 && steps.indexOf(step) === steps.length - 1 && (
                                      <button type="submit" id="btn-submit"
                                              className="btn-fluid btn-secondary btn-submit"
                                              onClick={e => this.handleSubmit(e, step)}>
                                        {lc_data.jst[105]}
                                      </button>
                                    )}
                                    {steps.indexOf(step) > 0 && steps.indexOf(step) !== (steps.length - 1) && (
                                      <button type="button"
                                              className="flex justify-between items-center px-24 py-16 bg-green-700 shadow-md rounded font-bold text-white hover:bg-green-800"
                                              onClick={previous}
                                      >
                                        <ReactSVG
                                          src={`${lc_data.dir}dist/${ArrowLeftIcon}`}
                                          className="mr-20 w-16 h-16 fill-white"
                                        />
                                        <span>{lc_data.jst[324]}</span>
                                      </button>
                                    )}
                                    {steps.indexOf(step) > 0 && steps.indexOf(step) === (steps.length - 1) && (
                                      <Fragment>
                                        <button type="button"
                                                className="flex justify-between items-center px-24 py-16 bg-green-700 shadow-md rounded font-bold text-white hover:bg-green-800"
                                                onClick={previous}
                                        >
                                          <ReactSVG
                                            src={`${lc_data.dir}dist/${ArrowLeftIcon}`}
                                            className="mr-20 w-16 h-16 fill-white"
                                          />
                                          <span>{lc_data.jst[324]}</span>
                                        </button>
                                        <button
                                          type="button"
                                          className={`flex justify-between items-center px-24 py-16 bg-blue-700 shadow-md rounded font-bold text-white hover:bg-blue-800 ${finalCost > 0 ? 'mt-10 sm:mt-0' : ''}`}
                                          onClick={e => this.handleSubmit(e, step)}>
                                          {finalCost > 0
                                            ?
                                            <span>{lc_data.jst[322]}</span>
                                            :
                                            <span>{this.props.edit ? lc_data.jst[323] : lc_data.jst[116]}</span>
                                          }
                                          <ReactSVG
                                            src={`${lc_data.dir}dist/${ArrowRightIcon}`}
                                            className="ml-20 w-16 h-16 fill-white"
                                          />
                                        </button>
                                      </Fragment>
                                    )}
                                    {steps.indexOf(step) < steps.length - 1 && (
                                      <Fragment>
                                        {0 === stepIndex && (
                                          <button
                                            type="button"
                                            className="flex justify-between items-center px-24 py-16 bg-grey-300 shadow-md rounded font-bold text-white cursor-default"
                                          >
                                            <ReactSVG
                                              src={`${lc_data.dir}dist/${ArrowLeftIcon}`}
                                              className="mr-20 w-16 h-16 fill-white"
                                            />
                                            <span>{lc_data.jst[324]}</span>
                                          </button>
                                        )}
                                        <button
                                          type="button"
                                          className="flex justify-between items-center px-24 py-16 bg-blue-700 shadow-md rounded font-bold text-white hover:bg-blue-800"
                                          onClick={e => this.handleStepsNext(e, next, step, steps, group)}>
                                          <span>{lc_data.jst[325]}</span>
                                          <ReactSVG
                                            src={`${lc_data.dir}dist/${ArrowRightIcon}`}
                                            className="ml-20 w-16 h-16 fill-white"
                                          />
                                        </button>
                                      </Fragment>
                                    )}
                                  </div>

                                </form>}
                              {notice &&
                                <div className="notice">
                                  <h3
                                    className="flex w-full mb-20 capitalize font-bold text-grey-1000">{lc_data.jst[326]}</h3>
                                  <span>{notice}</span>
                                </div>
                              }
                            </Step>
                          );
                        })}
                      </Steps>
                    </div>

                  </div>
                );
              }}
              />
            </div>

            <aside
              className="dashboard--widgets flex flex-row flex-wrap xl:flex-col justify-between xl:justify-start mt-30 xl:mt-0 ml-auto xl:-mb-20 xl:pl-col w-full xl:w-5/16">
              {!!this.props.options?.packages &&
                <ActivePackage product={payment_package} productId={this.props.match.params.package}/>}
            </aside>

          </div>
        }
        <ModalDemo
          isLogged={lc_data.logged_in}
          open={this.state.modalOpen}
          closeModal={() => this.setState({ modalOpen: false })}
          title={lc_data.jst[606]}
        >
          <div className="font-semibold text-lg text-grey-700" dangerouslySetInnerHTML={{
            __html: lc_data.jst[607],
          }}
          />
        </ModalDemo>
      </Fragment>
    );
  }
}

function mapStateToProps(state) {
  const {
    fields,
    formErrors,
    formData,
    costs,
    business,
    promotions,
    info,
    customFields,
    taxonomies,
    terms,
    paymentPackage
  } = state;
  return {
    fields,
    formErrors,
    formData,
    costs,
    business,
    promotions,
    info,
    customFields,
    taxonomies,
    terms,
    paymentPackage,
  };
}

export default connect(mapStateToProps)(FormSubmit);
