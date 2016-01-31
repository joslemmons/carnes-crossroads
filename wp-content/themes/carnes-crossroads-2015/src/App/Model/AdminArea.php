<?php namespace App\Model;

class AdminArea
{
    private static $_noticesUpdateNag = array();
    private static $_noticesUpdated = array();
    private static $_genericNotice = array();

    public static function init()
    {
        add_action('login_enqueue_scripts', array(get_class(), 'customizeLoginScreen'));
        add_filter('custom_menu_order', array(get_class(), 'customMenuOrder'));
        add_action('admin_menu', array(get_class(), 'customizeAdminMenus'));
        add_filter('menu_order', array(get_class(), 'customMenuOrder'));
        add_action('jetpack_admin_menu', array(get_class(), 'removeUserMenus'));
        add_action('wp_before_admin_bar_render', array(get_class(), 'customizeAdminBar'));
        add_action('admin_notices', array(get_class(), 'displayNotices'));

        self::_registerClientRole();
        self::_registerEventManagerRole();
        self::_addClientCapabilityToAdmin();
    }

    public static function addUpdateNagNotice($message)
    {
        self::$_noticesUpdateNag[] = $message;
    }

    public static function addUpdatedNotice($message)
    {
        self::$_noticesUpdated[] = $message;
    }

    public static function addGenericNotice($message)
    {
        self::$_genericNotice[] = $message;
    }

    public static function displayNotices()
    {
        $used_messages = array();
        foreach (self::$_noticesUpdateNag as $message) {
            if (in_array($message, $used_messages) === false) {
                $used_messages[] = $message;
                echo "<div class='update-nag'><p>{$message}</p></div>";
            }
        }

        foreach (self::$_noticesUpdated as $message) {
            if (in_array($message, $used_messages) === false) {
                $used_messages[] = $message;
                echo "<div class='updated'><p>{$message}</p></div>";
            }
        }

        foreach (self::$_genericNotice as $message) {
            if (in_array($message, $used_messages) === false) {
                $used_messages[] = $message;
                echo "<div class='notice'><p>{$message}</p></div>";
            }
        }
    }

    public static function _addClientCapabilityToAdmin()
    {
        $role_object = get_role('administrator');
        $role_object->add_cap('manage_content');
    }

    public static function _registerClientRole()
    {
        remove_role('content_admin');
        add_role(
            'content_admin',
            __('Content Admin'),
            array(
                'activate_plugins' => false,
                'delete_others_pages' => true,
                'delete_others_posts' => true,
                'delete_pages' => true,
                'delete_plugins' => false,
                'delete_posts' => true,
                'delete_private_pages' => true,
                'delete_private_posts' => true,
                'delete_published_pages' => true,
                'delete_published_posts' => true,
                'delete_themes' => false,
                'edit_dashboard' => false,
                'edit_others_pages' => true,
                'edit_others_posts' => true,
                'edit_pages' => true,
                'edit_plugins' => false,
                'edit_posts' => true,
                'edit_private_pages' => true,
                'edit_private_posts' => true,
                'edit_published_pages' => true,
                'edit_published_posts' => true,
                'edit_theme_options' => true,
                'edit_themes' => false,
                'export' => false,
                'import' => false,
                'install_plugins' => false,
                'install_themes' => false,
                'manage_categories' => true,
                'manage_links' => false,
                'manage_options' => false,
                'moderate_comments' => false,
                'publish_pages' => true,
                'publish_posts' => true,
                'read' => true,
                'read_private_pages' => true,
                'read_private_posts' => true,
                'switch_themes' => false,
                'unfiltered_html' => false,
                'unfiltered_upload' => false,
                'update_core' => false,
                'update_plugins' => false,
                'update_themes' => false,
                'upload_files' => true,
                'manage_content' => true,
                'edit_tribe_event' => true,
                'read_tribe_event' => true,
                'delete_tribe_event' => true,
                'delete_tribe_events' => true,
                'edit_tribe_events' => true,
                'edit_others_tribe_events' => true,
                'delete_others_tribe_events' => true,
                'publish_tribe_events' => true,
                'edit_published_tribe_events' => true,
                'delete_published_tribe_events' => true,
                'delete_private_tribe_events' => true,
                'edit_private_tribe_events' => true,
                'read_private_tribe_events' => true,
                'edit_tribe_venue' => true,
                'read_tribe_venue' => true,
                'delete_tribe_venue' => true,
                'delete_tribe_venues' => true,
                'edit_tribe_venues' => true,
                'edit_others_tribe_venues' => true,
                'delete_others_tribe_venues' => true,
                'publish_tribe_venues' => true,
                'edit_published_tribe_venues' => true,
                'delete_published_tribe_venues' => true,
                'delete_private_tribe_venues' => true,
                'edit_private_tribe_venues' => true,
                'read_private_tribe_venues' => true,
                'edit_tribe_organizer' => true,
                'read_tribe_organizer' => true,
                'delete_tribe_organizer' => true,
                'delete_tribe_organizers' => true,
                'edit_tribe_organizers' => true,
                'edit_others_tribe_organizers' => true,
                'delete_others_tribe_organizers' => true,
                'publish_tribe_organizers' => true,
                'edit_published_tribe_organizers' => true,
                'delete_published_tribe_organizers' => true,
                'delete_private_tribe_organizers' => true,
                'edit_private_tribe_organizers' => true,
                'read_private_tribe_organizers' => true,
                'gravityforms_edit_forms' => true,
                'gravityforms_delete_forms' => true,
                'gravityforms_create_form' => true,
                'gravityforms_view_entries' => true,
                'gravityforms_edit_entries' => true,
                'gravityforms_delete_entries' => true,
                'gravityforms_view_settings' => true,
                'gravityforms_edit_settings' => true,
                'gravityforms_export_entries' => true,
                'gravityforms_uninstall' => false,
                'gravityforms_view_entry_notes' => true,
                'gravityforms_edit_entry_notes' => true,
                'gravityforms_view_updates' => true,
                'gravityforms_view_addons' => true,
                'gravityforms_preview_forms' => true,
            )
        );
    }

    private static function _registerEventManagerRole()
    {
        remove_role('events_admin');
        add_role(
            'events_admin',
            __('Events Admin'),
            array(
                'activate_plugins' => false,
                'add_users' => false,
                'create_users' => false,
                'delete_others_pages' => false,
                'delete_others_posts' => false,
                'delete_pages' => false,
                'delete_plugins' => false,
                'delete_posts' => false,
                'delete_private_pages' => false,
                'delete_private_posts' => false,
                'delete_published_pages' => false,
                'delete_published_posts' => false,
                'delete_themes' => false,
                'delete_users' => false,
                'edit_dashboard' => false,
                'edit_others_pages' => false,
                'edit_others_posts' => false,
                'edit_pages' => false,
                'edit_plugins' => false,
                'edit_posts' => false,
                'edit_private_pages' => false,
                'edit_private_posts' => false,
                'edit_published_pages' => false,
                'edit_published_posts' => false,
                'edit_theme_options' => false,
                'edit_themes' => false,
                'edit_users' => false,
                'export' => false,
                'import' => false,
                'install_plugins' => false,
                'install_themes' => false,
                'list_users' => false,
                'manage_categories' => false,
                'manage_links' => false,
                'manage_options' => false,
                'moderate_comments' => false,
                'promote_users' => false,
                'publish_pages' => false,
                'publish_posts' => false,
                'read' => true,
                'read_private_pages' => false,
                'read_private_posts' => false,
                'remove_users' => false,
                'switch_themes' => false,
                'unfiltered_html' => false,
                'unfiltered_upload' => false,
                'update_core' => false,
                'update_plugins' => false,
                'update_themes' => false,
                'upload_files' => false,
                'manage_content' => false,
                'edit_tribe_event' => true,
                'read_tribe_event' => true,
                'delete_tribe_event' => true,
                'delete_tribe_events' => true,
                'edit_tribe_events' => true,
                'edit_others_tribe_events' => true,
                'delete_others_tribe_events' => true,
                'publish_tribe_events' => true,
                'edit_published_tribe_events' => true,
                'delete_published_tribe_events' => true,
                'delete_private_tribe_events' => true,
                'edit_private_tribe_events' => true,
                'read_private_tribe_events' => true,
                'edit_tribe_venue' => true,
                'read_tribe_venue' => true,
                'delete_tribe_venue' => true,
                'delete_tribe_venues' => true,
                'edit_tribe_venues' => true,
                'edit_others_tribe_venues' => true,
                'delete_others_tribe_venues' => true,
                'publish_tribe_venues' => true,
                'edit_published_tribe_venues' => true,
                'delete_published_tribe_venues' => true,
                'delete_private_tribe_venues' => true,
                'edit_private_tribe_venues' => true,
                'read_private_tribe_venues' => true,
                'edit_tribe_organizer' => true,
                'read_tribe_organizer' => true,
                'delete_tribe_organizer' => true,
                'delete_tribe_organizers' => true,
                'edit_tribe_organizers' => true,
                'edit_others_tribe_organizers' => true,
                'delete_others_tribe_organizers' => true,
                'publish_tribe_organizers' => true,
                'edit_published_tribe_organizers' => true,
                'delete_published_tribe_organizers' => true,
                'delete_private_tribe_organizers' => true,
                'edit_private_tribe_organizers' => true,
                'read_private_tribe_organizers' => true
            )
        );
    }

    public static function customizeLoginScreen()
    {
        $login_logo_src = Config::getLoginLogoSrc();

        ?>
        <style type="text/css">
            body.login div#login h1 a {
                background-image: url(<?php echo $login_logo_src;  ?>);
                width: 115px;
                height: 115px;
            }
        </style>
        <?php
    }

    public static function customMenuOrder($menu_ord)
    {

        return array(
            'index.php', // Dashboard
            'profile.php',
            'separator1', // First separator
            'edit.php', // Posts
            'upload.php', // Media
            'link-manager.php', // Links
            'edit.php?post_type=page', // Pages
            'edit-comments.php', // Comments
            'separator2', // Second separator
            'themes.php', // Appearance
            'plugins.php', // Plugins
            'users.php', // Users
            'tools.php', // Tools
            'options-general.php', // Settings
            'separator-last', // Last separator
        );

    }

    public static function customizeAdminBar()
    {
        if (!current_user_can('activate_plugins')) {
            global $wp_admin_bar;

            $wp_admin_bar->remove_menu('wp-logo');
            $wp_admin_bar->remove_menu('comments');
            $wp_admin_bar->remove_menu('new-content');
        }
    }


    public static function removeUserMenus()
    {
        if (!current_user_can('activate_plugins')) {
            remove_menu_page('jetpack');
        }
    }

    public static function customizeAdminMenus()
    {
        if (!current_user_can('activate_plugins')) {
            global $submenu;

            remove_menu_page('edit-comments.php');
            remove_menu_page('tools.php');
            remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=post_tag');
            remove_submenu_page('themes.php', 'themes.php');
            unset($submenu['themes.php'][6]); // customize.php

            remove_menu_page('edit-tags.php?taxonomy=link_category');
            remove_menu_page('plugins.php');
            remove_menu_page('options-general.php');
            remove_menu_page('piklist');
        }

        global $menu;
        foreach ($menu as $key => $item) {
            if (isset($item[2]) && $item[2] === 'social-options') {
                $item[6] = 'dashicons-businessman';
            }

            if (isset($item[2]) && $item[2] === 'analytics-options') {
                $item[6] = 'dashicons-analytics';
            }

            $menu[$key] = $item;
        }
    }
}
