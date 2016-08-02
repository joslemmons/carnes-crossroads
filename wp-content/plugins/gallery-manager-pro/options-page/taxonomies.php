<?php Namespace WordPress\Plugin\GalleryManager ?>

<p><?php Echo I18n::t('Please select the taxonomies you want to use to classify your galleries.') ?></p>

<table>
<?php
$active_taxonomies = (Array) Options::Get('gallery_taxonomies');
foreach (Gallery_Taxonomies::getTaxonomies() AS $taxonomy => $tax_args): ?>
<tr>
  <td>
    <input type="checkbox" name="gallery_taxonomies[<?php Echo $taxonomy ?>][name]" id="gallery_taxonomies_<?php Echo $taxonomy ?>" value="<?php Echo $taxonomy ?>" <?php Checked(isSet($active_taxonomies[$taxonomy])) ?> ><label for="gallery_taxonomies_<?php Echo $taxonomy ?>"><?php Echo $tax_args['labels']['name'] ?></label>
  </td>
  <td>
    <input type="checkbox" name="gallery_taxonomies[<?php Echo $taxonomy ?>][hierarchical]" id="gallery_taxonomies_<?php Echo $taxonomy ?>_hierarchical" <?php Checked(isSet($active_taxonomies[$taxonomy]['hierarchical'])) ?>><label for="gallery_taxonomies_<?php Echo $taxonomy ?>_hierarchical"><?php Echo I18n::t('hierarchical') ?></label>
  </td>
</tr>
<?php endforeach ?>
</table>
