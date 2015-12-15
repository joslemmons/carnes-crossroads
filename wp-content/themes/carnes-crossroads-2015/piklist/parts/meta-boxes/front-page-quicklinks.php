<?php
/*
 * Title: Featured Horizontal Slider (Carnes Crossroads 2015 Theme)
 * ID: 23
 * collapse: false
 * order: 2
 */

use App\Model\FrontPage;
use App\Model\Helper;

piklist('field', array(
    'type' => 'group',
    'add_more' => true,
    'field' => FrontPage::$field_horizontal_slider,
    'label' => 'Slides',
    'description' => '<img width="170" src="' . get_template_directory_uri() . '/img/admin/front-page-horizontal-content.png' . '" />',
    'fields' => array(
        array(
            'type' => 'file',
            'label' => 'Image',
            'field' => FrontPage::$field_horizontal_slider_image_attachment_id,
            'columns' => 12
        ),
        array(
            'type' => 'text',
            'label' => 'Title',
            'field' => FrontPage::$field_horizontal_slider_title,
            'columns' => 12
        ),
        array(
            'type' => 'text',
            'label' => 'Subtitle',
            'field' => FrontPage::$field_horizontal_slider_subtitle,
            'columns' => 12
        ),
        array(
            'type' => 'radio',
            'field' => FrontPage::$field_horizontal_slider_link_action,
            'label' => 'Link Action',
            'choices' => FrontPage::getChildPageButtonLinkOptionsForPiklist(),
            'value' => FrontPage::IS_LINK_TO_PAGE,
            'columns' => 12
        ),
        array(
            'type' => 'select',
            'field' => FrontPage::$field_horizontal_slider_link_action_page_to_link_to,
            'label' => 'Link to Internal Page',
            'columns' => 12,
            'choices' => Helper::getPagesForPiklist(),
            'conditions' => array(
                array(
                    'field' => FrontPage::$field_horizontal_slider . ':' . FrontPage::$field_horizontal_slider_link_action,
                    'value' => FrontPage::IS_LINK_TO_PAGE
                )
            )
        ),
        array(
            'type' => 'text',
            'field' => FrontPage::$field_horizontal_slider_link_action_custom_link,
            'label' => 'Custom Link',
            'description' => 'ie. http://google.com',
            'columns' => 12,
            'conditions' => array(
                array(
                    'field' => FrontPage::$field_horizontal_slider . ':' . FrontPage::$field_horizontal_slider_link_action,
                    'value' => FrontPage::IS_CUSTOM_LINK
                )
            )
        )
    )
));
