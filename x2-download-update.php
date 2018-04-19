<?php
function x2_download_update() {

	global $wpdb;

	$id = $_GET['id'];
	$table_name = $wpdb->prefix . "x2_download";

	if ( $_POST ) {
		$nome_ebook = $_POST['nome_ebook'];
		$descricao_ebook = $_POST['descricao_ebook'];

		if ( file_exists($_FILES['file_ebook']['tmp_name']) || is_uploaded_file($_FILES['file_ebook']['tmp_name']) ) {
			$path_array = wp_upload_dir();
			$path       = $path_array['basedir'] . '/ebook/' . $id;
			$pathInsert = $path_array['baseurl'] . '/ebook/' . $id;
			if ( ! file_exists( $path ) ) {
        wp_mkdir_p( $path );
      }

			$files = glob($path . '/*'); // get all file names
			foreach($files as $file){ // iterate files
				if(is_file($file))
					unlink($file); // delete file
			}

			$target_path_sia = uniqid() . wpartisan_sanitize_file_name( $_FILES["file_ebook"]["name"] );

			if ( move_uploaded_file( $_FILES["file_ebook"]["tmp_name"], $path . "/" . $target_path_sia ) ) {
				chmod( $path . "/" . $target_path_sia, 0666 );

				$caminhoBanco = $pathInsert . "/" . $target_path_sia;
				$caminho      = substr( $caminhoBanco, 0 );
			}

			$datatoupdate = array(
				'titulo'   => $nome_ebook,
				'descricao'      => $descricao_ebook,
				'anexo' => $caminho
			);
		} else {
			$datatoupdate = array(
				'titulo'   => $nome_ebook,
				'descricao'      => $descricao_ebook,
			);
		}
		//update information in table x2_download

		$wpdb->update(
			$table_name, //table
			$datatoupdate,
			array( 'id' => $id )
		);

	} 

		$ebook = $wpdb->get_results( $wpdb->prepare( "SELECT * from $table_name where id=%s", $id ) );

		foreach ( $ebook as $e ) {
			$titulo = $e->titulo;
			$descricao = $e->descricao;
			$anexo = $e->anexo;
			$data_publicacao = $e->data_publicacao;
		}

	?>
		<link type="text/css" href="<?php plugins_url('/includes/style-admin.css', __FILE__); ?>" rel="stylesheet"/>
		<div class="wrap">
				<h2>Ebook: <?= $titulo; ?></h2>
		<?php if ( $_SERVER["REQUEST_METHOD"] == "POST" ) { ?>
						<div class="updated">
								<p>Ebook Atualizado com sucesso!&ensp;&ensp;&ensp;
										<a class="button" href="<?php echo admin_url( 'admin.php?page=view-all' ) ?>">&laquo;
												Voltar para lista de E-books</a>
								</p>
						</div>
		<?php } ?>
				<form data-toggle="validator" enctype="multipart/form-data" method="POST"
							action="<?php echo $_SERVER['REQUEST_URI']; ?>">
						<input type="text" hidden value="<?= $id ?>" name="id" id="id">
						<table class='wp-list-table widefat'>
								<tr>
									<th class="ss-th-width">Alterar E-book:</th>
									<td>
											<input type="file" name="file_ebook" class="ss-field-width"/>
											<br/><small>(caso você faça upload de um novo arquivo, o arquivo antigo será apagado)</small>
									</td>
								</tr>
							<tr>
										<th class="ss-th-width">Arquivo do E-book:</th>
										<td>
												<a href='<?= $anexo; ?>'><?= $anexo; ?></a>
										</td>
								</tr>
								<tr>
										<th class="ss-th-width">Data da Publicação:</th>
										<td><?= date( "d/m/Y", strtotime( $data_publicacao ) ); ?></td>
								</tr>
								<tr>
										<th class="ss-th-width">Nome do E-book</th>
										<td><input type="text" name="nome_ebook" value="<?= $titulo; ?>"
															 class="ss-field-width" required/></td>
										<div class="help-block with-errors"></div>
								</tr>
								<tr>
										<th class="ss-th-width" valign="top" align="right">Breve Descrição</th>
										<td>
												<textarea type="text" name="descricao_ebook" class="ss-field-width" cols="50"
																	rows="5"><?= $descricao; ?></textarea>
										</td>
								</tr>
								<tr>
									<td colspan="2" align="center">
										<div class="form-group">
												<input type='submit' name="update" value='Salvar Alterações' class='button'>
												<button type="reset" class="button">Cancelar</button>
										</div>
									</td>
						</table>
				</form>
		</div>
<?php } ?>