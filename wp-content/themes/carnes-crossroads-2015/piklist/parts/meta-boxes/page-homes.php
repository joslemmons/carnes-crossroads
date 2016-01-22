<?php
/*
 * Title: Homes Page Options (Carnes Crossroads 2015 Theme)
 * id: 5
 */

use App\Model\HomesPage;

piklist('field', array(
    'field' => HomesPage::$field_hero,
    'type' => 'group',
    'label' => 'Hero Information',
    'description' => '<img src="' . get_template_directory_uri() . '/img/admin/homes-headline.png' . '" width="150" />',
    'fields' => array(
        array(
            'type' => 'text',
            'field' => HomesPage::$field_hero_headline,
            'label' => 'Headline',
            'columns' => 12
        ),
        array(
            'type' => 'group',
            'field' => HomesPage::$field_hero_quicklinks,
            'label' => 'Quicklinks',
            'add_more' => true,
            'fields' => array(
                array(
                    'type' => 'text',
                    'field' => HomesPage::$field_hero_quicklink_title,
                    'label' => 'Title',
                    'columns' => 12
                ),
                array(
                    'type' => 'text',
                    'field' => HomesPage::$field_hero_quicklink_link,
                    'label' => 'Link',
                    'columns' => 12
                )
            )
        )
    )
));

