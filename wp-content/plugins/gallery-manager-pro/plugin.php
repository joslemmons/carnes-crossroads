<?php
/*
Plugin Name: Gallery Manager Pro
Plugin URI: http://dennishoppe.de/wordpress-plugins/gallery-manager
Description: This awesome Gallery Manager enables you to create and manage image galleries easily. Furthermore it associates linked images in posts and pages with a nice and responsive touch-enabled lightbox.
Version: 1.3.11
Author: Dennis Hoppe
Author URI: http://DennisHoppe.de
*/

$includeFiles = function($pattern){
  $arr_files = Glob($pattern);
  if (!empty($arr_files) && is_Array($arr_files)){
    foreach ($arr_files as $include_file){
      Include_Once($include_file);
    }
  }
};

# Load core classes
$plugin_folder = DirName(__FILE__);
$includeFiles("{$plugin_folder}/classes/*.php");
$includeFiles("{$plugin_folder}/widgets/*.php");

# Inititalize Plugin
WordPress\Plugin\GalleryManager\Core::init(__FILE__);
