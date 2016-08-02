<?php Namespace WordPress\Plugin\GalleryManager ?>

<p>
  <?php Echo I18n::t('Gallery previews are randomly chosen images from a gallery.') ?>
  <?php Echo I18n::t('They will be shown where your theme would display a text excerpt for regular posts usually.') ?>
</p>

<?php
/*
$arr_previews = Array(
  'enable_previews_gallery_single' => I18n::t('Enable previews for the gallery single page'),
  'enable_previews_gallery_archive' => I18n::t('Enable previews for gallery archives'),
  'enable_previews_gallery_taxonomy' => I18n::t('Enable previews for gallery taxonomy archives'),
  'enable_previews_gallery_search' => I18n::t('Enable previews for gallery search results'),
  'enable_previews_global_search' => I18n::t('Enable previews for global search results'),
);

foreach ($arr_previews as $preview_option => $preview_caption): ?>
<p>
  <input type="hidden" name="<?php Echo $preview_option ?>" value="0">
  <input type="checkbox" name="<?php Echo $preview_option ?>" id="<?php Echo $preview_option ?>" value="1" <?php Checked(Options::Get($preview_option)) ?>>
  <label for="<?php Echo $preview_option ?>"><?php Echo $preview_caption ?></label>
</p>
<?php endforeach */ ?>

<p>
  <input type="hidden" name="enable_previews" value="0">
  <input type="checkbox" name="enable_previews" id="enable_previews" value="1" <?php Checked(Options::Get('enable_previews')) ?> data-toggle="div#preview-options">
  <label for="enable_previews"><?php Echo I18n::t('Enable previews for auto generated excerpts.') ?></label>
</p>

<div id="preview-options">
  <table class="form-table">
    <tr>
      <th><label for="preview_image_number"><?php Echo I18n::t('Number of images') ?></label></th>
      <td><input type="number" name="preview_image_number" id="preview_image_number" value="<?php Echo Esc_Attr(Options::Get('preview_image_number')) ?>" min="1"></td>
    </tr>

    <tr>
      <th><label for="preview_columns"><?php _e('Columns') ?></label></th>
      <td><select name="preview_columns" id="preview_columns" class="number">
        <?php $selected = Options::Get('preview_columns'); for ($columns = 1; $columns < 10; $columns++): ?>
        <option value="<?php Echo $columns ?>" <?php Selected($selected, $columns) ?>><?php Echo $columns ?></option>
        <?php endfor ?>
      </select></td>
    </tr>

    <tr>
      <th><label for="preview_thumb_size"><?php Echo I18n::t('Size') ?></label></th>
      <td><?php Echo Thumbnails::getDropdown(Array(
        'name' => 'preview_thumb_size',
        'id' => 'preview_thumb_size',
        'selected' => Options::Get('preview_thumb_size')
      )) ?></td>
    </tr>
  </table>

  <p>
    <input type="hidden" name="enable_previews_for_custom_excerpts" value="0">
    <input type="checkbox" name="enable_previews_for_custom_excerpts" id="enable_previews_for_custom_excerpts" value="1" <?php Checked(Options::Get('enable_previews_for_custom_excerpts')) ?>>
    <label for="enable_previews_for_custom_excerpts"><?php Echo I18n::t('Enable previews for custom excerpts too. (Do not activate this option if your theme displays the excerpt and the content on the same page.)') ?></label>
  </p>

</div>
