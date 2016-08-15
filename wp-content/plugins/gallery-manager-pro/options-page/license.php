<?php Namespace WordPress\Plugin\GalleryManager ?>

<p><?php Echo I18n::t('Please enter the license data (username and password) you got within the purchase of this plugin.') ?></p>

<table>
<tr>
  <td><label for="update_username"><?php _e('Username') ?></label></td>
  <td><input type="text" name="update_username" id="update_username" value="<?php Echo Options::Get('update_username') ?>" placeholder="<?php _e('Username') ?>"></td>
</tr>
<tr>
  <td><label for="update_password"><?php _e('Password') ?></label></td>
  <td><input type="password" name="update_password" id="update_password" value="<?php Echo Options::Get('update_password') ?>" placeholder="<?php _e('Password') ?>"></td>
</tr>
</table>

<p><?php Echo I18n::t('When you change your username or password on our website you need to come back and update it here too.') ?></p>
