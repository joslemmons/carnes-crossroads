<?php Namespace WordPress\Plugin\GalleryManager;

abstract class Template {

  static function init(){
    add_Filter('search_template', Array(__CLASS__, 'changeSearchTemplate'));
  }

  static function changeSearchTemplate($template){
    Global $wp_query;

    if (Query::isGallerySearch($wp_query) && $search_template = Locate_Template(SPrintF('search-%s.php', Gallery_Post_Type::post_type_name)))
      return $search_template;
    else
      return $template;
  }

  static function load($template_name, $vars = Array()){
		Extract($vars);
		$template_path = Locate_Template("{$template_name}.php");
		Ob_Start();

    if (!Empty($template_path))
      Include $template_path;
		else
      Include SPrintF('%s/templates/%s.php', Core::$plugin_folder, $template_name);

		return Ob_Get_Clean();
  }

}

Template::init();
