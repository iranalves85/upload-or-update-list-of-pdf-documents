<?php 
/*
    Template Name: Manage Files
    Versão: 0.1
*/

//Carrega header do template
get_header();    

//Se estiver var global for classe WP_USER redireciona para painel
if ( is_user_logged_in() ) {
    get_template_part('templates/dashboard');
} else {
    get_template_part('templates/login');
}

//Carrega footer do template
get_footer();