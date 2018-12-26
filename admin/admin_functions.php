<?php

if (!defined('ABSPATH')) {
    exit;
}

/*change the footer text*/
$adm_path = plugin_dir_path(__FILE__).'../../motivar_functions_child/admin';
$global_adm_path = plugin_dir_path(__FILE__);

function motivar_functions_footer_admin()
{
    echo apply_filters('motivar_admin_footer_filter', 'Powered by <a href="https://motivar.io/" target="_blank" title="Web Services Professionals Corfu">Motivar.io</a>, '.date('Y'));
}

add_filter('admin_footer_text', 'motivar_functions_footer_admin');

function motivar_functions_footer_shh()
{
    remove_filter('update_footer', 'core_update_footer');
}

add_action('admin_menu', 'motivar_functions_footer_shh');
add_action('admin_menu', 'motivar_functions_wphidenag');

function motivar_functions_wphidenag()
{
    if (!is_super_admin()) {
        remove_action('admin_notices', 'update_nag', 3);
    } else {
        require_once 'super_user.php';
    }
}

/*add information page*/

/*remove notifications for other users*/
add_action('admin_init', 'motivar_functions_check_myuser');
function motivar_functions_check_myuser()
{
    if (!is_super_admin()) {
        remove_action('load-update-core.php', 'wp_update_plugins');
        add_filter('pre_site_transient_update_plugins', '__return_null');
        add_filter('pre_option_update_core', '__return_null');
        add_filter('pre_site_transient_update_core', '__return_null');
        add_filter('pre_site_transient_update_themes', 'motivar_functions_core_updates');
        add_action('after_setup_theme', 'motivar_functions_core_updates');
    }
}

function motivar_functions_core_updates()
{
    global $wp_version;

    return(object) array('last_checked' => time(), 'version_checked' => $wp_version);
    add_action('init', create_function('$a', "remove_action( 'init', 'wp_version_check' );"), 2);
}

function motivar_functions_admin_site_enqueue_styles()
{
    $level = array();
    if (current_user_can('administrator')) {
        $level['user'] = 'admin';
    } else {
        $level['user'] = 'editor';
    }
    wp_enqueue_script('motivar-admin-myscript', plugin_dir_url(__FILE__).'../../motivar_functions_child/admin/myscript.js', array(), array(), false);
    wp_localize_script('motivar-admin-myscript', 'sbp_user_level', $level);

    wp_enqueue_style('motivar-admin-css', plugin_dir_url(__FILE__).'../../motivar_functions_child/admin/admin-style.css', true, '1.0.0');
    if (!current_user_can('administrator')) {
        wp_enqueue_style('motivar-editor-css', plugin_dir_url(__FILE__).'../../motivar_functions_child/admin/editor.css', true, '1.0.0');
    }
}

function motivar_functions_admin_enqueue_styles()
{
    wp_enqueue_script('motivar-admin-global-script', plugin_dir_url(__FILE__).'admin-script.js', array(), array(), false);
    wp_enqueue_style('motivar-admin-global', plugin_dir_url(__FILE__).'admin-global.css', true, '1.0.0');
    if (!is_super_admin()) {
        wp_enqueue_style('motivar-editor-global', plugin_dir_url(__FILE__).'editor-global.css', true, '1.0.0');
    }
}
add_action('admin_enqueue_scripts', 'motivar_functions_admin_enqueue_styles', 20);

if (file_exists($global_adm_path)) {
    $global_files = array('global/on_save/posts', 'global/on_save/taxonomies');
    foreach ($global_files as $ff) {
        $fil = $global_adm_path.'/'.$ff.'.php';
        if (file_exists($fil)) {
            require_once $fil;
        }
    }
}
if (file_exists($adm_path)) {
    /*check if exist child folder*/
    $files = array('meta/posts', 'meta/taxonomies', 'meta/media', 'on_save/taxonomies', 'on_save/media', 'on_save/posts', 'custom_db/register_tables', 'user/registration');
    foreach ($files as $ff) {
        $fil = $adm_path.'/'.$ff.'.php';
        if (file_exists($fil)) {
            require_once $fil;
        }
    }
    add_action('admin_enqueue_scripts', 'motivar_functions_admin_site_enqueue_styles', 20);
}

if (get_option('motivar_functions_map_key')) {
    add_action('acf/init', 'my_acf_init');
}

function my_acf_init()
{
    acf_update_setting('google_api_key', get_option('motivar_functions_map_key'));
}

function motivar_save_client_to_mailchimp($post_id, $contact_type)
{
    $industry = get_the_terms($post_id, 'mr_industry');

    $mcp_path = realpath(dirname(__FILE__).'/../../../motivar_functions/admin/');
    require_once $mcp_path.'/MCAPI.class.php';
    require_once $mcp_path.'/config.inc.php';
    $api = new MCAPI($apikey);
    $batch[] = array(
        'EMAIL' => get_field('client_email'),
        'NAME' => get_field('client_name'),
        'SURNAME' => get_field('client_surname'),
        'LAN' => strtoupper(get_field('client_language')),
        'AGE' => get_field('client_age'),
        'GENDER' => get_field('client_genre'),
        'INDUSTRY' => $industry[0]->name,
        'CONTACTYPE' => $contact_type,
        );

    $optin = false; //yes, send optin emails
    $up_exist = true; // yes, update currently subscribed users
    $replace_int = false; // no, add interest, don't replace
    $vals = $api->listBatchSubscribe($listId, $batch, $optin, $up_exist, $replace_int);
    if ($api->errorCode) {
        echo "Batch Subscribe failed!\n";
        echo 'code:'.$api->errorCode."\n";
        echo 'msg :'.$api->errorMessage."\n";
    } else {
    }
}
