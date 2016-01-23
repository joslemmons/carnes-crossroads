<?php namespace App\Model;

class Page extends \TimberPost
{

    const BUILDER_PAGE_ID = 38;

    public static $field_headline;
    public static $field_gallery_image_attachment_ids;
    public static $field_show_button;
    public static $field_button_action;
    public static $field_button_page_to_link_to;
    public static $field_button_custom_link;
    public static $field_button_text;

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

    private static $_agents = null;

    const IS_LINK_TO_PAGE = 'page';
    const IS_CUSTOM_LINK = 'custom';
    const USE_CUSTOM_QUICKLINKS = 'true';
    const DO_NOT_USE_CUSTOM_QUICKLINK = 'false';
    const USE_DEFAULT_QUICKLINKS = 'default';
    const SHOW_BUTTON = 'true';
    const DO_NOT_SHOW_BUTTON = 'false';

    public static function bootstrap()
    {
        self::$field_headline = Config::getKeyPrefix() . 'headline';
        self::$field_gallery_image_attachment_ids = Config::getKeyPrefix() . 'gallery_image_attachment_ids';
        self::$field_show_button = Config::getKeyPrefix() . 'show_button';
        self::$field_button_action = Config::getKeyPrefix() . 'button_action';
        self::$field_button_page_to_link_to = Config::getKeyPrefix() . 'button_page_to_link_to';
        self::$field_button_custom_link = Config::getKeyPrefix() . 'button_custom_link';
        self::$field_button_text = Config::getKeyPrefix() . 'button_text';

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
            self::USE_DEFAULT_QUICKLINKS => 'Yes, use default',
            self::USE_CUSTOM_QUICKLINKS => 'Yes, use custom',
            self::DO_NOT_USE_CUSTOM_QUICKLINK => 'No',
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
        $field = self::$field_quicklinks_group;
        $quick_link_group = $this->$field;

        if (false === $quick_link_group) {
            $quick_link_choice = self::USE_DEFAULT_QUICKLINKS;
        } else {
            $quick_link_choice = array_pop($quick_link_group[self::$field_use_custom_quicklinks]);
            if (is_array($quick_link_choice)) {
                $quick_link_choice = array_pop($quick_link_choice);
            }
        }

        switch (true) {
            case (self::USE_DEFAULT_QUICKLINKS === $quick_link_choice):
                $box_one = array(array(
                    'title' => 'Community Map',
                    'subtitle' => 'See the Bigger Picture',
                    'image' => array(
                        'get_src' => get_template_directory_uri() . '/img/bg-button-map.jpg'
                    ),
                    'button' => array(
                        'link' => '/map/'
                    )
                ));

                $box_two = array(array(
                    'title' => 'Plan your Visit',
                    'subtitle' => 'Contact us today',
                    'image' => array(
                        'get_src' => get_template_directory_uri() . '/img/bg-button-visit.jpg'
                    ),
                    'button' => array(
                        'link' => '/contact/'
                    )
                ));

                $box_three = array(array(
                    'title' => 'Upcoming Events',
                    'subtitle' => 'Concerts, Classes & More',
                    'image' => array(
                        'get_src' => get_template_directory_uri() . '/img/bg-button-events.jpg'
                    ),
                    'button' => array(
                        'link' => '/events/'
                    )
                ));
                return array_merge($box_one, $box_two, $box_three);
            case (self::USE_CUSTOM_QUICKLINKS === $quick_link_choice):
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
            case (self::DO_NOT_USE_CUSTOM_QUICKLINK === $quick_link_choice):
            default:
                return array();
        }
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

    public static function getButtonChoicesForPiklist()
    {
        return array(
            Page::SHOW_BUTTON => 'Yes',
            Page::DO_NOT_SHOW_BUTTON => 'No'
        );
    }

    public function getButton()
    {
        $field = self::$field_show_button;
        $show_field = $this->$field;

        $button = array();
        if (self::SHOW_BUTTON === $show_field) {
            $field_link_action = self::$field_button_action;
            $link_action = $this->$field_link_action;

            if (self::IS_LINK_TO_PAGE === $link_action) {
                $field_link_to_page_id = self::$field_button_page_to_link_to;
                $link_to_page_id = $this->$field_link_to_page_id;
                $button['link'] = get_page_link($link_to_page_id);
            } else {
                $field_link_to_custom = self::$field_button_custom_link;
                $custom_link = $this->$field_link_to_custom;
                $button['link'] = $custom_link;
            }

            $field_button_text = self::$field_button_text;
            $button_text = $this->$field_button_text;

            $button['title'] = 'More Info';
            if ('' !== trim($button_text)) {
                $button['title'] = $button_text;
            }
        }

        return $button;
    }

    public static function getBreadcrumbs()
    {
        if (function_exists('yoast_breadcrumb')) {
            return yoast_breadcrumb('', '', false);
        }

        return '';
    }

    /**
     * @return array
     */
    public function getChildren()
    {
        switch ($this->slug) {
            case ('sales-team') :
                // only do this once. we want the menu to line up with the content
                if (self::$_agents === null) {
                    $agents = RealEstateAgent::all();
                    shuffle($agents);
                    self::$_agents = $agents;
                }

                return self::$_agents;
            default:
                return $this->children('page', '\App\Model\Page');
        }
    }

}