<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 30px;  
  padding-left: 5px;
  padding-right: 5px;   
  margin-right: 0px; 
}
.isi_combo{   
  height: 30px;
  border:1px solid #ccc;
  padding-left:1.5px;
}
</style>
<body onload="window.print()">
<?php 
$row = $dt_alasan->row();
?>          
<table class="myTable1" width="90%" align="center" border="0">
  <tr>
    <td width="60%">
      <?php echo $row->no_surat ?>
    </td>
    <td width="40%">
      <?php
      $tanggal = date("d F Y", strtotime($row->tgl_entry)); 
      echo $tanggal 
      ?>
    </td>
  </tr>
  <tr>
    <td valign="top">
      Penggantian / Koreksi Faktur Polisi
    </td>
    <td>
      Kepada Yth, <br>
      PT.Astra Honda Motor <br>
      AR/AP Dept head <br>
      Jl.Laksada Yos Sudarso. Sunter I <br>
      Jakarta 14350 <br>
      UP.Bpk Priyo Lambang
    </td>
  </tr>            
  <tr>
    <td colspan="2">
      Dengan hormat, <br>
      <p>
        Dengan ini kami mohon untuk diterbitkan kembali Faktur Polisi / Sertifikat di bawah ini:
      </p>
      <table class="myTable1" width="100%" border="1">
        <tr>
          <td width="5%">No</td>
          <td width="45%">No aktur</td>
          <td width="50%">Alasan Penggantian</td>
        </tr>
        <?php 
        $no=1;
        $tr = $this->db->query("SELECT * FROM tr_penggantian_fkb_detail INNER JOIN tr_fkb ON tr_penggantian_fkb_detail.no_mesin=tr_fkb.no_mesin_spasi
           WHERE tr_penggantian_fkb_detail.no_surat = '$row->no_surat'");
        foreach ($tr->result() as $isi) {
          echo "
          <tr>
            <td>$no</td>
            <td>$isi->nomor_faktur</td>
            <td>$isi->alasan_penggantian</td>
          </tr>
          ";
        $no++;
        }
        ?>
        <tr>
          
        </tr>
      </table>
    </td>
  </tr>  
  <tr>
    <td colspan="2">
      Atas bantuan dan kerjasamanya kami ucapkan terima kasih.
    </td>                
  </tr>
  <tr>
    <td width="60%"></td>
    <td width="40%">
      Hormat Kami, <br><br><br>
      <u>Tony Attan</u> <br>
      <i>Direktur</i>
    </td>
  </tr>
</table>            
         