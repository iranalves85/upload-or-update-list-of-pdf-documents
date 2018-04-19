<main class="dashboard">

    <?php 
        $current_user = wp_get_current_user(); //Retorna objeto WP_Users
        $component = isset($_GET['p']) ? $_GET['p'] : 'dashboard'; //Pega variavel get
    ?>

    <div class="container-fluid">            
        
        <div class="row">

            <aside class="menu-lateral">                                        
                <ul>
                    <li><a href="?p=dashboard" <?php mp_linkActive($component, 'dashboard');?>><?php _e('Painel', 'maple-bear') ?></a></li>
                    <li><a href="?p=relatorio" <?php mp_linkActive($component, 'relatorio');?>><?php _e('Relatórios', 'maple-bear') ?></a></li>
                    <li><a href="?p=cardapio" <?php mp_linkActive($component, 'cardapio');?>><?php _e('Cardápio', 'maple-bear') ?></a></li>
                    <li><a href="?p=agenda" <?php mp_linkActive($component, 'agenda');?>><?php _e('Agenda de Eventos', 'maple-bear') ?></a></li>
                    <li><a href="?p=guia" <?php mp_linkActive($component, 'guia');?>><?php _e('Guia dos Pais', 'maple-bear') ?></a></li>
                    <li><a href="?p=contato" <?php mp_linkActive($component, 'contato');?>><?php _e('Atualize seus contatos', 'maple-bear') ?></a></li>
                </ul>
            </aside><!-- menu-lateral -->

            <section class="col content">
                <div class="menu-superior">
                    <div class="container-fluid">
                        <div class="col-md-10">
                            <ul class="list-inline">
                                <li class="list-inline-item"><h1>Área dos Pais</h1></li>
                                <li class="list-inline-item float-right">
                                    <ul class="list-inline">
                                        <li class="list-inline-item btn">
                                            <?php _e('Olá, ', 'maple-bear') ?>
                                            <a href="?p=contato">
                                                <?php echo $current_user->display_name; ?>
                                            </a>
                                        </li>
                                        <li class="list-inline-item">|</li>
                                        <li class="list-inline-item">
                                            <a class="btn btn-outline-light" href="<?php echo wp_logout_url(get_permalink()); ?>"><?php _e('Logout', 'maple-bear') ?></a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>                        
                    </div>                    
                </div> 

                <div class="block-section">
                    <div class="row">
                        <?php 
                            
                            switch ($component) {
                                case 'dashboard':
                                    get_template_part('templates/dashboard','index');
                                    break; 
                                case 'relatorio':
                                    get_template_part('templates/dashboard','relatorio');
                                    break; 
                                case 'cardapio':
                                    get_template_part('templates/dashboard','cardapio');
                                    break; 
                                case 'agenda':
                                    get_template_part('templates/dashboard','agenda');
                                    break; 
                                case 'guia':
                                    get_template_part('templates/dashboard','guia');
                                    break;
                                case 'contato':
                                    get_template_part('templates/dashboard','contato');
                                    break; 
                                default:
                                    get_template_part('templates/dashboard','index');
                                    break;
                            }
                        
                        ?>
                    </div><!-- row -->
                    
                </div>                    
            
            </section>
                            
        </div><!-- row -->

    </div><!-- container -->
    
</main>