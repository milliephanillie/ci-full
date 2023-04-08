/* global lc_data, React */
/**
 * External dependencies.
 */
import { Component, Fragment, createRef, useState, useRef, useEffect } from '@wordpress/element';
import { connect, useDispatch, useSelector } from 'react-redux';
import { map, get } from 'lodash';
import { divIcon } from 'leaflet';
import { Map, TileLayer, Marker } from 'react-leaflet';
import MarkerClusterGroup from 'react-leaflet-markercluster';
import MapPopup from '../page-search/partials/MapPopup';

const SearchMapEl = (props) => {
  const data = useSelector(state => state);
  const { onChange, reloadMap, searchData } = data;
  const  showMap  = data.showMap;
  const  showFilters  = data.showFilters;
  const dispatch = useDispatch();
  const [results, setResults] = useState({});
  const [markers, setMarkers] = useState([]);
  const [position, setPosition] = useState([50.505, -29.09]);
  const [bounds, setBounds] = useState([]);
  const [mapWidth, setMapWidth] = useState('');
  const [zoom, setZoom] = useState(8);
  const marker = useRef(null);
  const mapEl = useRef(null);

  useEffect(() => {
    window.addEventListener('resize', calculateMapWidth);

    calculateMapWidth();

    return () => window.removeEventListener('resize', calculateMapWidth);
  }, []);

  useEffect(() => {
    const markersNew = [];
    setResults(data?.postsByUrl?.RECEIVE_POSTS?.items || {});

    if (showMap) {
      results?.products && map(results.products, product => {
        markersNew.push([parseFloat(product.meta.location.lat), parseFloat(product.meta.location.lng)]);
      });
      const boundsNew = new L.LatLngBounds(markersNew);
      setMarkers(markersNew);
      setBounds(boundsNew);
    }

  }, [data]);

  const calculateMapWidth = e => {
    let classes = '';
    if (window.innerWidth < 1025 && showFilters) {
      classes = 'min-w-48%';
    } else if (window.innerWidth > 770 && window.innerWidth < 1025 && !showFilters) {
      classes = 'min-w-9/16';
    } else if (window.innerWidth > 770 && window.innerWidth < 1025 && showFilters) {
      classes = 'min-w-1/2';
    } else if (window.innerWidth > 645 && window.innerWidth < 770) {
      classes = 'min-w-48%';
    } else if (window.innerWidth < 645) {
      classes = 'absolute top-0 left-0 w-full h-full z-20';
    }

    return classes;
  };

  const handleShowMap = e => {
    props.handleShowMap();
  };

  const handlePopupOpen = (e) => {
    const coords = e.target.getLatLng();
    const point = map.current.leafletElement.latLngToContainerPoint(coords);
    const newPoint = L.point([point.x + 90, point.y - 40]);
    map.current.leafletElement.panTo(map.current.leafletElement.containerPointToLatLng(newPoint));
  };

  const clusterIcon = function (cluster) {
    return divIcon({
      html: `<div class="cluster flex-center h-full bg-blue-700 rounded-full"><span class="cluster--count font-bold text-xl text-white">${cluster.getChildCount()}</span></div>`,
      className: 'cluster--wrapper bg-white rounded-full shadow-cluster font-sans',
      iconSize: L.point(48, 48, true),
      iconAnchor: L.point(24, 48),
    });
  };

  const mapUrl = lc_data.mapbox_url !== '' ? lc_data.mapbox_url : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';



  return (
    showMap &&
    <div className={`map--width ml-auto ${mapWidth}`}>
      {get(results, 'products') && <div className="map h-full">
        {markers.length > 1
          ?
          <Map zoom={zoom}
               bounds={bounds}
               boundsOptions={{ padding: [100, 100] }}
               maxZoom={19}
               ref={mapEl}>
            <TileLayer url={mapUrl}/>
            {results &&
            <MarkerClusterGroup iconCreateFunction={clusterIcon}>
              {map(results.products, product => {
                let price = product.price_html;
                let priceClass = '';
                switch (product.meta.price_type) {
                  case 'price_on_call':
                    price = lc_data.jst[475];
                    priceClass = 'text-blue-700';
                    break;
                  case 'free':
                    price = lc_data.jst[128];
                    priceClass = 'text-green-700';
                    break;
                  default:
                    price = product.price_html;
                    break;
                }
                const markerShadow = `<svg class="absolute w-full h-full" version="1.1"
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                            viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000"
                                            xml:space="preserve"
                                            style="left: 2px; bottom: -3px; opacity: .15; z-index: 1;"
                                            >
\t<g><g transform="translate(0.000000,511.000000) scale(0.100000,-0.100000)"><path d="M4624.6,4997.8c-781.1-81.8-1535.5-413-2122.4-932.4c-619.5-545.9-1053-1316.8-1210.4-2146.9c-65.4-351.7-65.4-952.8,2.1-1316.8c57.3-314.9,206.5-774.9,347.6-1071.4c314.9-668.6,742.2-1247.2,1801.3-2435.2c730-817.9,1106.2-1282,1410.8-1733.9c128.8-190.1,157.4-196.3,253.5-45c229,363.9,707.5,954.9,1466,1809.5c703.4,793.3,954.9,1098,1241.1,1494.7c707.5,989.6,1030.5,2040.6,922.2,3020c-184,1686.8-1441.5,3032.2-3103.8,3318.5C5358.7,5005.9,4878.2,5024.3,4624.6,4997.8z"/></g></g>
</svg>`;
                const markerIcon = `<svg class="w-full h-full fill-blue-700" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
\t viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve">
\t<g><g transform="translate(0.000000,511.000000) scale(0.100000,-0.100000)"><path d="M4624.6,4997.8c-781.1-81.8-1535.5-413-2122.4-932.4c-619.5-545.9-1053-1316.8-1210.4-2146.9c-65.4-351.7-65.4-952.8,2.1-1316.8c57.3-314.9,206.5-774.9,347.6-1071.4c314.9-668.6,742.2-1247.2,1801.3-2435.2c730-817.9,1106.2-1282,1410.8-1733.9c128.8-190.1,157.4-196.3,253.5-45c229,363.9,707.5,954.9,1466,1809.5c703.4,793.3,954.9,1098,1241.1,1494.7c707.5,989.6,1030.5,2040.6,922.2,3020c-184,1686.8-1441.5,3032.2-3103.8,3318.5C5358.7,5005.9,4878.2,5024.3,4624.6,4997.8z"/></g></g>
</svg>${markerShadow}`;
                const markerIconPromoted = `<svg class="w-full h-full fill-red-700" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
\t viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve">
\t<g><g transform="translate(0.000000,511.000000) scale(0.100000,-0.100000)"><path d="M4624.6,4997.8c-781.1-81.8-1535.5-413-2122.4-932.4c-619.5-545.9-1053-1316.8-1210.4-2146.9c-65.4-351.7-65.4-952.8,2.1-1316.8c57.3-314.9,206.5-774.9,347.6-1071.4c314.9-668.6,742.2-1247.2,1801.3-2435.2c730-817.9,1106.2-1282,1410.8-1733.9c128.8-190.1,157.4-196.3,253.5-45c229,363.9,707.5,954.9,1466,1809.5c703.4,793.3,954.9,1098,1241.1,1494.7c707.5,989.6,1030.5,2040.6,922.2,3020c-184,1686.8-1441.5,3032.2-3103.8,3318.5C5358.7,5005.9,4878.2,5024.3,4624.6,4997.8z"/></g></g>
</svg>${markerShadow}`;
                const marker = divIcon({
                  className: 'lisfinity-divicon',
                  html: `<div class="relative marker flex items-center w-32" data-marker-id="${product.ID}">${markerIcon}</div>`,
                  iconSize: [12, 32],
                  iconAnchor: [12, 32],
                });
                const markerPromoted = divIcon({
                  className: 'lisfinity-divicon',
                  html: `<div class="relative marker flex items-center w-32" data-marker-id="${product.ID}">${markerIconPromoted}</div>`,
                  iconSize: [12, 32],
                  iconAnchor: [12, 32],
                });
                return (
                  <Marker
                    key={product.ID}
                    position={[parseFloat(product.meta.location.lat), parseFloat(product.meta.location.lng)]}
                    icon={product.promoted ? markerPromoted : marker}
                    onClick={(e) => handlePopupOpen(e)}
                  >
                    <MapPopup forwardRef={props.popup} product={product}/>
                  </Marker>
                );
              })}
            </MarkerClusterGroup>
            }
          </Map>
          :
          <Map zoom={zoom} center={markers[0]}
               maxZoom={19}
               ref={mapEl}>
            <TileLayer url={mapUrl}/>
            {results &&
            <MarkerClusterGroup iconCreateFunction={clusterIcon}>
              {map(results.products, product => {
                const markerShadow = `<svg class="absolute w-full h-full" version="1.1"
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                            viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000"
                                            xml:space="preserve"
                                            style="left: 2px; bottom: -3px; opacity: .15; z-index: 1;"
                                            >
\t<g><g transform="translate(0.000000,511.000000) scale(0.100000,-0.100000)"><path d="M4624.6,4997.8c-781.1-81.8-1535.5-413-2122.4-932.4c-619.5-545.9-1053-1316.8-1210.4-2146.9c-65.4-351.7-65.4-952.8,2.1-1316.8c57.3-314.9,206.5-774.9,347.6-1071.4c314.9-668.6,742.2-1247.2,1801.3-2435.2c730-817.9,1106.2-1282,1410.8-1733.9c128.8-190.1,157.4-196.3,253.5-45c229,363.9,707.5,954.9,1466,1809.5c703.4,793.3,954.9,1098,1241.1,1494.7c707.5,989.6,1030.5,2040.6,922.2,3020c-184,1686.8-1441.5,3032.2-3103.8,3318.5C5358.7,5005.9,4878.2,5024.3,4624.6,4997.8z"/></g></g>
</svg>`;
                const markerIcon = `<svg class="w-full h-full fill-blue-700" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
\t viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve">
\t<g><g transform="translate(0.000000,511.000000) scale(0.100000,-0.100000)"><path d="M4624.6,4997.8c-781.1-81.8-1535.5-413-2122.4-932.4c-619.5-545.9-1053-1316.8-1210.4-2146.9c-65.4-351.7-65.4-952.8,2.1-1316.8c57.3-314.9,206.5-774.9,347.6-1071.4c314.9-668.6,742.2-1247.2,1801.3-2435.2c730-817.9,1106.2-1282,1410.8-1733.9c128.8-190.1,157.4-196.3,253.5-45c229,363.9,707.5,954.9,1466,1809.5c703.4,793.3,954.9,1098,1241.1,1494.7c707.5,989.6,1030.5,2040.6,922.2,3020c-184,1686.8-1441.5,3032.2-3103.8,3318.5C5358.7,5005.9,4878.2,5024.3,4624.6,4997.8z"/></g></g>
</svg>${markerShadow}`;
                const markerIconPromoted = `<svg class="w-full h-full fill-red-700" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
\t viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve">
\t<g><g transform="translate(0.000000,511.000000) scale(0.100000,-0.100000)"><path d="M4624.6,4997.8c-781.1-81.8-1535.5-413-2122.4-932.4c-619.5-545.9-1053-1316.8-1210.4-2146.9c-65.4-351.7-65.4-952.8,2.1-1316.8c57.3-314.9,206.5-774.9,347.6-1071.4c314.9-668.6,742.2-1247.2,1801.3-2435.2c730-817.9,1106.2-1282,1410.8-1733.9c128.8-190.1,157.4-196.3,253.5-45c229,363.9,707.5,954.9,1466,1809.5c703.4,793.3,954.9,1098,1241.1,1494.7c707.5,989.6,1030.5,2040.6,922.2,3020c-184,1686.8-1441.5,3032.2-3103.8,3318.5C5358.7,5005.9,4878.2,5024.3,4624.6,4997.8z"/></g></g>
</svg>${markerShadow}`;
                const marker = divIcon({
                  className: 'lisfinity-divicon',
                  html: `<div class="relative marker flex items-center w-32" data-marker-id="${product.ID}">${markerIcon}</div>`,
                  iconSize: [12, 32],
                  iconAnchor: [12, 32],
                });
                const markerPromoted = divIcon({
                  className: 'lisfinity-divicon',
                  html: `<div class="relative marker flex items-center w-32" data-marker-id="${product.ID}">${markerIconPromoted}</div>`,
                  iconSize: [12, 32],
                  iconAnchor: [12, 32],
                });
                return (
                  <Marker
                    key={product.ID}
                    position={[parseFloat(product.meta.location.lat), parseFloat(product.meta.location.lng)]}
                    icon={product.promoted ? markerPromoted : marker}
                  >
                    <MapPopup product={product}/>
                  </Marker>
                );
              })}
            </MarkerClusterGroup>
            }
          </Map>
        }
      </div>
      }
    </div>
  );
};

export default SearchMapEl;
