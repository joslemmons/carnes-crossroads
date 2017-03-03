<?php
/*
Title: iMap Listing Options (Carnes Crossroads 2015 Theme)
Post Type: place_of_interest
Order: 1
Priority: high
*/

piklist('field', array(
    'type' => 'text',
    'field' => \App\Model\PlaceOfInterest::$field_latitude,
    'label' => 'Latitude',
    'columns' => 12
));

piklist('field', array(
    'type' => 'text',
    'field' => \App\Model\PlaceOfInterest::$field_longitude,
    'label' => 'Longitude',
    'columns' => 12
));

piklist('field', array(
    'type' => 'text',
    'field' => \App\Model\PlaceOfInterest::$field_address,
    'label' => 'Address',
    'columns' => 12
));

piklist('field', array(
    'type' => 'text',
    'field' => \App\Model\PlaceOfInterest::$field_website_url,
    'label' => 'Website URL',
    'columns' => 12
));
