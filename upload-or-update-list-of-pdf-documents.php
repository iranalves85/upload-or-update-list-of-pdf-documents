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
define('_VALID_FIELDS_', serialize(array('formulario','regulamento','relatorio_performance','lamina','download_cotas', 'como_investir')));

$GLOBALS['valid_fields'] = unserialize(_VALID_FIELDS_);

add_action('init', 'uulpd_setup_initial');
//load css to frontend
add_action( 'wp_enqueue_scripts', 'uulpd_add_css');
//load scripts js to frontend
add_action( 'wp_enqueue_scripts', 'uulpd_add_scripts');
//Load custom template
add_action( 'plugins_loaded', array( 'LoadCustomTemplate', 'get_instance' ) );

/* Requisições Ajax */
add_action( 'wp_ajax_uulpd_query_pages', 'uulpd_query_pages' );

//Registrando shortcodes
add_shortcode('uulpd_page_file', 'uulpd_shortcode');

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

    //Meta Keys Válidas
    global $valid_fields;
    $meta_keys = $valid_fields;

    //Percorre array e pega meta_data do BD
    foreach ($meta_keys as $key) {
        $data[$key] = get_post_meta($id, $key, true);
    }

    return $data;
}

/* Verifica se variavel contém conteúdo ou nulo */
function uulpd_print_filename($array, $key){

    $meta_value = $array[$key];
    
    if (empty($meta_value)) {
        _e('Não cadastrado', 'uulpd');
        return;
    }

    if (array_key_exists('file', $meta_value)) {
        echo "<a class='btn btn-md btn-info' target='_blank' href='" .  $meta_value['file'] . "'>" . _x('Arquivo Cadastrado', 'uulpd') . "</a>";
        return;
    }

    if (array_key_exists('url', $meta_value)) {
        echo $meta_value['url'];
        return;        
    }    

}

/* Check se checkbox ativo ou não */
function uulpd_hide_show($array, $key) {

    $meta_value = $array[$key];
    
    if (empty($meta_value)) {
        return;
    }

    if (array_key_exists('hide', $meta_value) && $meta_value['hide'] == 'on') {
        echo " checked='checked'";
        return;
    }  

}

/* Adiciona ou Atualiza os dados no BD */
function uulpd_update_data_database(){
    
    //Verifica se houve envio de http
    if (!isset($_POST['uulpd_files'])) {
        return false;
    }

    //Validar o formulário de envio
    if ( !wp_verify_nonce($_POST['uulpd_add_edit_files'], 'uulpd' ) ) {
        return false;
    }

    //Se não existir ID, retorna erro
    if ( isset($_POST['uulpd_page_id']) ) {
        $page_id = $_POST['uulpd_page_id'];
    }
    else {
        return false;
    }

    $fields = array(); //Arquivo para armazenar dados dos campos
    $msg = '';
    global $valid_fields;

    //Se foi submetido arquivos via POST
    if ( isset($_FILES['uulpd_files']) ) {
        
        //Percorre array de arquivos enviados e adiciona os arquivos a página
        foreach ( $_FILES['uulpd_files']['name'] as $key => $value ) {
            
            //Adiciona array a variável
            $file = $_FILES["uulpd_files"]["name"][$key]['file'];
            
            //Adicionar arquivo de array, verifica valor e vai proxima $key
            if ( empty($file) ) {
                continue;
            }

            //Configurando caminhos para upload
            $fileTemp = $_FILES["uulpd_files"]["tmp_name"][$key]['file'];
            $path_array = wp_upload_dir();
            $path = $path_array['basedir'] . '/arquivos-fundos';
            $pathInsert = $path_array['baseurl'] . '/arquivos-fundos';
            
            //Se arquivo não existir cria diretório
            if ( ! file_exists( $path ) ) {
                wp_mkdir_p( $path );
            }            

            //Sanitize o nome do arquivo
            $target_path_sia = uulpd_sanitize_file_name( $file );

            if ( move_uploaded_file( $fileTemp, $path . "/" . $target_path_sia ) ) {
                chmod( $path . "/" . $target_path_sia, 0666 );

                $caminhoBanco = $pathInsert . "/" . $target_path_sia;
                $caminho = substr( $caminhoBanco, 0 );
            }

            //Valores a serem inseridos no banco
            $fields[$key] = array('file' => $caminho);

        }
    }    

    //Percorre array e os links a página
    foreach ( $valid_fields as $key ) {

        //Verifica se houve Post do campo
        $context = (isset($_POST['uulpd_files'][$key]))? $_POST['uulpd_files'][$key] : array();
        
        //Valores a serem inseridos no banco
        $meta_value = array(
            'hide'  => (isset($context['hide']))? $context['hide'] : 'off'
        );

        //Adiciona array a variável
        if (!is_null($context) && array_key_exists('url', $context) ) {            
            //Valores a serem inseridos no banco
            $meta_value['url'] = filter_var($context['url'], FILTER_SANITIZE_URL);
        }        

        //Combina os array de arquivos e dados de post comum
        if (isset($fields[$key]) && is_array($fields[$key])) {
            $arrayMixin = array_merge($fields[$key], $meta_value);
        }
        else {
            $arrayMixin = $meta_value;
        }

        //Pega dados da database e verifica se existe
        $getData = get_post_meta($page_id, $key, true);

        //Atualiza dados
        uulpd_insert_data($getData, $page_id, $key, $arrayMixin);
    }
    
}

/* Função para atualizar os dados existentes no banco */
function uulpd_insert_data($getData, $id, $meta_key, $newValue) {

    $msg = '';

    if (!$getData) {
        //Adiciona novos dados
        if (add_post_meta($id, $meta_key, $newValue, true)) {
            $msg = "<div class='container'><div class='row justify-content-center'><div class='col-9 alert alert-success'>" . $meta_key . ": Cadastrado com sucesso.</div></div></div>";
        }
        else {
            $msg = "<div class='container'><div class='row justify-content-center'><div class='col-9 alert alert-warning'>" . $meta_key . ": Houve um erro ao cadastrar os novos dados. Tente novamente mais tarde.</div></div></div>";
        }        
    }
    else {
        //Combina os arrays atualizando os dados de array
        $data = array_merge($getData, $newValue);

        //Atualiza dados no BD
        if (update_post_meta($id, $meta_key, $data)) {
            $msg = "<div class='container'><div class='row justify-content-center'><div class='col-9 alert alert-success'>" . $meta_key . ": Atualizado com sucesso.</div></div></div>";
        }     
    } 
    
    //Imprime se houve error no cadastro dos dados
    echo $msg;

}


/* Register shortcode to show list of Files in page */ 
function uulpd_shortcode($atts){
    
    //Verifica os atributos válidos
    $atribute_defaults = extract(shortcode_atts(array(
        'location' => "header",
     ), $atts, 'uulpd_page_file'));
    
    //global $valid_fields; //Pega array de campos válidos

    $page_id = get_the_ID(); //Pega o ID de contexto, página atual

    //Setando os links a mostrar
    $linksShow = ($atts['location'] == 'header')? array('formulario' => 'Formulário','regulamento' => 'Regulamento','relatorio_performance' => 'Relatório de Performance', 'como_investir' => 'Como Investir') : array('download_cotas' => 'Download de Cotas', 'lamina' => 'Lâmina');

    //Retorna os valores do BD
    $meta = get_metadata('post', $page_id);

    //Aponta para o final do array
    end($linksShow);

    //Pega o ultimo indice do array
    $last = key($linksShow);

    //Inicializa o html para imprimir
    $html = '<ul class="list-inline">';

    foreach ($linksShow as $key => $value) { 
        
        $currentMeta = unserialize($meta[$key][0]); //Define meta_values
        
        //Se for setado para esconder, pula a interação para próximo indice do array
        if ($currentMeta['hide'] == 'off') { continue; }

        //Define o link para tag link
        $link = (isset($currentMeta['url']))? $currentMeta['url'] : $currentMeta['file'];       
    
        $html .= '<li class="list-inline-item">
                        <a class="btn btn-primary" href="' . $link . '" target="_blank">
                            ' . $value .'
                        </a>
                    </li>';

        //Imprime os separadores
        if ($key != $last){
            $html .= '<li class="list-inline-item">|</li>';
        }
    }

    $html .= '</ul><!-- lista-de-links -->';

    return $html;

}   

require_once( _PLUGIN_PATH_ . 'loadCustomTemplate.php' );