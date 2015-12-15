<?php namespace App\Model;

class FrontPage extends \TimberPost
{
    // top featured
    public static $field_top_featured_group_primary_items;
    public static $field_top_featured_group_primary_title;
    public static $field_top_featured_group_primary_image_attachment_id;
    public static $field_top_featured_group_primary_has_video;
    public static $field_top_featured_group_primary_video_attachment_id;
    public static $field_top_featured_group_primary_video_src;
    public static $field_top_featured_group_primary_subtitle;
    public static $field_top_featured_group_primary_link_text;
    public static $field_top_featured_group_primary_link_action;
    public static $field_top_featured_group_primary_link_action_page_to_link_to;
    public static $field_top_featured_group_primary_link_action_custom_link;

    public static $field_top_featured_group_secondary_items;
    public static $field_top_featured_group_secondary_title;
    public static $field_top_featured_group_secondary_image_attachment_id;
    public static $field_top_featured_group_secondary_has_video;
    public static $field_top_featured_group_secondary_video_attachment_id;
    public static $field_top_featured_group_secondary_video_src;
    public static $field_top_featured_group_secondary_subtitle;
    public static $field_top_featured_group_secondary_link_text;
    public static $field_top_featured_group_secondary_link_action;
    public static $field_top_featured_group_secondary_link_action_page_to_link_to;
    public static $field_top_featured_group_secondary_link_action_custom_link;

    public static $field_top_featured_group_tertiary_items;
    public static $field_top_featured_group_tertiary_title;
    public static $field_top_featured_group_tertiary_subtitle;
    public static $field_top_featured_group_tertiary_link_action;
    public static $field_top_featured_group_tertiary_link_action_page_to_link_to;
    public static $field_top_featured_group_tertiary_link_action_custom_link;

    // "horizontal_slider"
    public static $field_horizontal_slider;
    public static $field_horizontal_slider_image_attachment_id;
    public static $field_horizontal_slider_title;
    public static $field_horizontal_slider_subtitle;
    public static $field_horizontal_slider_link_action;
    public static $field_horizontal_slider_link_action_page_to_link_to;
    public static $field_horizontal_slider_link_action_custom_link;

    // bottom featured
    public static $field_bottom_featured_group_primary_items;
    public static $field_bottom_featured_group_primary_title;
    public static $field_bottom_featured_group_primary_image_attachment_id;
    public static $field_bottom_featured_group_primary_has_video;
    public static $field_bottom_featured_group_primary_video_attachment_id;
    public static $field_bottom_featured_group_primary_video_src;
    public static $field_bottom_featured_group_primary_subtitle;
    public static $field_bottom_featured_group_primary_link_text;
    public static $field_bottom_featured_group_primary_link_action;
    public static $field_bottom_featured_group_primary_link_action_page_to_link_to;
    public static $field_bottom_featured_group_primary_link_action_custom_link;

    public static $field_bottom_featured_group_secondary_items;
    public static $field_bottom_featured_group_secondary_title;
    public static $field_bottom_featured_group_secondary_image_attachment_id;
    public static $field_bottom_featured_group_secondary_has_video;
    public static $field_bottom_featured_group_secondary_video_attachment_id;
    public static $field_bottom_featured_group_secondary_video_src;
    public static $field_bottom_featured_group_secondary_subtitle;
    public static $field_bottom_featured_group_secondary_link_text;
    public static $field_bottom_featured_group_secondary_link_action;
    public static $field_bottom_featured_group_secondary_link_action_page_to_link_to;
    public static $field_bottom_featured_group_secondary_link_action_custom_link;

    public static $field_bottom_featured_group_tertiary_items;
    public static $field_bottom_featured_group_tertiary_title;
    public static $field_bottom_featured_group_tertiary_subtitle;
    public static $field_bottom_featured_group_tertiary_link_action;
    public static $field_bottom_featured_group_tertiary_link_action_page_to_link_to;
    public static $field_bottom_featured_group_tertiary_link_action_custom_link;

    const IS_LINK_TO_PAGE = 'page';
    const IS_CUSTOM_LINK = 'custom';
    const HAS_VIDEO_AS_ATTACHMENT = 'file';
    const HAS_VIDEO_AS_LINK = 'link';
    const DOES_NOT_HAVE_VIDEO = 'no';

    public static function bootstrap()
    {
        self::$field_top_featured_group_primary_items = Config::getKeyPrefix() . 'top_featured_group_primary_items';
        self::$field_top_featured_group_primary_title = Config::getKeyPrefix() . 'top_featured_group_primary_title';
        self::$field_top_featured_group_primary_image_attachment_id = Config::getKeyPrefix() . 'top_featured_group_primary_image_attachment_id';
        self::$field_top_featured_group_primary_has_video = Config::getKeyPrefix() . 'top_featured_group_primary_has_video';
        self::$field_top_featured_group_primary_video_attachment_id = Config::getKeyPrefix() . 'top_featured_group_primary_video_attachment_id';
        self::$field_top_featured_group_primary_video_src = Config::getKeyPrefix() . 'top_featured_group_primary_video_src';
        self::$field_top_featured_group_primary_subtitle = Config::getKeyPrefix() . 'top_featured_group_primary_subtitle';
        self::$field_top_featured_group_primary_link_text = Config::getKeyPrefix() . 'top_featured_group_primary_link_text';
        self::$field_top_featured_group_primary_link_action = Config::getKeyPrefix() . 'top_featured_group_primary_link_action';
        self::$field_top_featured_group_primary_link_action_page_to_link_to = Config::getKeyPrefix() . 'top_featured_group_primary_link_action_page_to_link_to';
        self::$field_top_featured_group_primary_link_action_custom_link = Config::getKeyPrefix() . 'top_featured_group_primary_link_action_custom_link';

        self::$field_top_featured_group_secondary_items = Config::getKeyPrefix() . 'top_featured_group_secondary_items';
        self::$field_top_featured_group_secondary_title = Config::getKeyPrefix() . 'top_featured_group_secondary_items';
        self::$field_top_featured_group_secondary_image_attachment_id = Config::getKeyPrefix() . 'top_featured_group_secondary_image_attachment_id';
        self::$field_top_featured_group_secondary_has_video = Config::getKeyPrefix() . 'top_featured_group_secondary_has_video';
        self::$field_top_featured_group_secondary_video_attachment_id = Config::getKeyPrefix() . 'top_featured_group_secondary_video_attachment_id';
        self::$field_top_featured_group_secondary_video_src = Config::getKeyPrefix() . 'top_featured_group_secondary_video_src';
        self::$field_top_featured_group_secondary_subtitle = Config::getKeyPrefix() . 'top_featured_group_secondary_subtitle';
        self::$field_top_featured_group_secondary_link_text = Config::getKeyPrefix() . 'top_featured_group_secondary_link_text';
        self::$field_top_featured_group_secondary_link_action = Config::getKeyPrefix() . 'top_featured_group_secondary_link_action';
        self::$field_top_featured_group_secondary_link_action_page_to_link_to = Config::getKeyPrefix() . 'top_featured_group_secondary_link_action_page_to_link_to';
        self::$field_top_featured_group_secondary_link_action_custom_link = Config::getKeyPrefix() . 'top_featured_group_secondary_link_action_custom_link';

        self::$field_top_featured_group_tertiary_items = Config::getKeyPrefix() . 'top_featured_group_tertiary_items';
        self::$field_top_featured_group_tertiary_title = Config::getKeyPrefix() . 'top_featured_group_tertiary_title';
        self::$field_top_featured_group_tertiary_subtitle = Config::getKeyPrefix() . 'top_featured_group_tertiary_subtitle';
        self::$field_top_featured_group_tertiary_link_action = Config::getKeyPrefix() . 'top_featured_group_tertiary_link_action';
        self::$field_top_featured_group_tertiary_link_action_page_to_link_to = Config::getKeyPrefix() . 'top_featured_group_tertiary_link_action_page_to_link_to';
        self::$field_top_featured_group_tertiary_link_action_custom_link = Config::getKeyPrefix() . 'top_featured_group_tertiary_link_action_custom_link';

        self::$field_horizontal_slider = Config::getKeyPrefix() . 'horizontal_slider';
        self::$field_horizontal_slider_image_attachment_id = Config::getKeyPrefix() . 'horizontal_slider_image_attachment_id';
        self::$field_horizontal_slider_title = Config::getKeyPrefix() . 'horizontal_slider_title';
        self::$field_horizontal_slider_subtitle = Config::getKeyPrefix() . 'horizontal_slider_subtitle';
        self::$field_horizontal_slider_link_action = Config::getKeyPrefix() . 'horizontal_slider_link_action';
        self::$field_horizontal_slider_link_action_page_to_link_to = Config::getKeyPrefix() . 'horizontal_slider_link_action_page_to_link_to';
        self::$field_horizontal_slider_link_action_custom_link = Config::getKeyPrefix() . 'horizontal_slider_link_action_custom_link';

        self::$field_bottom_featured_group_primary_items = Config::getKeyPrefix() . 'bottom_featured_group_primary_items';
        self::$field_bottom_featured_group_primary_title = Config::getKeyPrefix() . 'bottom_featured_group_primary_title';
        self::$field_bottom_featured_group_primary_image_attachment_id = Config::getKeyPrefix() . 'bottom_featured_group_primary_image_attachment_id';
        self::$field_bottom_featured_group_primary_has_video = Config::getKeyPrefix() . 'bottom_featured_group_primary_has_video';
        self::$field_bottom_featured_group_primary_video_attachment_id = Config::getKeyPrefix() . 'bottom_featured_group_primary_video_attachment_id';
        self::$field_bottom_featured_group_primary_video_src = Config::getKeyPrefix() . 'bottom_featured_group_primary_video_src';
        self::$field_bottom_featured_group_primary_subtitle = Config::getKeyPrefix() . 'bottom_featured_group_primary_subtitle';
        self::$field_bottom_featured_group_primary_link_text = Config::getKeyPrefix() . 'bottom_featured_group_primary_link_text';
        self::$field_bottom_featured_group_primary_link_action = Config::getKeyPrefix() . 'bottom_featured_group_primary_link_action';
        self::$field_bottom_featured_group_primary_link_action_page_to_link_to = Config::getKeyPrefix() . 'bottom_featured_group_primary_link_action_page_to_link_to';
        self::$field_bottom_featured_group_primary_link_action_custom_link = Config::getKeyPrefix() . 'bottom_featured_group_primary_link_action_custom_link';

        self::$field_bottom_featured_group_secondary_items = Config::getKeyPrefix() . 'bottom_featured_group_secondary_items';
        self::$field_bottom_featured_group_secondary_title = Config::getKeyPrefix() . 'bottom_featured_group_secondary_items';
        self::$field_bottom_featured_group_secondary_image_attachment_id = Config::getKeyPrefix() . 'bottom_featured_group_secondary_image_attachment_id';
        self::$field_bottom_featured_group_secondary_has_video = Config::getKeyPrefix() . 'bottom_featured_group_secondary_has_video';
        self::$field_bottom_featured_group_secondary_video_attachment_id = Config::getKeyPrefix() . 'bottom_featured_group_secondary_video_attachment_id';
        self::$field_bottom_featured_group_secondary_video_src = Config::getKeyPrefix() . 'bottom_featured_group_secondary_video_src';
        self::$field_bottom_featured_group_secondary_subtitle = Config::getKeyPrefix() . 'bottom_featured_group_secondary_subtitle';
        self::$field_bottom_featured_group_secondary_link_text = Config::getKeyPrefix() . 'bottom_featured_group_secondary_link_text';
        self::$field_bottom_featured_group_secondary_link_action = Config::getKeyPrefix() . 'bottom_featured_group_secondary_link_action';
        self::$field_bottom_featured_group_secondary_link_action_page_to_link_to = Config::getKeyPrefix() . 'bottom_featured_group_secondary_link_action_page_to_link_to';
        self::$field_bottom_featured_group_secondary_link_action_custom_link = Config::getKeyPrefix() . 'bottom_featured_group_secondary_link_action_custom_link';

        self::$field_bottom_featured_group_tertiary_items = Config::getKeyPrefix() . 'bottom_featured_group_tertiary_items';
        self::$field_bottom_featured_group_tertiary_title = Config::getKeyPrefix() . 'bottom_featured_group_tertiary_title';
        self::$field_bottom_featured_group_tertiary_subtitle = Config::getKeyPrefix() . 'bottom_featured_group_tertiary_subtitle';
        self::$field_bottom_featured_group_tertiary_link_action = Config::getKeyPrefix() . 'bottom_featured_group_tertiary_link_action';
        self::$field_bottom_featured_group_tertiary_link_action_page_to_link_to = Config::getKeyPrefix() . 'bottom_featured_group_tertiary_link_action_page_to_link_to';
        self::$field_bottom_featured_group_tertiary_link_action_custom_link = Config::getKeyPrefix() . 'bottom_featured_group_tertiary_link_action_custom_link';
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

    

    public function getTopFeaturedPrimaryItems()
    {
        $field = self::$field_top_featured_group_primary_items;
        $items = $this->$field;

        $primary_items = Helper::getContentAsArrayFromPiklist($items, array(
            'title' => self::$field_top_featured_group_primary_title,
            'sub_title' => self::$field_top_featured_group_primary_subtitle,
            'image_attachment_id' => self::$field_top_featured_group_primary_image_attachment_id,
            'link_action' => self::$field_top_featured_group_primary_link_action,
            'link_action_page_id' => self::$field_top_featured_group_primary_link_action_page_to_link_to,
            'link_action_custom_link' => self::$field_top_featured_group_primary_link_action_custom_link,
            'list_action_text' => self::$field_top_featured_group_primary_link_text,
            'has_video' => self::$field_top_featured_group_primary_has_video,
            'video_attachment_id' => self::$field_top_featured_group_primary_video_attachment_id,
            'video_custom_link' => self::$field_top_featured_group_primary_video_src,
        ));

        return $primary_items;
    }

    public function getTopFeaturedSecondaryItems()
    {
        $field = self::$field_top_featured_group_secondary_items;
        $items = $this->$field;

        $secondary_items = Helper::getContentAsArrayFromPiklist($items, array(
            'title' => self::$field_top_featured_group_secondary_title,
            'sub_title' => self::$field_top_featured_group_secondary_subtitle,
            'image_attachment_id' => self::$field_top_featured_group_secondary_image_attachment_id,
            'link_action' => self::$field_top_featured_group_secondary_link_action,
            'link_action_page_id' => self::$field_top_featured_group_secondary_link_action_page_to_link_to,
            'link_action_custom_link' => self::$field_top_featured_group_secondary_link_action_custom_link,
            'list_action_text' => self::$field_top_featured_group_secondary_link_text,
            'has_video' => self::$field_top_featured_group_secondary_has_video,
            'video_attachment_id' => self::$field_top_featured_group_secondary_video_attachment_id,
            'video_custom_link' => self::$field_top_featured_group_secondary_video_src,
        ));

        return $secondary_items;
    }

    public function getTopFeaturedTertiaryItems()
    {
        $field = self::$field_top_featured_group_tertiary_items;
        $items = $this->$field;

        $tertiary_items = Helper::getContentAsArrayFromPiklist($items, array(
            'title' => self::$field_top_featured_group_tertiary_title,
            'sub_title' => self::$field_top_featured_group_tertiary_subtitle,
            'link_action' => self::$field_top_featured_group_tertiary_link_action,
            'link_action_page_id' => self::$field_top_featured_group_tertiary_link_action_page_to_link_to,
            'link_action_custom_link' => self::$field_top_featured_group_tertiary_link_action_custom_link,
        ));

        return $tertiary_items;
    }

    public function getHorizontalSliderItems()
    {
        $field = self::$field_horizontal_slider;
        $items = $this->$field;

        $horizontal_items = Helper::getContentAsArrayFromPiklist($items, array(
            'title' => self::$field_horizontal_slider_title,
            'sub_title' => self::$field_horizontal_slider_subtitle,
            'image_attachment_id' => self::$field_horizontal_slider_image_attachment_id,
            'link_action' => self::$field_horizontal_slider_link_action,
            'link_action_page_id' => self::$field_horizontal_slider_link_action_page_to_link_to,
            'link_action_custom_link' => self::$field_horizontal_slider_link_action_custom_link,
        ));

        return $horizontal_items;
    }

    public function getBottomFeaturedPrimaryItems()
    {
        $field = self::$field_bottom_featured_group_primary_items;
        $items = $this->$field;

        $primary_items = Helper::getContentAsArrayFromPiklist($items, array(
            'title' => self::$field_bottom_featured_group_primary_title,
            'sub_title' => self::$field_bottom_featured_group_primary_subtitle,
            'image_attachment_id' => self::$field_bottom_featured_group_primary_image_attachment_id,
            'link_action' => self::$field_bottom_featured_group_primary_link_action,
            'link_action_page_id' => self::$field_bottom_featured_group_primary_link_action_page_to_link_to,
            'link_action_custom_link' => self::$field_bottom_featured_group_primary_link_action_custom_link,
            'list_action_text' => self::$field_bottom_featured_group_primary_link_text,
            'has_video' => self::$field_bottom_featured_group_primary_has_video,
            'video_attachment_id' => self::$field_bottom_featured_group_primary_video_attachment_id,
            'video_custom_link' => self::$field_bottom_featured_group_primary_video_src,
        ));

        return $primary_items;
    }

    public function getBottomFeaturedSecondaryItems()
    {
        $field = self::$field_bottom_featured_group_secondary_items;
        $items = $this->$field;

        $secondary_items = Helper::getContentAsArrayFromPiklist($items, array(
            'title' => self::$field_bottom_featured_group_secondary_title,
            'sub_title' => self::$field_bottom_featured_group_secondary_subtitle,
            'image_attachment_id' => self::$field_bottom_featured_group_secondary_image_attachment_id,
            'link_action' => self::$field_bottom_featured_group_secondary_link_action,
            'link_action_page_id' => self::$field_bottom_featured_group_secondary_link_action_page_to_link_to,
            'link_action_custom_link' => self::$field_bottom_featured_group_secondary_link_action_custom_link,
            'list_action_text' => self::$field_bottom_featured_group_secondary_link_text,
            'has_video' => self::$field_bottom_featured_group_secondary_has_video,
            'video_attachment_id' => self::$field_bottom_featured_group_secondary_video_attachment_id,
            'video_custom_link' => self::$field_bottom_featured_group_secondary_video_src,
        ));

        return $secondary_items;
    }

    public function getBottomFeaturedTertiaryItems()
    {
        $field = self::$field_bottom_featured_group_tertiary_items;
        $items = $this->$field;

        $tertiary_items = Helper::getContentAsArrayFromPiklist($items, array(
            'title' => self::$field_bottom_featured_group_tertiary_title,
            'sub_title' => self::$field_bottom_featured_group_tertiary_subtitle,
            'link_action' => self::$field_bottom_featured_group_tertiary_link_action,
            'link_action_page_id' => self::$field_bottom_featured_group_tertiary_link_action_page_to_link_to,
            'link_action_custom_link' => self::$field_bottom_featured_group_tertiary_link_action_custom_link,
        ));

        return $tertiary_items;
    }



}