<div id='general-content'>
<table id="example2" class="table myTable1 table-bordered table-hover">
  <thead>
    <tr>
      <th>No Mesin</th>
      <th>No Rangka</th>
      <th>No BPKB</th>
      <th>Nama Konsumen</th>
      <th>Tipe</th>
      <th>Tahun Produksi</th>    
      <th>Status Pembayaran</th>                
      <th><input type="checkbox" class="select_all"></th>                 
    </tr>
  </thead>
 
  <tbody>                    
    <?php   
    $no = 1;
    foreach($dt_bpkb->result() as $isi) {       
      // $cek = $this->db->query("SELECT * FROM tr_sales_order WHERE no_mesin = '$isi->no_mesin'");        
      // if($cek->num_rows() > 0){      
      //   $r = $cek->row();
      //   $biaya_biro = $r->biaya_biro;
      // }else{
      //   $biaya_biro = 0;
      // }
      $cek_bayar = $this->db->query("SELECT * FROM tr_faktur_stnk INNER JOIN tr_faktur_stnk_detail ON tr_faktur_stnk.no_bastd = tr_faktur_stnk_detail.no_bastd
        WHERE tr_faktur_stnk_detail.no_mesin = '$isi->no_mesin' and tr_faktur_stnk.status_faktur ='approved'");
      if($cek_bayar->num_rows() > 0){
        $r = $cek_bayar->row();
        if($r->status_bayar == 'lunas'){
          $status = "Lunas";
        }else{
          $status = "Belum Lunas";
        }
      }else{
        $status = "Belum Lunas";
      }

      $bpkb = $isi->no_bpkb;
      if($bpkb == ""){
        $cek = $this->m_admin->getByID("tr_entry_stnk","no_mesin",$isi->no_mesin);
        $bpkb = ($cek->num_rows() > 0) ? $cek->row()->no_bpkb : "" ;
        $masuk = $this->db->query("UPDATE tr_terima_bj SET no_bpkb = '$bpkb' WHERE no_mesin = '$isi->no_mesin'");
      }
      $jum = $dt_bpkb->num_rows();
      echo "
      <tr>                     
        <td>$isi->no_mesin</td> 
        <td>$isi->no_rangka</td> 
        <td>$bpkb</td> 
        <td>$isi->nama_konsumen</td> 
        <td>$isi->deskripsi_ahm</td>       
        <td>$isi->tahun</td>    
        <td>$status</td>                 
        <td align='center'>
            <input type='hidden' value='$jum' name='jum'>        
            <input type='hidden' value='$isi->no_mesin' name='no_mesin_$no'>        
            <input type='checkbox' class='data-check' id='cek_nosin[]' name='cek_nosin_$no'>        
        </td>              
      </tr>";
      $no++;
      }
    ?>
  </tbody>
</table>     
</div>
<br>  
<div id="general">
  <i>  
    <span style="font-size:20px;" id="counter" class="counter badge badge-secondary"></span>
  </i>
</div>
<script type="text/javascript">
  $('#general i .counter').text(' ');

var fnUpdateCount = function() {
  var generallen = $("#general-content input[id='cek_nosin[]']:checked").length;
    console.log(generallen,$("#general i .counter") )
  if (generallen > 0) {
    $("#general i .counter").text('Total Checklist : ' + generallen + ' data');
  } else {
    $("#general i .counter").text(' ');
  }
};

$("#general-content input:checkbox").on("change", function() {
      fnUpdateCount();
    });

$('.select_all').change(function() {
      var checkthis = $(this);
      var checkboxes = $("#general-content input:checkbox");

      if (checkthis.is(':checked')) {
        checkboxes.prop('checked', true);
      } else {
        checkboxes.prop('checked', false);
      }
            fnUpdateCount();
    });
</script>