/* global lc_data */
/**
 * External dependencies.
 */
import * as actions from '../../../dashboard/packages/store/actions';
import cx from 'classnames';
import { Component, Fragment, createRef } from '@wordpress/element';
import { Map, TileLayer, Marker } from 'react-leaflet';
import { sprintf, __ } from '@wordpress/i18n';
import { map, filter, join, isEmpty } from 'lodash';
import ReactSVG from 'react-svg';
import QuestionIcon from '../../../../images/icons/question-circle.svg';
import { divIcon } from 'leaflet';
import ReactTooltip from 'react-tooltip';
import { connect } from 'react-redux';

class Location extends Component {
  constructor(props) {
    super(props);
    this.state = {
      value: '',
      data: {
        location: {}
      },
      taxonomies: {},
      terms: {},
      marker: {
        lat: this.props.props.latitude,
        lng: this.props.props.longitude,
      },
      map: {
        center: {
          lat: this.props.props.latitude,
          lng: this.props.props.longitude,
        },
        zoom: this.props.props.zoom,
      },
      draggable: true,
      map_tabs: {
        google: this.props.props.google,
        coords: this.props.props.coords,
      },
      address: this.props.props.address || '',
      active_tab: this.props.props.google ? 'google' : 'coords',
      google_predictions: [],
      active: false,
      googleFieldActive: false,
      latitudeFieldActive: false,
      longitudeFieldActive: false,
    };

    this.handleChange = this.handleChange.bind(this);
  }

  /**
   * Create reference to the DOM element
   * ------------------------------------
   *
   * @type {React.RefObject<any>}
   */
  marker = createRef();
  latitude = createRef();
  longitude = createRef();
  google = createRef();
  select = createRef();

  /**
   * Component has been mounted
   */
  componentDidMount() {
    const { dispatch, edit } = this.props;
    const data = this.props.formData;
    const address = this.google.current;
    const latitude = this.latitude.current;
    const longitude = this.longitude.current;
    let position;

    if (this.props.taxonomies.xloc) {
      this.setState({ taxonomies: this.props.taxonomies.xloc });
    }
    if (this.props.terms) {
      this.setState({ terms: this.props.terms });
    }
    if ((this.props.field && this.props.field.taxonomy) && !this.props.terms) {
      this.fetchLocations();
    }

    if (isEmpty(data[`location[${this.props.name}]`])) {
      data[`location[${this.props.name}]`] = {};
      data[`location[${this.props.name}]`].marker = {};
      data[`location[${this.props.name}]`].marker.lat = latitude.value;
      data[`location[${this.props.name}]`].marker.lng = longitude.value;
      this.state.map_tabs.google ? data[`location[${this.props.name}]`].address = address.value : '';
      data[`location[${this.props.name}]`].active_tab = this.state.active_tab;
      position = {
        lat: latitude.value ?? this.props.props.latitude,
        lng: longitude.value ?? this.props.props.longitude,
      };
      dispatch(actions.updateFormData(data));
    } else {
      position = {
        lat: this.props.props.latitude,
        lng: this.props.props.longitude,
      };
    }
    this.updateAddressInfo(position, this.state.map_tabs.google ? data[`location[${this.props.name}]`].address : false, false);
  }

  /**
   * Geocode the address from the give coordinates
   * ---------------------------------------------
   *
   * @param latLng
   */
  reverseGeocodeAddress(latLng) {
    const geocoder = new google.maps.Geocoder;
    geocoder.geocode({ 'location': latLng }, (results, status) => {
      if (status === 'OK') {
        if (results[0]) {
          this.updateAddressInfo(latLng, results[0].formatted_address, true);
        }
      }
      return false;
    });
  }

  /**
   * Update the location information
   * -------------------------------
   *
   * @param position
   * @param address
   * @param byDrag
   */
  updateAddressInfo(position, address = false, byDrag = false) {
    const { dispatch } = this.props;
    const data = this.props.formData;
    const latitude = position.lat;
    const longitude = position.lng;

    // if updated by dragging a marker.
    setTimeout(() => {
      this.setState(prevState => ({
        map: {
          center: {
            ...prevState,
            lat: latitude,
            lng: longitude,
          },
        },
        marker: {
          ...prevState,
          lat: latitude,
          lng: longitude,
        },
      }));
      if (address) {
        this.setState({ address });
      }
    }, 20);
    // update form data.
    data[`location[${this.props.name}]`].marker = {};
    data[`location[${this.props.name}]`].marker.lat = latitude;
    data[`location[${this.props.name}]`].marker.lng = longitude;
    if (address) {
      data[`location[${this.props.name}]`].address = address;
    } else {
      data[`location[${this.props.name}]`].address = this.state.address;
    }
    dispatch(actions.updateFormData(data));
  }

  /**
   * Set draggable state to false while dragging
   * -------------------------------------------
   */
  toggleDraggable = () => {
    this.setState({ draggable: !this.state.draggable });
  };

  updatePositionOnClick = (e) => {
    // disallowed at the moment.
    // This should update the address on user click on the map.
    return;
    const position = e.latlng;
    this.setState(prevState => ({
      map: {
        center: {
          ...prevState,
          lat: position.lat,
          lng: position.lng,
        },
      },
      marker: {
        ...prevState,
        lat: position.lat,
        lng: position.lng,
      },
    }));
  };

  /**
   * Update position on marker drag
   * ------------------------------
   */
  updatePosition = (e) => {
    const marker = this.marker.current;
    if (marker != null) {
      const latLng = marker.leafletElement.getLatLng();

      // reverse geocode address if google maps are enabled.
      if (this.state.map_tabs.google && this.state.active_tab === 'google') {
        this.reverseGeocodeAddress(e.target._latlng);
        // we need to wait to obtain result from the google.
      } else {
        // update the form data and the fields.
        this.updateAddressInfo(e.target._latlng, this.state.address, true);
      }

    }
  };

  /**
   * Update marker position when changing coordinates manually
   * ---------------------------------------------------------
   *
   * @param e
   */
  handleCoordsChange = (e) => {
    const data = this.props.formData;
    const latitude = this.latitude.current;
    const longitude = this.longitude.current;
    const position = { lat: latitude.value, lng: longitude.value };
    if (!isEmpty(e.target.value) && !isNaN(e.target.value) && e.target.value <= 90 && e.target.value >= -90) {
      this.updateAddressInfo(position);
    } else {
      alert(sprintf(`${lc_data.jst[336]}${lc_data.jst[690]}`, e.target.name));
    }

    this.setState({ draggable: true });
  };

  /**
   * Update the address value
   * ------------------------
   *
   * @param e
   * @param id
   */
  handleGoogleLocation = (e, id) => {
    const { dispatch } = this.props;
    const data = this.props.formData;
    this.setState({ address: address.value });

    data[`location[${this.props.name}]`]['address'] = address.value;
    dispatch(actions.updateFormData(data));
  };

  /**
   * Handle Location change when using Google autocomplete
   * -----------------------------------------------------
   */
  handleAddressChange = (e) => {
    this.setState({ address: e.target.value });
    this.locationAutocomplete();
  };

  /**
   * Google Location Autocomplete
   * ----------------------------
   */
  locationAutocomplete = () => {
    const { dispatch } = this.props;
    const data = this.props.formData;
    const input = this.google.current;
    let latitude = '';
    let longitude = '';

    const options = {};
    if (lc_data.country_restriction) {
      options.componentRestrictions = { country: lc_data.country_restriction };
    }
    const searchBox = new google.maps.places.Autocomplete(input, options);

    const self = this;
    searchBox.addListener('place_changed', function () {
      const place = searchBox.getPlace();

      if (place.length === 0) {
        return;
      }

      latitude = place.geometry.location.lat();
      longitude = place.geometry.location.lng();
      const position = {
        lat: latitude,
        lng: longitude,
      };
      self.updateAddressInfo(position, place.formatted_address, false);
    });
  };

  /**
   * Fetch location taxonomies
   * -------------------------
   */
  fetchLocations() {
    const { fields } = this.props;
    fetch(lc_data.taxonomy_location_options)
      .then(json => json.json()).then(taxonomies => {
      this.setState({ taxonomies });
      this.fetchTerms(taxonomies);
    });
  }

  /**
   * Fetch location terms
   * --------------------
   *
   * @param taxonomies
   */
  fetchTerms(taxonomies) {
    const terms = [];
    map(taxonomies, taxonomy => {
      if (!terms.includes(taxonomy.slug)) {
        terms.push(taxonomy.slug);
      }
    });
    const termsUrl = join(terms, ',');
    fetch(`${lc_data.terms_get}/${termsUrl}`)
      .then(json => json.json())
      .then(terms => this.setState({ terms }));
  }

  /**
   * Show location fields
   * --------------------
   *
   * @param taxonomy
   * @param index
   * @param group
   * @returns {*}
   */
  showField(taxonomy, index, group) {
    const { description, error } = this.props;
    const { taxonomies, terms } = this.state;
    const { field, name } = this.props;
    const data = this.props.formData;
    const chosenValue = !isEmpty(data[taxonomy.field_group]) ? data[taxonomy.field_group][taxonomy.slug] : '';
    const parentValue = !isEmpty(data[taxonomy.field_group]) ? data[taxonomy.field_group][taxonomy.parent] : '';
    let choices = [];
    if (isEmpty(taxonomy.parent) && isEmpty(parentValue)) {
      choices = filter(this.props.terms, { taxonomy: taxonomy.slug });
    } else {
      choices = filter(this.props.terms, {
        taxonomy: taxonomy.slug,
        meta: { parent_slug: parentValue || [parentValue] }
      });
    }
    return (
      !isEmpty(choices) &&
      <div key={index}>
        <div className="field--top flex justify-between">
          <label htmlFor={name} className="field--label flex items-center mb-6 text-sm text-grey-500">
            {taxonomy.single_name}
            {description &&
            <ReactSVG src={`${lc_data.dir}dist/${QuestionIcon}`} data-tip={description}
                      className="relative left-6 w-14 h-14 fill-blue-700" style={{ top: '-8px' }}/>
            }
          </label>
          {error && error[taxonomy.slug] &&
          <div className="field--error text-sm text-red-700 w-2/3 text-right">{error[taxonomy.slug]}</div>}
        </div>
        <select
          className={`lisfinity-field flex mb-20 p-10 px-20 h-44 w-full border border-grey-300 rounded font-semibold cursor-pointer outline-none ${this.state.active ? 'bg-transparent' : 'bg-grey-100'}`}
          key={index}
          id={name}
          value={chosenValue || ''}
          onChange={(e) => this.handleChange(e.target.value, taxonomy.slug, taxonomy.field_group)}
          onFocus={() => this.setState({ active: true })}
          onBlur={() => this.setState({ active: false })}
        >
          <option key={10000} value="">{sprintf(lc_data.jst[337], taxonomy.single_name)}</option>
          {map(choices, (term, index) => {
            return <option key={term.term_id} value={term.slug}>{term.name}</option>;
          })}
        </select>
      </div>
    );
  }

  /**
   * Handle the change of location
   * -----------------------------
   *
   * @param value
   * @param taxonomy
   * @param group
   */
  handleChange(value, taxonomy, group) {
    const { dispatch } = this.props;
    const data = this.props.formData;
    if (undefined === data[group]) {
      data[group] = {};
    }
    data[group][taxonomy] = value;
    dispatch(actions.updateFormData(data));
    this.setState(prevState => ({
      data: {
        ...prevState.data,
        [group.taxonomy]: value,
      }
    }));
  }

  /**
   * Handle active tabs switching
   * ----------------------------
   *
   * @param e
   * @param active_tab
   */
  handleTabs = (e, active_tab) => {
    const data = this.props.formData;
    data[`location[${this.props.name}]`].active_tab = active_tab;
    this.setState({ active_tab });
  };

  /**
   * Render the component.
   *
   * @return {Object}
   */
  render() {
    const {
      display,
      type,
      id,
      name,
      value,
      description,
      label,
    } = this.props;
    const data = this.props.formData;
    const { address } = this.state;
    const { taxonomies } = this.state;
    const position = [parseFloat(this.state.map.center.lat), parseFloat(this.state.map.center.lng)];
    const markerPosition = [parseFloat(this.state.marker.lat), parseFloat(this.state.marker.lng)];
    const mapUrl = lc_data.mapbox_url !== '' ? lc_data.mapbox_url : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    const marker = divIcon({
      className: 'lisfinity-divicon',
      html: `<div class="marker--red flex-center h-24 w-24 bg-red-600 rounded-full"></div><span class="marker--triangle__red cluster--triangle"></span>`,
    });

    return (
      display &&
      <div className="flex flex-col w-full mb-40">
        <div className="form-field">
          {taxonomies && map(taxonomies, (taxonomy, index) => this.showField(taxonomy, index))}
        </div>
        <div className="map">
          <div className="map-tabs">
            {this.state.map_tabs.google && this.state.map_tabs.coords &&
            <div className="mt-20 mb-40">
              <div className="field--label relative flex items-center mb-6 text-sm text-grey-500">
                {lc_data.jst[338]}
                <ReactSVG src={`${lc_data.dir}dist/${QuestionIcon}`}
                          data-tip={lc_data.jst[339]}
                          className="relative left-6 w-14 h-14 fill-blue-700" style={{ top: '-8px' }}/>
                <ReactTooltip/>
              </div>
              <div className="map-tabs--nav flex items-center">
                <button
                  type="button"
                  className={`map-tabs--nav__google flex-center py-10 w-1/2 sm:w-1/3 border border-grey-300 rounded-l font-bold text-grey-700 ${this.state.active_tab === 'google' ? 'bg-blue-200' : 'bg-grey-100'}`}
                  onClick={e => this.handleTabs(e, 'google')}
                >
                  {lc_data.jst[340]}
                </button>
                <button
                  type="button"
                  className={`map-tabs--nav__coordinates flex-center -ml-1 py-10 w-1/2 sm:w-1/3 border border-grey-300 rounded-r font-bold text-grey-700  ${this.state.active_tab !== 'google' ? 'bg-blue-200' : 'bg-grey-100'}`}
                  onClick={e => this.handleTabs(e, 'coords')}
                >
                  {lc_data.jst[341]}
                </button>
              </div>
            </div>
            }
            <div className="map-tabs--content mb-40">
              {'google' === this.state.active_tab
                ?
                <div className="map-tab--google w-2/3">

                  <div className="flex flex-col mb-20">
                    <div className="field--top flex justify-between">
                      <label htmlFor="address-google"
                             className="field--label flex items-center mb-6 text-sm text-grey-500">
                        {lc_data.jst[248]}
                        <ReactSVG src={`${lc_data.dir}dist/${QuestionIcon}`}
                                  data-tip={lc_data.jst[342]}
                                  className="relative left-6 w-14 h-14 fill-blue-700" style={{ top: '-8px' }}/>
                      </label>
                      <ReactTooltip/>
                    </div>

                    <div
                      className={`field--wrapper relative flex items-center h-44 p-14 border border-grey-300 rounded ${this.state.googleFieldActive ? 'bg-transparent' : 'bg-grey-100'}`}>
                      <input type="text" ref={this.google} id="address-google"
                             className="address-google w-full bg-transparent"
                             name="address"
                             value={address}
                             onChange={this.handleAddressChange}
                             autoComplete="off"
                             placeholder={lc_data.jst[343]}
                             onFocus={() => this.setState({ googleFieldActive: true })}
                             onBlur={() => this.setState({ googleFieldActive: false })}
                      />
                    </div>
                  </div>

                  <input ref={this.latitude} type="hidden" id="address-latitude" className="address-latitude"
                         name="latitude"
                         value={markerPosition[0]}
                         onChange={this.handleCoordsChange}
                  />
                  <input ref={this.longitude} type="hidden" id="address-longitude" className="address-longitude"
                         name="longitude"
                         value={markerPosition[1]}
                         onChange={this.handleCoordsChange}
                  />
                  {this.state.google_predictions &&
                  <div className="google-predictions">
                    {map(this.state.google_predictions, (result, index) => {
                      return <div key={index}
                                  onClick={e => this.handleGoogleLocation(e, result.id)}>{result.city}</div>;
                    })}
                  </div>
                  }
                </div>
                :
                <div className="flex flex-wrap justify-between map-tab--coords w-2/3">

                  <div className="flex flex-col pr-4 w-1/2">
                    <div className="field--top flex justify-between">
                      <label htmlFor="address-google"
                             className="field--label flex items-center mb-6 text-sm text-grey-500">
                        {lc_data.jst[344]}
                        <ReactSVG src={`${lc_data.dir}dist/${QuestionIcon}`}
                                  data-tip={lc_data.jst[345]}
                                  className="relative left-6 w-14 h-14 fill-blue-700" style={{ top: '-8px' }}/>
                      </label>
                      <ReactTooltip/>
                    </div>

                    <div
                      className={`field--wrapper relative flex items-center h-44 p-14 border border-grey-300 rounded ${this.state.latitudeFieldActive ? 'bg-transparent' : 'bg-grey-100'}`}>
                      <input ref={this.latitude} type="text" id="address-latitude"
                             className="address-latitude w-full bg-transparent"
                             name="latitude"
                             value={markerPosition[0]}
                             onChange={this.handleCoordsChange}
                             onFocus={() => this.setState({ latitudeFieldActive: true })}
                             onBlur={() => this.setState({ latitudeFieldActive: false })}
                      />
                    </div>
                  </div>

                  <div className="flex flex-col pl-4 w-1/2">
                    <div className="field--top flex justify-between">
                      <label htmlFor="address-google"
                             className="field--label flex items-center mb-6 text-sm text-grey-500">
                        {lc_data.jst[346]}
                        <ReactSVG src={`${lc_data.dir}dist/${QuestionIcon}`}
                                  data-tip={lc_data.jst[347]}
                                  className="relative left-6 w-14 h-14 fill-blue-700" style={{ top: '-8px' }}/>
                      </label>
                      <ReactTooltip/>
                    </div>

                    <div
                      className={`field--wrapper relative flex items-center h-44 p-14 border border-grey-300 rounded ${this.state.longitudeFieldActive ? 'bg-transparent' : 'bg-grey-100'}`}>
                      <input ref={this.longitude} type="text" id="address-longitude"
                             className="address-longitude w-full bg-transparent"
                             name="longitude"
                             value={markerPosition[1]}
                             onChange={this.handleCoordsChange}
                             onFocus={() => this.setState({ longitudeFieldActive: true })}
                             onBlur={() => this.setState({ longitudeFieldActive: false })}
                      />
                    </div>
                  </div>

                  <div className="description flex mt-10 w-full text-sm text-grey-500 underline hover:text-blue-700">
                    <a href="https://www.latlong.net/convert-address-to-lat-long.html"
                       target="_blank">{lc_data.jst[348]}</a>
                  </div>

                </div>
              }
            </div>
          </div>
          <div className="flex flex-col">
            <div className="field--label relative flex items-center mb-6 text-sm text-grey-500">
              {lc_data.jst[349]}
              <ReactSVG src={`${lc_data.dir}dist/${QuestionIcon}`}
                        data-tip={lc_data.jst[350]}
                        className="relative left-6 w-14 h-14 fill-blue-700" style={{ top: '-8px' }}/>
            </div>
            <Map center={position} zoom={this.state.map.zoom} onClick={e => this.updatePositionOnClick(e)}>
              <TileLayer url={mapUrl}/>
              <Marker
                position={markerPosition}
                draggable={this.state.draggable}
                onDragend={(e) => {
                  this.updatePosition(e);
                }}
                zoomControl={false}
                icon={marker}
                ref={this.marker}
              />
            </Map>
          </div>
        </div>
      </div>
    );
  }
}

function mapStateToProps(state) {
  const { fields, formErrors, formData, costs, terms, taxonomies } = state;
  return {
    fields,
    formErrors,
    formData,
    costs,
    taxonomies,
    terms,
  };
}

export default connect(mapStateToProps)(Location);
