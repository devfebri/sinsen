<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Customer List Dealer_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<table class="table table-bordered table-container">

              <thead>
                <tr>       
                  <div class="row">
                      <th  class="sticky-col first-col">No</th>                          
                      <th  class="sticky-col first-col">Kode Dealer</th>                          
                      <th  class="sticky-col second-col">Dealer</th>  
                    <?php 
                          foreach ($flp_sales_tipe_kendaraan as $row) 
                     
                          { ?>
                          
                          <th scope="scroll-data">
                                  <?=$row->id_tipe_kendaraan?></th>
                      <?}
                      ?>   

                    <th >Total Jumlah</th>        
         
                   <div>
                  </div>
                           
                </tr>
              </thead>
              <tbody>            
       
              <?php   

              $temp = array();
                       $no = 1;
                       foreach ($sales_force_detail as $set => $item) : ?>
                      <tr>
                      <td class="sticky-col first-col"><?=$no++?></td>
                          <td  class="sticky-col first-col">
                          <?= $item['id_dealer'];?>
                        </td>

                        <td  class="sticky-col second-col">
                          <?= $item['nama_dealer'];?>
                        </td>
                        
                          <?php

                          $total = 0;
                          foreach ($item['sales_data'] as $key => $sales) 
                          {  
                          ?>
                          <td style="width:140px"> 
                            <input type="hidden" name="sales[tipe_kendaraan][]"  value="<?= $sales['tipe_kendaraan']?>" placeholder="Tipe Kendaraan" />
                            <input type="hidden" name="sales[kode_dealer][]"     value="<?= $item['id_dealer']?>"       placeholder="Kode Dealer" />
                            <input type="text"   name="sales[jumlah][]"          value="<?= $sales['jumlah']?>"         placeholder="Jumlah" class="form-control jumlah-tipe-kendaraan set_sum<?=$key?>"  readonly />
                          <?

                          $total += floatval($sales['jumlah']);
                          }
                          ?>
                          <td >
                          <input type="text" value="<?= $total;?>" class="form-control" readonly>  
                        </td>
                      </tr>
                  <?php endforeach;?>

                  <tr>
                    <td colspan="3">Total</td>
                    <?php 
             
                  foreach ($sales_force_detail_footer as $key => $sales) 
                  { ?>
                      <td><?= $key?></td>
                    <?}
                    ?>
                    
                  </tr>
              </tbody>
              </table>

<?php 
 	$nom=1;	
	if($downloadExcel->num_rows()>0){
		
		$temp = array();
		$no = 1;
		foreach ($sales_force_detail as $set => $item) : ?>
	   <tr>
	   <td class="sticky-col first-col"><?=$no++?></td>
		   <td  class="sticky-col first-col">
		   <?= $item['id_dealer'];?>
		 </td>

		 <td  class="sticky-col second-col">
		   <?= $item['nama_dealer'];?>
		 </td>
		 
		   <?php

		   $total = 0;
		   foreach ($item['sales_data'] as $key => $sales) 
		   {  
		   ?>
		   <td style="width:140px"> 
			 <input type="hidden" name="sales[tipe_kendaraan][]"  value="<?= $sales['tipe_kendaraan']?>" placeholder="Tipe Kendaraan" />
			 <input type="hidden" name="sales[kode_dealer][]"     value="<?= $item['id_dealer']?>"       placeholder="Kode Dealer" />
			 <input type="text"   name="sales[jumlah][]"          value="<?= $sales['jumlah']?>"         placeholder="Jumlah" class="form-control jumlah-tipe-kendaraan set_sum<?=$key?>"  readonly />
		   <?

		   $total += floatval($sales['jumlah']);
		   }
		   ?>
		   <td >
		   <input type="text" value="<?= $total;?>" class="form-control" readonly>  
		 </td>
	   </tr>
   <?php endforeach;?>
	<?}else{
		echo "<td colspan='8' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>


