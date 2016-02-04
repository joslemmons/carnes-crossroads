jQuery(function ($) {
    $('#modal-view-on-map').on('hidden.bs.modal', function (e) {
        $('#imap').empty();
    });

    $('#modal-view-on-map').on('show.bs.modal', function (e) {
        var $modals = $('div.modal');
        $.each($modals, function (i, el) {
            if (($(el).data('bs.modal') || {}).isShown === true) {
                $(el).modal('hide');
            }
        });
    });

    $(document).on('click', '#viewListingsOnMap, a.viewIMap', function () {
        var $modal = $('#modal-view-on-map');

        if (typeof ($modal.data('bs.modal') || {}).isShown === 'undefined' || ($modal.data('bs.modal') || {}).isShown === false) {
            $modal.modal('show');
        }
        else {
            return false;
        }

        initImap(locations);

        return false;
    });

    $(document).on('click', '#viewOnMap', function () {
        var $modal = $('#modal-view-on-map'),
            latitude = $(this).attr('data-latitude'),
            longitude = $(this).attr('data-longitude'),
            address = $(this).attr('data-address'),
            link = $(this).attr('data-link'),
            propertyType = $(this).attr('data-property-type'),
            toolTipHTML = $(this).attr('data-tool-tip');

        if (propertyType === 'Homesite') {
            propertyType = 0;
        }

        if (typeof ($modal.data('bs.modal') || {}).isShown === 'undefined' || ($modal.data('bs.modal') || {}).isShown === false) {
            $modal.modal('show');
        }
        else {
            return false;
        }

        initImap([
            [
                address,
                latitude,
                longitude,
                '4',
                link,
                toolTipHTML,
                propertyType
            ]
        ]);
    });

    $(document).on('click', 'a.showProperty', function () {
        var propertyId = $(this).attr('data-property-id'),
            propertyAddress = $(this).attr('data-property-address');

        $('div.listing.active').removeClass('active');
        $('div.listing[data-property-id="' + propertyId + '"]').addClass('active');

        router.navigate('home-finder/properties/' + propertyAddress + '/' + propertyId + '/', {trigger: true});

        $('#modal-view-on-map').modal('hide');

        return false;
    });

// misc vars
    var mapMinZoom = 17;
    var mapMaxZoom = 19;

//set custom tiles (maptype)
    var diOptions = {
        getTileUrl: function (coord, zoom) {
            var ymax = 1 << zoom;
            var y = ymax - coord.y - 1;
            url = DI.templateUri + "/img/imap/tiles/" + zoom + "/" + coord.x + "/" + y + ".png";
            return url;
        },
        tileSize: new google.maps.Size(256, 256),
        isPng: true
    };

    var diMapType = new google.maps.ImageMapType(diOptions);
    var bounds = new google.maps.LatLngBounds();
    var geocoder = new google.maps.Geocoder();

//<?php if ( $z_query->query->is_archive ) { ?>
// remove below to zoom into properties
    bounds.extend(new google.maps.LatLng(32.83064187300698, -79.93316068560326));	//set min x/y
    bounds.extend(new google.maps.LatLng(32.89325262945007, -79.88402077618287));	//set max x/y
//<?php } ?>

    /**
     * Data for the markers consisting of a name, a LatLng and a zIndex for
     * the order in which these markers should display on top of each
     * other.
     */


// googles info pane
    var infowindow = new google.maps.InfoWindow({
        size: new google.maps.Size(150, 50)
    });


    function setMarkers(map, locations) {
        // Add markers to the map
        var house_image = new google.maps.MarkerImage(DI.templateUri + '/img/imap/icons/house-icon-2.png',
            // This marker is 20 pixels wide by 32 pixels tall.
            new google.maps.Size(20, 32),
            // The origin for this image is 0,0.
            new google.maps.Point(0, 0),
            // The anchor for this image is the base of the flagpole at 0,32.
            new google.maps.Point(5, 20));

        var lot_image = new google.maps.MarkerImage(DI.templateUri + '/img/imap/icons/lot-icon-2.png',
            // This marker is 20 pixels wide by 32 pixels tall.
            new google.maps.Size(20, 32),
            // The origin for this image is 0,0.
            new google.maps.Point(0, 0),
            // The anchor for this image is the base of the flagpole at 0,32.
            new google.maps.Point(5, 20));

        var shadow;	//not used
        /*
         var shadow = new google.maps.MarkerImage('<?php //echo get_stylesheet_directory_uri();?>/images/house-icon-shadow.png', //should be set to a shadow image
         // The shadow image is larger in the horizontal dimension
         // while the position and offset are the same as for the main image.
         new google.maps.Size(45, 40),
         new google.maps.Point(0,0),
         new google.maps.Point(0, 40));*/

        // Shapes define the clickable region of the icon. The type defines an HTML &lt;area&gt; element 'poly' whichtraces out a polygon as a series of X,Y points. The final coordinate closes the poly by connecting to the firstcoordinate. NOT USED
        var shape = {
            coord: [1, 1, 1, 32, 20, 32, 20, 1],
            type: 'poly'
        };

        var markers = [];

        for (var i = 0; i < locations.length; i++) {
            var location = locations[i];
            var title = location[0];
            var zIndex = location[3];
            var theLink = location[4];
            var html = location[5];
            var prop_type = location[6];

            if (location[1] && location[2]) {
                var myLatLng = new google.maps.LatLng(location[1], location[2]);

                if (prop_type != 0)
                    var icon_image = house_image;
                else
                    var icon_image = lot_image;

                var marker = createMarker(map, myLatLng, icon_image, shadow, title, zIndex, theLink, html);
                markers.push(marker);

                //<?php if ( $z_query->query->is_archive ) { ?>
                //	//use this if you want to only show all the points in the map bounds
                //	bounds.extend(myLatLng);
                //	map.panTo(myLatLng);
                //<?php } ?>
            } else {
                geocoder.geocode({'address': location[0]}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        // var myLatLng = results[0].geometry.location;

                        if (prop_type)
                            var icon_image = house_image;
                        else
                            var icon_image = lot_image;

                        var marker = createMarker(map, results[0].geometry.location, icon_image, shadow, title, zIndex, theLink, html);
                        markers.push(marker);

                        //<?php if ( $z_query->query->is_archive ) { ?>
                        //	//use this if you want to only show all the points in the map bounds
                        //	bounds.extend(myLatLng);
                        //	map.panTo(myLatLng);
                        //<?php } ?>
                    } else {
                        alert("Geocode was not successful for the following reason: " + status);
                    }
                });
            }

            //if (i === locations.length - 1) {
            //    map.panTo(new google.maps.LatLng(location[1], location[2]));
            //}

            return markers;

        } // end for
    } // end function setMarkers

    function createMarker(map, myLatLng, image, shadow, title, zIndex, theLink, html) {

        var contentString = html;

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            //shadow: shadow,
            icon: image,
            //shape: shape,
            //title: title,
            zIndex: 99999,
            clickable: true
        });

        //<?php if ( $z_query->query->is_single ) { ?>
        //	bounds.extend(myLatLng); // extend bounds of map to show all results
        //	map.panTo(myLatLng); // centers map to last property
        //<?php } ?>

        //mouseovers
        google.maps.event.addListener(marker, "click", function () {
            infowindow.setContent(contentString);
            infowindow.open(map, marker);

        });

        return marker;
    } // end function createMarker


    function initImap(properties) {
        var myOptions = {
            zoom: mapMinZoom,
            center: new google.maps.LatLng(33.055457, -80.103917),
            navigationControl: true,
            mapTypeControl: false,
            scaleControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("imap"), myOptions);
        map.overlayMapTypes.insertAt(0, diMapType);

        //needed for gettin pixes from latlng for mouseover div position
        overlay = new google.maps.OverlayView();
        overlay.draw = function () {
        };
        overlay.setMap(map);

        // don't allow panning pass bounds. Listen for the dragend event
        //google.maps.event.addListener(map, 'dragend', function () {
        //
        //     if ( bounds.contains( map.getCenter() ) ) return;
        //
        //    // Out of bounds - Move the map back within the bounds
        //    var c = map.getCenter(),
        //        x = c.lng(),
        //        y = c.lat(),
        //        maxX = bounds.getNorthEast().lng(),
        //        maxY = bounds.getNorthEast().lat(),
        //        minX = bounds.getSouthWest().lng(),
        //        minY = bounds.getSouthWest().lat();
        //
        //    if (x < minX) x = minX;
        //    if (x > maxX) x = maxX;
        //    if (y < minY) y = minY;
        //    if (y > maxY) y = maxY;
        //
        //    map.setCenter(new google.maps.LatLng(y, x));
        //
        //});

        // restrict max and min zoom in v3 api
        google.maps.event.addListener(map, "zoom_changed", function () {
            if (map.getZoom() < mapMinZoom) map.setZoom(mapMinZoom);
            if (map.getZoom() > mapMaxZoom) map.setZoom(mapMaxZoom);
        });

        var markers = setMarkers(map, properties);

        $("#modal-view-on-map").on("shown.bs.modal", function () {
            google.maps.event.trigger(map, "resize");

            if (properties.length === 1) {
                map.setCenter(new google.maps.LatLng(properties[0][1], properties[0][2]));

                if (markers.length === 1) {
                    google.maps.event.trigger(markers[0], 'click');
                }
            }
            else {
                map.setCenter(new google.maps.LatLng(33.055457, -80.103917));
            }
        });

        //<?php if ( $z_query->query->is_archive ) { ?>
        //map.fitBounds(bounds);
        //<?php } ?>

    } // end function initImap
});
