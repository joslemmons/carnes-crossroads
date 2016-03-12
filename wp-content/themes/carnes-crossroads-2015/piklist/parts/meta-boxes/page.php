<?php
/*
 * Title: Page Options (Carnes Crossroads 2015 Theme)
 * Post Type: page
 * new: false
 */

global $post;

use App\Model\AccountPage;
use App\Model\FAQPage;
use App\Model\FrontPage;
use App\Model\Page;

$post = Timber::get_post($post->ID);

if (
    $post && $post->id !== FrontPage::PAGE_ID &&
    $post->id !== FAQPage::PAGE_ID &&
    $post->id !== AccountPage::PAGE_ID
) {
    if (
        false === $post->parent()
    ) {
        // this is a primary page

    piklist('field', array(
        'type' => 'textarea',
        'field' => Page::$field_headline,
        'label' => 'Headline',
        'columns' => 12,
        'attributes' => array(
            'rows' => 3
        )
    ));

        if ($post->id !== Page::BUILDER_PAGE_ID) {
//        piklist('field', array(
//            'type' => 'group',
//            'field' => Page::$field_quicklinks_group,
//            'label' => 'Quicklinks',
//            'description' => 'Three boxes at the bottom of the page. By default the quicklinks boxes are "community map", "plan your visit", and "upcoming events".<br /><img width="175" src="' . get_template_directory_uri() . '/img/admin/quicklinks.png' . '" />',
//            'fields' => array(
//                array(
//                    'type' => 'radio',
//                    'field' => Page::$field_use_custom_quicklinks,
//                    'label' => 'Use Custom Quicklink Boxes?',
//                    'choices' => Page::getUseCustomQuicklinksOptionsForPiklist(),
//                    'value' => Page::USE_DEFAULT_QUICKLINKS
//                ),
//                array(
//                    'type' => 'file',
//                    'field' => Page::$field_quicklinks_group_item_one_image_attachment_id,
//                    'label' => 'Box One Image',
//                    'conditions' => array(
//                        array(
//                            'field' => Page::$field_quicklinks_group . ':' . Page::$field_use_custom_quicklinks,
//                            'value' => Page::USE_CUSTOM_QUICKLINKS
//                        )
//                    ),
//                    'columns' => 12
//                ),
//                array(
//                    'type' => 'text',
//                    'field' => Page::$field_quicklinks_group_item_one_title,
//                    'label' => 'Box One Title',
//                    'columns' => 12,
//                    'conditions' => array(
//                        array(
//                            'field' => Page::$field_quicklinks_group . ':' . Page::$field_use_custom_quicklinks,
//                            'value' => Page::USE_CUSTOM_QUICKLINKS
//                        )
//                    )
//                ),
//                array(
//                    'type' => 'text',
//                    'field' => Page::$field_quicklinks_group_item_one_subtitle,
//                    'label' => 'Box One Sub Title',
//                    'columns' => 12,
//                    'conditions' => array(
//                        array(
//                            'field' => Page::$field_quicklinks_group . ':' . Page::$field_use_custom_quicklinks,
//                            'value' => Page::USE_CUSTOM_QUICKLINKS
//                        )
//                    )
//                ),
//                array(
//                    'type' => 'radio',
//                    'field' => Page::$field_quicklinks_group_item_one_action,
//                    'label' => 'Box One Button Action',
//                    'choices' => Page::getChildPageButtonLinkOptionsForPiklist(),
//                    'value' => Page::IS_LINK_TO_PAGE,
//                    'conditions' => array(
//                        array(
//                            'field' => Page::$field_quicklinks_group . ':' . Page::$field_use_custom_quicklinks,
//                            'value' => Page::USE_CUSTOM_QUICKLINKS
//                        )
//                    )
//                ),
//                array(
//                    'type' => 'select',
//                    'field' => Page::$field_quicklinks_group_item_one_action_page_to_link_to,
//                    'label' => 'Link to Internal Page',
//                    'columns' => 12,
//                    'choices' => Helper::getPagesForPiklist(),
//                    'conditions' => array(
//                        array(
//                            'field' => Page::$field_quicklinks_group . ':' . Page::$field_quicklinks_group_item_one_action,
//                            'value' => Page::IS_LINK_TO_PAGE
//                        ),
//                        array(
//                            'field' => Page::$field_quicklinks_group . ':' . Page::$field_use_custom_quicklinks,
//                            'value' => Page::USE_CUSTOM_QUICKLINKS
//                        )
//                    )
//                ),
//                array(
//                    'type' => 'text',
//                    'field' => Page::$field_quicklinks_group_item_one_action_custom_link,
//                    'label' => 'Custom Link',
//                    'description' => 'ie. http://google.com',
//                    'columns' => 12,
//                    'conditions' => array(
//                        array(
//                            'field' => Page::$field_quicklinks_group . ':' . Page::$field_quicklinks_group_item_one_action,
//                            'value' => Page::IS_CUSTOM_LINK
//                        ),
//                        array(
//                            'field' => Page::$field_quicklinks_group . ':' . Page::$field_use_custom_quicklinks,
//                            'value' => Page::USE_CUSTOM_QUICKLINKS
//                        )
//                    )
//                ),
//                array(
//                    'type' => 'file',
//                    'field' => Page::$field_quicklinks_group_item_two_image_attachment_id,
//                    'label' => 'Box Two Image',
//                    'conditions' => array(
//                        array(
//                            'field' => Page::$field_quicklinks_group . ':' . Page::$field_use_custom_quicklinks,
//                            'value' => Page::USE_CUSTOM_QUICKLINKS
//                        )
//                    ),
//                    'columns' => 12
//                ),
//                array(
//                    'type' => 'text',
//                    'field' => Page::$field_quicklinks_group_item_two_title,
//                    'label' => 'Box Two Title',
//                    'columns' => 12,
//                    'conditions' => array(
//                        array(
//                            'field' => Page::$field_quicklinks_group . ':' . Page::$field_use_custom_quicklinks,
//                            'value' => Page::USE_CUSTOM_QUICKLINKS
//                        )
//                    )
//                ),
//                array(
//                    'type' => 'text',
//                    'field' => Page::$field_quicklinks_group_item_two_subtitle,
//                    'label' => 'Box Two Sub Title',
//                    'columns' => 12,
//                    'conditions' => array(
//                        array(
//                            'field' => Page::$field_quicklinks_group . ':' . Page::$field_use_custom_quicklinks,
//                            'value' => Page::USE_CUSTOM_QUICKLINKS
//                        )
//                    )
//                ),
//                array(
//                    'type' => 'radio',
//                    'field' => Page::$field_quicklinks_group_item_two_action,
//                    'label' => 'Box Two Button Action',
//                    'choices' => Page::getChildPageButtonLinkOptionsForPiklist(),
//                    'value' => Page::IS_LINK_TO_PAGE,
//                    'conditions' => array(
//                        array(
//                            'field' => Page::$field_quicklinks_group . ':' . Page::$field_use_custom_quicklinks,
//                            'value' => Page::USE_CUSTOM_QUICKLINKS
//                        )
//                    )
//                ),
//                array(
//                    'type' => 'select',
//                    'field' => Page::$field_quicklinks_group_item_two_action_page_to_link_to,
//                    'label' => 'Link to Internal Page',
//                    'columns' => 12,
//                    'choices' => Helper::getPagesForPiklist(),
//                    'conditions' => array(
//                        array(
//                            'field' => Page::$field_quicklinks_group . ':' . Page::$field_quicklinks_group_item_two_action,
//                            'value' => Page::IS_LINK_TO_PAGE
//                        ),
//                        array(
//                            'field' => Page::$field_quicklinks_group . ':' . Page::$field_use_custom_quicklinks,
//                            'value' => Page::USE_CUSTOM_QUICKLINKS
//                        )
//                    )
//                ),
//                array(
//                    'type' => 'text',
//                    'field' => Page::$field_quicklinks_group_item_two_action_custom_link,
//                    'label' => 'Custom Link',
//                    'description' => 'ie. http://google.com',
//                    'columns' => 12,
//                    'conditions' => array(
//                        array(
//                            'field' => Page::$field_quicklinks_group . ':' . Page::$field_quicklinks_group_item_two_action,
//                            'value' => Page::IS_CUSTOM_LINK
//                        ),
//                        array(
//                            'field' => Page::$field_quicklinks_group . ':' . Page::$field_use_custom_quicklinks,
//                            'value' => Page::USE_CUSTOM_QUICKLINKS
//                        )
//                    )
//                ),
//                array(
//                    'type' => 'file',
//                    'field' => Page::$field_quicklinks_group_item_three_image_attachment_id,
//                    'label' => 'Box Three Image',
//                    'conditions' => array(
//                        array(
//                            'field' => Page::$field_quicklinks_group . ':' . Page::$field_use_custom_quicklinks,
//                            'value' => Page::USE_CUSTOM_QUICKLINKS
//                        )
//                    ),
//                    'columns' => 12
//                ),
//                array(
//                    'type' => 'text',
//                    'field' => Page::$field_quicklinks_group_item_three_title,
//                    'label' => 'Box Three Title',
//                    'columns' => 12,
//                    'conditions' => array(
//                        array(
//                            'field' => Page::$field_quicklinks_group . ':' . Page::$field_use_custom_quicklinks,
//                            'value' => Page::USE_CUSTOM_QUICKLINKS
//                        )
//                    )
//                ),
//                array(
//                    'type' => 'text',
//                    'field' => Page::$field_quicklinks_group_item_three_subtitle,
//                    'label' => 'Box Three Sub Title',
//                    'columns' => 12,
//                    'conditions' => array(
//                        array(
//                            'field' => Page::$field_quicklinks_group . ':' . Page::$field_use_custom_quicklinks,
//                            'value' => Page::USE_CUSTOM_QUICKLINKS
//                        )
//                    )
//                ),
//                array(
//                    'type' => 'radio',
//                    'field' => Page::$field_quicklinks_group_item_three_action,
//                    'label' => 'Box Three Button Action',
//                    'choices' => Page::getChildPageButtonLinkOptionsForPiklist(),
//                    'value' => Page::IS_LINK_TO_PAGE,
//                    'conditions' => array(
//                        array(
//                            'field' => Page::$field_quicklinks_group . ':' . Page::$field_use_custom_quicklinks,
//                            'value' => Page::USE_CUSTOM_QUICKLINKS
//                        )
//                    )
//                ),
//                array(
//                    'type' => 'select',
//                    'field' => Page::$field_quicklinks_group_item_three_action_page_to_link_to,
//                    'label' => 'Link to Internal Page',
//                    'columns' => 12,
//                    'choices' => Helper::getPagesForPiklist(),
//                    'conditions' => array(
//                        array(
//                            'field' => Page::$field_quicklinks_group . ':' . Page::$field_quicklinks_group_item_three_action,
//                            'value' => Page::IS_LINK_TO_PAGE
//                        ),
//                        array(
//                            'field' => Page::$field_quicklinks_group . ':' . Page::$field_use_custom_quicklinks,
//                            'value' => Page::USE_CUSTOM_QUICKLINKS
//                        )
//                    )
//                ),
//                array(
//                    'type' => 'text',
//                    'field' => Page::$field_quicklinks_group_item_three_action_custom_link,
//                    'label' => 'Custom Link',
//                    'description' => 'ie. http://google.com',
//                    'columns' => 12,
//                    'conditions' => array(
//                        array(
//                            'field' => Page::$field_quicklinks_group . ':' . Page::$field_quicklinks_group_item_three_action,
//                            'value' => Page::IS_CUSTOM_LINK
//                        ),
//                        array(
//                            'field' => Page::$field_quicklinks_group . ':' . Page::$field_use_custom_quicklinks,
//                            'value' => Page::USE_CUSTOM_QUICKLINKS
//                        )
//                    )
//                )
//            )
//        ));
        }

        $children = $post->children('page');
        if (false === $post->parent() && false === empty($children)) {
            $child_pages_html = '<ul>';

            foreach ($children as $child) {
                $child_pages_html .= '<li><a href="' . get_edit_post_link($child->id) . '">' . $child->title() . '</a></li>';
            }
            $child_pages_html .= '</ul>';

            piklist('field', array(
                'type' => 'html',
                'field' => '_',
                'label' => 'Child Pages',
                'value' => $child_pages_html
            ));
        }
    } else {
        // this is a child page

        piklist('field', array(
            'type' => 'file',
            'field' => Page::$field_gallery_image_attachment_ids,
            'label' => 'Gallery'
        ));

        piklist('field', array(
            'type' => 'radio',
            'field' => Page::$field_show_button,
            'value' => Page::DO_NOT_SHOW_BUTTON,
            'label' => 'Always show button link to learn more?',
            'description' => 'If the content is >850 characters, then the learn more button is will be shown no matter the choice here',
            'choices' => Page::getButtonChoicesForPiklist()
        ));

//        piklist('field', array(
//            'type' => 'radio',
//            'field' => Page::$field_button_action,
//            'label' => 'Link Action',
//            'choices' => Page::getChildPageButtonLinkOptionsForPiklist(),
//            'value' => Page::IS_LINK_TO_PAGE,
//            'conditions' => array(
//                array(
//                    'field' => Page::$field_show_button,
//                    'value' => Page::SHOW_BUTTON
//                )
//            )
//        ));
//
//        piklist('field', array(
//            'type' => 'select',
//            'field' => Page::$field_button_page_to_link_to,
//            'label' => 'Link to Internal Page',
//            'columns' => 12,
//            'choices' => Helper::getPagesForPiklist(),
//            'conditions' => array(
//                array(
//                    'field' => Page::$field_show_button,
//                    'value' => Page::SHOW_BUTTON
//                ),
//                array(
//                    'field' => Page::$field_button_action,
//                    'value' => Page::IS_LINK_TO_PAGE
//                )
//            )
//        ));
//
//        piklist('field', array(
//            'type' => 'text',
//            'field' => Page::$field_button_custom_link,
//            'label' => 'Custom Link',
//            'description' => 'ie. http://google.com',
//            'columns' => 12,
//            'conditions' => array(
//                array(
//                    'field' => Page::$field_show_button,
//                    'value' => Page::SHOW_BUTTON
//                ),
//                array(
//                    'field' => Page::$field_button_action,
//                    'value' => Page::IS_CUSTOM_LINK
//                )
//            )
//        ));
//
//        piklist('field', array(
//            'type' => 'text',
//            'field' => Page::$field_button_text,
//            'label' => 'Button Text',
//            'columns' => 12,
//            'conditions' => array(
//                array(
//                    'field' => Page::$field_show_button,
//                    'value' => Page::SHOW_BUTTON
//                )
//            )
//        ));
    }

}
else {
    echo "<p>Intentionally left blank</p>";
}


