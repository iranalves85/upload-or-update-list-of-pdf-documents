<?php

function x2_download_report_user()
{

		$id_file = (isset($_GET['id_file']))?$_GET['id_file']:'';
		?>
		<script type="text/javascript">
				function printDiv()
				{
						var divToPrint=document.getElementById('content-print');
						var newWin=window.open('','Print-Window');
						newWin.document.open();
						newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
						newWin.document.close();
						setTimeout(function(){newWin.close();},10);

				}
			<?php
				global $wpdb;
				$rows = $wpdb->get_results("SELECT b.id, b.titulo, c.nome_empresa, c.email, c.telefone, c.cidade, c.estado, SUM(a.count_downloads) as downloads, a.last_download
					FROM wp_x2_download_report a 
					INNER JOIN wp_x2_download b ON a.id_file = b.id
					INNER JOIN wp_ebooks_users c ON a.id_user = c.id
					WHERE a.id_file = $id_file
					GROUP BY c.nome_empresa, b.id
					ORDER BY downloads DESC");
			?>
		</script>
		<div class="wrap">
				<h2>Historico de Download - Ebook '<?= $rows[0]->titulo ?>' </h2>
				<div class="tablenav top">
						<div class="alignleft actions">
								<a class="button" href="<?= admin_url('admin.php?page=my-reports') ?>">&laquo; Voltar para
										relatorio por e-book</a>
						</div>
						<div class="alignleft actions">
								<a href="#" onclick='printDiv();' class="button">Imprimir</a>
						</div>
						<br class="clear">
				</div>
				<?php add_thickbox(); ?>
				<br>
				<div id="content-print">
						<table class='wp-list-table widefat fixed striped posts'>
								<tr>
										<th width="10%">N° Downloads</th>
										<th width="25%">Usuário</th>
										<th width="25%">Email</th>
										<th width="10%">Telefone/Celular</th>
										<th width="10%">Cidade</th>
										<th width="5%">Estado</th>
										<th width="15%">Data da última vez</th>
								</tr>
								<?php foreach ($rows as $row) { ?>
										<tr>
											<td class="manage-column ss-list-width"><?= $row->downloads; ?></td>
											<td class="manage-column ss-list-width"><?= $row->nome_empresa; ?></td>
											<td class="manage-column ss-list-width"><?= $row->email; ?></td>
											<td class="manage-column ss-list-width"><?= $row->telefone; ?></td>
											<td class="manage-column ss-list-width"><?= $row->cidade; ?></td>
											<td class="manage-column ss-list-width"><?= $row->estado; ?></td>
											<td class="manage-column ss-list-width"><?= date( "d/m/Y", strtotime( $row->last_download ) ); ?></td>
										</tr>
								<?php } ?>
						</table>
				</div>
		</div>
		<?php } ?>