<?php Namespace WordPress\Plugin\GalleryManager;

# load post type object
$post_type = get_Post_Type_Object(Gallery_Post_Type::post_type_name);
if (!$post_type) return;

# create capabilities list
$arr_capabilities = Array(
  $post_type->cap->edit_posts => I18n::t('Edit and create (own) galleries'),
  $post_type->cap->edit_others_posts => I18n::t('Edit others galleries'),
  $post_type->cap->edit_private_posts => I18n::t('Edit (own) private galleries'),
  $post_type->cap->edit_published_posts => I18n::t('Edit (own) published galleries'),

  $post_type->cap->delete_posts => I18n::t('Delete (own) galleries'),
  $post_type->cap->delete_private_posts => I18n::t('Delete (own) private galleries'),
  $post_type->cap->delete_published_posts => I18n::t('Delete (own) published galleries'),
  $post_type->cap->delete_others_posts => I18n::t('Delete others galleries'),

  $post_type->cap->publish_posts => I18n::t('Publish galleries'),
  $post_type->cap->read_private_posts => I18n::t('View (others) private galleries')
);

# Taxonomies
foreach (Get_Object_Taxonomies(Gallery_Post_Type::post_type_name) as $taxonomy){
  $taxonomy = get_Taxonomy($taxonomy);
  $arr_capabilities[$taxonomy->cap->manage_terms] = SPrintF(I18n::t('Manage %s'), $taxonomy->labels->name);
}

# Show the user roles
foreach ($GLOBALS['wp_roles']->roles AS $role_name => $arr_role): ?>
  <h4><?php Echo Translate_User_Role($arr_role['name']) ?></h4>

  <?php foreach ($arr_capabilities AS $capability => $caption): ?>
  <div class="capability-selection">
    <input type="hidden" name="capabilities[<?php Echo $role_name ?>][<?php Echo $capability ?>]" value="no">
    <input type="checkbox" name="capabilities[<?php Echo $role_name ?>][<?php Echo $capability ?>]" id="capabilities[<?php Echo $role_name ?>][<?php Echo $capability ?>][yes]" value="yes" <?php Checked(isSet($arr_role['capabilities'][$capability])) ?> >
    <label class="caption" for="capabilities[<?php Echo $role_name ?>][<?php Echo $capability ?>][yes]"><?php Echo $caption ?></label>
  </div>
  <?php endforeach ?>

<?php endforeach;
