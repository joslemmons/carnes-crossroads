<?php namespace App\Model;

class Assets
{
    public static function enqueue()
    {
        add_action('wp_enqueue_scripts', array(get_class(), 'enqueue_stylesheets'));
        add_action('wp_enqueue_scripts', array(get_class(), 'enqueue_scripts'));
    }

    public static function enqueue_stylesheets()
    {
        wp_enqueue_style('style-css', get_template_directory_uri() . '/style.css');
    }

    public static function enqueue_scripts()
    {
        wp_enqueue_script('jquery-colour-brightness-js', get_template_directory_uri() . '/js/lib/jquery.colourbrightness.js', array('jquery'), false, true);
        wp_enqueue_script('slick-js', get_template_directory_uri() . '/bower_components/slick-carousel/slick/slick.min.js', array('jquery'), false, false);
        wp_enqueue_script('cn-load-videos-js', get_template_directory_uri() . '/js/lib/cn.load-videos.js', array('jquery'), false, true);
        wp_enqueue_script('bootstrap-js', get_template_directory_uri() . '/bower_components/bootstrap/dist/js/bootstrap.min.js', array('jquery'), false, true);
        wp_enqueue_script('respond-js', get_template_directory_uri() . '/bower_components/respond-minmax/dest/respond.min.js', array(), false, true);
        wp_enqueue_script('modernizr-js', get_template_directory_uri() . '/bower_components/modernizr/modernizr.js', array(), false, true);
        wp_enqueue_script('box-slider-js', get_template_directory_uri() . '/bower_components/bxslider-4/dist/jquery.bxslider.min.js', array('jquery'), false, true);
        wp_enqueue_script('global-js', get_template_directory_uri() . '/js/global.js', array('jquery', 'box-slider-js'), false, true);
        wp_enqueue_script('pluralize-js', get_template_directory_uri() . '/bower_components/pluralize/pluralize.js', array(), false, true);
        wp_enqueue_script('jquery-colorbox-js', get_template_directory_uri() . '/js/lib/colorbox/jquery.colorbox-min.js', array('jquery'), false, true);
    }

}
