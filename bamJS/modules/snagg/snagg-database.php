<?php
   $PageTitle="SNAGG: Social Networks and Ancient Greek Garrisons";
   $NavBarBrand ="SNAGG";
   include_once('BAM-header.php');
   include_once('BAM-networks-and-maps.php'); ?>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    
   <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
  <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>

<style>
td
{
    max-width: 0;
    overflow: scroll;
    text-overflow: ellipsis;
}
</style>

    <br />
    <br />
    

    <?php
    $html = file_get_contents('snagg_table.html');
 $html = preg_replace('#(<[a-z ]*)(style=("|\')(.*?)("|\'))([a-z ]*>)#', '\\1\\6', $html);
  $html = preg_replace('#(<[a-z ]*)(class=("|\')(.*?)("|\'))([a-z ]*>)#', '\\1\\6', $html);

echo $html;
		?>
        <script>
            $(document).ready(function() {
                var table = $('#snagg').DataTable({
                                      dom: 'Bfrtip',
                        buttons: [
           'csv', 'print'
        ],
                    "autoWidth": false,
                  "columnDefs": [
                  	{ targets: '_all', "width": "25px"  }
                  ],
                    bAutoWidth: false,
                      "autoWidth": false
        
                });
            });
        </script>

        <?php include_once('BAM-footer.php');
      ?>