<?php
/*
Title:
Description:
Setting: app_contact_footer_options
*/

use App\Model\ContactFooter;
use App\Model\Helper;

piklist('field', array(
    'type' => 'text',
    'field' => ContactFooter::$field_contact_form_title,
    'label' => 'Contact Form Title',
    'columns' => 12
));

piklist('field', array(
    'type' => 'select',
    'field' => ContactFooter::$field_contact_form_gravity_form_id,
    'label' => 'Choose Gravity Form',
    'columns' => 12,
    'choices' => ContactFooter::getFormChoicesForPiklist()
));

piklist('field', array(
    'type' => 'group',
    'field' => ContactFooter::$field_featured_items,
    'label' => 'Featured Content <img width="170" src="' . get_template_directory_uri() . '/img/admin/contact-footer-featured.png' . '" />',
    'description' => '',
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'file',
            'field' => ContactFooter::$field_featured_image_attachment_id,
            'label' => 'Image',
            'columns' => 12
        ),
        array(
            'type' => 'html',
            'field' => '__tmp',
            'label' => '',
            'value' => '<hr />'
        ),
        array(
            'type' => 'radio',
            'field' => ContactFooter::$field_featured_has_video,
            'label' => 'Has Video?',
            'choices' => ContactFooter::getVideoOptionsForPiklist(),
            'value' => ContactFooter::DOES_NOT_HAVE_VIDEO,
            'columns' => 12
        ),
        array(
            'type' => 'file',
            'field' => ContactFooter::$field_featured_video_attachment_id,
            'label' => 'If has video, then either pick/upload video',
//            'conditions' => array(
//                array(
//                    'field' => ContactFooter::$field_featured_items . ':' . ContactFooter::$field_featured_has_video,
//                    'value' => ContactFooter::HAS_VIDEO_AS_ATTACHMENT
//                )
//            ),
            'columns' => 12
        ),
        array(
            'type' => 'text',
            'field' => ContactFooter::$field_featured_video_src,
            'label' => 'Or enter video link',
//            'conditions' => array(
//                array(
//                    'field' => ContactFooter::$field_featured_items . ':' . ContactFooter::$field_featured_has_video,
//                    'value' => ContactFooter::HAS_VIDEO_AS_LINK
//                )
//            ),
            'columns' => 12
        ),
        array(
            'type' => 'html',
            'field' => '__tmp_aa',
            'label' => '',
            'value' => '<hr />'
        ),
        array(
            'type' => 'text',
            'field' => ContactFooter::$field_featured_title,
            'label' => 'Title',
            'columns' => 12
        ),
        array(
            'type' => 'text',
            'field' => ContactFooter::$field_featured_subtitle,
            'label' => 'Subtitle',
            'columns' => 12
        ),
        array(
            'type' => 'html',
            'field' => '__tmp_aaa',
            'label' => '',
            'value' => '<hr />'
        ),
        array(
            'type' => 'radio',
            'field' => ContactFooter::$field_featured_link_action,
            'label' => 'Link Action',
            'choices' => ContactFooter::getChildPageButtonLinkOptionsForPiklist(),
            'value' => ContactFooter::IS_LINK_TO_PAGE,
            'columns' => 12
        ),
        array(
            'type' => 'select',
            'field' => ContactFooter::$field_featured_link_action_page_to_link_to,
            'label' => 'Either pick an Internal Page',
            'columns' => 12,
            'choices' => Helper::getPagesForPiklist(),
//            'conditions' => array(
//                array(
//                    'field' => ContactFooter::$field_featured_items . ':' . ContactFooter::$field_featured_link_action,
//                    'value' => ContactFooter::IS_LINK_TO_PAGE
//                )
//            )
        ),
        array(
            'type' => 'text',
            'field' => ContactFooter::$field_featured_link_action_custom_link,
            'label' => 'Or enter a custom link',
            'description' => 'ie. http://google.com',
            'columns' => 12,
//            'conditions' => array(
//                array(
//                    'field' => ContactFooter::$field_featured_items . ':' . ContactFooter::$field_featured_link_action,
//                    'value' => ContactFooter::IS_LINK_TO_PAGE
//                )
//            )
        ),
        array(
            'type' => 'html',
            'field' => '__tmp_aaaa',
            'label' => '',
            'value' => '<hr />'
        ),
        array(
            'type' => 'text',
            'field' => ContactFooter::$field_featured_link_text,
            'label' => 'Link Text',
            'columns' => 12
        )
    )
));
