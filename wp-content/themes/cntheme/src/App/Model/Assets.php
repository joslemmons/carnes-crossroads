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
        wp_enqueue_script('bootstrap-js', get_template_directory_uri() . '/bower_components/bootstrap/dist/js/bootstrap.min.js', array('jquery'), false, true);
        wp_enqueue_script('respond-js', get_template_directory_uri() . '/bower_components/respond-minmax/dest/respond.min.js', array(), false, true);
        wp_enqueue_script('modernizr-js', get_template_directory_uri() . '/bower_components/modernizr/modernizr.js', array(), false, true);

        wp_register_script('jquery-quicksearch-js', get_template_directory_uri() . '/js/lib/jquery.quicksearch.js', array('jquery'), Config::getAppVersion(), true);
    }

}
