<?php namespace App\Model;

class Page extends \TimberPost
{

    public static $field_headline;

    public static $field_use_custom_quicklinks;

    public static $field_quicklinks_group;
    public static $field_quicklinks_group_item_one_title;
    public static $field_quicklinks_group_item_one_subtitle;
    public static $field_quicklinks_group_item_one_action;
    public static $field_quicklinks_group_item_one_action_page_to_link_to;
    public static $field_quicklinks_group_item_one_action_custom_link;

    public static $field_quicklinks_group_item_two_title;
    public static $field_quicklinks_group_item_two_subtitle;
    public static $field_quicklinks_group_item_two_action;
    public static $field_quicklinks_group_item_two_action_page_to_link_to;
    public static $field_quicklinks_group_item_two_action_custom_link;

    public static $field_quicklinks_group_item_three_title;
    public static $field_quicklinks_group_item_three_subtitle;
    public static $field_quicklinks_group_item_three_action;
    public static $field_quicklinks_group_item_three_action_page_to_link_to;
    public static $field_quicklinks_group_item_three_action_custom_link;

    const IS_LINK_TO_PAGE = 'page';
    const IS_CUSTOM_LINK = 'custom';
    const USE_CUSTOM_QUICKLINKS = 'true';
    CONST DO_NOT_USE_CUSTOM_QUICKLINK = 'false';

    public static function bootstrap()
    {
        self::$field_headline = Config::getKeyPrefix() . 'headline';

        self::$field_use_custom_quicklinks = Config::getKeyPrefix() . 'use_custom_quicklinks';

        self::$field_quicklinks_group = Config::getKeyPrefix() . 'quicklinks_group';
        self::$field_quicklinks_group_item_one_title = Config::getKeyPrefix() . 'quicklinks_group_item_one_title';
        self::$field_quicklinks_group_item_one_subtitle = Config::getKeyPrefix() . 'quicklinks_group_item_one_subtitle';
        self::$field_quicklinks_group_item_one_action = Config::getKeyPrefix() . 'quicklinks_group_item_one_action';
        self::$field_quicklinks_group_item_one_action_page_to_link_to = Config::getKeyPrefix() . 'quicklinks_group_item_one_action_page_to_link_to';
        self::$field_quicklinks_group_item_one_action_custom_link = Config::getKeyPrefix() . 'quicklinks_group_item_one_action_custom_link';

        self::$field_quicklinks_group_item_two_title = Config::getKeyPrefix() . 'quicklinks_group_item_two_title';
        self::$field_quicklinks_group_item_two_subtitle = Config::getKeyPrefix() . 'quicklinks_group_item_two_subtitle';
        self::$field_quicklinks_group_item_two_action = Config::getKeyPrefix() . 'quicklinks_group_item_two_action';
        self::$field_quicklinks_group_item_two_action_page_to_link_to = Config::getKeyPrefix() . 'quicklinks_group_item_two_action_page_to_link_to';
        self::$field_quicklinks_group_item_two_action_custom_link = Config::getKeyPrefix() . 'quicklinks_group_item_two_action_custom_link';

        self::$field_quicklinks_group_item_three_title = Config::getKeyPrefix() . 'quicklinks_group_item_three_title';
        self::$field_quicklinks_group_item_three_subtitle = Config::getKeyPrefix() . 'quicklinks_group_item_three_subtitle';
        self::$field_quicklinks_group_item_three_action = Config::getKeyPrefix() . 'quicklinks_group_item_three_action';
        self::$field_quicklinks_group_item_three_action_page_to_link_to = Config::getKeyPrefix() . 'quicklinks_group_item_three_action_page_to_link_to';
        self::$field_quicklinks_group_item_three_action_custom_link = Config::getKeyPrefix() . 'quicklinks_group_item_three_action_custom_link';
    }

    public static function getUseCustomQuicklinksOptionsForPiklist()
    {
        return array(
            self::USE_CUSTOM_QUICKLINKS => 'Yes',
            self::DO_NOT_USE_CUSTOM_QUICKLINK => 'No'
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