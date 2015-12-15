<?php namespace App\Model;

class Page extends \TimberPost
{

    public static $field_headline;
    public static $field_gallery_image_attachment_ids;

    public static $field_use_custom_quicklinks;

    public static $field_quicklinks_group;
    public static $field_quicklinks_group_item_one_image_attachment_id;
    public static $field_quicklinks_group_item_one_title;
    public static $field_quicklinks_group_item_one_subtitle;
    public static $field_quicklinks_group_item_one_action;
    public static $field_quicklinks_group_item_one_action_page_to_link_to;
    public static $field_quicklinks_group_item_one_action_custom_link;

    public static $field_quicklinks_group_item_two_image_attachment_id;
    public static $field_quicklinks_group_item_two_title;
    public static $field_quicklinks_group_item_two_subtitle;
    public static $field_quicklinks_group_item_two_action;
    public static $field_quicklinks_group_item_two_action_page_to_link_to;
    public static $field_quicklinks_group_item_two_action_custom_link;

    public static $field_quicklinks_group_item_three_image_attachment_id;
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
        self::$field_gallery_image_attachment_ids = Config::getKeyPrefix() . 'gallery_image_attachment_ids';

        self::$field_use_custom_quicklinks = Config::getKeyPrefix() . 'use_custom_quicklinks';

        self::$field_quicklinks_group = Config::getKeyPrefix() . 'quicklinks_group';
        self::$field_quicklinks_group_item_one_image_attachment_id = Config::getKeyPrefix() . 'quicklinks_group_item_one_image_attachment_id';
        self::$field_quicklinks_group_item_one_title = Config::getKeyPrefix() . 'quicklinks_group_item_one_title';
        self::$field_quicklinks_group_item_one_subtitle = Config::getKeyPrefix() . 'quicklinks_group_item_one_subtitle';
        self::$field_quicklinks_group_item_one_action = Config::getKeyPrefix() . 'quicklinks_group_item_one_action';
        self::$field_quicklinks_group_item_one_action_page_to_link_to = Config::getKeyPrefix() . 'quicklinks_group_item_one_action_page_to_link_to';
        self::$field_quicklinks_group_item_one_action_custom_link = Config::getKeyPrefix() . 'quicklinks_group_item_one_action_custom_link';

        self::$field_quicklinks_group_item_two_image_attachment_id = Config::getKeyPrefix() . 'quicklinks_group_item_two_image_attachment_id';
        self::$field_quicklinks_group_item_two_title = Config::getKeyPrefix() . 'quicklinks_group_item_two_title';
        self::$field_quicklinks_group_item_two_subtitle = Config::getKeyPrefix() . 'quicklinks_group_item_two_subtitle';
        self::$field_quicklinks_group_item_two_action = Config::getKeyPrefix() . 'quicklinks_group_item_two_action';
        self::$field_quicklinks_group_item_two_action_page_to_link_to = Config::getKeyPrefix() . 'quicklinks_group_item_two_action_page_to_link_to';
        self::$field_quicklinks_group_item_two_action_custom_link = Config::getKeyPrefix() . 'quicklinks_group_item_two_action_custom_link';

        self::$field_quicklinks_group_item_three_image_attachment_id = Config::getKeyPrefix() . 'quicklinks_group_item_three_image_attachment_id';
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

    public function getHeadline()
    {
        $field = self::$field_headline;
        return $this->$field;
    }

    public function getQuicklinkBoxes()
    {
        $field = self::$field_use_custom_quicklinks;

        if (self::DO_NOT_USE_CUSTOM_QUICKLINK === $this->$field) {
            return array();
        }

        $field = self::$field_quicklinks_group;
        $quick_link_group = $this->$field;

        $box_one = Helper::getContentAsArrayFromPiklist($quick_link_group, array(
            'title' => self::$field_quicklinks_group_item_one_title,
            'sub_title' => self::$field_quicklinks_group_item_one_subtitle,
            'image_attachment_id' => self::$field_quicklinks_group_item_one_image_attachment_id,
            'link_action' => self::$field_quicklinks_group_item_one_action,
            'link_action_page_id' => self::$field_quicklinks_group_item_one_action_page_to_link_to,
            'link_action_custom_link' => self::$field_quicklinks_group_item_one_action_custom_link
        ));

        $box_two = Helper::getContentAsArrayFromPiklist($quick_link_group, array(
            'title' => self::$field_quicklinks_group_item_two_title,
            'sub_title' => self::$field_quicklinks_group_item_two_subtitle,
            'image_attachment_id' => self::$field_quicklinks_group_item_two_image_attachment_id,
            'link_action' => self::$field_quicklinks_group_item_two_action,
            'link_action_page_id' => self::$field_quicklinks_group_item_two_action_page_to_link_to,
            'link_action_custom_link' => self::$field_quicklinks_group_item_two_action_custom_link
        ));

        $box_three = Helper::getContentAsArrayFromPiklist($quick_link_group, array(
            'title' => self::$field_quicklinks_group_item_three_title,
            'sub_title' => self::$field_quicklinks_group_item_three_subtitle,
            'image_attachment_id' => self::$field_quicklinks_group_item_three_image_attachment_id,
            'link_action' => self::$field_quicklinks_group_item_three_action,
            'link_action_page_id' => self::$field_quicklinks_group_item_three_action_page_to_link_to,
            'link_action_custom_link' => self::$field_quicklinks_group_item_three_action_custom_link
        ));

        return array_merge($box_one, $box_two, $box_three);
    }

    public function getGalleryImages()
    {
        $field = self::$field_gallery_image_attachment_ids;
        $attachment_ids = $this->$field;

        $gallery = array();
        if (false !== $attachment_ids) {
            if (false === is_array($attachment_ids) && ($attachment_ids === 'undefined' || $attachment_ids === '')) {
                return $gallery;
            }

            if (false === is_array($attachment_ids)) {
                $attachment_ids = array($attachment_ids);
            }

            foreach ($attachment_ids as $attachment_id) {
                $image = new \TimberImage($attachment_id);
                $gallery[] = $image;
            }
        }

        return $gallery;
    }

    public function getHeroColor()
    {
        $color = 'red';

        $posts = get_post_ancestors($this->id);
        $posts[] = $this->id;

        // Location
        if (true === in_array(11, $posts)) {
            $color = 'green';
        }

        // News/Events
        if (true === in_array(13, $posts)) {
            $color = 'orange';
        }

        return $color;
    }

}