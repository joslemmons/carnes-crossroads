<?php namespace App\Model;

use Carbon\Carbon;

class LandingPage extends \TimberPost
{
    private static $_postType;
    private static $_labelTitle;

    public static $field_title;
    public static $field_sub_title;
    public static $field_image_attachment_id;

    public static $field_featured_section_title;
    public static $field_featured_section_content;
    public static $field_featured_section_image_attachment_id;
    public static $field_featured_section_has_video;
    public static $field_featured_section_video_attachment_id;
    public static $field_featured_section_video_src;
    public static $field_featured_section_button_action;
    public static $field_featured_section_button_action_page_to_link_to;
    public static $field_featured_section_button_action_custom_link;
    public static $field_featured_section_button_text;

    public static $field_secondary_sections;
    public static $field_secondary_section_title;
    public static $field_secondary_section_content;
    public static $field_secondary_section_image_attachment_id;
    public static $field_secondary_section_has_video;
    public static $field_secondary_section_video_attachment_id;
    public static $field_secondary_section_video_src;
    public static $field_secondary_section_button_action;
    public static $field_secondary_section_button_action_page_to_link_to;
    public static $field_secondary_section_button_action_custom_link;
    public static $field_secondary_section_button_text;

    public static $field_tertiary_sections;
    public static $field_tertiary_section_title;
    public static $field_tertiary_section_content;
    public static $field_tertiary_section_image_attachment_id;
    public static $field_tertiary_section_has_video;
    public static $field_tertiary_section_video_attachment_id;
    public static $field_tertiary_section_video_src;
    public static $field_tertiary_section_button_action;
    public static $field_tertiary_section_button_action_page_to_link_to;
    public static $field_tertiary_section_button_action_custom_link;
    public static $field_tertiary_section_button_text;

    public static $field_footer_section_image_attachment_id;
    public static $field_footer_section_title;
    public static $field_footer_section_description;
    public static $field_footer_section_gravity_form_id;

    const IS_LINK_TO_PAGE = 'page';
    const IS_CUSTOM_LINK = 'custom';
    const GALLERY_NO_VIDEO = 'no video';
    const GALLERY_UPLOAD_VIDEO = 'upload video';
    const GALLERY_LINK_VIDEO = 'link video';

    public static function bootstrap()
    {
        self::$_postType = 'landing_page';
        self::$_labelTitle = 'Landing Page';

        self::$field_title = Config::getKeyPrefix() . 'title';
        self::$field_sub_title = Config::getKeyPrefix() . 'sub_title';
        self::$field_image_attachment_id = Config::getKeyPrefix() . 'image_attachment_id';
        self::$field_featured_section_title = Config::getKeyPrefix() . 'featured_section_title';
        self::$field_featured_section_content = Config::getKeyPrefix() . 'featured_section_content';
        self::$field_featured_section_image_attachment_id = Config::getKeyPrefix() . 'featured_section_image_attachment_id';
        self::$field_featured_section_has_video = Config::getKeyPrefix() . 'featured_section_has_video';
        self::$field_featured_section_video_attachment_id = Config::getKeyPrefix() . 'featured_section_video_attachment_id';
        self::$field_featured_section_video_src = Config::getKeyPrefix() . 'featured_section_video_src';
        self::$field_featured_section_button_action = Config::getKeyPrefix() . 'featured_section_button_action';
        self::$field_featured_section_button_action_page_to_link_to = Config::getKeyPrefix() . 'featured_section_button_action_page_to_link_to';
        self::$field_featured_section_button_action_custom_link = Config::getKeyPrefix() . 'featured_section_button_action_custom_link';
        self::$field_featured_section_button_text = Config::getKeyPrefix() . 'featured_section_button_text';
        self::$field_secondary_sections = Config::getKeyPrefix() . 'secondary_sections';
        self::$field_secondary_section_title = Config::getKeyPrefix() . 'secondary_section_title';
        self::$field_secondary_section_content = Config::getKeyPrefix() . 'secondary_section_content';
        self::$field_secondary_section_image_attachment_id = Config::getKeyPrefix() . 'secondary_section_image_attachment_id';
        self::$field_secondary_section_has_video = Config::getKeyPrefix() . 'secondary_section_has_video';
        self::$field_secondary_section_video_attachment_id = Config::getKeyPrefix() . 'secondary_section_video_attachment_id';
        self::$field_secondary_section_video_src = Config::getKeyPrefix() . 'secondary_section_video_src';
        self::$field_secondary_section_button_action = Config::getKeyPrefix() . 'secondary_section_button_action';
        self::$field_secondary_section_button_action_page_to_link_to = Config::getKeyPrefix() . 'secondary_section_button_action_page_to_link_to';
        self::$field_secondary_section_button_action_custom_link = Config::getKeyPrefix() . 'secondary_section_button_action_custom_link';
        self::$field_secondary_section_button_text = Config::getKeyPrefix() . 'secondary_section_button_text';
        self::$field_tertiary_sections = Config::getKeyPrefix() . 'tertiary_sections';
        self::$field_tertiary_section_title = Config::getKeyPrefix() . 'tertiary_section_title';
        self::$field_tertiary_section_content = Config::getKeyPrefix() . 'tertiary_section_content';
        self::$field_tertiary_section_image_attachment_id = Config::getKeyPrefix() . 'tertiary_section_image_attachment_id';
        self::$field_tertiary_section_has_video = Config::getKeyPrefix() . 'tertiary_section_has_video';
        self::$field_tertiary_section_video_attachment_id = Config::getKeyPrefix() . 'tertiary_section_video_attachment_id';
        self::$field_tertiary_section_video_src = Config::getKeyPrefix() . 'tertiary_section_video_src';
        self::$field_tertiary_section_button_action = Config::getKeyPrefix() . 'tertiary_section_button_action';
        self::$field_tertiary_section_button_action_page_to_link_to = Config::getKeyPrefix() . 'tertiary_section_button_action_page_to_link_to';
        self::$field_tertiary_section_button_action_custom_link = Config::getKeyPrefix() . 'tertiary_section_button_action_custom_link';
        self::$field_tertiary_section_button_text = Config::getKeyPrefix() . 'tertiary_section_button_text';
        self::$field_footer_section_image_attachment_id = Config::getKeyPrefix() . '';
        self::$field_footer_section_title = Config::getKeyPrefix() . 'footer_section_title';
        self::$field_footer_section_description = Config::getKeyPrefix() . 'footer_section_description';
        self::$field_footer_section_gravity_form_id = Config::getKeyPrefix() . 'footer_section_gravity_form_id';

        add_filter('piklist_post_types', array(get_class(), 'registerCPT'));
    }

    public static function getPostType()
    {
        return self::$_postType;
    }

    public static function getHasVideoOptionsForPiklist()
    {
        return array(
            self::GALLERY_NO_VIDEO => 'No Video',
            self::GALLERY_UPLOAD_VIDEO => 'Upload/Choose a Video',
            self::GALLERY_LINK_VIDEO => 'Insert link to Video'
        );
    }

    public static function getButtonLinkOptionsForPiklist()
    {
        return array(
            self::IS_LINK_TO_PAGE => 'Link to Internal Page',
            self::IS_CUSTOM_LINK => 'Custom Link'
        );
    }

    public static function registerCPT($post_types)
    {
        $post_types[self::$_postType] = array(
            'labels' => piklist('post_type_labels', self::$_labelTitle),
            'title' => __('Nickname for Landing Page'),
            'public' => true,
            'menu_icon' => 'dashicons-desktop',
            'rewrite' => array(
                'slug' => 'lp'
            ),
            'supports' => array(
                'title',
                'editor'
            ),
            'hide_meta_box' => array(
                'slug',
                'author',
                'comments',
                'commentstatus',
            )
        );

        return $post_types;
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

    public function getHeaderImage()
    {
        $field = self::$field_image_attachment_id;
        $image_attachment_id = $this->$field;

        if ($image_attachment_id) {
            return new \TimberImage($image_attachment_id);
        }

        return false;
    }

    public function getTitle()
    {
        $field = self::$field_title;
        return $this->$field;
    }

    public function getSubTitle()
    {
        $field = self::$field_sub_title;
        return $this->$field;
    }

    public function getFeatured()
    {
        $field_title = self::$field_featured_section_title;
        $field_content = self::$field_featured_section_content;
        $field_image_attachment_id = self::$field_featured_section_image_attachment_id;
        $field_has_video = self::$field_featured_section_has_video;
        $field_video_attachment_id = self::$field_featured_section_video_attachment_id;
        $field_video_src = self::$field_featured_section_video_src;
        $field_button_action = self::$field_featured_section_button_action;
        $field_button_page_to_link_to = self::$field_featured_section_button_action_page_to_link_to;
        $field_button_custom_link = self::$field_featured_section_button_action_custom_link;
        $field_button_title = self::$field_featured_section_button_text;

        $featured_data = array();

        $featured_data['title'] = $this->$field_title;
        $featured_data['content'] = $this->$field_content;

        $image_attachment_id = $this->$field_image_attachment_id;
        if ($image_attachment_id) {
            if (is_array($image_attachment_id)) {
                $image_attachment_id = array_pop($image_attachment_id);
            }
            $featured_data['image'] = new \TimberImage($image_attachment_id);
        }

        if (self::GALLERY_LINK_VIDEO === $this->$field_has_video) {
            $featured_data['video_src'] = $this->$field_video_src;
        }

        if (self::GALLERY_UPLOAD_VIDEO === $this->$field_has_video) {
            $featured_data['video_src'] = wp_get_attachment_url($this->$field_video_attachment_id);
        }

        if (self::IS_CUSTOM_LINK === $this->$field_button_action) {
            $featured_data['button']['link'] = $this->$field_button_custom_link;
            $featured_data['button']['title'] = $this->$field_button_title;
        }

        if (self::IS_LINK_TO_PAGE === $this->$field_button_action) {
            if ('' !== $this->$field_button_page_to_link_to) {
                $page = \Timber::get_post($this->$field_button_page_to_link_to, get_class());
                if ($page) {
                    $featured_data['button']['link'] = $page->link();
                    $featured_data['button']['title'] = $this->$field_button_title;
                }
            }
        }

        return $featured_data;
    }

    public function getSecondary()
    {
        $field_secondary_sections = self::$field_secondary_sections;
        $secondary_sections = $this->$field_secondary_sections;

        $secondary_data = array();
        foreach ($secondary_sections as $section) {
            $instance = array();

            $field_title = self::$field_secondary_section_title;
            $field_content = self::$field_secondary_section_content;
            $field_image_attachment_id = self::$field_secondary_section_image_attachment_id;
            $field_has_video = self::$field_secondary_section_has_video;
            $field_video_attachment_id = self::$field_secondary_section_video_attachment_id;
            $field_video_src = self::$field_secondary_section_video_src;
            $field_button_action = self::$field_secondary_section_button_action;
            $field_button_page_to_link_to = self::$field_secondary_section_button_action_page_to_link_to;
            $field_button_custom_link = self::$field_secondary_section_button_action_custom_link;
            $field_button_title = self::$field_secondary_section_button_text;

            $instance['title'] = $section[$field_title];
            $instance['content'] = $section[$field_content];

            $image_attachment_id = $section[$field_image_attachment_id];
            if ($image_attachment_id) {
                if (is_array($image_attachment_id)) {
                    $image_attachment_id = array_pop($image_attachment_id);
                }
                $instance['image'] = new \TimberImage($image_attachment_id);
            }

            if (self::GALLERY_LINK_VIDEO === $section[$field_has_video]) {
                $instance['video_src'] = $section[$field_video_src];
            }

            if (self::GALLERY_UPLOAD_VIDEO === $section[$field_has_video]) {
                $instance['video_src'] = wp_get_attachment_url($section[$field_video_attachment_id]);
            }

            if (self::IS_CUSTOM_LINK === $section[$field_button_action][0]) {
                $instance['button']['link'] = $section[$field_button_custom_link];
                $instance['button']['title'] = $section[$field_button_title];
            }

            if (self::IS_LINK_TO_PAGE === $section[$field_button_action][0]) {
                if ('' !== $section[$field_button_page_to_link_to]) {
                    $page = \Timber::get_post($section[$field_button_page_to_link_to], get_class());
                    if ($page) {
                        $instance['button']['link'] = $page->link();
                        $instance['button']['title'] = $section[$field_button_title];
                    }
                }
            }

            $secondary_data[] = $instance;
        }


        return $secondary_data;
    }

    public function getTertiary()
    {
        $field_tertiary_sections = self::$field_tertiary_sections;
        $tertiary_sections = $this->$field_tertiary_sections;

        $tertiary_data = array();
        foreach ($tertiary_sections as $section) {
            $instance = array();

            $field_title = self::$field_tertiary_section_title;
            $field_content = self::$field_tertiary_section_content;
            $field_image_attachment_id = self::$field_tertiary_section_image_attachment_id;
            $field_has_video = self::$field_tertiary_section_has_video;
            $field_video_attachment_id = self::$field_tertiary_section_video_attachment_id;
            $field_video_src = self::$field_tertiary_section_video_src;
            $field_button_action = self::$field_tertiary_section_button_action;
            $field_button_page_to_link_to = self::$field_tertiary_section_button_action_page_to_link_to;
            $field_button_custom_link = self::$field_tertiary_section_button_action_custom_link;
            $field_button_title = self::$field_tertiary_section_button_text;

            $instance['title'] = $section[$field_title];
            $instance['content'] = $section[$field_content];

            $image_attachment_id = $section[$field_image_attachment_id];
            if ($image_attachment_id) {
                if (is_array($image_attachment_id)) {
                    $image_attachment_id = array_pop($image_attachment_id);
                }
                $instance['image'] = new \TimberImage($image_attachment_id);
            }

            if (self::GALLERY_LINK_VIDEO === $section[$field_has_video]) {
                $instance['video_src'] = $section[$field_video_src];
            }

            if (self::GALLERY_UPLOAD_VIDEO === $section[$field_has_video]) {
                $instance['video_src'] = wp_get_attachment_url($section[$field_video_attachment_id]);
            }

            if (self::IS_CUSTOM_LINK === $section[$field_button_action][0]) {
                $instance['button']['link'] = $section[$field_button_custom_link];
                $instance['button']['title'] = $section[$field_button_title];
            }

            if (self::IS_LINK_TO_PAGE === $section[$field_button_action][0]) {
                if ('' !== $section[$field_button_page_to_link_to]) {
                    $page = \Timber::get_post($section[$field_button_page_to_link_to], get_class());
                    if ($page) {
                        $instance['button']['link'] = $page->link();
                        $instance['button']['title'] = $section[$field_button_title];
                    }
                }
            }

            $tertiary_data[] = $instance;
        }


        return $tertiary_data;
    }

    public function getFooter()
    {
        $footer_data = array();

        $field_title = self::$field_footer_section_title;
        $footer_data['title'] = $this->$field_title;

        $field_content = self::$field_footer_section_description;
        $footer_data['content'] = $this->$field_content;

        $field_image_attachment_id = self::$field_footer_section_image_attachment_id;
        $image_attachment_id = $this->$field_image_attachment_id;
        if ($image_attachment_id) {
            if (is_array($image_attachment_id)) {
                $image_attachment_id = array_pop($image_attachment_id);
            }
            $footer_data['image'] = new \TimberImage($image_attachment_id);
        }

        $field_gravity_form_id = self::$field_footer_section_gravity_form_id;
        $footer_data['gravity_form_id'] = $this->$field_gravity_form_id;

        return $footer_data;
    }
}