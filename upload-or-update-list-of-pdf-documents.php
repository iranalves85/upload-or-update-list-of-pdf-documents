<?php
/*
Plugin Name: Upload or Update List of PDF's Documents
Description: Generate list of pdfs for download or other purposes
Version: 0.1
Author: Iran Alves
Author URI: makingpie.com.br
License: GPL2
Copyright (C) 2018 Iran
*/

ob_start();

// Constantes
define('_PLUGIN_ID_', 'uulpd_');

add_action( 'admin_menu', 'uulpd_menu_admin' );
add_action( 'admin_enqueue_scripts', 'add_theme_scripts_x2' );

//load css to frontend
add_action( 'wp_enqueue_scripts', 'load_specific_css_x2');

// Register activation/deactivation hooks
register_activation_hook( __FILE__, 'x2_download_activate' );
register_deactivation_hook( __FILE__, 'x2_download_deactivate' );
register_theme_directory( plugin_dir_path( __FILE__ ).'/includes' );

function add_theme_scripts_x2($hook) {
	wp_enqueue_style( 'custom-style',  plugins_url('/includes/style-admin.css', __FILE__) );
	wp_enqueue_style( 'datatables-style', 'https://cdn.datatables.net/v/dt/jqc-1.12.3/jqc-1.12.3/dt-1.10.16/datatables.min.css' );

	// Register the script like this for a plugin:
	wp_register_script( 'datatables-x2', 'https://cdn.datatables.net/v/dt/jqc-1.12.3/jqc-1.12.3/dt-1.10.16/datatables.min.js', array( 'jquery' ) );
	
  if( in_array( $hook, array("meus-downloads_page_view-all", "meus-downloads_page_my-reports" ) ) ) {
    wp_enqueue_script( 'datatables-x2' );
  }
}


function load_specific_css_x2() {
  wp_enqueue_style( 'custom-style',  plugins_url('/includes/x2-download.css', __FILE__) );
}

	/**
	 * Add a menu at admin sidebar
	 *
	 * @since  0.1
	 */
  function uulpd_menu_admin() {
    
    //Menu
    add_menu_page( 'PDF Lists', 'PDF Lists', 'manage_options', 'uulpd-lists', '', plugins_url('/includes/icon.png', __FILE__) );
    
    //Submenu
    add_submenu_page( 'uulpd-lists', 'Nova Lista', 'Nova Lista', 'manage_options', 'uulpd-new-list', 'x2_download_create' );
    
  }

	/**
	 * Activate plugin
	 *
	 * @since  0.1
	 */
	function uulpd_activate_plugin() {
		
    
	}

		/**
	 * Desactivate plugin
	 *
	 * @since  0.1
	 */
	function uulpd_deactivate_plugin() {
		

  }
  
  //Logando usuário e verificando
  function  uulpd_custom_login() {

    //Verifica se informações foram enviadas e faz o login
    if( isset($_POST['log']) && isset($_POST['pwd']) ):
        $credentials = array(
            'user_login' => filter_var($_POST['log'], FILTER_SANITIZE_STRING),
            'user_password' => filter_var($_POST['pwd'], FILTER_SANITIZE_STRING),
            'remember' => true
        );
        $user = wp_signon( $credentials );

        if(is_wp_error($user)){
            $GLOBALS['loginReturn'] = $user;
            return;
        }

        if(is_a($user, 'WP_User')){
            wp_set_current_user( $user->ID, $user->user_login );
            wp_set_auth_cookie( $user->ID, true, false );
            do_action( 'wp_login', $user->user->login );
        }

    endif;
    
  }
  // run it before the headers and cookies are sent
  add_action( 'send_headers', 'uulpd_custom_login' );

  /* Disabilitar barra de admin */
  if ( ! current_user_can( 'manage_options' ) ) {
    show_admin_bar( false );
  }


  /**
   * Produces cleaner filenames for uploads
   *
   * @param  string $filename
   * @return string
   */
  function uulpd_sanitize_file_name( $filename ) {
  
      $sanitized_filename = remove_accents( $filename ); // Convert to ASCII
  
      // Standard replacements
      $invalid = array(
          ' '   => '-',
          '%20' => '-',
          '_'   => '-',
      );
      $sanitized_filename = str_replace( array_keys( $invalid ), array_values( $invalid ), $sanitized_filename );
  
      $sanitized_filename = preg_replace('/[^A-Za-z0-9-\. ]/', '', $sanitized_filename); // Remove all non-alphanumeric except .
      $sanitized_filename = preg_replace('/\.(?=.*\.)/', '', $sanitized_filename); // Remove all but last .
      $sanitized_filename = preg_replace('/-+/', '-', $sanitized_filename); // Replace any more than one - in a row
      $sanitized_filename = str_replace('-.', '.', $sanitized_filename); // Remove last - if at the end
      $sanitized_filename = strtolower( $sanitized_filename ); // Lowercase
  
      return $sanitized_filename;
  }

  function removeDirectory($path) {
    $files = glob($path . '/*');
    foreach ($files as $file) {
      is_dir($file) ? removeDirectory($file) : unlink($file);
    }
    rmdir($path);
    return;
  }

define( 'MY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
require_once( MY_PLUGIN_PATH . 'x2-download-create.php' );

require_once( MY_PLUGIN_PATH . 'x2-download-list.php' );
require_once( MY_PLUGIN_PATH . 'x2-download-update.php' );

require_once( MY_PLUGIN_PATH . 'x2-download-report.php' );
require_once( MY_PLUGIN_PATH . 'x2-download-report-user.php' );

require_once( MY_PLUGIN_PATH . 'loadCustomTemplate.php' );

add_action( 'plugins_loaded', array( 'LoadCustomTemplate', 'get_instance' ) );