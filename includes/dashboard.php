<main class="dashboard">

    <?php 
        //Retorna objeto WP_Users
        $current_user = wp_get_current_user(); 

        //Se formulário enviado realiza as atualizações
        uulpd_update_data_database();
        
        //Pega variavel get
        $page_id = isset($_POST['uulpd_page_id']) ? $_POST['uulpd_page_id'] : null; 

        //Retorna as páginas dos fundos existentes
        $pages = uulpd_query_pages(); 

        //Retorna meta_values com arquivos cadastrados 
        $currentFiles = uulpd_current_files($page_id);

    ?>

    <div class="container-fluid">            
        
        <div class="row">

            <section class="col content">
                <div class="menu-superior">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-8">                             
                                    <h1><?php _e('Gerenciador de Arquivos - AZ Quest', 'uulpd'); ?></h1> 
                                </div>
                                <div class="col-md-4">
                                    <ul class="list-inline">
                                        <li class="list-inline-item float-right">
                                            <ul class="list-inline">
                                                <li class="list-inline-item btn">
                                                    <?php _e('Olá, ', 'uulpd') ?>
                                                    <?php echo $current_user->display_name; ?>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a class="btn btn-sm btn-outline-primary" href="<?php echo wp_logout_url(get_permalink()); ?>"><?php _e('Logout', 'uulpd') ?></a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                    </div>                            
                                </div> 
                            </div>
                        </div>          
                </div> 

                <div class="block-section">
                    <div class="row justify-content-center">
                            
                            <div class="col-md-8 mb-5">
                                    
                                <?php if( count($pages) > 0 ): ?>

                                    <h2><?php _e('Selecionar Fundo', 'uulpd'); ?></h2>

                                    <form action="" class="form-inline" name="uulpd_select_page" method="post">

                                    <select name="uulpd_page_id" class="form-control">
                                        
                                        <?php foreach ($pages as $key => $value): ?>
                                            
                                            <option value="<?php echo $value->ID; ?>" 
                                            <?php if($value->ID == $page_id){ echo "selected='selected'"; } ?>>
                                                <?php echo $value->post_title; ?>
                                            </option>

                                        <?php endforeach; ?>

                                    </select> 

                                    <input type="submit" value="<?php _e('Selecionar', 'uulpd') ?>" class="ml-2 mr-4" />

                                    <?php wp_nonce_field('uulpd', 'uulpd_select_page'); ?>

                                    </form> 

                                <?php endif; ?>
                                                                   
                            </div>

                            <div class="col-md-8">
                            
                            <?php if (isset($page_id)) { ?>

                                <hr />

                                <h2><?php echo get_the_title($page_id); ?></h2>
                                
                                <form action="" method="post" name="uulpd_add_edit_files" enctype="multipart/form-data">
                                    <table class="table table-borderless">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col">Mostrar</th>
                                                <th scope="col">Tipo de documento</th>       
                                                <th scope="col">Novo arquivo</th>
                                                <th scope="col">Arquivo Atual</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td align="center"><input type="checkbox" name="uulpd_files[formulario][hide]" <?php uulpd_hide_show($currentFiles, 'formulario'); ?>></td>
                                                <td>Formulário</td>
                                                <td style="max-width:'300px'"><input type="file" name="uulpd_files[formulario][file]"  class="form-control"></td>
                                                <td><?php uulpd_print_filename($currentFiles, 'formulario'); ?></td>
                                            </tr>
                                            <tr>
                                                <td align="center"><input type="checkbox" name="uulpd_files[regulamento][hide]" <?php uulpd_hide_show($currentFiles, 'regulamento'); ?>></td>
                                                <td>Regulamento</td>
                                                <td><input type="file" name="uulpd_files[regulamento][file]" class="form-control"></td>
                                                <td><?php uulpd_print_filename($currentFiles, 'regulamento'); ?></td>
                                            </tr>
                                            <tr>
                                                <td align="center"><input type="checkbox" name="uulpd_files[relatorio_performance][hide]" <?php uulpd_hide_show($currentFiles, 'relatorio_performance'); ?>></td>
                                                <td>Relatório de Performance</td>
                                                <td><input type="file" name="uulpd_files[relatorio_performance][file]" class="form-control"></td>
                                                <td><?php uulpd_print_filename($currentFiles, 'relatorio_performance'); ?></td>
                                            </tr> 

                                            <tr>
                                                <td align="center"><input type="checkbox" name="uulpd_files[como_investir][hide]" <?php uulpd_hide_show($currentFiles, 'como_investir'); ?>></td>
                                                <td>Como investir</td>
                                                <td colspan="2"><input type="url" name="uulpd_files[como_investir][url]" class="form-control" placeholder="Insira um link" value="<?php uulpd_print_filename($currentFiles, 'como_investir'); ?>"></td>
                                            </tr>                                           
                                            <tr>
                                                <td align="center"><input type="checkbox" name="uulpd_files[lamina][hide]" <?php uulpd_hide_show($currentFiles, 'lamina'); ?>></td>
                                                <td>Lâmina</td>
                                                <td><input type="file" name="uulpd_files[lamina][file]" class="form-control"></td>
                                                <td><?php uulpd_print_filename($currentFiles, 'lamina'); ?></td>
                                            </tr>
                                            <tr>
                                                <td align="center"><input type="checkbox" name="uulpd_files[download_cotas][hide]" <?php uulpd_hide_show($currentFiles, 'download_cotas'); ?>></td>
                                                <td>Download de cotas</td>
                                                <td colspan="2"><input type="url" name="uulpd_files[download_cotas][url]" class="form-control" placeholder="Insira um link" value="<?php uulpd_print_filename($currentFiles, 'download_cotas'); ?>"></td>            
                                            </tr>
                                                
                                        </tbody>                                        
                                    </table>
                                    <input type="submit" class="btn btn-primary right" value="Atualizar">
                                    <input type="hidden" name="uulpd_page_id" value="<?php echo $page_id; ?>" />
                                    <?php wp_nonce_field('uulpd', 'uulpd_add_edit_files'); ?>
                                </form>

                                <?php } ?>                                                                
                            </div>

                            <div class="col-md-8">
                                <br />
                                <p>
                                    <?php _e('Em todas as páginas dos Fundos, devem ser incluídos as shortcodes abaixo:', 'uulpd'); ?>
                                </p>
                                
                                <ul class="list-inline">
                                    <li class="list-inline-item"><code>[uulpd_page_file location="header"]</code></li>
                                    <li class="list-inline-item"><code>[uulpd_page_file location="footer"]</code></li>
                                </ul>
                            </div>
                        
                    </div><!-- row -->
                    
                </div>                    
            
            </section>
                            
        </div><!-- row -->

    </div><!-- container -->
    
</main>