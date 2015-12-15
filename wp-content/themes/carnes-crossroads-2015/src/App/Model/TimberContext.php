<?php namespace App\Model;

class TimberContext
{
    public static function init()
    {
        add_filter('timber_context', array(get_class(), 'registerMenu'));
        add_filter('timber_context', array(get_class(), 'addToTimberContext'));
        add_filter('timber_context', array(get_class(), 'addFooterData'));
    }

    public static function addFooterData($data)
    {
        $options = get_option('app_contact_footer_options');

        $gravity_form_id = $options[ContactFooter::$field_contact_form_gravity_form_id];
        $form_title = $options[ContactFooter::$field_contact_form_title];

        $featured_content = $options[ContactFooter::$field_featured_items];
        $content = Helper::getContentAsArrayFromPiklist($featured_content, array(
            'title' => ContactFooter::$field_featured_title,
            'sub_title' => ContactFooter::$field_featured_subtitle,
            'image_attachment_id' => ContactFooter::$field_featured_image_attachment_id,
            'link_action' => ContactFooter::$field_featured_link_action,
            'link_action_page_id' => ContactFooter::$field_featured_link_action_page_to_link_to,
            'link_action_custom_link' => ContactFooter::$field_featured_link_action_custom_link,
            'list_action_text' => ContactFooter::$field_featured_link_text,
            'has_video' => ContactFooter::$field_featured_has_video,
            'video_attachment_id' => ContactFooter::$field_featured_video_attachment_id,
            'video_custom_link' => ContactFooter::$field_featured_link_action_custom_link,
        ));

        $data['footer_gravity_form_id'] = $gravity_form_id;
        $data['footer_form_title'] = $form_title;
        $data['footer_featured_content'] = $content;

        return $data;
    }

    public static function registerMenu($data)
    {
        $data['primary_menu'] = new \TimberMenu('Primary Menu');
        $data['header_menu'] = new \TimberMenu('Header Menu');
        $data['footer_menu'] = new \TimberMenu('Footer Menu');

        return $data;
    }

    public static function addToTimberContext($data)
    {
        $data['typekit_src'] = Config::getTypekitSrc();
        $data['wp_footer'] = \TimberHelper::function_wrapper('wp_footer');
        $data['wp_head'] = \TimberHelper::function_wrapper('wp_head');
        $data['use_ga'] = Analytics::shouldIncludeGoogleAnalytics();
        $data['ga_code'] = Analytics::getGoogleAnalyticsCode();
        $data['use_crazyegg'] = Analytics::shouldIncludeCrazyEgg();
        $data['add_this_src'] = Config::getAddThisSrc();

        return $data;
    }

}
