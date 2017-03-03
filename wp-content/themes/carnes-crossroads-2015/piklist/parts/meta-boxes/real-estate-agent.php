<?php
/*
 * Title: Real Estate Agent Options (Carnes Crossroads Theme 2015)
 * Post Type: agent
 */

use App\Model\RealEstateAgent;

piklist('field', array(
    'type' => 'text',
    'field' => RealEstateAgent::$field_listing_agent_property_base_id,
    'label' => 'Listing Agent\'s Property Base ID',
    'description' => 'ie. 005C0000003VOPHIA4',
    'columns' => 12
));

piklist('field', array(
    'type' => 'group',
    'label' => 'Contact Information',
    'fields' => array(
        array(
            'type' => 'text',
            'field' => RealEstateAgent::$field_contact_name,
            'label' => 'Name',
            'columns' => 12
        ),
        array(
            'type' => 'text',
            'field' => RealEstateAgent::$field_contact_office_number,
            'label' => 'Office Number',
            'columns' => 12
        ),
        array(
            'type' => 'text',
            'field' => RealEstateAgent::$field_contact_mobile_number,
            'label' => 'Mobile Number',
            'columns' => 12
        ),
        array(
            'type' => 'text',
            'field' => RealEstateAgent::$field_contact_email,
            'label' => 'Email',
            'columns' => 12,
            'validate' => array(
                array(
                    'type' => 'email'
                )
            )
        )
    )
));