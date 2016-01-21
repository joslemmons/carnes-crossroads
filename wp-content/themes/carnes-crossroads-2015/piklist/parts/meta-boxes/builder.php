<?php
/*
 * Title: Builder Options (Carnes Crossroads 2015 Theme)
 * Post Type: builder
 */

use App\Model\Builder;

piklist('field', array(
    'type' => 'radio',
    'field' => Builder::$field_show_available_homes_button,
    'value' => Builder::YES,
    'label' => 'Show Available Homes Button?',
    'description' => 'This will link to the HomeFinder with a filter set for this builder even if there are 0 results',
    'choices' => Builder::getYesOrNoChoicesForPiklist()
));

piklist('field', array(
    'type' => 'radio',
    'field' => Builder::$field_show_home_plans_button,
    'value' => Builder::YES,
    'label' => 'Show Home Plans Button?',
    'choices' => Builder::getYesOrNoChoicesForPiklist()
));

piklist('field', array(
    'type' => 'file',
    'field' => Builder::$field_standard_featured_attachment_id,
    'label' => 'Upload Standard Features'
));

piklist('field', array(
    'type' => 'text',
    'field' => Builder::$field_featured_video_src,
    'label' => 'Featured Video Link',
    'description' => 'YouTube Link',
    'columns' => 12
));

piklist('field', array(
    'type' => 'group',
    'field' => Builder::$field_floor_plans,
    'label' => 'Floor Plans',
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'text',
            'label' => 'Title',
            'field' => Builder::$field_floor_plan_title,
            'columns' => 12
        ),
        array(
            'type' => 'textarea',
            'label' => 'Description',
            'field' => Builder::$field_floor_plan_description,
            'columns' => 12,
            'attributes' => array(
                'rows' => 12
            )
        ),
        array(
            'type' => 'file',
            'label' => 'Featured Images',
            'field' => Builder::$field_floor_plan_featured_image_attachment_ids
        ),
        array(
            'type' => 'text',
            'label' => 'Price',
            'field' => Builder::$field_floor_plan_price,
            'columns' => 12
        ),
        array(
            'type' => 'select',
            'field' => Builder::$field_floor_plan_bedrooms,
            'label' => 'Bedrooms',
            'choices' => Builder::getBedroomChoicesForPiklist(),
            'columns' => 12
        ),
        array(
            'type' => 'select',
            'field' => Builder::$field_floor_plan_full_bathrooms,
            'label' => 'Full Bathrooms',
            'choices' => Builder::getFullBathroomChoicesForPiklist(),
            'columns' => 12
        ),
        array(
            'type' => 'select',
            'field' => Builder::$field_floor_plan_half_bathrooms,
            'label' => 'Half Bathrooms',
            'choices' => Builder::getHalfBathroomChoicesForPiklist(),
            'columns' => 12
        ),
        array(
            'type' => 'text',
            'label' => 'Square Footage',
            'field' => Builder::$field_floor_plan_square_footage,
            'columns' => 12
        ),
        array(
            'type' => 'file',
            'label' => 'Upload Brochure',
            'field' => Builder::$field_floor_plan_brochure_attachment_id
        ),
        array(
            'type' => 'file',
            'label' => 'Upload Floor Plan',
            'field' => Builder::$field_floor_plan_floor_plan_attachment_id
        )
    )
));
