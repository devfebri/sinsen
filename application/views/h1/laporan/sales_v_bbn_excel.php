<?php 
$no = date("dmyhis");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=AktualSalesBBN_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
function mata_uang3($a){
  if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
    if(is_numeric($a) AND $a != 0 AND $a != ""){
      return number_format($a, 0, ',', '.');
    }else{
      return $a;
    }        
}
function bln($a){
  $bulan=$bl=$month=$a;
  switch($bulan)
  {
    case"1":$bulan="Januari"; break;
    case"2":$bulan="Februari"; break;
    case"3":$bulan="Maret"; break;
    case"4":$bulan="April"; break;
    case"5":$bulan="Mei"; break;
    case"6":$bulan="Juni"; break;
    case"7":$bulan="Juli"; break;
    case"8":$bulan="Agustus"; break;
    case"9":$bulan="September"; break;
    case"10":$bulan="Oktober"; break;
    case"11":$bulan="November"; break;
    case"12":$bulan="Desember"; break;
  }
  $bln = $bulan;
  return $bln;
}
?>
<?php if($tipe == 'per_tanggal'){ $tgl = bln($bulan)." ".$tahun; ?>
        
<div style="text-align: center;font-size: 13pt"><b>Laporan Aktual Sales VS BBN</b></div>                
<div style="text-align: center;font-size: 10pt"><b>Bulan : <?php echo $tgl ?></b></div>                
<br>
<?php  
$bln = sprintf("%'.02d",$bulan);                            
$tgl_surat_1 = $tahun."-".$bln;
$sql = $this->db->query("SELECT * FROM ms_tipe_kendaraan INNER JOIN tr_scan_barcode ON ms_tipe_kendaraan.id_tipe_kendaraan = tr_scan_barcode.tipe_motor
            INNER JOIN tr_sales_order ON tr_scan_barcode.no_mesin = tr_sales_order.no_mesin
            INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
            WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1'
            GROUP BY tr_sales_order.id_dealer");
foreach ($sql->result() as $amb) {          
  echo "<b>Dealer : $amb->kode_dealer_md - $amb->nama_dealer</b>";
?>
  <table class='table table-bordered' style='font-size: 9pt' width='100%'>
    <thead>
      <tr>
        <td bgcolor='yellow' class='bold text-center'>Tanggal</td>              
        <td bgcolor='yellow' class='bold text-center'>Penjualan</td>                                        
        <td bgcolor='yellow' class='bold text-center'>Data BBN</td>                                        
        <td bgcolor='yellow' class='bold text-center'>Selisih</td>                                                      
      </tr>
    </thead>
    <tbody>
      <?php             
      $tot=0;$tot1=0;$tot2=0;
      for ($i=1; $i <= 31; $i++) {               
        $bln = sprintf("%'.02d",$bulan);                            
        $tgl = sprintf("%'.02d",$i);                            
        $tgl_surat_1 = $tgl."-".$bln."-".$tahun;              
        $tgl_surat_2 = $tahun."-".$bln."-".$tgl;              
        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
          INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) = '$tgl_surat_2' AND tr_sales_order.id_dealer = '$amb->id_dealer'")->row()->jum;
        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
          else $jumlah_jual = 0;

        $cek_bbn = $this->db->query("SELECT COUNT(tr_faktur_stnk_detail.no_mesin) AS jum FROM tr_faktur_stnk_detail
          INNER JOIN tr_scan_barcode ON tr_faktur_stnk_detail.no_mesin = tr_scan_barcode.no_mesin                                                   
          INNER JOIN tr_faktur_stnk ON tr_faktur_stnk.no_bastd = tr_faktur_stnk_detail.no_bastd                  
          WHERE tr_faktur_stnk.tgl_bastd = '$tgl_surat_2' AND tr_faktur_stnk.id_dealer = '$amb->id_dealer'")->row()->jum;
        if(isset($cek_bbn) AND $cek_bbn != 0) $jumlah_jual2 = $cek_bbn;
          else $jumlah_jual2 = 0;
        $hasil = $jumlah_jual - $jumlah_jual2;

        $tot += $jumlah_jual;
        $tot1 += $jumlah_jual2;
        $tot2 += $hasil;
        echo "<tr>
                <td>$tgl_surat_1</td>
                <td align='center'>$jumlah_jual</td>
                <td align='center'>$jumlah_jual2</td>
                <td align='center'>$hasil</td>
              </tr>";
        }                             
      ?>                  
    </tbody>
    <tfoot>
      <tr>
        <td bgcolor='yellow' class='bold text-center'>TOTAL</td>              
        <td bgcolor='yellow' class='bold text-center'><?php echo $tot ?></td>
        <td bgcolor='yellow' class='bold text-center'><?php echo $tot1 ?></td>
        <td bgcolor='yellow' class='bold text-center'><?php echo $tot2 ?></td>
      </tr>
    </tfoot>
  </table> <br>
<?php
} 
}elseif($tipe == 'per_dealer' AND $id_dealer == 'all'){ $tgl = bln($bulan)." ".$tahun; ?>

<div style="text-align: center;font-size: 13pt"><b>Rekap Penjualan VS BBN</b></div>                
<div style="text-align: center;font-size: 10pt"><b>Bulan : <?php echo $tgl ?></b></div>                
<br>                
<table class='table table-bordered' style='font-size: 9pt' width='100%'>
  <thead>
    <tr>
      <td bgcolor='yellow' class='bold text-center'>Nama Dealer</td>              
      <td bgcolor='yellow' class='bold text-center'>Penjualan</td>                                        
      <td bgcolor='yellow' class='bold text-center'>Data BBN</td>                                        
      <td bgcolor='yellow' class='bold text-center'>Selisih</td>                                                      
    </tr>
  </thead>
  <tbody>
    <?php             
    $tot=0;$tot1=0;$tot2=0;
    $sql = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1");
    foreach ($sql->result() as $row) {              
      $bln = sprintf("%'.02d",$bulan);                                          
      $tgl_surat_2 = $tahun."-".$bln;
      $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
        INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
        INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
        WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
      if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
        else $jumlah_jual = 0;

      $cek_bbn = $this->db->query("SELECT COUNT(tr_faktur_stnk_detail.no_mesin) AS jum FROM tr_faktur_stnk_detail
        INNER JOIN tr_scan_barcode ON tr_faktur_stnk_detail.no_mesin = tr_scan_barcode.no_mesin                                                   
        INNER JOIN tr_faktur_stnk ON tr_faktur_stnk.no_bastd = tr_faktur_stnk_detail.no_bastd                  
        WHERE LEFT(tr_faktur_stnk.tgl_bastd,7) = '$tgl_surat_2' AND tr_faktur_stnk.id_dealer = '$row->id_dealer'")->row()->jum;
      if(isset($cek_bbn) AND $cek_bbn != 0) $jumlah_jual2 = $cek_bbn;
        else $jumlah_jual2 = 0;
      $hasil = $jumlah_jual - $jumlah_jual2;

      $tot += $jumlah_jual;
      $tot1 += $jumlah_jual2;
      $tot2 += $hasil;
      echo "<tr>
              <td>$row->nama_dealer</td>
              <td align='center'>$jumlah_jual</td>
              <td align='center'>$jumlah_jual2</td>
              <td align='center'>$hasil</td>
            </tr>";
      }                             
    ?>                  
  </tbody>
  <tfoot>
    <tr>
      <td bgcolor='yellow' class='bold text-center'>TOTAL</td>              
      <td bgcolor='yellow' class='bold text-center'><?php echo $tot ?></td>
      <td bgcolor='yellow' class='bold text-center'><?php echo $tot1 ?></td>
      <td bgcolor='yellow' class='bold text-center'><?php echo $tot2 ?></td>
    </tr>
  </tfoot>
</table> <br>
<?php      
}
?>