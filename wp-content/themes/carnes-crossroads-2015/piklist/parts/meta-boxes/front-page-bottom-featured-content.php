<?php
/*
 * Title: Bottom Featured Content (Carnes Crossroads 2015 Theme)
 * ID: 23
 * order: 3
 */

use App\Model\FrontPage;
use App\Model\Helper;

// bottom primary
piklist('field', array(
    'type' => 'group',
    'field' => FrontPage::$field_bottom_featured_group_primary_items,
    'label' => 'Bottom Primary Featured Content',
    'description' => '<img width="170" src="' . get_template_directory_uri() . '/img/admin/front-page-bottom-primary.png' . '" />',
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'file',
            'field' => FrontPage::$field_bottom_featured_group_primary_image_attachment_id,
            'label' => 'Image'
        ),
        array(
            'type' => 'radio',
            'field' => FrontPage::$field_bottom_featured_group_primary_has_video,
            'label' => 'Has Video?',
            'choices' => FrontPage::getVideoOptionsForPiklist(),
            'value' => FrontPage::DOES_NOT_HAVE_VIDEO,
            'columns' => 12
        ),
        array(
            'type' => 'file',
            'field' => FrontPage::$field_bottom_featured_group_primary_video_attachment_id,
            'label' => 'Pick Video',
            'conditions' => array(
                array(
                    'field' => FrontPage::$field_bottom_featured_group_primary_items . ':' . FrontPage::$field_bottom_featured_group_primary_has_video,
                    'value' => FrontPage::HAS_VIDEO_AS_ATTACHMENT
                )
            ),
            'columns' => 12
        ),
        array(
            'type' => 'text',
            'field' => FrontPage::$field_bottom_featured_group_primary_video_src,
            'label' => 'Enter Video Link',
            'conditions' => array(
                array(
                    'field' => FrontPage::$field_bottom_featured_group_primary_items . ':' . FrontPage::$field_bottom_featured_group_primary_has_video,
                    'value' => FrontPage::HAS_VIDEO_AS_LINK
                )
            ),
            'columns' => 12
        ),
        array(
            'type' => 'text',
            'field' => FrontPage::$field_bottom_featured_group_primary_title,
            'label' => 'Title',
            'columns' => 12
        ),
        array(
            'type' => 'text',
            'field' => FrontPage::$field_bottom_featured_group_primary_subtitle,
            'label' => 'Subtitle',
            'columns' => 12
        ),
        array(
            'type' => 'radio',
            'field' => FrontPage::$field_bottom_featured_group_primary_link_action,
            'label' => 'Link Action',
            'choices' => FrontPage::getChildPageButtonLinkOptionsForPiklist(),
            'value' => FrontPage::IS_LINK_TO_PAGE,
            'columns' => 12
        ),
        array(
            'type' => 'select',
            'field' => FrontPage::$field_bottom_featured_group_primary_link_action_page_to_link_to,
            'label' => 'Link to Internal Page',
            'columns' => 12,
            'choices' => Helper::getPagesForPiklist(),
            'conditions' => array(
                array(
                    'field' => FrontPage::$field_bottom_featured_group_primary_items . ':' . FrontPage::$field_bottom_featured_group_primary_link_action,
                    'value' => FrontPage::IS_LINK_TO_PAGE
                )
            )
        ),
        array(
            'type' => 'text',
            'field' => FrontPage::$field_bottom_featured_group_primary_link_action_custom_link,
            'label' => 'Custom Link',
            'description' => 'ie. http://google.com',
            'columns' => 12,
            'conditions' => array(
                array(
                    'field' => FrontPage::$field_bottom_featured_group_primary_items . ':' . FrontPage::$field_bottom_featured_group_primary_link_action,
                    'value' => FrontPage::IS_CUSTOM_LINK
                )
            )
        ),
        array(
            'type' => 'text',
            'field' => FrontPage::$field_bottom_featured_group_primary_link_text,
            'label' => 'Link Text',
            'columns' => 12
        )
    )
));

// bottom secondary
piklist('field', array(
    'type' => 'group',
    'field' => FrontPage::$field_bottom_featured_group_secondary_items,
    'label' => 'Bottom Secondary Featured Content',
    'description' => '<img width="170" src="' . get_template_directory_uri() . '/img/admin/front-page-bottom-secondary.png' . '" />',
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'file',
            'field' => FrontPage::$field_bottom_featured_group_secondary_image_attachment_id,
            'label' => 'Image'
        ),
        array(
            'type' => 'radio',
            'field' => FrontPage::$field_bottom_featured_group_secondary_has_video,
            'label' => 'Has Video?',
            'choices' => FrontPage::getVideoOptionsForPiklist(),
            'value' => FrontPage::DOES_NOT_HAVE_VIDEO,
            'columns' => 12
        ),
        array(
            'type' => 'file',
            'field' => FrontPage::$field_bottom_featured_group_secondary_video_attachment_id,
            'label' => 'Pick Video',
            'conditions' => array(
                array(
                    'field' => FrontPage::$field_bottom_featured_group_secondary_items . ':' . FrontPage::$field_bottom_featured_group_secondary_has_video,
                    'value' => FrontPage::HAS_VIDEO_AS_ATTACHMENT
                )
            ),
            'columns' => 12
        ),
        array(
            'type' => 'text',
            'field' => FrontPage::$field_bottom_featured_group_secondary_video_src,
            'label' => 'Enter Video Link',
            'conditions' => array(
                array(
                    'field' => FrontPage::$field_bottom_featured_group_secondary_items . ':' . FrontPage::$field_bottom_featured_group_secondary_has_video,
                    'value' => FrontPage::HAS_VIDEO_AS_LINK
                )
            ),
            'columns' => 12
        ),
        array(
            'type' => 'text',
            'field' => FrontPage::$field_bottom_featured_group_secondary_title,
            'label' => 'Title',
            'columns' => 12
        ),
        array(
            'type' => 'text',
            'field' => FrontPage::$field_bottom_featured_group_secondary_subtitle,
            'label' => 'Subtitle',
            'columns' => 12
        ),
        array(
            'type' => 'radio',
            'field' => FrontPage::$field_bottom_featured_group_secondary_link_action,
            'label' => 'Link Action',
            'choices' => FrontPage::getChildPageButtonLinkOptionsForPiklist(),
            'value' => FrontPage::IS_LINK_TO_PAGE,
            'columns' => 12
        ),
        array(
            'type' => 'select',
            'field' => FrontPage::$field_bottom_featured_group_secondary_link_action_page_to_link_to,
            'label' => 'Link to Internal Page',
            'columns' => 12,
            'choices' => Helper::getPagesForPiklist(),
            'conditions' => array(
                array(
                    'field' => FrontPage::$field_bottom_featured_group_secondary_items . ':' . FrontPage::$field_bottom_featured_group_secondary_link_action,
                    'value' => FrontPage::IS_LINK_TO_PAGE
                )
            )
        ),
        array(
            'type' => 'text',
            'field' => FrontPage::$field_bottom_featured_group_secondary_link_action_custom_link,
            'label' => 'Custom Link',
            'description' => 'ie. http://google.com',
            'columns' => 12,
            'conditions' => array(
                array(
                    'field' => FrontPage::$field_bottom_featured_group_secondary_items . ':' . FrontPage::$field_bottom_featured_group_secondary_link_action,
                    'value' => FrontPage::IS_CUSTOM_LINK
                )
            )
        ),
        array(
            'type' => 'text',
            'field' => FrontPage::$field_bottom_featured_group_secondary_link_text,
            'label' => 'Link Text',
            'columns' => 12
        )
    )
));

// bottom tertiary
piklist('field', array(
    'type' => 'group',
    'field' => FrontPage::$field_bottom_featured_group_tertiary_items,
    'label' => 'Bottom Tertiary Featured Content',
    'description' => '<img width="170" src="' . get_template_directory_uri() . '/img/admin/front-page-bottom-tertiary.png' . '" />',
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'text',
            'field' => FrontPage::$field_bottom_featured_group_tertiary_title,
            'label' => 'Title',
            'columns' => 12
        ),
        array(
            'type' => 'text',
            'field' => FrontPage::$field_bottom_featured_group_tertiary_subtitle,
            'label' => 'Subtitle',
            'columns' => 12
        ),
        array(
            'type' => 'radio',
            'field' => FrontPage::$field_bottom_featured_group_tertiary_link_action,
            'label' => 'Link Action',
            'choices' => FrontPage::getChildPageButtonLinkOptionsForPiklist(),
            'value' => FrontPage::IS_LINK_TO_PAGE,
            'columns' => 12
        ),
        array(
            'type' => 'select',
            'field' => FrontPage::$field_bottom_featured_group_tertiary_link_action_page_to_link_to,
            'label' => 'Link to Internal Page',
            'columns' => 12,
            'choices' => Helper::getPagesForPiklist(),
            'conditions' => array(
                array(
                    'field' => FrontPage::$field_bottom_featured_group_tertiary_items . ':' . FrontPage::$field_bottom_featured_group_tertiary_link_action,
                    'value' => FrontPage::IS_LINK_TO_PAGE
                )
            )
        ),
        array(
            'type' => 'text',
            'field' => FrontPage::$field_bottom_featured_group_tertiary_link_action_custom_link,
            'label' => 'Custom Link',
            'description' => 'ie. http://google.com',
            'columns' => 12,
            'conditions' => array(
                array(
                    'field' => FrontPage::$field_bottom_featured_group_tertiary_items . ':' . FrontPage::$field_bottom_featured_group_tertiary_link_action,
                    'value' => FrontPage::IS_CUSTOM_LINK
                )
            )
        )
    )
));
