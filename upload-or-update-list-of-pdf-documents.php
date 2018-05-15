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
define('_PLUGIN_PATH_', plugin_dir_path( __FILE__ ));
define('_PLUGIN_URL_', plugin_dir_url( __FILE__ ));

add_action('init', 'uulpd_setup_initial');
//load css to frontend
add_action( 'wp_enqueue_scripts', 'uulpd_add_css');
//load scripts js to frontend
add_action( 'wp_enqueue_scripts', 'uulpd_add_scripts');
//Load custom template
add_action( 'plugins_loaded', array( 'LoadCustomTemplate', 'get_instance' ) );

/* Requisições Ajax */
add_action( 'wp_ajax_uulpd_query_pages', 'uulpd_query_pages' );

// Register activation/deactivation hooks
register_activation_hook( __FILE__, 'uulpd_activate_plugin' );
register_deactivation_hook( __FILE__, 'uulpd_deactivate_plugin' );
register_theme_directory( plugin_dir_path( __FILE__ ).'/includes' );

function uulpd_setup_initial(){
  /*Desabilitar barra de admin */
  if ( ! current_user_can( 'manage_options' ) ) {
    show_admin_bar( false );
  }
}

function uulpd_add_scripts($hook) {
    //wp_enqueue_script( "angularjs", _PLUGIN_URL_ . "assets/angular.min.js", "", "1.6.9", true );

    // Localize the script with new data
    /*$translation_array = array(
        'some_string' => __( 'Some string to translate', 'plugin-domain' ),
        'a_value' => '10'
    );
    wp_localize_script( 'some_handle', 'object_name', $translation_array );

    // Enqueued script with localized data.
    wp_enqueue_script( 'some_handle' );

    wp_register_script( "main", _PLUGIN_URL_ . "assets/main.js", "", "0.1", true );**/
}

function uulpd_add_css() {
  wp_enqueue_style( "bootstrap", "https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css", "", "media" );  
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

/* Retorna as páginas identificadas com o meta_key 'uulpd-pages' */
function uulpd_query_pages() {
    
    $get_pages = new WP_Query(array('posts_per_page' => -1, 'post_type' => 'page', 'meta_key' => 'uulpd_pages', 'meta_value' => 'true'));

    wp_reset_query();

    return $get_pages->posts;
}

/* Retorna os meta values de acordo com o ID selecionado */
function uulpd_current_files($id){

    $data = get_post_meta($id, 'uulpd_files');
    return $data;
}

/* Verifica se variavel contém conteúdo ou nulo */
function uulpd_print_filename($array, $key){
    if(count($array) <= 0){
        echo "Não cadastrado";
        return;
    }
}

/*  */
function uulpd_update_data_database(){
    
    if(! $_POST){
        return false;
    }

    if ( isset($_FILES["uulpd_files"]) ) {

        if( isset($_POST['form_id']) ){
            $page_id = $_POST['form_id'];
        }
        else{
            return false;
        }

        $path_array = wp_upload_dir();
        $path = $path_array['basedir'] . '/arquivos-fundos/' . $id;
        $pathInsert = $path_array['baseurl'] . '/arquivos-fundos/' . $id;
        if ( ! file_exists( $path ) ) {
            wp_mkdir_p( $path );
        }

        $target_path_sia = uniqid() . wpartisan_sanitize_file_name( $_FILES["file_ebook"]["name"] );

        if ( move_uploaded_file( $_FILES["file_ebook"]["tmp_name"], $path . "/" . $target_path_sia ) ) {
            chmod( $path . "/" . $target_path_sia, 0666 );

            $caminhoBanco = $pathInsert . "/" . $target_path_sia;
            $caminho = substr( $caminhoBanco, 0 );
        }
    } 
}

require_once( _PLUGIN_PATH_ . 'loadCustomTemplate.php' );