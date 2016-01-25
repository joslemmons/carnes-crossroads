<?php
/*
 * Title: Account Page Options (Carnes Crossroads Theme 2015)
 * Id: 257
 */

use App\Model\AccountPage;

//piklist('field', array(
//    'type' => 'editor',
//    'field' => AccountPage::$field_what_s_new_content,
//    'label' => 'What\'s New Copy'
//));

piklist('field', array(
    'type' => 'editor',
    'field' => AccountPage::$field_create_account_content,
    'label' => 'Sign in / Create Account Copy'
));

piklist('field', array(
    'type' => 'editor',
    'field' => AccountPage::$field_favorites_content,
    'label' => 'Favorites Copy'
));

piklist('field', array(
    'type' => 'editor',
    'field' => AccountPage::$field_saved_searches_content,
    'label' => 'Saves Searches Copy'
));

piklist('field', array(
    'type' => 'editor',
    'field' => AccountPage::$field_notifications_on_listings_options_content,
    'label' => 'Notifications on Listings Copy'
));

//piklist('field', array(
//    'type' => 'editor',
//    'field' => AccountPage::$field_change_email_password_content,
//    'label' => 'Change Email/Password Copy'
//));
