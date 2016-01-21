<?php
/*
 * Title: Landing Page Options (Daniel Island 2015 Theme)
 * Post Type: landing_page
 */

use App\Model\Helper;
use App\Model\LandingPage;

piklist('field', array(
    'type' => 'group',
    'label' => 'Header Options',
    'description' => '<img src="' . get_template_directory_uri() . '/img/admin/lp-header.png" width="150" /><br />Size should be 560x600px or better',
    'fields' => array(
        array(
            'type' => 'text',
            'field' => LandingPage::$field_title,
            'label' => 'Title',
            'columns' => 12
        ),
        array(
            'type' => 'text',
            'field' => LandingPage::$field_sub_title,
            'label' => 'Sub Title',
            'columns' => 12
        ),
        array(
            'type' => 'file',
            'field' => LandingPage::$field_image_attachment_id,
            'label' => 'Image'
        )
    )
));

piklist('field', array(
    'type' => 'group',
    'label' => 'Featured Content Options',
    'description' => '<img src="' . get_template_directory_uri() . '/img/admin/lp-featured.png" width="150" />',
    'fields' => array(
        array(
            'type' => 'text',
            'field' => LandingPage::$field_featured_section_title,
            'label' => 'Title',
            'columns' => 12
        ),
        array(
            'type' => 'textarea',
            'field' => LandingPage::$field_featured_section_content,
            'label' => 'Content',
            'columns' => 12,
            'attributes' => array(
                'rows' => 6
            )
        ),
        array(
            'type' => 'file',
            'field' => LandingPage::$field_featured_section_image_attachment_id,
            'label' => 'Image'
        ),
        array(
            'type' => 'radio',
            'field' => LandingPage::$field_featured_section_has_video,
            'label' => 'Video Options',
            'choices' => LandingPage::getHasVideoOptionsForPiklist(),
            'value' => LandingPage::GALLERY_NO_VIDEO,
        ),
        array(
            'type' => 'file',
            'field' => LandingPage::$field_featured_section_video_attachment_id,
            'label' => 'Video',
            'columns' => 12,
            'conditions' => array(
                array(
                    'field' => LandingPage::$field_featured_section_has_video,
                    'value' => LandingPage::GALLERY_UPLOAD_VIDEO
                )
            )
        ),
        array(
            'type' => 'text',
            'columns' => 12,
            'field' => LandingPage::$field_featured_section_video_src,
            'label' => 'Video Link (YouTube, Vimeo, etc)',
            'conditions' => array(
                array(
                    'field' => LandingPage::$field_featured_section_has_video,
                    'value' => LandingPage::GALLERY_LINK_VIDEO
                )
            )
        ),
        array(
            'type' => 'radio',
            'field' => LandingPage::$field_featured_section_button_action,
            'label' => 'Button Action',
            'choices' => LandingPage::getButtonLinkOptionsForPiklist(),
            'value' => LandingPage::IS_LINK_TO_PAGE,
        ),
        array(
            'type' => 'select',
            'field' => LandingPage::$field_featured_section_button_action_page_to_link_to,
            'label' => 'Button Action Page',
            'columns' => 12,
            'choices' => Helper::getPagesForPiklist(),
            'conditions' => array(
                array(
                    'field' => LandingPage::$field_featured_section_button_action,
                    'value' => LandingPage::IS_LINK_TO_PAGE
                )
            )
        ),
        array(
            'type' => 'text',
            'field' => LandingPage::$field_featured_section_button_action_custom_link,
            'label' => 'Button Action Custom Link',
            'description' => 'ie. http://google.com',
            'columns' => 12,
            'conditions' => array(
                array(
                    'field' => LandingPage::$field_featured_section_button_action,
                    'value' => LandingPage::IS_CUSTOM_LINK
                )
            )
        ),
        array(
            'type' => 'text',
            'field' => LandingPage::$field_featured_section_button_text,
            'label' => 'Button Title',
            'columns' => 12
        )
    )
));

piklist('field', array(
    'type' => 'group',
    'label' => 'Secondary Content Options',
    'description' => '<img src="' . get_template_directory_uri() . '/img/admin/lp-secondary.png" width="150" />',
    'field' => LandingPage::$field_secondary_sections,
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'text',
            'field' => LandingPage::$field_secondary_section_title,
            'label' => 'Title',
            'columns' => 12
        ),
        array(
            'type' => 'textarea',
            'field' => LandingPage::$field_secondary_section_content,
            'label' => 'Content',
            'columns' => 12,
            'attributes' => array(
                'rows' => 6
            )
        ),
        array(
            'type' => 'file',
            'field' => LandingPage::$field_secondary_section_image_attachment_id,
            'label' => 'Image'
        ),
        array(
            'type' => 'radio',
            'field' => LandingPage::$field_secondary_section_has_video,
            'label' => 'Video Options',
            'choices' => LandingPage::getHasVideoOptionsForPiklist(),
            'value' => LandingPage::GALLERY_NO_VIDEO,
        ),
        array(
            'type' => 'file',
            'field' => LandingPage::$field_secondary_section_video_attachment_id,
            'label' => 'Video',
            'conditions' => array(
                array(
                    'field' => LandingPage::$field_secondary_sections . ':' . LandingPage::$field_secondary_section_has_video,
                    'value' => LandingPage::GALLERY_UPLOAD_VIDEO
                )
            )
        ),
        array(
            'type' => 'text',
            'columns' => 12,
            'field' => LandingPage::$field_secondary_section_video_src,
            'label' => 'Video Link (YouTube, Vimeo, etc)',
            'conditions' => array(
                array(
                    'field' => LandingPage::$field_secondary_sections . ':' . LandingPage::$field_secondary_section_has_video,
                    'value' => LandingPage::GALLERY_LINK_VIDEO
                )
            )
        ),
        array(
            'type' => 'radio',
            'field' => LandingPage::$field_secondary_section_button_action,
            'label' => 'Button Action',
            'choices' => LandingPage::getButtonLinkOptionsForPiklist(),
            'value' => LandingPage::IS_LINK_TO_PAGE,
        ),
        array(
            'type' => 'select',
            'field' => LandingPage::$field_secondary_section_button_action_page_to_link_to,
            'label' => 'Button Action Page',
            'columns' => 12,
            'choices' => Helper::getPagesForPiklist(),
            'conditions' => array(
                array(
                    'field' => LandingPage::$field_secondary_sections . ':' . LandingPage::$field_secondary_section_button_action,
                    'value' => LandingPage::IS_LINK_TO_PAGE
                )
            )
        ),
        array(
            'type' => 'text',
            'field' => LandingPage::$field_secondary_section_button_action_custom_link,
            'label' => 'Button Action Custom Link',
            'description' => 'ie. http://google.com',
            'columns' => 12,
            'conditions' => array(
                array(
                    'field' => LandingPage::$field_secondary_sections . ':' . LandingPage::$field_secondary_section_button_action,
                    'value' => LandingPage::IS_CUSTOM_LINK
                )
            )
        ),
        array(
            'type' => 'text',
            'field' => LandingPage::$field_secondary_section_button_text,
            'label' => 'Button Title',
            'columns' => 12
        )
    )
));

piklist('field', array(
    'type' => 'group',
    'label' => 'Horizontal List Content Options',
    'description' => '<img src="' . get_template_directory_uri() . '/img/admin/lp-horizontal-list.png" width="150" />',
    'field' => LandingPage::$field_tertiary_sections,
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'text',
            'field' => LandingPage::$field_tertiary_section_title,
            'label' => 'Title',
            'columns' => 12
        ),
        array(
            'type' => 'textarea',
            'field' => LandingPage::$field_tertiary_section_content,
            'label' => 'Content',
            'columns' => 12,
            'attributes' => array(
                'rows' => 6
            )
        ),
        array(
            'type' => 'file',
            'field' => LandingPage::$field_tertiary_section_image_attachment_id,
            'label' => 'Image'
        ),
        array(
            'type' => 'radio',
            'field' => LandingPage::$field_tertiary_section_has_video,
            'label' => 'Video Options',
            'choices' => LandingPage::getHasVideoOptionsForPiklist(),
            'value' => LandingPage::GALLERY_NO_VIDEO,
        ),
        array(
            'type' => 'file',
            'field' => LandingPage::$field_tertiary_section_video_attachment_id,
            'label' => 'Video',
            'conditions' => array(
                array(
                    'field' => LandingPage::$field_tertiary_sections . ':' . LandingPage::$field_tertiary_section_has_video,
                    'value' => LandingPage::GALLERY_UPLOAD_VIDEO
                )
            )
        ),
        array(
            'type' => 'text',
            'columns' => 12,
            'field' => LandingPage::$field_tertiary_section_video_src,
            'label' => 'Video Link (YouTube, Vimeo, etc)',
            'conditions' => array(
                array(
                    'field' => LandingPage::$field_tertiary_sections . ':' . LandingPage::$field_tertiary_section_has_video,
                    'value' => LandingPage::GALLERY_LINK_VIDEO
                )
            )
        ),
        array(
            'type' => 'radio',
            'field' => LandingPage::$field_tertiary_section_button_action,
            'label' => 'Button Action',
            'choices' => LandingPage::getButtonLinkOptionsForPiklist(),
            'value' => LandingPage::IS_LINK_TO_PAGE,
        ),
        array(
            'type' => 'select',
            'field' => LandingPage::$field_tertiary_section_button_action_page_to_link_to,
            'label' => 'Button Action Page',
            'columns' => 12,
            'choices' => Helper::getPagesForPiklist(),
            'conditions' => array(
                array(
                    'field' => LandingPage::$field_tertiary_sections . ':' . LandingPage::$field_tertiary_section_button_action,
                    'value' => LandingPage::IS_LINK_TO_PAGE
                )
            )
        ),
        array(
            'type' => 'text',
            'field' => LandingPage::$field_tertiary_section_button_action_custom_link,
            'label' => 'Button Action Custom Link',
            'description' => 'ie. http://google.com',
            'columns' => 12,
            'conditions' => array(
                array(
                    'field' => LandingPage::$field_tertiary_sections . ':' . LandingPage::$field_tertiary_section_button_action,
                    'value' => LandingPage::IS_CUSTOM_LINK
                )
            )
        ),
        array(
            'type' => 'text',
            'field' => LandingPage::$field_tertiary_section_button_text,
            'label' => 'Button Title',
            'columns' => 12
        )
    )
));

piklist('field', array(
    'type' => 'group',
    'label' => 'Footer Options',
    'description' => '<img src="' . get_template_directory_uri() . '/img/admin/lp-footer.png" width="150" />',
    'fields' => array(
        array(
            'type' => 'file',
            'field' => LandingPage::$field_footer_section_image_attachment_id,
            'label' => 'Image',
            'columns' => 12
        ),
        array(
            'type' => 'text',
            'field' => LandingPage::$field_footer_section_title,
            'label' => 'Title',
            'columns' => 12
        ),
        array(
            'type' => 'textarea',
            'field' => LandingPage::$field_footer_section_description,
            'label' => 'Content',
            'columns' => 12,
            'attributes' => array(
                'rows' => 6
            )
        ),
        array(
            'type' => 'select',
            'field' => LandingPage::$field_footer_section_gravity_form_id,
            'label' => 'Choose Gravity Form',
            'columns' => 12,
            'choices' => LandingPage::getFormChoicesForPiklist()
        )
    )
));
