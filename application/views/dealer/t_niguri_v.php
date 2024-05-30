<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  foreach($dt_niguri->result() as $row) {           
    echo "   
    <tr>                    
      <td rowspan='4' width='6%'>$row->id_tipe_kendaraan</td>
      <td rowspan='4' width='17%'>$row->tipe_ahm</td>
      <td rowspan='4' width='17%''>$row->deskripsi_ahm</td>

      <td width='17.8%' align='right'>MD Dist to Dealer</td>
      <td width='7%'>$row->a_m1</td>
      <td width='7%'>$row->a_m</td>
      <td width='7%''>$row->a_fix</td>
      <td width='7%'>$row->a_t1</td>
      <td width='7%'>$row->a_t2</td>
    </tr>";      
    
    echo "                                                
    </tr>
    <tr>
      <td width='17.8%' align='right'>Retail Sales</td>
      <td width='7%'>$row->b_m1</td>
      <td width='7%'>$row->b_m</td>
      <td width='7%''>$row->b_fix</td>
      <td width='7%'>$row->b_t1</td>
      <td width='7%'>$row->b_t2</td>                                                
    </tr>
    <tr>  
      <td width='17.8%' align='right'>Total Stock</td>
      <td width='7%'>$row->c_m1</td>
      <td width='7%'>$row->c_m</td>
      <td width='7%''>$row->c_fix</td>
      <td width='7%'>$row->c_t1</td>
      <td width='7%'>$row->c_t2</td>                                                
    </tr>
    <tr>  
      <td width='17.8%' align='right'>Total Stock Days</td>
      <td width='7%'>$row->d_m1</td>
      <td width='7%'>$row->d_m</td>
      <td width='7%''>$row->d_fix</td>
      <td width='7%'>$row->d_t1</td>
      <td width='7%'>$row->d_t2</td>                                                      
    </tr>";
     
    }
  ?>  
</table>
