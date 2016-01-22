<?php
/*
 * Title: Front Page Hero Options (Carnes Crossroads 2015 Theme)
 * id: 23
 */

use App\Model\FrontPage;

piklist('field', array(
    'field' => FrontPage::$field_hero,
    'type' => 'group',
    'label' => 'Hero Information',
    'description' => '<img src="' . get_template_directory_uri() . '/img/admin/front-page-hero.png' . '" width="150" />',
    'fields' => array(
        array(
            'type' => 'text',
            'field' => FrontPage::$field_hero_headline,
            'label' => 'Headline',
            'columns' => 12
        ),
        array(
            'type' => 'group',
            'field' => FrontPage::$field_hero_quicklinks,
            'label' => 'Quicklinks',
            'add_more' => true,
            'fields' => array(
                array(
                    'type' => 'text',
                    'field' => FrontPage::$field_hero_quicklink_title,
                    'label' => 'Title',
                    'columns' => 12
                ),
                array(
                    'type' => 'text',
                    'field' => FrontPage::$field_hero_quicklink_link,
                    'label' => 'Link',
                    'columns' => 12
                )
            )
        ),
        array(
            'type' => 'text',
            'field' => FrontPage::$field_hero_bottom_text,
            'label' => 'Bottom Link Text',
            'columns' => 12
        ),
        array(
            'type' => 'text',
            'field' => FrontPage::$field_hero_bottom_link,
            'label' => 'Bottom Link',
            'columns' => 12
        )
    )
));