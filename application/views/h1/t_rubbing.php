<button type="reset" class="btn btn-warning btn-flat btn-block" disabled>Detail Part No Mesin NRFS</button>                                             
<br>

<table id="example2" class="table table-bordered table-hovered myTable1" width="100%">
  <thead>
    <tr>
      <th width='10%'>ID Part</th>
      <th width='10%'>Nama Part</th>                    
      <!-- <th width='1%' align="center">Checklist</th> -->                    
    </tr>  
  </thead>
  <tbody>
    <?php 
    $no=1;  
    foreach($dt_nosin->result() as $row) {           
      $cek = $this->db->query("SELECT * FROM tr_rubbing_detail WHERE no_mesin = '$row->no_mesin' AND id_part = '$row->id_part' AND cek = 'ya'");
      if($cek->num_rows() > 0){
        $c = 'checked';
      }else{
        $c = '';
      }
      $jum = $dt_nosin->num_rows();
      echo "   
      <tr>                    
        <td width='10%'>$row->id_part</td>
        <td width='20%'>
          $row->nama_part
          <input type='hidden' value='$jum' name='jum'>
          <input type='hidden' value='$row->id_part' name='id_part_$no'>
          <input type='hidden' value='$row->no_mesin' name='no_mesin_$no'>          
        </td>
      </tr>";    
      $no++;
      }
    ?> 
  </tbody>  
</table>  
<!-- <i>Note : Checklist part yang dipilih untuk di-Rubbing</i> -->
