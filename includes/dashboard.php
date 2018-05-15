<main class="dashboard" ng-app="updateDoc">

    <?php 
        //Retorna objeto WP_Users
        $current_user = wp_get_current_user(); 

        //Se formulário enviado realiza as atualizações
        uulpd_update_data_database();
        
        //Pega variavel get
        $page_id = isset($_POST['uulpd_page']) ? $_POST['uulpd_page'] : null; 

        //
        if(!is_null($page_id)): 
            $shortcode = $page_id;
        endif;

        //Retorna as páginas dos fundos existentes
        $pages = uulpd_query_pages(); 

        //Retorna meta_values com arquivos cadastrados 
        $currentFiles = uulpd_current_files($page_id);

    ?>

    <div class="container-fluid" ng-controller="updateDocController">            
        
        <div class="row">

            <section class="col content">
                <div class="menu-superior">
                    <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            
                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        <h1><?php _e('Gerenciar Arquivos', 'uulpd'); ?></h1>
                                    </li>
                                    <li class="list-inline-item float-right">
                                        <ul class="list-inline">
                                            <li class="list-inline-item btn">
                                                <?php _e('Olá, ', 'uulpd') ?>       
                                                <?php echo $current_user->display_name; ?>
                                            </li>
                                            <li class="list-inline-item">
                                                <a class="btn btn-sm btn-outline-primary" href="<?php echo wp_logout_url(get_permalink()); ?>"><?php _e('Logout', 'uuppd') ?></a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>                            
                        </div>                        
                    </div>                    
                </div> 

                <div class="block-section">
                    <div class="row justify-content-center">
                            
                            <div class="col-md-8 mb-5">
                                <h2><?php _e('Selecionar Fundo', 'uulpd'); ?></h2>

                                <form action="" class="form-inline" name="uulpd_select_page" method="post">
                                    
                                    <?php if( count($pages) > 0 ): ?>

                                        <select name="uulpd_page" class="form-control">
                                            
                                            <?php foreach ($pages as $key => $value): ?>
                                                
                                                <option value="<?php echo $value->ID; ?>" 
                                                <?php if($value->ID == $page_id){ echo "selected='selected'"; } ?>>
                                                    <?php echo $value->post_title; ?>
                                                </option>

                                            <?php endforeach; ?>

                                        </select> 

                                    <?php endif; ?>
                                    
                                    <input type="submit" value="<?php _e('Selecionar', 'uulpd') ?>" class="ml-2 mr-4" />
                                    <?php wp_nonce_field('uulpd', 'uulpd_select_page'); ?>

                                </form>                                
                            </div>

                            <div class="col-md-8">
                                <h2><?php _e('Atualizar Arquivos', 'uulpd'); ?></h2>
                                <form action="" method="post" name="uulpd_add_edit_files" enctype="multipart/form-data">
                                    <table class="table table-striped table-responsive">
                                        <thead>
                                            <tr>
                                                <th>Esconder</th>
                                                <th>Tipo de documento</th>       
                                                <th>Novo arquivo</th>
                                                <th>Arquivo Atual</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><input type="checkbox"></td>
                                                <td>Formulário</td>
                                                <td><input type="file" name="uulpd_files[formulario]"  class="form-control"></td>
                                                <td><?php uulpd_print_filename($currentFiles, 'formulario'); ?></td>
                                            </tr>
                                            <tr>
                                                <td><input type="checkbox"></td>
                                                <td>Regulamento</td>
                                                <td><input type="file" name="uulpd_files[regulamento]" class="form-control"></td>
                                                <td><?php uulpd_print_filename($currentFiles, 'regulamento'); ?></td>
                                            </tr>
                                            <tr>
                                                <td><input type="checkbox"></td>
                                                <td>Relatório de Performance</td>
                                                <td><input type="file" name="uulpd_files[relatorio_performance]" class="form-control"></td>
                                                <td><?php uulpd_print_filename($currentFiles, 'relatorio_performance'); ?></td>
                                            </tr>                                            
                                            <tr>
                                                <td><input type="checkbox"></td>
                                                <td>Lâmina</td>
                                                <td><input type="file" name="uulpd_files[lamina]" class="form-control"></td>
                                                <td><?php uulpd_print_filename($currentFiles, 'lamina'); ?></td>
                                            </tr>
                                            <tr>
                                                <td><input type="checkbox"></td>
                                                <td>Download de cotas</td>
                                                <td><input type="url" name="download_cotas" class="form-control"></td>
                                                <td><?php uulpd_print_filename($currentFiles, 'download_cotas'); ?></td>
                                            </tr>
                                            <tr>
                                                <td><input type="checkbox"></td>
                                                <td>Como investir</td>
                                                <td><input type="url" name="como_investir" class="form-control"></td>
                                                <td><?php uulpd_print_filename($currentFiles, 'como_investir'); ?></td>
                                            </tr>                                            
                                        </tbody>                                        
                                    </table>
                                    <input type="submit" class="btn btn-primary right" value="Atualizar">
                                    <input type="hidden" name="form_id" value="<?php echo $page_id; ?>" />
                                    <?php wp_nonce_field('uulpd', 'uulpd_add_edit_files'); ?>
                                </form>                                
                                
                            </div>

                            <div class="col-md-8">
                                <br />
                                <p>
                                    <?php _e('Em todas as páginas dos Fundos, devem ser incluídos as shortcodes abaixo:', 'uulpd'); ?>
                                </p>
                                
                                <ul>
                                    <li><code>[shortcode location="header"]</code></li>
                                    <li><code>[shortcode location="footer"]</code></li>
                                </ul>
                            </div>
                        
                    </div><!-- row -->
                    
                </div>                    
            
            </section>
                            
        </div><!-- row -->

    </div><!-- container -->
    
</main>