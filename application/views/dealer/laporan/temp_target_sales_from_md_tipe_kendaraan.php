

<?php 

$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Target Sales From MD - Tipe Kendaraan.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php 


if(count($flp_sales_tipe_kendaraan)>0){ ?>

<table  border="1" >

  <tr>       
        <th ><b>No</b></th>                          
        <th ><b>Kode Dealer</b></th>                          
        <th ><b>Nama Dealer</b></th>  
      <?php 
            foreach ($flp_sales_tipe_kendaraan as $row) 
            { ?>
            
            <th >
                    <?=$row->id_tipe_kendaraan?></th>
        <?}
        ?>   

      <th ><b>  Total Jumlah</b></th>        
  </tr>

<?php   

$temp = array();
$array_total = array();
         $no = 1;
         foreach ($sales_force_detail as $set => $item) : ?>
        <tr>
        <td ><?=$no++?></td>
            <td  width="12%">
            '<?= $item['id_dealer'];?>
          </td>

          <td   width="40%">
            <?= $item['nama_dealer'];?>
          </td>
            <?php
            $total = 0;
            foreach ($item['sales_data'] as $key => $sales) 
            {  

            ?>
            <td style="width:140px"> 
           <?= $sales['jumlah'];
           
           $array_total[]= $total += floatval($sales['jumlah']);
            }
            ?>
            <td  width="20%">
              <?=$total?>
          </td>
        </tr>
    <?php endforeach;?>
    

<?}else{
		echo "<td colspan='8' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>




