<?php
/*
 * Title: Recent Sales Activity Options (Daniel Island 2015)
 * Post Type: post
 * Priority: low
 * new: false
 *
 */

use App\Model\Post;

global $post;

if ($post) {
    $post = Timber::get_post($post->ID, '\App\Model\Post');
}

piklist('field', array(
    'type' => 'radio',
    'label' => 'Is Recent Sales Activity Post?',
    'columns' => 12,
    'field' => Post::$field_is_recent_sales_activity_post,
    'value' => Post::IS_NOT_RECENT_SALES_ACTIVITY_POST,
    'choices' => Post::getIsRecentSalesActivityOptionsForPiklist()
));

//piklist('field', array(
//    'type' => 'group',
//    'label' => 'Header Options',
//    'description' => '<img src="' . get_template_directory_uri() . '/img/admin/lp-header.png" width="150" /><br />Size should be 560x600px or better',
//    'conditions' => array(
//        array(
//            'field' => Post::$field_is_recent_sales_activity_post,
//            'value' => Post::IS_RECENT_SALES_ACTIVITY_POST
//        )
//    ),
//    'fields' => array(
//        array(
//            'type' => 'text',
//            'field' => Post::$field_title,
//            'label' => 'Title',
//            'columns' => 12
//        ),
//        array(
//            'type' => 'text',
//            'field' => Post::$field_sub_title,
//            'label' => 'Sub Title',
//            'columns' => 12
//        ),
//        array(
//            'type' => 'file',
//            'field' => Post::$field_image_attachment_id,
//            'label' => 'Image'
//        )
//    )
//));

piklist('field', array(
    'type' => 'group',
    'field' => Post::$field_recently_listed_group,
    'label' => 'Recently Listed',
    'description' => 'Pick 4+ Properties to show as Recently Listed',
    'add_more' => true,
    'conditions' => array(
        array(
            'field' => Post::$field_is_recent_sales_activity_post,
            'value' => Post::IS_RECENT_SALES_ACTIVITY_POST
        )
    ),
    'fields' => array(
//        array(
//            'type' => 'radio',
//            'field' => Post::$field_recently_listed_pick_or_manual,
//            'label' => 'Choose Property',
//            'value' => Post::CHOOSE_FROM_LIST,
//            'choices' => Post::getPickPropertyBaseIdChoicesForPiklist()
//        ),
        array(
            'type' => 'text',
            'field' => Post::$field_recently_listed_manual_property_base_id,
            'label' => 'Enter Property Base ID',
            'columns' => 12,
//            'conditions' => array(
//                array(
//                    'field' => Post::$field_recently_listed_group . ':' . Post::$field_recently_listed_pick_or_manual,
//                    'value' => Post::MANUAL
//                )
//            )
        ),
        array(
            'type' => 'select',
            'field' => Post::$field_recently_listed_pick_property_base_id,
            'label' => 'Or choose From List',
            'choices' => Post::getRecentlyListedOptionsForPiklist($post),
//            'conditions' => array(
//                array(
//                    'field' => Post::$field_recently_listed_group . ':' . Post::$field_recently_listed_pick_or_manual,
//                    'value' => Post::CHOOSE_FROM_LIST
//                )
//            )
        )
    )
));

piklist('field', array(
    'type' => 'group',
    'field' => Post::$field_recently_sold_group,
    'label' => 'Recently Sold',
    'description' => 'Pick 4+ Properties to show as Recently Sold. The date marked as sold is currently not available from Property Base (1/24/16).',
    'add_more' => true,
    'conditions' => array(
        array(
            'field' => Post::$field_is_recent_sales_activity_post,
            'value' => Post::IS_RECENT_SALES_ACTIVITY_POST
        )
    ),
    'fields' => array(
//        array(
//            'type' => 'radio',
//            'field' => Post::$field_recently_sold_pick_or_manual,
//            'label' => 'Choose Property',
//            'value' => Post::MANUAL,
//            'choices' => Post::getPickPropertyBaseIdChoicesForPiklist()
//        ),
        array(
            'type' => 'text',
            'field' => Post::$field_recently_sold_manual_property_base_id,
            'label' => 'Enter Property Base ID',
            'columns' => 12,
//            'conditions' => array(
//                array(
//                    'field' => Post::$field_recently_sold_group . ':' . Post::$field_recently_sold_pick_or_manual,
//                    'value' => Post::MANUAL
//                )
//            )
        ),
//        array(
//            'type' => 'select',
//            'field' => Post::$field_recently_sold_pick_property_base_id,
//            'label' => 'Choose From List',
//            'choices' => Post::getRecentlyListedOptionsForPiklist($post),
//            'conditions' => array(
//                array(
//                    'field' => Post::$field_recently_sold_group . ':' . Post::$field_recently_sold_pick_or_manual,
//                    'value' => Post::CHOOSE_FROM_LIST
//                )
//            )
//        )
    )
));

piklist('field', array(
    'type' => 'group',
    'label' => 'Footer Options',
    'description' => '<img src="' . get_template_directory_uri() . '/img/admin/lp-footer.png" width="150" />',
    'conditions' => array(
        array(
            'field' => Post::$field_is_recent_sales_activity_post,
            'value' => Post::IS_RECENT_SALES_ACTIVITY_POST
        )
    ),
    'fields' => array(
        array(
            'type' => 'file',
            'field' => Post::$field_footer_section_image_attachment_id,
            'label' => 'Image',
            'columns' => 12
        ),
        array(
            'type' => 'text',
            'field' => Post::$field_footer_section_title,
            'label' => 'Title',
            'columns' => 12
        ),
        array(
            'type' => 'textarea',
            'field' => Post::$field_footer_section_description,
            'label' => 'Content',
            'columns' => 12,
            'attributes' => array(
                'rows' => 6
            )
        ),
        array(
            'type' => 'select',
            'field' => Post::$field_footer_section_gravity_form_id,
            'label' => 'Choose Gravity Form',
            'columns' => 12,
            'choices' => Post::getFormChoicesForPiklist()
        )
    )
));