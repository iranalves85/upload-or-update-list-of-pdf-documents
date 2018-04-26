<main class="login">        

    <section class="block-section">

        <div class="container">            
            
            <div class="row justify-content-center">
                <div class="col-12">
                    <h1 class="titulo-secao text-center"><?php _e("Gerenciar Arquivos", "uuppd");?></h1>   
                </div>                
                <div class="col-10 col-md-4 login-box">
                    <h2 class="titulo-secao text-center"><?php _e("Login", "uuppd");?></h2>
                    <form action="<?php the_permalink(); ?>" method="POST" name="login">
                        <?php wp_login_form(array(
                            'echo'           => true,
                            'remember'       => true,
                            'form_id'        => 'loginform',
                            'id_username'    => 'user_login',
                            'id_password'    => 'user_pass',
                            'id_remember'    => 'rememberme',
                            'id_submit'      => 'wp-submit',
                            'label_username' => __( 'Username' ),
                            'label_password' => __( 'Password' ),
                            'label_remember' => __( 'Remember Me' ),
                            'label_log_in'   => __( 'Log In' ),
                            'value_username' => '',
                            'value_remember' => false
                        ));  ?>
                    </form>
                    <br />
                    <?php 
                        global $loginReturn; 
                        if(isset($loginReturn) && is_a($loginReturn, 'WP_Error')):
                            foreach ($loginReturn->errors as $key => $value) {
                                echo "<br /><br /><div class='alert alert-warning' >" . $value[0] . "</div>";
                            }                            
                        endif; 
                        
                    ?>
                </div>
                                
            </div>
        </div>
    </section><!-- login -->
    
</main>