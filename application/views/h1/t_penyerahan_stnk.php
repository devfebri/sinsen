<?php 
function mata_uang2($a){
  if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
    if(is_numeric($a) AND $a != 0 AND $a != ""){
      return number_format($a, 0, ',', '.');
    }else{
      return $a;
    }        
}
?>
<div id='general-content'>
<table id="example2" class="table myTable1 table-bordered table-hover">
  <thead>
    <tr>
      <th>No Mesin</th>
      <th>No Rangka</th>
      <th>No STNK</th>
      <th>No Polisi</th>
      <th>Nama Konsumen</th>
      <th>Tipe</th>
      <th>Tahun Produksi</th>
      <th>Nilai BBN</th>                    
      <th>Notice Pajak</th>
      <th><input type="checkbox" class="select_all"></th>                 
    </tr>
  </thead>
 
  <tbody>                    
    <?php   
    $no = 1;
    foreach($dt_stnk->result() as $isi) {       
      // $cek = $this->db->query("SELECT * FROM tr_sales_order WHERE no_mesin = '$isi->no_mesin'");        
      // if($cek->num_rows() > 0){      
      //   $r = $cek->row();
      //   $biaya_biro = $r->biaya_biro;
      // }else{
      //   $biaya_biro = 0;
      // }
      $jum = $dt_stnk->num_rows();
      if ($isi->no_stnk!=null) {
        $bg="";

        $amb = $this->m_admin->getByID("tr_entry_stnk","no_mesin",$isi->no_mesin);
        if($amb->num_rows() > 0){
          $ambil_pajak = $amb->row();
          $notice_pajak = $ambil_pajak->notice_pajak;
          $no_pol = $ambil_pajak->no_pol;
        }else{ 
          $notice_pajak = 0;
          $no_pol = "";
        }

        $amb2 = $this->m_admin->getByID("tr_pengajuan_bbn_detail","no_mesin",$isi->no_mesin);
        if($amb2->num_rows() > 0){
          $ambil_bbn = $amb2->row();
          $nilai_bbn = $ambil_bbn->biaya_bbn_md_bj;
        }else{ 
          $nilai_bbn = 0;
        }

        if($nilai_bbn != $notice_pajak) $bg = "bgcolor='red'";        
        echo "
      <tr $bg>                     
        <td>$isi->no_mesin</td> 
        <td>$isi->no_rangka</td> 
        <td>$isi->no_stnk</td> 
        <td>$no_pol</td> 
        <td>$isi->nama_konsumen</td> 
        <td>$isi->deskripsi_ahm</td>       
        <td>$isi->tahun</td>                 
        <td>".mata_uang2($nilai_bbn)."</td>                 
        <td>".mata_uang2($notice_pajak)."</td>                 
        <td align='center'>
          <input type='hidden' value='$jum' name='jum'>        
          <input type='hidden' value='$isi->no_mesin' name='no_mesin_$no'>        
          <input type='checkbox' name='cek_nosin_$no' id='cek_nosin[]'>
        </td>              
      </tr>";
      $no++;
      }
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