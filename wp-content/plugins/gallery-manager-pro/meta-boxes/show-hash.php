<?php Namespace WordPress\Plugin\GalleryManager ?>

<p><?php Echo I18n::t('To start this gallery in a lightbox by clicking a link you can link to this <em>#hash</em>:') ?></p>
<p><input type="text" class="gallery-hash" value="#gallery-<?php Echo get_The_ID() ?>" readonly="readonly"></p>
<p><small><?php Echo I18n::t('Just use this hash as link target (href).') ?></small></p>
