<?php

function x2_download_list() {
	if ( $_GET['id'] ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "x2_download";
		$wpdb->query( $wpdb->prepare( "DELETE FROM $table_name WHERE id = %s", $_GET['id'] ) );
		$message = "E-book excluido com sucesso!";
	}
	?>

    <div class="wrap">
        <h2>E-books</h2>
		<?php if ( isset( $message ) ): ?>
            <div class="updated"><p><?php echo $message; ?></p></div>
		<?php endif; ?>
        <div class="tablenav top">
            <div class="alignleft actions">
                <a class="button" href="<?php echo admin_url( 'admin.php?page=new-download' ); ?>">Adicionar
                    Novo</a>
            </div>
            <br class="clear">
        </div>
        <br>
		<?php
		global $wpdb;
		$table_name = $wpdb->prefix . "x2_download";
    $sql = "SELECT * FROM $table_name ORDER BY data_publicacao DESC";
		$rows = $wpdb->get_results( $sql );
		?>
        <table id="example" class="display wp-list-table widefat fixed striped posts" width="100%">
            <thead>
            <tr>
                <th class="manage-column" width="10%">Código</th>
                <th class="manage-column ss-list-width" width="15%">Título do E-book</th>
                <th class="manage-column ss-list-width" width="55%">Descrição do E-book</th>
                <th class="manage-column ss-list-width" width="10%">Data de Publicação</th>
                <th width="10%">&nbsp;</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th class="manage-column">Código</th>
                <th class="manage-column ss-list-width">Título</th>
                <th class="manage-column ss-list-width">Descrição</th>
                <th class="manage-column ss-list-width">Data</th>
                <th>&nbsp;</th>
            </tr>
            </tfoot>
            <tbody>
              <?php foreach ( $rows as $row ) { ?>
                <tr>
                    <td class="manage-column"><?= $row->id; ?></td>
                    <td class="manage-column ss-list-width"><?= $row->titulo; ?></td>
                    <td class="manage-column ss-list-width"><?= $row->descricao; ?></td>
                    <td class="manage-column ss-list-width"><?= date( "d/m/Y", strtotime( $row->data_publicacao ) ); ?></td>
                    <td>
                        <a href="<?php echo admin_url( 'admin.php?page=download-update&id=' . $row->id ); ?>">Editar</a> -
                        <a href="<?php echo admin_url( 'admin.php?page=view-all&id=' . $row->id ); ?>"
                           onclick="return confirm('Tem certeza que deseja excluir esse e-book?')">Excluir</a>
                    </td>
                </tr>
			<?php } ?>
            </tbody>
        </table>
        <script type="text/javascript" language="javascript" class="init">
            $(document).ready(function () {
                // Setup - add a text input to each footer cell
                $('#example tfoot th').each(function () {
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="Pesquisar ' + title + '" />');
                });

                // DataTable
                var table = $('#example').DataTable(
                    {
                        "order": [[ 4, "desc" ]],
                        language: {
                          url: 'https://cdn.datatables.net/plug-ins/1.10.16/i18n/Portuguese-Brasil.json'
                        }
                    }
                );

                // Apply the search
                table.columns().every(function () {
                    var that = this;

                    $('input', this.footer()).on('keyup change', function () {
                        if (that.search() !== this.value) {
                            that
                                .search(this.value)
                                .draw();
                        }
                    });
                });
            });
        </script>
    </div>

	<?php } ?>