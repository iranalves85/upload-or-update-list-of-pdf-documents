<?php

function x2_download_create() {
	global $wpdb;


//    $id = $_POST["id"];
	$nome_ebook = $_POST['nome_ebook'];
	$descricao_ebook = $_POST['descricao_ebook'];
	$data_publicacao = date( 'Y-m-d', current_time( 'timestamp' ) );
	$table_name = $wpdb->prefix . "x2_download";
	
	//search next id
	$get_id = $wpdb->get_row("SHOW TABLE STATUS LIKE '$table_name'"); 
	$id = $get_id->Auto_increment;


	if ( $_POST ) {

		if ( $_FILES["file_ebook"] ) {
			$path_array = wp_upload_dir();
			$path = $path_array['basedir'] . '/ebook/' . $id;
			$pathInsert = $path_array['baseurl'] . '/ebook/' . $id;
			if ( ! file_exists( $path ) ) {
				wp_mkdir_p( $path );
			}

			$target_path_sia = uniqid() . wpartisan_sanitize_file_name( $_FILES["file_ebook"]["name"] );

			if ( move_uploaded_file( $_FILES["file_ebook"]["tmp_name"], $path . "/" . $target_path_sia ) ) {
				chmod( $path . "/" . $target_path_sia, 0666 );

				$caminhoBanco = $pathInsert . "/" . $target_path_sia;
				$caminho = substr( $caminhoBanco, 0 );
			}
		} else {
			$caminho = '';
		}
		//insert information in table x2_download
		$wpdb->insert(
			$table_name, //table
			array(
				'titulo' => $nome_ebook,
				'descricao' => $descricao_ebook,
				'data_publicacao' => $data_publicacao,
				'anexo' => $caminho
			)
		);
		$message = "Ebook cadastrado com sucesso!";
	}
	?>
		<link type="text/css" href="<?php plugins_url('/includes/style-admin.css', __FILE__); ?>" rel="stylesheet"/>
		<div class="wrap">
		<?php if ( $_POST ) { ?>
						<h2>Ebook: <?= $nome_ebook; ?></h2>
						<div class="updated"><p>Ebook cadastrado com sucesso !</p></div>
						<a href="<?php echo admin_url( 'admin.php?page=new-download' ) ?>">&laquo; Inserir um novo E-book </a><br/>
            <a href="<?php echo admin_url( 'admin.php?page=view-all' ) ?>">&laquo; Ver todos os E-books </a>
		<?php } else { ?>
				<h2>Adicionar novo E-book</h2>
		<?php

		if ( isset( $message ) ) { ?>
						<div class="updated"><p><?= $message; ?></p></div>
		<?php } ?>
				<form data-toggle="validator" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"
							enctype="multipart/form-data">
						<table class='wp-list-table widefat'>
							<tr>
										<th class="ss-th-width">Anexar E-book:</th>
										<td>
												<input type="file" name="file_ebook" class="ss-field-width" required/>
										</td>
								</tr>
								<tr>
										<th class="ss-th-width">Nome do E-book</th>
										<td><input type="text" name="nome_ebook" value="<?= isset( $numero_edital ); ?>"
															 class="ss-field-width" required/></td>
										<div class="help-block with-errors"></div>
								</tr>
								<tr>
										<th class="ss-th-width" valign="top" align="right">Breve Descrição</th>
										<td>
												<textarea type="text" name="descricao_ebook" class="ss-field-width" cols="50"
																	rows="5"><?= $status ?></textarea>
										</td>
								</tr>
								<tr>
									<td colspan="2" align="center">
										<div class="form-group">
												<button type="submit" class="button btn-success">Cadastrar</button>
												<button type="reset" class="button">Cancelar</button>
										</div>
									</td>
						</table>
				</form>
		</div>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
						integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
						crossorigin="anonymous"></script>
	<?php };}; ?>