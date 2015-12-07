<?php namespace App\Model;

use Carbon\Carbon;

class ContactFooter
{
    public static $field_contact_form_title;
    public static $field_contact_form_gravity_form_id;

    public static $field_featured_items;
    public static $field_featured_image_attachment_id;
    public static $field_featured_has_video;
    public static $field_featured_video_attachment_id;
    public static $field_featured_video_src;
    public static $field_featured_title;
    public static $field_featured_subtitle;
    public static $field_featured_link_action;
    public static $field_featured_link_action_page_to_link_to;
    public static $field_featured_link_action_custom_link;
    public static $field_featured_link_text;

    const IS_LINK_TO_PAGE = 'page';
    const IS_CUSTOM_LINK = 'custom';
    const HAS_VIDEO_AS_ATTACHMENT = 'file';
    const HAS_VIDEO_AS_LINK = 'link';
    const DOES_NOT_HAVE_VIDEO = 'no';

    public static function init()
    {
        self::$field_contact_form_title = Config::getKeyPrefix() . 'contact_form_title';
        self::$field_contact_form_gravity_form_id = Config::getKeyPrefix() . 'contact_form_gravity_form_id';
        self::$field_featured_items = Config::getKeyPrefix() . 'featured_items';
        self::$field_featured_image_attachment_id = Config::getKeyPrefix() . 'featured_image_attachment_id';
        self::$field_featured_has_video = Config::getKeyPrefix() . 'featured_has_video';
        self::$field_featured_video_attachment_id = Config::getKeyPrefix() . 'featured_video_attachment_id';
        self::$field_featured_video_src = Config::getKeyPrefix() . 'featured_video_src';
        self::$field_featured_title = Config::getKeyPrefix() . 'featured_title';
        self::$field_featured_subtitle = Config::getKeyPrefix() . 'featured_subtitle';
        self::$field_featured_link_action = Config::getKeyPrefix() . 'featured_link_action';
        self::$field_featured_link_action_page_to_link_to = Config::getKeyPrefix() . 'featured_link_action_page_to_link_to';
        self::$field_featured_link_action_custom_link = Config::getKeyPrefix() . 'featured_link_action_custom_link';
        self::$field_featured_link_text = Config::getKeyPrefix() . 'featured_link_text';

        add_action('piklist_admin_pages', array(get_class(), '_registerSettingsPage'), 10, 1);
    }

    public static function _registerSettingsPage($pages)
    {
        $pages[] = array(
            'page_title' => __('Contact Footer', Config::getTextDomain()),
            'menu_title' => __('Contact Footer', Config::getTextDomain()),
            'capability' => 'manage_content',
            'menu_slug' => 'contact-footer-options',
            'setting' => 'app_contact_footer_options',
            'save_text' => 'Save settings'
        );

        return $pages;
    }

    public static function getFormChoicesForPiklist()
    {
        $forms = array();
        $gravity_forms = \GFAPI::get_forms();

        $forms[0] = '-- Choose Gravity Form --';

        foreach ($gravity_forms as $gravity_form) {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $gravity_form['date_created']);
            $forms[$gravity_form['id']] = $gravity_form['title'] . ' (' . $date->format('M j, Y') . ')';
        }

        return $forms;
    }

    public static function getVideoOptionsForPiklist()
    {
        return array(
            self::HAS_VIDEO_AS_ATTACHMENT => 'Yes, I will upload a video.',
            self::HAS_VIDEO_AS_LINK => 'Yes, I will enter a link to the video',
            self::DOES_NOT_HAVE_VIDEO => 'No'
        );
    }

    public static function getChildPageButtonLinkOptionsForPiklist()
    {
        return array(
            self::IS_LINK_TO_PAGE => 'Link to Internal Page',
            self::IS_CUSTOM_LINK => 'Custom Link'
        );
    }
}