<?php

function x2_download_report_list()
{
    ?>
    <div class="wrap">
        <h2>Relátorios por Edital</h2>
        <?php
        global $wpdb;
        $rows = $wpdb->get_results("SELECT b.id, b.titulo, b.descricao, SUM(a.count_downloads) as downloads
              FROM wp_x2_download_report a 
              INNER JOIN wp_x2_download b ON a.id_file = b.id
              GROUP BY b.id
              ORDER BY b.id ASC");
        ?>
        <br>
        <table class='wp-list-table widefat fixed striped posts' id="example">
          <thead>
            <tr>
                <th class="manage-column ss-list-width" width="7%">Cód.</th>
                <th class="manage-column ss-list-width" width="15%">Título</th>
                <th class="manage-column ss-list-width" width="58%">Descricao</th>
                <th class="manage-column ss-list-width" width="10%">N° Downloads</th>
                <th class="manage-column ss-list-width" width="10%">Consultar</th>
            </tr>
          </thead>

            <?php foreach ($rows as $row) { ?>
                <tr>
                    <td class="manage-column ss-list-width"><?= $row->id; ?></td>
                    <td class="manage-column ss-list-width"><?= $row->titulo; ?></td>
                    <td class="manage-column ss-list-width"><?= $row->descricao; ?></td>
                    <td class="manage-column ss-list-width"><?= $row->downloads; ?></td>
                    <td>
                        <a href="<?php echo admin_url('admin.php?page=my-report-user&id_file=' . $row->id); ?>">Consultar</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <script type="text/javascript" language="javascript" class="init">
            $(document).ready(function () {


                // DataTable
                var table = $('#example').DataTable(
                    {
                        "order": [[ 3, "desc" ]],
                        language: {
                          url: 'https://cdn.datatables.net/plug-ins/1.10.16/i18n/Portuguese-Brasil.json'
                        }
                    }
                );

            });
        </script>
    </div>
    <?php
}

?>