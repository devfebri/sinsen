<!doctype html>
<html>
    <head>
        <title>Download File</title>
        <style>
            .word-table {
                border:1px solid black !important; 
                border-collapse: collapse !important;
                width: 100%;
            }
            .word-table tr th, .word-table tr td{
                border:1px solid black !important; 
                padding: 2px 2px;
                font-family:'sans-serif';
                font-weight:normal;
            }
        </style>
    </head>
    <body>
    <?php
  header("Content-type: application/vnd-ms-excel");
  $tanggal=date('Y-m-d').".xls";
  $name_file ="Master_jasa_dealer";
  header("Content-Disposition: attachment; filename=$name_file.xls"); 
   ?> 
        <table class="word-table" style="margin-bottom: 10px">
            <tr>
                <th>ID Jasa</th>
                <th>ID Dealer</th>
                <th>Harga</th>
            </tr>
                <?php
                
                $start=1;
                $query = $this->db->query("SELECT * FROM ms_h2_jasa_dealer where active ='1'")->result();
                foreach($query as $rows){
                ?>
                <tr>
                <td><?php echo $rows->id_jasa?></td>
                <td><?php echo $rows->id_dealer?></td>
                <td><?php echo $rows->harga_dealer?></td>
            </tr>
             <?php
                $start++;
            }
            ?>
        </table>
    </body>
</html>