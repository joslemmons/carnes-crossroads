<?php Namespace WordPress\Plugin\GalleryManager;

abstract class Gallery_Taxonomies {

  static function init(){
    add_Action('init', Array(__CLASS__, 'registerTaxonomies'), 15);
    add_Action('init', Array(__CLASS__, 'addTaxonomyArchiveUrls'), 50);
    add_Filter('nav_menu_meta_box_object', Array(__CLASS__, 'changeTaxonomyMenuLabel'));
  }

  static function getTaxonomies(){
    return Array(
      'gallery-category' => Array(
        'label' => I18n::t('Gallery Categories'),
        'labels' => Array(
          'name' => I18n::t('Categories'),
          'singular_name' => I18n::t('Category'),
          'all_items' => I18n::t('All Categories'),
          'edit_item' => I18n::t('Edit Category'),
          'view_item' => I18n::t('View Category'),
          'update_item' => I18n::t('Update Category'),
          'add_new_item' => I18n::t('Add New Category'),
          'new_item_name' => I18n::t('New Category'),
          'parent_item' => I18n::t('Parent Category'),
          'parent_item_colon' => I18n::t('Parent Category:'),
          'search_items' =>  I18n::t('Search Categories'),
          'popular_items' => I18n::t('Popular Categories'),
          'separate_items_with_commas' => I18n::t('Separate Categories with commas'),
          'add_or_remove_items' => I18n::t('Add or remove Categories'),
          'choose_from_most_used' => I18n::t('Choose from the most used Categories'),
          'not_found' => I18n::t('No Categories found.')
        ),
        'show_admin_column' => True,
        'hierarchical' => False,
        'show_ui' => True,
        'query_var' => True,
        'rewrite' => Array(
          'with_front' => False,
          'slug' => SPrintF(I18n::t('%s/category', 'URL slug'), I18n::t('galleries', 'URL slug'))
        ),
        'capabilities' => Array (
          'manage_terms' => 'manage_gallery_categories',
          'edit_terms' => 'manage_gallery_categories',
          'delete_terms' => 'manage_gallery_categories',
          'assign_terms' => 'edit_galleries'
        )
      ),

      'gallery-tag' => Array(
        'label' => I18n::t( 'Gallery Tags' ),
        'labels' => Array(
          'name' => I18n::t('Tags'),
          'singular_name' => I18n::t('Tag'),
          'all_items' => I18n::t('All Tags'),
          'edit_item' => I18n::t('Edit Tag'),
          'view_item' => I18n::t('View Tag'),
          'update_item' => I18n::t('Update Tag'),
          'add_new_item' => I18n::t('Add New Tag'),
          'new_item_name' => I18n::t('New Tag'),
          'parent_item' => I18n::t('Parent Tag'),
          'parent_item_colon' => I18n::t('Parent Tag:'),
          'search_items' =>  I18n::t('Search Tags'),
          'popular_items' => I18n::t('Popular Tags'),
          'separate_items_with_commas' => I18n::t('Separate Tags with commas'),
          'add_or_remove_items' => I18n::t('Add or remove Tags'),
          'choose_from_most_used' => I18n::t('Choose from the most used Tags'),
          'not_found' => I18n::t('No Tags found.')
        ),
        'show_admin_column' => True,
        'hierarchical' => False,
        'show_ui' => True,
        'query_var' => True,
        'rewrite' => Array(
          'with_front' => False,
          'slug' => SPrintF(I18n::t('%s/tag', 'URL slug'), I18n::t('galleries', 'URL slug'))
        ),
        'capabilities' => Array (
          'manage_terms' => 'manage_gallery_tags',
          'edit_terms' => 'manage_gallery_tags',
          'delete_terms' => 'manage_gallery_tags',
          'assign_terms' => 'edit_galleries'
        )
      ),

      'gallery-event' => Array(
        'label' => I18n::t( 'Gallery Events' ),
        'labels' => Array(
          'name' => I18n::t('Events'),
          'singular_name' => I18n::t('Event'),
          'all_items' => I18n::t('All Events'),
          'edit_item' => I18n::t('Edit Event'),
          'view_item' => I18n::t('View Event'),
          'update_item' => I18n::t('Update Event'),
          'add_new_item' => I18n::t('Add New Event'),
          'new_item_name' => I18n::t('New Event'),
          'parent_item' => I18n::t('Parent Event'),
          'parent_item_colon' => I18n::t('Parent Event:'),
          'search_items' =>  I18n::t('Search Events'),
          'popular_items' => I18n::t('Popular Events'),
          'separate_items_with_commas' => I18n::t('Separate Events with commas'),
          'add_or_remove_items' => I18n::t('Add or remove Events'),
          'choose_from_most_used' => I18n::t('Choose from the most used Events'),
          'not_found' => I18n::t('No Events found.')
        ),
        'show_admin_column' => True,
        'hierarchical' => False,
        'show_ui' => True,
        'query_var' => True,
        'rewrite' => Array(
          'with_front' => False,
          'slug' => SPrintF(I18n::t('%s/event', 'URL slug'), I18n::t('galleries', 'URL slug'))
        ),
        'capabilities' => Array (
          'manage_terms' => 'manage_gallery_events',
          'edit_terms' => 'manage_gallery_events',
          'delete_terms' => 'manage_gallery_events',
          'assign_terms' => 'edit_galleries'
        )
      ),

      'gallery-place' => Array(
        'label' => I18n::t( 'Gallery Places' ),
        'labels' => Array(
          'name' => I18n::t('Places'),
          'singular_name' => I18n::t('Place'),
          'all_items' => I18n::t('All Places'),
          'edit_item' => I18n::t('Edit Place'),
          'view_item' => I18n::t('View Place'),
          'update_item' => I18n::t('Update Place'),
          'add_new_item' => I18n::t('Add New Place'),
          'new_item_name' => I18n::t('New Place'),
          'parent_item' => I18n::t('Parent Place'),
          'parent_item_colon' => I18n::t('Parent Place:'),
          'search_items' =>  I18n::t('Search Places'),
          'popular_items' => I18n::t('Popular Places'),
          'separate_items_with_commas' => I18n::t('Separate Places with commas'),
          'add_or_remove_items' => I18n::t('Add or remove Places'),
          'choose_from_most_used' => I18n::t('Choose from the most used Places'),
          'not_found' => I18n::t('No Places found.')
        ),
        'show_admin_column' => True,
        'hierarchical' => False,
        'show_ui' => True,
        'query_var' => True,
        'rewrite' => Array(
          'with_front' => False,
          'slug' => SPrintF(I18n::t('%s/place', 'URL slug'), I18n::t('galleries', 'URL slug'))
        ),
        'capabilities' => Array (
          'manage_terms' => 'manage_gallery_places',
          'edit_terms' => 'manage_gallery_places',
          'delete_terms' => 'manage_gallery_places',
          'assign_terms' => 'edit_galleries'
        )
      ),

      'gallery-date' => Array(
        'label' => I18n::t('Gallery Dates'),
        'labels' => Array(
          'name' => I18n::t('Dates'),
          'singular_name' => I18n::t('Date'),
          'all_items' => I18n::t('All Dates'),
          'edit_item' => I18n::t('Edit Date'),
          'view_item' => I18n::t('View Date'),
          'update_item' => I18n::t('Update Date'),
          'add_new_item' => I18n::t('Add New Date'),
          'new_item_name' => I18n::t('New Date'),
          'parent_item' => I18n::t('Parent Date'),
          'parent_item_colon' => I18n::t('Parent Date:'),
          'search_items' =>  I18n::t('Search Dates'),
          'popular_items' => I18n::t('Popular Dates'),
          'separate_items_with_commas' => I18n::t('Separate Dates with commas'),
          'add_or_remove_items' => I18n::t('Add or remove Dates'),
          'choose_from_most_used' => I18n::t('Choose from the most used Dates'),
          'not_found' => I18n::t('No Dates found.')
        ),
        'show_admin_column' => True,
        'hierarchical' => False,
        'show_ui' => True,
        'query_var' => True,
        'rewrite' => Array(
          'with_front' => False,
          'slug' => SPrintF(I18n::t('%s/date', 'URL slug'), I18n::t('galleries', 'URL slug'))
        ),
        'capabilities' => Array (
          'manage_terms' => 'manage_gallery_dates',
          'edit_terms' => 'manage_gallery_dates',
          'delete_terms' => 'manage_gallery_dates',
          'assign_terms' => 'edit_galleries'
        )
      ),

      'gallery-person' => Array(
        'label' => I18n::t('Gallery Persons'),
        'labels' => Array(
          'name' => I18n::t('Persons'),
          'singular_name' => I18n::t('Person'),
          'all_items' => I18n::t('All Persons'),
          'edit_item' => I18n::t('Edit Person'),
          'view_item' => I18n::t('View Person'),
          'update_item' => I18n::t('Update Person'),
          'add_new_item' => I18n::t('Add New Person'),
          'new_item_name' => I18n::t('New Person'),
          'parent_item' => I18n::t('Parent Person'),
          'parent_item_colon' => I18n::t('Parent Person:'),
          'search_items' =>  I18n::t('Search Persons'),
          'popular_items' => I18n::t('Popular Persons'),
          'separate_items_with_commas' => I18n::t('Separate Persons with commas'),
          'add_or_remove_items' => I18n::t('Add or remove Persons'),
          'choose_from_most_used' => I18n::t('Choose from the most used Persons'),
          'not_found' => I18n::t('No Persons found.')
        ),
        'show_admin_column' => True,
        'hierarchical' => False,
        'show_ui' => True,
        'query_var' => True,
        'rewrite' => Array(
          'with_front' => False,
          'slug' => SPrintF(I18n::t('%s/person', 'URL slug'), I18n::t('galleries', 'URL slug'))
        ),
        'capabilities' => Array (
          'manage_terms' => 'manage_gallery_persons',
          'edit_terms' => 'manage_gallery_persons',
          'delete_terms' => 'manage_gallery_persons',
          'assign_terms' => 'edit_galleries'
        )
      ),

      'gallery-photographer' => Array(
        'label' => I18n::t('Gallery Photographers'),
        'labels' => Array(
          'name' => I18n::t('Photographers'),
          'singular_name' => I18n::t('Photographer'),
          'all_items' => I18n::t('All Photographers'),
          'edit_item' => I18n::t('Edit Photographer'),
          'view_item' => I18n::t('View Photographer'),
          'update_item' => I18n::t('Update Photographer'),
          'add_new_item' => I18n::t('Add New Photographer'),
          'new_item_name' => I18n::t('New Photographer'),
          'parent_item' => I18n::t('Parent Photographer'),
          'parent_item_colon' => I18n::t('Parent Photographer:'),
          'search_items' =>  I18n::t('Search Photographers'),
          'popular_items' => I18n::t('Popular Photographers'),
          'separate_items_with_commas' => I18n::t('Separate Photographers with commas'),
          'add_or_remove_items' => I18n::t('Add or remove Photographers'),
          'choose_from_most_used' => I18n::t('Choose from the most used Photographers'),
          'not_found' => I18n::t('No Photographers found.')
        ),
        'show_admin_column' => True,
        'hierarchical' => False,
        'show_ui' => True,
        'query_var' => True,
        'rewrite' => Array(
          'with_front' => False,
          'slug' => SPrintF(I18n::t('%s/photographer', 'URL slug'), I18n::t('galleries', 'URL slug'))
        ),
        'capabilities' => Array (
          'manage_terms' => 'manage_gallery_photographers',
          'edit_terms' => 'manage_gallery_photographers',
          'delete_terms' => 'manage_gallery_photographers',
          'assign_terms' => 'edit_galleries'
        )
      )
    );
  }

  static function registerTaxonomies(){
    # Load Taxonomies
    $arr_taxonomies = self::getTaxonomies();
    $arr_taxonomies = Apply_Filters('gallery-manager-taxonomies', $arr_taxonomies);

    # Check the enabled taxonomies
    $enabled_taxonomies = Options::Get('gallery_taxonomies');
    setType($enabled_taxonomies, 'ARRAY');

    if (Empty($enabled_taxonomies)) return False;

    # Register Taxonomies
    foreach ($enabled_taxonomies as $taxonomie => $attributes){
      if (isSet($arr_taxonomies[$taxonomie])){
        Register_Taxonomy($taxonomie, Gallery_Post_Type::post_type_name, Array_Merge($arr_taxonomies[$taxonomie], $attributes));
      }
    }
  }

  static function addTaxonomyArchiveUrls(){
    foreach (get_Object_Taxonomies(Gallery_Post_Type::post_type_name) as $taxonomy){
      add_Action ($taxonomy.'_edit_form_fields', Array(__CLASS__, 'printTaxonomyArchiveUrls'), 10, 3);
    }
  }

  static function printTaxonomyArchiveUrls($tag, $taxonomy){
    $taxonomy = get_Taxonomy($taxonomy);
    $archive_url = get_Term_Link(get_Term($tag->term_id, $taxonomy->name));
    $archive_feed = get_Term_Feed_Link($tag->term_id, $taxonomy->name);
    ?>
    <tr class="form-field">
      <th scope="row" valign="top"><?php Echo I18n::t('Archive Url') ?></th>
      <td>
        <a href="<?php Echo $archive_url ?>" target="_blank"><?php Echo $archive_url ?></a><br>
        <span class="description"><?php PrintF(I18n::t('This is the URL to the archive of this %s.'), $taxonomy->labels->singular_name) ?></span>
      </td>
    </tr>
    <tr class="form-field">
      <th scope="row" valign="top"><?php Echo I18n::t('Archive Feed') ?></th>
      <td>
        <a href="<?php Echo $archive_feed ?>" target="_blank"><?php Echo $archive_feed ?></a><br />
        <span class="description"><?php PrintF(I18n::t('This is the URL to the feed of the archive of this %s.'), $taxonomy->labels->singular_name) ?></span>
      </td>
    </tr>
    <?php
  }

  static function changeTaxonomyMenuLabel($tax){
    if (isSet($tax->object_type) && in_Array(Gallery_Post_Type::post_type_name, $tax->object_type)){
      $gallery_post_type_object = get_Post_Type_Object(Gallery_Post_Type::post_type_name);
      $tax->labels->name = SPrintF('%1$s &raquo; %2$s', $gallery_post_type_object->label, $tax->labels->name);
    }
    return $tax;
  }

  static function updateTaxonomyNames(){
    Global $wpdb;

    $arr_rename = Array(
      'gallery_category' => 'gallery-category',
      'gallery_tag' => 'gallery-tag',
      'gallery_event' => 'gallery-event',
      'gallery_place' => 'gallery-place',
      'gallery_date' => 'gallery-date',
      'gallery_person' => 'gallery-person',
      'gallery_photographer' => 'gallery-photographer'
    );

    foreach ($arr_rename as $rename_from => $rename_to){
      $wpdb->Update($wpdb->term_taxonomy, Array('taxonomy' => $rename_to), Array('taxonomy' => $rename_from));
    }
	}

}

Gallery_Taxonomies::init();
