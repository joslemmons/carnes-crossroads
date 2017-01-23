/**
 * Created by jcolfer on 12/19/16.
 */

L.mapbox.accessToken = 'pk.eyJ1IjoiZGlkZXZjbyIsImEiOiJjaXM3cWY3NDEwNDc0Mnpwa2w5YnllMXZkIn0.4pWeAL6-vhtobhpFd2HDuA';

var map = L.mapbox.map('imap', 'mapbox.streets', {
    minZoom: 15,
    maxZoom: 17,
    zoom: 15
});
var filters = document.getElementById('legend-items');
var checkboxes = document.getElementsByClassName('squared-checkbox');

var layer = L.mapbox.featureLayer().addTo(map);

var southWest = L.latLng(32.83064187300698, -79.93316068560326);
var northEast = L.latLng(32.89325262945007, -79.88402077618287);
var bounds = L.latLngBounds(southWest, northEast);

var stamenLayer = L.tileLayer(DI.templateUri + '/img/imap/tiles/{z}/{x}/{y}.png',{
    minZoom: 15,
    maxZoom: 17
}).addTo(map);

var geoJson = {
    type: 'FeatureCollection',
    features: []
};

for (var i = 0; i < locations.length; i++) {
    var color = setMarkerColor(locations[i][4]);

    geoJson.features.push({
        "type": "Feature",
        "geometry": {
            "type": "Point",
            "coordinates": [parseFloat(locations[i][3]), parseFloat(locations[i][2])]
        },
        "properties": {
            "listing-type": locations[i][4],
            "marker-color": setMarkerColor(locations[i][4])
        }
    });
}

layer.setGeoJSON(geoJson);
map.fitBounds(bounds);

map.featureLayer.on('click', function (e) {
    map.panTo(e.layer.getLatLng());
});

map.setZoom(16);

//re-filter the markers when the form is changed
filters.onchange = change;
//initially trigger the filter
change();

function setMarkerColor(listingType) {
    var color = null;

    switch (locations[i][4]) {
        case 'sports-fitness':
            color = '#56c1b1';
            break;

        case 'commercial':
            color = '#536377';
            break;

        case 'schools':
            color = '#695e49';
            break;

        case 'churches':
            color = '#bf7616';
            break;

        case 'libraries':
            color = '#553184';
            break;

        case 'parks-pools':
            color = '#aa0979';
            break;

        case 'waterways':
            color = '#d8b830';
            break;

        case 'golf':
            color = '#22a82e';
            break;

        default:
            break;
    }

    return color;
}

function change() {
    var on = [];
    // Find all checkboxes that are checked and build a list of their values
    for(var i = 0; i < checkboxes.length; i++) {
        if(checkboxes[i].childNodes[1].checked) on.push(checkboxes[i].childNodes[1].name);
    }
    // The filter function takes a GeoJSON feature object
    // and returns true to show it or false to hide it.
    layer.setFilter(function (f) {
        // check each marker's symbol to see if its value is in the list
        // of symbols that should be on, stored in the 'on' array
        return on.indexOf(f.properties["listing-type"]) !== -1;
    });
    return false;
}
