<?php 
/*
    Template Name: Manage Files
    Versão: 0.1
*/

//Carrega header do template
get_header();    

//Se estiver var global for classe WP_USER redireciona para painel
if ( is_user_logged_in() ) {
    include_once(_PLUGIN_PATH_ . 'includes/dashboard.php');
} else {
    include_once(_PLUGIN_PATH_ . 'includes/login.php');
}

//Carrega footer do template
get_footer();