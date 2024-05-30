<table id="myTable1" class="table myTable1 table-bordered table-hover">
  <?php   
  foreach($dt_niguri->result() as $row) {           
    echo "   
    <tr>                    
      <td rowspan='4' width='10%'>$row->id_item</td>
      <td rowspan='4' width='20%'>$row->tipe_ahm</td>
      <td rowspan='4' width='14%''>$row->warna</td>

      <td width='17.8%' align='right'>AHM Dist to MD</td>
      <td width='7%'>$row->a_m1</td>
      <td width='7%'>$row->a_m</td>
      <td width='7%''>$row->a_fix</td>
      <td width='7%'>$row->a_t1</td>
      <td width='7%'>$row->a_t2</td>
      <td>"; ?>
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
            onClick="hapus_niguri('<?php echo $row->id_niguri_detail; ?>','<?php echo $row->id_item; ?>')"></button>
      </td>
    <?php
    echo "                                                
    </tr>
    <tr>
      <td width='17.8%' align='right'>Retail Sales</td>
      <td width='7%'>$row->b_m1</td>
      <td width='7%'>$row->b_m</td>
      <td width='7%''>$row->b_fix</td>
      <td width='7%'>$row->b_t1</td>
      <td width='7%'>$row->b_t2</td>
      <td>"; ?>
        <a href="javascript:void(0)" title="Edit" class="btn btn-sm btn-primary btn-flat" data-toggle="tooltip modal"
          onclick="edit_niguri(<?php echo $row->id_niguri_detail ?>)"><i class='fa fa-edit'></i></a>
      </td>                                                
    <?php
    echo "
    </tr>
    <tr>  
      <td width='17.8%' align='right'>Total Stock</td>
      <td width='7%'>$row->c_m1</td>
      <td width='7%'>$row->c_m</td>
      <td width='7%''>$row->c_fix</td>
      <td width='7%'>$row->c_t1</td>
      <td width='7%'>$row->c_t2</td>
      <td></td>                                                
    </tr>
    <tr>  
      <td width='17.8%' align='right'>Total Stock Days</td>
      <td width='7%'>$row->d_m1</td>
      <td width='7%'>$row->d_m</td>
      <td width='7%''>$row->d_fix</td>
      <td width='7%'>$row->d_t1</td>
      <td width='7%'>$row->d_t2</td>                                                      
      <td></td>
    </tr>";
     
    }
  ?>  
<!-- </table> -->
