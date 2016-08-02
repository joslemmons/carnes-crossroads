<?php Namespace WordPress\Plugin\GalleryManager;

abstract class Gallery_Post_Type {
  const
    meta_field_name = '_gallery', # Name of the meta field which is used in the database
    post_type_name = 'gallery'; # Name of the gallery post type

  public static
    $arr_meta_box = Array(); # Meta boxes for the gallery post type

  static function init(){
    add_Action('init', Array(__CLASS__, 'registerPostType'), 20); # For the permalinks it is important that the post type is registered after the taxonomies
    add_Filter('post_updated_messages', Array(__CLASS__, 'filterPostUpdateMessages'));
    add_Action(SPrintF('save_post_%s', self::post_type_name), Array(__CLASS__, 'savePost'), 10, 2);
    add_Filter(SPrintF('manage_%s_posts_columns', self::post_type_name), Array(__CLASS__, 'filterPostTypeColumns'));
    add_Action(SPrintF('manage_%s_posts_custom_column', self::post_type_name), Array(__CLASS__, 'filterPostTypeColumnValue'), 10, 2);
  }

  static function savePost($post_id, $post){
    # If this is an autosave we dont care
    if (Defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    # Delete deprecated post meta key
    Delete_Post_Meta ($post_id, '_wp_plugin_fancy_gallery');
    Delete_Post_Meta ($post_id, '_wp_plugin_fancy_gallery_pro');

    # Update post media attachments of this gallery
    if (isSet($_POST['images'])){
      $arr_images = $_POST['images'];
      setType($arr_images, 'ARRAY');
      $arr_images = Array_Filter($arr_images);
      $arr_images = Array_Values($arr_images);
      $arr_images = Array_Unique($arr_images);
      $gallery = new Gallery($post_id);
      $gallery->setImages($arr_images);
    }

    # Save gallery meta data like columns and thumbnail sizes
    if (isSet($_POST['gallery'])){
      $arr_meta = Array_Filter($_POST['gallery']);
      Update_Post_Meta($post_id, self::meta_field_name, $arr_meta);
    }
  }

  static function getMeta($key = Null, $default = False, $post_id = Null){
    $post_id = Empty($post_id) ? get_The_Id() : IntVal($post_id);

    # load meta data from database and convert it to array
    $arr_meta = get_Post_Meta($post_id, self::meta_field_name, True);
    setType($arr_meta, 'ARRAY');
    $arr_meta = Array_Filter($arr_meta);

    # merge meta data with the default meta values
    $arr_default_meta = self::getDefaultMeta();
    $arr_meta = Array_Merge($arr_default_meta, $arr_meta);

    # return the requested value
    if (Empty($key))
      return $arr_meta;
    elseif (isSet($arr_meta[$key]))
      return $arr_meta[$key];
    else
      return $default;
  }

  static function getDefaultMeta(){
    return Array(
      'columns' => 3,
      'image_size' => 'thumbnail'
    );
  }

  static function registerPostType(){
    # Register Post Type
    Register_Post_Type (self::post_type_name, Array(
      'labels' => Array(
        'name' => I18n::t('Galleries'),
        'singular_name' => I18n::t('Gallery'),
        'add_new' => I18n::t('Add Gallery'),
        'add_new_item' => I18n::t('New Gallery'),
        'edit_item' => I18n::t('Edit Gallery'),
        'view_item' => I18n::t('View Gallery'),
        'search_items' => I18n::t('Search Galleries'),
        'not_found' =>  I18n::t('No Galleries found'),
        'not_found_in_trash' => I18n::t('No Galleries found in Trash'),
        'parent_item_colon' => ''
      ),
      'public' => True,
      'show_ui' => True,
      'has_archive' => (bool) Options::Get('enable_archive'),
      'capability_type' => Array('gallery', 'galleries'),
			'map_meta_cap' => True,
			'hierarchical' => False,
      'rewrite' => Array(
        'slug' => I18n::t('galleries', 'URL slug'),
        'with_front' => False
      ),
      'supports' => Array('title', 'author'),
      'menu_position' => 10, # below Media
      'menu_icon' => 'dashicons-images-alt',
      'register_meta_box_cb' => Array(__CLASS__, 'addMetaBoxes')
    ));

    # Add optionally post type support
    if (Options::get('enable_editor'))
      Add_Post_Type_Support(self::post_type_name, 'editor');

    if (Options::get('enable_excerpts'))
      Add_Post_Type_Support(self::post_type_name, 'excerpt');

    if (Options::get('enable_comments'))
      Add_Post_Type_Support(self::post_type_name, Array('trackbacks', 'comments'));

    if (Options::get('enable_revisions'))
      Add_Post_Type_Support(self::post_type_name, 'revisions');

    if (Options::get('enable_featured_image'))
      Add_Post_Type_Support(self::post_type_name, 'thumbnail');

    if (Options::get('enable_custom_fields'))
      Add_Post_Type_Support(self::post_type_name, 'custom-fields');
  }

  static function filterPostUpdateMessages($arr_message){
    return Array_Merge ($arr_message, Array(self::post_type_name => Array(
      1 => SPrintF (I18n::t('Gallery updated. (<a href="%s">View gallery</a>)'), get_Permalink()),
      2 => __('Custom field updated.'),
      3 => __('Custom field deleted.'),
      4 => I18n::t('Gallery updated.'),
      5 => isSet($_GET['revision']) ? SPrintF(I18n::t('Gallery restored to revision from %s'), WP_Post_Revision_Title((Int) $_GET['revision'], False)) : False,
      6 => SPrintF(I18n::t('Gallery published. (<a href="%s">View gallery</a>)'), get_Permalink()),
      7 => I18n::t('Gallery saved.'),
      8 => I18n::t('Gallery submitted.'),
      9 => SPrintF(I18n::t('Gallery scheduled. (<a target="_blank" href="%s">View gallery</a>)'), get_Permalink()),
      10 => SPrintF(I18n::t('Gallery draft updated. (<a target="_blank" href="%s">Preview gallery</a>)'), Add_Query_Arg('preview', 'true', get_Permalink()))
    )));
  }

  static function addMetaBoxes(){
    Global $post_type_object;

    # Enqueue Edit Gallery JavaScript/CSS
    WP_Enqueue_Media();
    WP_Enqueue_Script('gallery-meta-boxes');
    WP_Enqueue_Style('gallery-meta-boxes', Core::$base_url . '/meta-boxes/meta-boxes.css', False, Core::version);

    self::addMetaBox(I18n::t('Images'), Core::$plugin_folder . '/meta-boxes/images.php', 'normal', 'high');

    self::addMetaBox(__('Appearance'), Core::$plugin_folder . '/meta-boxes/appearance.php', 'normal', 'high');

    Remove_Meta_Box('authordiv', self::post_type_name, 'normal');
    if (Current_User_Can($post_type_object->cap->edit_others_posts)){
      self::addMetaBox(I18n::t('Owner'), Core::$plugin_folder . '/meta-boxes/owner.php');
    }

    self::addMetaBox(I18n::t('Gallery Shortcode'), Core::$plugin_folder . '/meta-boxes/show-code.php', 'side', 'high');

    if (Options::get('lightbox'))
      self::addMetaBox(I18n::t('Gallery Hash'), Core::$plugin_folder . '/meta-boxes/show-hash.php', 'side', 'high');

    # Add Meta Boxes
    foreach (self::$arr_meta_box as $box_index => $meta_box){
      Add_Meta_Box(
        'meta-box-'.BaseName($meta_box['include_file'], '.php'),
        $meta_box['title'],
        Array(__CLASS__, 'printMetaBox'),
        self::post_type_name,
        $meta_box['column'],
        $meta_box['priority'],
        $box_index
      );
    }
  }

  static function addMetaBox($title, $include_file, $column = 'normal', $priority = 'default'){
    if (!$title) return False;
    if (!is_File($include_file)) return False;
    if ($column != 'side') $column = 'normal';

    # Add to array
    self::$arr_meta_box[] = Array(
      'title' => $title,
      'include_file' => $include_file,
      'column' => $column,
      'priority' => $priority
    );
  }

  static function printMetaBox($post, $box){
    $include_file = self::$arr_meta_box[$box['args']]['include_file'];
    is_File ($include_file) && include $include_file;
  }

  static function filterPostTypeColumns($arr_columns){
    $arr_columns['shortcode'] = I18n::t('Shortcode');
    return $arr_columns;
  }

  static function filterPostTypeColumnValue($column, $post_id){
    if ($column == 'shortcode'){
      PrintF('<input type="text" readonly value="[gallery id=&quot;%u&quot;]" onClick="this.select();">', $post_id);
    }
  }

  static function updatePostTypeName(){
    Global $wpdb;
    $wpdb->Update($wpdb->posts, Array('post_type' => self::post_type_name), Array('post_type' => 'fancy_gallery'));
    $wpdb->Update($wpdb->posts, Array('post_type' => self::post_type_name), Array('post_type' => 'fancy-gallery'));
	}

}

Gallery_Post_Type::init();
