<?php 
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
<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 25px;
  padding-left: 4px;
  padding-right: 4px;  
}
.vertical-text{
  writing-mode: lr-tb;
  text-orientation: mixed;
}
.rotate {
  -webkit-transform: rotate(-90deg);
  -moz-transform: rotate(-90deg);
}
#mySpan{
  writing-mode: vertical-lr; 
  transform: rotate(180deg);
}
</style>
<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Laporan</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    
    <?php 
    if($set=="view"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">        
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" id="frm" method="post" enctype="multipart/form-data">
              <div class="box-body">                                                              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-1 control-label">Bulan</label>
                  <div class="col-sm-2">
                    <select class="form-control" name="bulan" id="bulan">
                      <option value="">- choose -</option>                      
                      <?php 
                      $m = date("m"); 
                      for ($i=1; $i <= 12; $i++) { 
                        if($i==$m) $select="selected";
                          else $select = ''; 
                        $i_j = bln($i);    
                        echo "<option $select value='$i'>$i_j</option>";                                                
                      } ?>  
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-1 control-label">Tahun</label>
                  <div class="col-sm-1">
                    <select class="form-control" name="tahun" id="tahun">                      
                      <?php 
                      $y = date("Y");
                      for ($i=$y - 5; $i <= $y + 10; $i++) { 
                        if($i==$y) $select="selected";
                          else $select = '';
                        echo "<option $select>$i</option>";
                      }
                      ?>                          
                    </select>
                  </div>                               
                  <div class="col-sm-2">                    
                    <input type="radio" name="tipe" id="tipe" value="detail_transaksi" class="minimal" checked> Detail Transaksi
                  </div>
                  <div class="col-sm-2">                    
                    <input type="radio" name="tipe" id="tipe" value="per_dealer" class="minimal"> Global Per Dealer
                  </div>
                  <div class="col-sm-3">                    
                    <input type="radio" name="tipe" id="tipe" value="per_kab" class="minimal"> Per Tipe & Kabupaten
                  </div>                                      
                </div>             
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-1 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_dealer" id="id_dealer">
                      <option value="all">All Dealers</option>
                      <?php 
                      $sql_kab = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1 ORDER BY ms_dealer.id_dealer ASC");
                      foreach ($sql_kab->result() as $isi) {
                        echo "<option value='$isi->id_dealer'>$isi->kode_dealer_md - $isi->nama_dealer</option>";
                      }
                       ?>
                    </select>
                  </div>
                  <div class="col-sm-2">                    
                    <input type="radio" name="tipe" id="tipe" value="rekap" class="minimal"> Rekap (Sales Stock)
                  </div>
                  <div class="col-sm-2">                    
                    <input type="radio" name="tipe" id="tipe" value="per_tipe" class="minimal"> Global Per Tipe
                  </div>                      
                  
                  
                  <div class="col-sm-2">
                    <button type="button" onclick="getReport()" name="process" value="edit" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-print"></i> Preview</button>                                                      
                  </div>                  
                </div>                
              </div><!-- /.box-body -->              
              <div class="box-footer">                                                              
                <div style="min-height: 600px">                 
                  <iframe style="overflow: auto; border: 0px solid #fff; width: 100%; height: 602px;margin-bottom: -5px;" id="showReport"></iframe>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    
    <?php }elseif ($set=='cetak') { ?>
    <!DOCTYPE html>
    <html>
    <!-- <html lang="ar"> for arabic only -->
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <title>Cetak</title>
      <style>
        @media print {
          @page {
            sheet-size: 297mm 210mm;
            margin-left: 0.8cm;
            margin-right: 0.8cm;
            margin-bottom: 1cm;
            margin-top: 1cm;
          }
          .text-center{text-align: center;}
          .bold{font-weight: bold;}
          .table {
            width: 100%;
            max-width: 100%;
            border-collapse: collapse;
           /*border-collapse: separate;*/
          }
          .table-bordered tr td {
            border: 0.01em solid black;
            padding-left: 6px;
            padding-right: 6px;
          }
          body{
            font-family: "Arial";
            font-size: 11pt;
          }
          
        }
      </style>
    </head>
    <body>      
      <?php if($tipe == 'detail_transaksi'){ $tgl = bln($bulan)." ".$tahun; ?>
        <div style="text-align: center;font-size: 13pt"><b>Laporan Transaksi Harian</b></div>        
        <div style="text-align: center; font-weight: bold;">Bulan : <?php echo $tgl ?></div>
        <hr>      
        <?php 
        if($id_dealer == 'all'){
          $sql_dealer = $this->db->query("SELECT * FROM ms_dealer WHERE ms_dealer.active = 1 ORDER BY ms_dealer.id_dealer ASC");
        }else{
          $sql_dealer = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer = '$id_dealer' ORDER BY ms_dealer.id_dealer ASC");
        }
        foreach ($sql_dealer->result() as $isi) {
              $sql_stok = $this->db->query("SELECT ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,tr_scan_barcode.* FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan
                  ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
                  INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
                  LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan = tr_scan_barcode.tipe_motor
                  WHERE tr_surat_jalan.id_dealer = '$isi->id_dealer' AND (tr_scan_barcode.status = 3 OR tr_scan_barcode.status = 4 OR tr_scan_barcode.status = 5)
                  GROUP BY ms_tipe_kendaraan.id_tipe_kendaraan");                        
              if($sql_stok->num_rows() > 0){
              echo "Dealer : $isi->kode_dealer_md - $isi->nama_dealer"; ?>
              <table class='table table-bordered' style='font-size: 9pt' width='100%'>
                <tr>                
                  <td bgcolor='yellow' class='bold text-center' rowspan="2">Tipe Motor</td>
                  <td bgcolor='yellow' class='bold text-center' rowspan="2">Stok Awal</td>
                  <td bgcolor='yellow' class='bold text-center' rowspan="2">Ttl Dist</td>
                  <td bgcolor='yellow' class='bold text-center' colspan="3">Penjualan Unit</td>
                  <td bgcolor='yellow' class='bold text-center' rowspan="2">Stok Akhir</td>                                    
                  <td bgcolor='yellow' class='bold text-center' colspan="31">Penjualan Per Tanggal</td>                  
                </tr>
                <tr>
                  <td>Umum</td>
                  <td>Grup</td>
                  <td>Total</td>
                  <?php for ($i=1; $i <= 31; $i++) { 
                    $i = sprintf("%'.02d",$i);    
                    echo "<td align='center'>$i</td>";
                  } ?>
                </tr>
              <?php 
              $no=1;$g_1=0;$g_2=0;$g_3=0;$g_4=0;$g_5=0;$g_6=0;$g_7=0;$g_8=0;
              foreach ($sql_stok->result() as $amb) {                            
                $bln = sprintf("%'.02d",$bulan);    
                $tgl = sprintf("%'.02d",$i);    
                $tgl_surat = $tahun."-".$bln;

                

                $cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
                  LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
                  LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                  LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                  LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                  LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
                  WHERE tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan' AND tr_penerimaan_unit_dealer.id_dealer = '$isi->id_dealer' 
                  AND (tr_scan_barcode.status = '4' OR tr_scan_barcode.status = '5') AND LEFT(tr_penerimaan_unit_dealer.tgl_penerimaan,7) < '$tgl_surat'")->row()->jum;
                if(isset($cek_qty) AND $cek_qty != 0) $isi_stok = $cek_qty;
                  else $isi_stok = 0;

                $cek_sj = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
                  INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin 
                  WHERE tr_surat_jalan.id_dealer = '$isi->id_dealer' AND tr_surat_jalan_detail.ceklist = 'ya' 
                  AND tr_scan_barcode.tipe_motor = '$amb->tipe_motor' AND LEFT(tr_surat_jalan.tgl_surat,7) = '$tgl_surat'")->row()->jum;
                if(isset($cek_sj) AND $cek_sj != 0) $jumlah = $cek_sj;
                  else $jumlah = 0;

                $cek_so = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
                  WHERE tr_sales_order.id_dealer = '$isi->id_dealer' AND tr_scan_barcode.tipe_motor = '$amb->tipe_motor'
                  AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat'")->row()->jum;
                if(isset($cek_so) AND $cek_so != 0) $jumlah_so = $cek_so;
                  else $jumlah_so = 0;

                $tot = $isi_stok + $jumlah - $jumlah_so;
                echo "
                <tr>                
                  <td align='center'>$amb->tipe_ahm</td>
                  <td align='center'>$isi_stok</td>
                  <td align='center'>$jumlah</td>
                  <td align='center'>$jumlah_so</td>
                  <td align='center'>0</td>
                  <td align='center'>$jumlah_so</td>
                  <td align='center'>$tot</td>";                  
                  for ($i=1; $i <= 31; $i++) { 
                    $bln = sprintf("%'.02d",$bulan);    
                    $tgl = sprintf("%'.02d",$i);    
                    $tgl_surat_1 = $tahun."-".$bln."-".$tgl;

                    $cek_so = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
                      WHERE tr_sales_order.id_dealer = '$isi->id_dealer' AND tr_scan_barcode.tipe_motor = '$amb->tipe_motor'
                      AND LEFT(tr_sales_order.tgl_create_ssu,10) = '$tgl_surat_1'")->row()->jum;
                    if(isset($cek_so) AND $cek_so != 0) $jumlah_isi = $cek_so;
                      else $jumlah_isi = '-';
                    echo "<td align='center'>$jumlah_isi</td>";
                  }
                  $g_1 += $isi_stok;
                  $g_2 += $jumlah;
                  $g_4 += $jumlah_so;
                  $g_5 += 0;
                  $g_6 += $jumlah_so;
                  $g_7 += $tot;
                  $g_8 += 0;
                  echo "
                </tr>";                            
              }
              echo "<tfoot>
                      <tr>
                        <td class='bold text-center' bgcolor='yellow'>Total</td>
                        <td class='bold text-center' bgcolor='yellow'>$g_1</td>
                        <td class='bold text-center' bgcolor='yellow'>$g_2</td>
                        <td class='bold text-center' bgcolor='yellow'>$g_4</td>
                        <td class='bold text-center' bgcolor='yellow'>$g_5</td>
                        <td class='bold text-center' bgcolor='yellow'>$g_6</td>
                        <td class='bold text-center' bgcolor='yellow'>$g_7</td>";
                        for ($i=1; $i <= 31; $i++) { 
                          $bln = sprintf("%'.02d",$bulan);    
                          $tgl = sprintf("%'.02d",$i);    
                          $tgl_surat_1 = $tahun."-".$bln."-".$tgl;

                          $cek_so = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
                            WHERE tr_sales_order.id_dealer = '$isi->id_dealer'
                            AND LEFT(tr_sales_order.tgl_create_ssu,10) = '$tgl_surat_1'")->row()->jum;
                          if(isset($cek_so) AND $cek_so != 0) $jumlah_isi = $cek_so;
                            else $jumlah_isi = 0;                    
                          echo "<td class='bold text-center' bgcolor='yellow' align='center'>$jumlah_isi</td>";
                        }
                      echo "
                      </tr>
                    </tfoot>";
            }
            ?>
            </table> <br>
            <?php
          } ?>
          Detail Pembiayaan
          <table class='table table-bordered' style='font-size: 9pt' width='100%'>
            <tr>                
              <td bgcolor='yellow' class='bold text-center'>Penjualan Via</td>
              <td bgcolor='yellow' class='bold text-center'>Total</td>              
              <?php for ($i=1; $i <= 31; $i++) { 
                $i = sprintf("%'.02d",$i);    
                echo "<td bgcolor='yellow' class='bold text-center'>$i</td>";
              } ?>
            </tr>
          <?php
          $g_total=0;
          if($id_dealer == 'all'){
            $sql_dealer = "";
          }else{
            $sql_dealer = "AND tr_sales_order.id_dealer = '$id_dealer'";
          }
          $sql_fin = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1
                ORDER BY ms_finance_company.id_finance_company ASC");
          foreach ($sql_fin->result() as $isi) {
            echo "<tr>
                    <td>$isi->finance_company</td>";
                    $bln = sprintf("%'.02d",$bulan);                        
                    $tgl_surat = $tahun."-".$bln;
                    $cek_ju = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      WHERE tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$isi->id_finance_company'
                      $sql_dealer
                      AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat'")->row()->jum;                    
                    $g_total += $cek_ju;
                    echo "<td align='center'>$cek_ju</td>";
                    for ($i=1; $i <= 31; $i++) { 
                      $bln = sprintf("%'.02d",$bulan);    
                      $tgl = sprintf("%'.02d",$i);    
                      $tgl_surat_1 = $tahun."-".$bln."-".$tgl;
                      $cek_so1 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                        INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
                        INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                        WHERE tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$isi->id_finance_company'
                        $sql_dealer
                        AND LEFT(tr_sales_order.tgl_create_ssu,10) = '$tgl_surat_1'")->row()->jum;
                      if(isset($cek_so1) AND $cek_so1 != 0) $jumlah_jual = $cek_so1;
                        else $jumlah_jual = 0;
                      
                      echo "<td align='center'>$jumlah_jual</td>";
                    }
                    echo "
                  </tr>";                    
          } ?>
            <tr>
              <td>Cash</td>
              <?php 
              $bln = sprintf("%'.02d",$bulan);                        
              $tgl_surat = $tahun."-".$bln;
              $cek_ju = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
                INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                WHERE tr_spk.jenis_beli = 'Cash'
                $sql_dealer
                AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat'")->row()->jum;
              $g_total += $cek_ju;
              echo "<td align='center'>$cek_ju</td>";
              ?>
              <?php 
              for ($i=1; $i <= 31; $i++) { 
                $bln = sprintf("%'.02d",$bulan);    
                $tgl = sprintf("%'.02d",$i);    
                $tgl_surat_1 = $tahun."-".$bln."-".$tgl;
                $cek_am = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  WHERE tr_spk.jenis_beli = 'Cash'
                  $sql_dealer
                  AND LEFT(tr_sales_order.tgl_create_ssu,10) = '$tgl_surat_1'")->row()->jum;
                if(isset($cek_am) AND $cek_am != 0) $jumlah_jual = $cek_am;
                  else $jumlah_jual = 0;
                
                echo "<td align='center'>$jumlah_jual</td>";
              }
              ?>
            </tr>
            <tfoot>
              <tr>
                <td bgcolor='yellow' class='bold text-center'>Total</td>
                <td bgcolor='yellow' class='bold text-center'><?php echo $g_total ?></td>
                <?php 
                for ($i=1; $i <= 31; $i++) {                   
                  $bln = sprintf("%'.02d",$bulan);    
                  $tgl = sprintf("%'.02d",$i);    
                  $tgl_surat_1 = $tahun."-".$bln."-".$tgl;
                  $cek_am = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                    INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
                    INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                    WHERE (tr_spk.jenis_beli = 'Cash' OR tr_spk.jenis_beli = 'Kredit')
                    $sql_dealer
                    AND LEFT(tr_sales_order.tgl_create_ssu,10) = '$tgl_surat_1'")->row()->jum;
                  if(isset($cek_am) AND $cek_am != 0) $jumlah_jual = $cek_am;
                    else $jumlah_jual = 0;
                  
                  echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual</td>";
                }
                ?>
              </tr>
            </tfoot>              
        </table>
        <?php
      }elseif($tipe=='per_tipe'){ $tgl = bln($bulan)." ".$tahun; 
        if($id_dealer != 'all'){        
          $ge = $this->m_admin->getByID("ms_dealer","id_dealer",$id_dealer)->row();
          $dealer = $ge->kode_dealer_md." - ".$ge->nama_dealer;
        }else{
          $dealer = "";
        }
        ?>
        <div style="text-align: center;font-size: 13pt"><b>Laporan Penjualan Harian (Per Tipe Motor) <?php echo $dealer ?></b></div>        
        <div style="text-align: center; font-weight: bold;">Bulan : <?php echo $tgl ?></div>
        <hr>      
        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <tr>                
            <td bgcolor='yellow' class='bold text-center' rowspan="2">Tipe Motor</td>
            <td bgcolor='yellow' class='bold text-center' rowspan="2">Stok Awal</td>
            <td bgcolor='yellow' class='bold text-center' rowspan="2">Ttl Dist</td>
            <td bgcolor='yellow' class='bold text-center' colspan="31">Penjualan Per Tanggal</td>
            <td bgcolor='yellow' class='bold text-center' colspan="3">Penjualan Unit</td>                
            <td bgcolor='yellow' class='bold text-center' rowspan="2">Stok Akhir</td>                
          </tr>
          <tr>            
            <?php for ($i=1; $i <= 31; $i++) { 
              $i = sprintf("%'.02d",$i);    
              echo "<td align='center'>$i</td>";
            } ?>
            <td>Umum</td>
            <td>Grup</td>
            <td>Total</td>
          </tr>
        <?php 
        $total_akhir=0;$g_1=0;$g_2=0;$g_3=0;$g_4=0;$g_5=0;$g_6=0;
        $sql_tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE ms_tipe_kendaraan.active = 1
          ORDER BY ms_tipe_kendaraan.tipe_ahm ASC");        
        foreach ($sql_tipe->result() as $isi) {              
                $bln = sprintf("%'.02d",$bulan);    
                $tgl = sprintf("%'.02d",$i);    
                $tgl_surat = $tahun."-".$bln;
                if($id_dealer == 'all'){
                  $query="";$query2="";$query3="";
                }else{
                  $query = "AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer'";   
                  $query2 = "AND tr_surat_jalan.id_dealer = '$id_dealer'";   
                  $query3 = "AND tr_sales_order.id_dealer = '$id_dealer'";   
                }
                
                $cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
                  LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
                  LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                  LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                  LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                  LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
                  WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' $query 
                  AND (tr_scan_barcode.status = '4' OR tr_scan_barcode.status = '5') AND LEFT(tr_penerimaan_unit_dealer.tgl_penerimaan,7) < '$tgl_surat'")->row()->jum;
                if(isset($cek_qty) AND $cek_qty != 0) $isi_stok = $cek_qty;
                  else $isi_stok = 0;

                $cek_sj = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
                  INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin 
                  WHERE tr_surat_jalan_detail.ceklist = 'ya' $query2
                  AND tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' AND LEFT(tr_surat_jalan.tgl_surat,7) = '$tgl_surat'")->row()->jum;
                if(isset($cek_sj) AND $cek_sj != 0) $jumlah = $cek_sj;
                  else $jumlah = 0;
                echo "
                <tr>                
                  <td>$isi->tipe_ahm</td>
                  <td>$isi_stok</td>
                  <td>$jumlah</td>";
                  $total=0;
                  for ($i=1; $i <= 31; $i++) { 
                    $bln = sprintf("%'.02d",$bulan);    
                    $tgl = sprintf("%'.02d",$i);    
                    $tgl_surat_1 = $tahun."-".$bln."-".$tgl;
                    $cek_so3 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                       
                      WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' $query3
                      AND LEFT(tr_sales_order.tgl_create_ssu,10) = '$tgl_surat_1'")->row()->jum;
                    if(isset($cek_so3) AND $cek_so3 != 0) $jumlah_j = $cek_so3;
                      else $jumlah_j = 0;
                    $total += $jumlah_j;
                    echo "<td align='center'>$jumlah_j</td>";
                  }
                  $total_akhir = $isi_stok + $jumlah - $total;
                  echo "<td class='bold text-center'>$total</td>
                        <td class='bold text-center'>0</td>
                        <td class='bold text-center'>$total</td>
                        <td class='bold text-center'>$total_akhir</td>
                </tr>";                                                                  
                $g_1 += $isi_stok;
                $g_2 += $jumlah;
                $g_3 += $total;
                $g_4 += 0;
                $g_5 += $total;
                $g_6 += $total_akhir;
              }
            ?>
              <tfoot>
                <tr>
                  <td class='bold text-center' bgcolor="yellow">Total</td>
                  <td class='bold text-center' bgcolor="yellow"><?php echo $g_1 ?></td>
                  <td class='bold text-center' bgcolor="yellow"><?php echo $g_2 ?></td>
                  <?php 
                  $total=0;
                  for ($i=1; $i <= 31; $i++) { 
                    $bln = sprintf("%'.02d",$bulan);    
                    $tgl = sprintf("%'.02d",$i);    
                    $tgl_surat_1 = $tahun."-".$bln."-".$tgl;
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                       
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) = '$tgl_surat_1' $query3")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                      else $jumlah_jual = 0;
                    $total += $jumlah_jual;
                    echo "<td class='bold text-center' bgcolor='yellow'>$jumlah_jual</td>";
                  } ?>
                  <td class='bold text-center' bgcolor="yellow"><?php echo $g_3 ?></td>
                  <td class='bold text-center' bgcolor="yellow"><?php echo $g_4 ?></td>
                  <td class='bold text-center' bgcolor="yellow"><?php echo $g_5 ?></td>
                  <td class='bold text-center' bgcolor="yellow"><?php echo $g_6 ?></td>
                  ?>
                </tr>
              </tfoot>
            </table> <br>
        <?php        
      }elseif($tipe=='per_dealer' AND $id_dealer=='all'){ $tgl = bln($bulan)." ".$tahun;         
        ?>
        <div style="text-align: center;font-size: 13pt"><b>Laporan Transaksi Harian </b></div>        
        <div style="text-align: center; font-weight: bold;">Bulan : <?php echo $tgl ?></div>
        <hr>      
        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <tr>                
            <td bgcolor='yellow' class='bold text-center' rowspan="2">Nama Dealer</td>
            <td bgcolor='yellow' class='bold text-center' rowspan="2">Stok Awal</td>
            <td bgcolor='yellow' class='bold text-center' rowspan="2">Ttl Dist</td>
            <td bgcolor='yellow' class='bold text-center' colspan="31">Penjualan Per Tanggal</td>
            <td bgcolor='yellow' class='bold text-center' colspan="3">Penjualan Unit</td>                
            <td bgcolor='yellow' class='bold text-center' rowspan="2">Stok Akhir</td>                
          </tr>
          <tr>            
            <?php for ($i=1; $i <= 31; $i++) { 
              $i = sprintf("%'.02d",$i);    
              echo "<td align='center'>$i</td>";
            } ?>
            <td>Umum</td>
            <td>Grup</td>
            <td>Total</td>
          </tr>
        <?php 
        $total_akhir=0;$tg_1=0;$tg_2=0;$tg_3=0;$tg_4=0;$tg_5=0;$tg_6=0;
        $sql_kab = $this->db->query("SELECT * FROM ms_kabupaten WHERE ms_kabupaten.id_provinsi = 1500
                  ORDER BY ms_kabupaten.Kabupaten ASC");
        $g_1=0;$g_2=0;$g_3=0;$g_4=0;$g_5=0;$g_6=0;
        foreach ($sql_kab->result() as $isi) {              
          $bln = sprintf("%'.02d",$bulan);    
          $tgl = sprintf("%'.02d",$i);    
          $tgl_surat = $tahun."-".$bln;
          $sql_dealer = $this->db->query("SELECT * FROM ms_dealer
            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
            WHERE ms_kabupaten.id_kabupaten = '$isi->id_kabupaten'");
          foreach ($sql_dealer->result() as $amb) {                  
            $cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
              LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
              LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
              LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
              LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
              LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
              WHERE tr_penerimaan_unit_dealer.id_dealer = '$amb->id_dealer'
              AND (tr_scan_barcode.status = '4' OR tr_scan_barcode.status = '5') AND LEFT(tr_penerimaan_unit_dealer.tgl_penerimaan,7) < '$tgl_surat'")->row()->jum;
            if(isset($cek_qty) AND $cek_qty != 0) $isi_stok = $cek_qty;
              else $isi_stok = 0;

            $cek_sj = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
              INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin 
              WHERE tr_surat_jalan_detail.ceklist = 'ya'
              AND tr_surat_jalan.id_dealer = '$amb->id_dealer' AND LEFT(tr_surat_jalan.tgl_surat,7) = '$tgl_surat'")->row()->jum;
            if(isset($cek_sj) AND $cek_sj != 0) $jumlah = $cek_sj;
              else $jumlah = 0;

            echo "
            <tr>                
              <td>$amb->nama_dealer</td>
              <td align='center'>$isi_stok</td>
              <td align='center'>$jumlah</td>";
              $total=0;
              for ($i=1; $i <= 31; $i++) { 
                $bln = sprintf("%'.02d",$bulan);    
                $tgl = sprintf("%'.02d",$i);    
                $tgl_surat_1 = $tahun."-".$bln."-".$tgl;
                $cek_so3 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                       
                  WHERE tr_sales_order.id_dealer = '$amb->id_dealer'
                  AND LEFT(tr_sales_order.tgl_create_ssu,10) = '$tgl_surat_1'")->row()->jum;
                if(isset($cek_so3) AND $cek_so3 != 0) $jumlah_j = $cek_so3;
                  else $jumlah_j = 0;
                $total += $jumlah_j;
                echo "<td align='center'>$jumlah_j</td>";
              }
              $total_akhir = $isi_stok + $jumlah - $total;
              echo "<td class='bold text-center'>$total</td>
                    <td class='bold text-center'>0</td>
                    <td class='bold text-center'>$total</td>
                    <td class='bold text-center'>$total_akhir</td>
            </tr>"; 
            $g_1 += $isi_stok;                                                                                   
            $g_2 += $jumlah;                                                                                               
            $g_3 += $total;                                                                                               
            $g_4 += 0;                                                                                               
            $g_5 += $total;                                                                                               
            $g_6 += $total_akhir;                                                                                               
          } ?>
          <tr>
            <td class='bold text-center' bgcolor="lime"><?php echo $isi->kabupaten ?></td>
            <td class='bold text-center' bgcolor="lime"><?php echo $g_1 ?></td>
            <td class='bold text-center' bgcolor="lime"><?php echo $g_2 ?></td>
            <?php 
            $total=0;
            for ($i=1; $i <= 31; $i++) { 
              $bln = sprintf("%'.02d",$bulan);    
              $tgl = sprintf("%'.02d",$i);    
              $tgl_surat_1 = $tahun."-".$bln."-".$tgl;
              $cek_so3 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                       
                  INNER JOIN ms_dealer ON ms_dealer.id_dealer = tr_sales_order.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                  WHERE ms_kabupaten.id_kabupaten = '$isi->id_kabupaten'
                  AND LEFT(tr_sales_order.tgl_create_ssu,10) = '$tgl_surat_1'")->row()->jum;
                if(isset($cek_so3) AND $cek_so3 != 0) $jumlah_j = $cek_so3;
                  else $jumlah_j = 0;
              $total += $jumlah_j;
              echo "<td class='bold text-center' bgcolor='lime'>$jumlah_j</td>";              
            } ?>
            <td class='bold text-center' bgcolor="lime"><?php echo $g_3 ?></td>
            <td class='bold text-center' bgcolor="lime"><?php echo $g_4 ?></td>
            <td class='bold text-center' bgcolor="lime"><?php echo $g_5 ?></td>
            <td class='bold text-center' bgcolor="lime"><?php echo $g_6 ?></td>
            ?>
          </tr>
        <?php
          $tg_1 += $g_1;
          $tg_2 += $g_2;
          $tg_3 += $g_3;
          $tg_4 += $g_4;
          $tg_5 += $g_5;
          $tg_6 += $g_6;
        }
            ?>
              <tfoot>
                <tr>
                  <td class='bold text-center' bgcolor="yellow">T O T A L</td>
                  <td class='bold text-center' bgcolor="yellow"><?php echo $tg_1 ?></td>
                  <td class='bold text-center' bgcolor="yellow"><?php echo $tg_2 ?></td>
                  <?php 
                  $total=0;
                  for ($i=1; $i <= 31; $i++) { 
                    $bln = sprintf("%'.02d",$bulan);    
                    $tgl = sprintf("%'.02d",$i);    
                    $tgl_surat_1 = $tahun."-".$bln."-".$tgl;
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                       
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) = '$tgl_surat_1'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                      else $jumlah_jual = 0;
                    $total += $jumlah_jual;
                    echo "<td class='bold text-center' bgcolor='yellow'>$jumlah_jual</td>";
                  } ?>
                  <td class='bold text-center' bgcolor="yellow"><?php echo $tg_3 ?></td>
                  <td class='bold text-center' bgcolor="yellow"><?php echo $tg_4 ?></td>
                  <td class='bold text-center' bgcolor="yellow"><?php echo $tg_5 ?></td>
                  <td class='bold text-center' bgcolor="yellow"><?php echo $tg_6 ?></td>
                  ?>
                </tr>
              </tfoot>
            </table> <br>

            Detail Pembiayaan
            <table class='table table-bordered' style='font-size: 9pt' width='100%'>
              <tr>                
                <td bgcolor='yellow' class='bold text-center'>Penjualan Via</td>
                <?php for ($i=1; $i <= 31; $i++) { 
                  $i = sprintf("%'.02d",$i);    
                  echo "<td bgcolor='yellow' class='bold text-center'>$i</td>";
                } ?>
                <td bgcolor='yellow' class='bold text-center'>Total</td>              
              </tr>
            <?php
            $g_total=0;
            $sql_fin = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1
                  ORDER BY ms_finance_company.id_finance_company ASC");
            foreach ($sql_fin->result() as $isi) {
              echo "<tr>
                      <td>$isi->finance_company</td>";
                      $bln = sprintf("%'.02d",$bulan);                        
                      $tgl_surat = $tahun."-".$bln;
                      $cek_ju = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                        INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
                        INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                        WHERE tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$isi->id_finance_company'
                        AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat'")->row()->jum;                    
                      $g_total += $cek_ju;
                      for ($i=1; $i <= 31; $i++) { 
                        $bln = sprintf("%'.02d",$bulan);    
                        $tgl = sprintf("%'.02d",$i);    
                        $tgl_surat_1 = $tahun."-".$bln."-".$tgl;
                        $cek_so1 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
                          INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                          WHERE tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$isi->id_finance_company'
                          AND LEFT(tr_sales_order.tgl_create_ssu,10) = '$tgl_surat_1'")->row()->jum;
                        if(isset($cek_so1) AND $cek_so1 != 0) $jumlah_jual = $cek_so1;
                          else $jumlah_jual = 0;
                        
                        echo "<td align='center'>$jumlah_jual</td>";
                      }
                      echo "<td align='center'>$cek_ju</td>";
                      echo "
                    </tr>";                    
            } ?>
              <tr>
                <td>Cash</td>
                <?php 
                $bln = sprintf("%'.02d",$bulan);                        
                $tgl_surat = $tahun."-".$bln;
                $cek_ju = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  WHERE tr_spk.jenis_beli = 'Cash'
                  AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat'")->row()->jum;
                $g_total += $cek_ju;
                ?>
                <?php 
                for ($i=1; $i <= 31; $i++) { 
                  $bln = sprintf("%'.02d",$bulan);    
                  $tgl = sprintf("%'.02d",$i);    
                  $tgl_surat_1 = $tahun."-".$bln."-".$tgl;
                  $cek_am = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                    INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
                    INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                    WHERE tr_spk.jenis_beli = 'Cash'
                    AND LEFT(tr_sales_order.tgl_create_ssu,10) = '$tgl_surat_1'")->row()->jum;
                  if(isset($cek_am) AND $cek_am != 0) $jumlah_jual = $cek_am;
                    else $jumlah_jual = 0;
                  
                  echo "<td align='center'>$jumlah_jual</td>";
                }
                  echo "<td align='center'>$cek_ju</td>";
                ?>
              </tr>
              <tfoot>
                <tr>
                  <td bgcolor='yellow' class='bold text-center'>Total</td>
                  <?php 
                  for ($i=1; $i <= 31; $i++) {                   
                    $bln = sprintf("%'.02d",$bulan);    
                    $tgl = sprintf("%'.02d",$i);    
                    $tgl_surat_1 = $tahun."-".$bln."-".$tgl;
                    $cek_am = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      WHERE (tr_spk.jenis_beli = 'Cash' OR tr_spk.jenis_beli = 'Kredit')
                      AND LEFT(tr_sales_order.tgl_create_ssu,10) = '$tgl_surat_1'")->row()->jum;
                    if(isset($cek_am) AND $cek_am != 0) $jumlah_jual = $cek_am;
                      else $jumlah_jual = 0;
                    
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual</td>";
                  }
                  ?>
                  <td bgcolor='yellow' class='bold text-center'><?php echo $g_total ?></td>
                </tr>
              </tfoot>              
          </table>
        <?php        
      }elseif(($tipe=='per_dealer' AND $id_dealer!='all') OR ($tipe=='per_kab' AND $id_dealer!='all')){ $tgl = bln($bulan)." ".$tahun;         
        if($id_dealer != 'all'){        
          $ge = $this->m_admin->getByID("ms_dealer","id_dealer",$id_dealer)->row();
          $dealer = $ge->kode_dealer_md." - ".$ge->nama_dealer;
        }else{
          $dealer = "";
        }        
        ?>
        <div style="text-align: center;font-size: 13pt"><b>Laporan Transaksi Harian <?php echo $dealer ?></b></div>        
        <div style="text-align: center; font-weight: bold;">Bulan : <?php echo $tgl ?></div>
        <hr>      
        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <tr>                
            <td bgcolor='yellow' class='bold text-center' rowspan="2">Nama Dealer</td>
            <td bgcolor='yellow' class='bold text-center' rowspan="2">Stok Awal</td>
            <td bgcolor='yellow' class='bold text-center' rowspan="2">Ttl Dist</td>
            <td bgcolor='yellow' class='bold text-center' colspan="31">Penjualan Per Tanggal</td>
            <td bgcolor='yellow' class='bold text-center' colspan="3">Penjualan Unit</td>                
            <td bgcolor='yellow' class='bold text-center' rowspan="2">Stok Akhir</td>                
          </tr>
          <tr>            
            <?php for ($i=1; $i <= 31; $i++) { 
              $i = sprintf("%'.02d",$i);    
              echo "<td align='center'>$i</td>";
            } ?>
            <td>Umum</td>
            <td>Grup</td>
            <td>Total</td>
          </tr>
        <?php         
          $bln = sprintf("%'.02d",$bulan);              
          $tgl_surat = $tahun."-".$bln;          
          $cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
              LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
              LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
              LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
              LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
              LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
              WHERE tr_penerimaan_unit_dealer.id_dealer = '$id_dealer'
              AND (tr_scan_barcode.status = '4' OR tr_scan_barcode.status = '5') AND LEFT(tr_penerimaan_unit_dealer.tgl_penerimaan,7) < '$tgl_surat'")->row()->jum;
            if(isset($cek_qty) AND $cek_qty != 0) $isi_stok = $cek_qty;
              else $isi_stok = 0;

            $cek_sj = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
              INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin 
              WHERE tr_surat_jalan_detail.ceklist = 'ya'
              AND tr_surat_jalan.id_dealer = '$id_dealer' AND LEFT(tr_surat_jalan.tgl_surat,7) = '$tgl_surat'")->row()->jum;
            if(isset($cek_sj) AND $cek_sj != 0) $jumlah = $cek_sj;
              else $jumlah = 0;

            echo "
            <tr>                
              <td>$ge->nama_dealer</td>
              <td align='center'>$isi_stok</td>
              <td align='center'>$jumlah</td>";
              $total=0;
              for ($i=1; $i <= 31; $i++) { 
                $bln = sprintf("%'.02d",$bulan);    
                $tgl = sprintf("%'.02d",$i);    
                $tgl_surat_1 = $tahun."-".$bln."-".$tgl;
                $cek_so3 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                       
                  WHERE tr_sales_order.id_dealer = '$id_dealer'
                  AND LEFT(tr_sales_order.tgl_create_ssu,10) = '$tgl_surat_1'")->row()->jum;
                if(isset($cek_so3) AND $cek_so3 != 0) $jumlah_j = $cek_so3;
                  else $jumlah_j = 0;
                $total += $jumlah_j;
                echo "<td align='center'>$jumlah_j</td>";
              }
              $total_akhir = $isi_stok + $jumlah - $total;
              echo "<td class='bold text-center'>$total</td>
                    <td class='bold text-center'>0</td>
                    <td class='bold text-center'>$total</td>
                    <td class='bold text-center'>$total_akhir</td>
            </tr>"; 
            $g_1 += $isi_stok;                                                                                   
            $g_2 += $jumlah;                                                                                               
            $g_3 += $total;                                                                                               
            $g_4 += 0;                                                                                               
            $g_5 += $total;                                                                                               
            $g_6 += $total_akhir;                                                                                               
          ?>
          
              <tfoot>
                <tr>
                  <td class='bold text-center' bgcolor="yellow">T O T A L</td>
                  <td class='bold text-center' bgcolor="yellow"><?php echo $g_1 ?></td>
                  <td class='bold text-center' bgcolor="yellow"><?php echo $g_2 ?></td>
                  <?php 
                  $total=0;
                  for ($i=1; $i <= 31; $i++) { 
                    $bln = sprintf("%'.02d",$bulan);    
                    $tgl = sprintf("%'.02d",$i);    
                    $tgl_surat_1 = $tahun."-".$bln."-".$tgl;
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                       
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) = '$tgl_surat_1' AND tr_sales_order.id_dealer = '$id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                      else $jumlah_jual = 0;
                    $total += $jumlah_jual;
                    echo "<td class='bold text-center' bgcolor='yellow'>$jumlah_jual</td>";
                  } ?>
                  <td class='bold text-center' bgcolor="yellow"><?php echo $g_3 ?></td>
                  <td class='bold text-center' bgcolor="yellow"><?php echo $g_4 ?></td>
                  <td class='bold text-center' bgcolor="yellow"><?php echo $g_5 ?></td>
                  <td class='bold text-center' bgcolor="yellow"><?php echo $g_6 ?></td>
                  ?>
                </tr>
              </tfoot>
            </table> <br>

            <?php if($tipe!='per_kab'){ ?>
            Detail Pembiayaan
            <table class='table table-bordered' style='font-size: 9pt' width='100%'>
              <tr>                
                <td bgcolor='yellow' class='bold text-center'>Penjualan Via</td>
                <?php for ($i=1; $i <= 31; $i++) { 
                  $i = sprintf("%'.02d",$i);    
                  echo "<td bgcolor='yellow' class='bold text-center'>$i</td>";
                } ?>
                <td bgcolor='yellow' class='bold text-center'>Total</td>              
              </tr>
            <?php
            $g_total=0;
            $sql_fin = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1
                  ORDER BY ms_finance_company.id_finance_company ASC");
            foreach ($sql_fin->result() as $isi) {
              echo "<tr>
                      <td>$isi->finance_company</td>";
                      $bln = sprintf("%'.02d",$bulan);                        
                      $tgl_surat = $tahun."-".$bln;
                      $cek_ju = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                        INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
                        INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                        WHERE tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$isi->id_finance_company'
                        AND tr_sales_order.id_dealer = '$id_dealer'
                        AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat'")->row()->jum;                    
                      $g_total += $cek_ju;
                      for ($i=1; $i <= 31; $i++) { 
                        $bln = sprintf("%'.02d",$bulan);    
                        $tgl = sprintf("%'.02d",$i);    
                        $tgl_surat_1 = $tahun."-".$bln."-".$tgl;
                        $cek_so1 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
                          INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                          WHERE tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$isi->id_finance_company'
                          AND tr_sales_order.id_dealer = '$id_dealer'
                          AND LEFT(tr_sales_order.tgl_create_ssu,10) = '$tgl_surat_1'")->row()->jum;
                        if(isset($cek_so1) AND $cek_so1 != 0) $jumlah_jual = $cek_so1;
                          else $jumlah_jual = 0;
                        
                        echo "<td align='center'>$jumlah_jual</td>";
                      }
                      echo "<td align='center'>$cek_ju</td>";
                      echo "
                    </tr>";                    
            } ?>
              <tr>
                <td>Cash</td>
                <?php 
                $bln = sprintf("%'.02d",$bulan);                        
                $tgl_surat = $tahun."-".$bln;
                $cek_ju = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  WHERE tr_spk.jenis_beli = 'Cash' AND tr_sales_order.id_dealer = '$id_dealer'
                  AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat'")->row()->jum;
                $g_total += $cek_ju;
                ?>
                <?php 
                for ($i=1; $i <= 31; $i++) { 
                  $bln = sprintf("%'.02d",$bulan);    
                  $tgl = sprintf("%'.02d",$i);    
                  $tgl_surat_1 = $tahun."-".$bln."-".$tgl;
                  $cek_am = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                    INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
                    INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                    WHERE tr_spk.jenis_beli = 'Cash' AND tr_sales_order.id_dealer = '$id_dealer'
                    AND LEFT(tr_sales_order.tgl_create_ssu,10) = '$tgl_surat_1'")->row()->jum;
                  if(isset($cek_am) AND $cek_am != 0) $jumlah_jual = $cek_am;
                    else $jumlah_jual = 0;
                  
                  echo "<td align='center'>$jumlah_jual</td>";
                }
                  echo "<td align='center'>$cek_ju</td>";
                ?>
              </tr>
              <tfoot>
                <tr>
                  <td bgcolor='yellow' class='bold text-center'>Total</td>
                  <?php 
                  for ($i=1; $i <= 31; $i++) {                   
                    $bln = sprintf("%'.02d",$bulan);    
                    $tgl = sprintf("%'.02d",$i);    
                    $tgl_surat_1 = $tahun."-".$bln."-".$tgl;
                    $cek_am = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      WHERE (tr_spk.jenis_beli = 'Cash' OR tr_spk.jenis_beli = 'Kredit')
                      AND tr_sales_order.id_dealer = '$id_dealer'
                      AND LEFT(tr_sales_order.tgl_create_ssu,10) = '$tgl_surat_1'")->row()->jum;
                    if(isset($cek_am) AND $cek_am != 0) $jumlah_jual = $cek_am;
                      else $jumlah_jual = 0;
                    
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual</td>";
                  }
                  ?>
                  <td bgcolor='yellow' class='bold text-center'><?php echo $g_total ?></td>
                </tr>
              </tfoot>
          </table>
          <?php } ?>              
        <?php        
      }elseif($tipe=='per_kab' AND $id_dealer == 'all'){ ?>
        <div style="text-align: center;font-size: 13pt"><b>Laporan Penjualan Harian</b></div>        
        <div style="text-align: center; font-weight: bold;">Tahun : <?php echo $tahun ?></div>
        <hr>      
        <?php 
        $sql_kab = $this->db->query("SELECT * FROM ms_kabupaten WHERE id_provinsi = 1500 ORDER BY id_kabupaten ASC");          
        foreach ($sql_kab->result() as $isi) { 
        echo "Kabupaten: $isi->kabupaten";
        ?>
        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <tr>    
            <td bgcolor='yellow' class='bold text-center' rowspan="2">Tipe Motor</td>
            <td bgcolor='yellow' class='bold text-center' rowspan="2">Stok Awal</td>
            <td bgcolor='yellow' class='bold text-center' rowspan="2">Ttl Dist</td>
            <td bgcolor='yellow' class='bold text-center' colspan="31">Penjualan Per Tanggal</td>
            <td bgcolor='yellow' class='bold text-center' colspan="3">Penjualan Unit</td>
            <td bgcolor='yellow' class='bold text-center' rowspan="2">Stok Akhir</td>
          </tr>
          <tr>
            <?php for ($i=1; $i <= 31; $i++) {               
              $i = sprintf("%'.02d",$i);    
              echo "<td align='center'>$i</td>";
            } ?>                
            <td bgcolor='yellow' class='bold text-center'>Umum</td>                
            <td bgcolor='yellow' class='bold text-center'>Grup</td>                
            <td bgcolor='yellow' class='bold text-center'>Total</td>                
          </tr>                          
          <?php 
          $sql_stok = $this->db->query("SELECT ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,tr_scan_barcode.* FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan
            ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan = tr_scan_barcode.tipe_motor
            LEFT JOIN ms_dealer ON tr_surat_jalan.id_dealer = ms_dealer.id_dealer
            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
            WHERE ms_kabupaten.id_kabupaten = '$isi->id_kabupaten' AND (tr_scan_barcode.status = 3 OR tr_scan_barcode.status = 4 OR tr_scan_barcode.status = 5)
            GROUP BY ms_tipe_kendaraan.id_tipe_kendaraan");                        
            $g1=0;$g2=0;$g3=0;$g4=0;$g5=0;$g6=0;
          foreach ($sql_stok->result() as $amb) {
            $bln = sprintf("%'.02d",$bulan);              
            $tgl_surat = $tahun."-".$bln;          
            $cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
                LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
                LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
                WHERE tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'
                AND (tr_scan_barcode.status = '4' OR tr_scan_barcode.status = '5') AND LEFT(tr_penerimaan_unit_dealer.tgl_penerimaan,7) < '$tgl_surat'")->row()->jum;
              if(isset($cek_qty) AND $cek_qty != 0) $isi_stok = $cek_qty;
                else $isi_stok = 0;

              $cek_sj = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
                INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin 
                WHERE tr_surat_jalan_detail.ceklist = 'ya'
                AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan' AND LEFT(tr_surat_jalan.tgl_surat,7) = '$tgl_surat'")->row()->jum;
              if(isset($cek_sj) AND $cek_sj != 0) $jumlah = $cek_sj;
                else $jumlah = 0;
            echo "
              <tr>
                <td>$amb->tipe_ahm</td>
                <td align='center'>$isi_stok</td>
                <td align='center'>$jumlah</td>";
                $total=0;
                for ($i=1; $i <= 31; $i++) { 
                  $bln = sprintf("%'.02d",$bulan);    
                  $tgl = sprintf("%'.02d",$i);    
                  $tgl_surat_1 = $tahun."-".$bln."-".$tgl;
                  $cek_so3 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                    INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                       
                    INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                    WHERE tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan' AND tr_spk.id_kabupaten = '$isi->id_kabupaten'
                    AND LEFT(tr_sales_order.tgl_create_ssu,10) = '$tgl_surat_1'")->row()->jum;
                  if(isset($cek_so3) AND $cek_so3 != 0) $jumlah_j = $cek_so3;
                    else $jumlah_j = 0;
                  $total += $jumlah_j;
                  echo "<td align='center'>$jumlah_j</td>";
                }
                $total_akhir = $isi_stok + $jumlah + $total;                
                echo "
                <td align='center'>$total</td>
                <td align='center'>0</td>
                <td align='center'>$jumlah_j</td>
                <td align='center'>$total_akhir</td>
              </tr>";
              $g1 += $isi_stok;
              $g2 += $jumlah;
              $g3 += $jumlah_j;
              $g4 += 0;
              $g5 += $jumlah_j;
              $g6 += $total_akhir;
          }
          ?>
          <tfoot>
            <tr>
              <td bgcolor='yellow' class='bold text-center'>Total</td>                
              <td bgcolor='yellow' class='bold text-center'><?php echo $g1 ?></td>                
              <td bgcolor='yellow' class='bold text-center'><?php echo $g2 ?></td>                
              <?php 
              for ($i=1; $i <= 31; $i++) { 
                $bln = sprintf("%'.02d",$bulan);    
                $tgl = sprintf("%'.02d",$i);    
                $tgl_surat_1 = $tahun."-".$bln."-".$tgl;
                $cek_so3 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                    INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                       
                    INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                    WHERE tr_spk.id_kabupaten = '$isi->id_kabupaten'
                    AND LEFT(tr_sales_order.tgl_create_ssu,10) = '$tgl_surat_1'")->row()->jum;
                if(isset($cek_so3) AND $cek_so3 != 0) $jumlah_j = $cek_so3;
                  else $jumlah_j = 0;
                $total += $jumlah_j;
                echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_j</td>";
              }
              ?>
              <td bgcolor='yellow' class='bold text-center'><?php echo $g3 ?></td>                
              <td bgcolor='yellow' class='bold text-center'><?php echo $g4 ?></td>                
              <td bgcolor='yellow' class='bold text-center'><?php echo $g5 ?></td>                
              <td bgcolor='yellow' class='bold text-center'><?php echo $g6 ?></td>                
            </tr>
          </tfoot>        
        </table> <br>
        <?php 
        }       
      }elseif($tipe=='rekap' AND $id_dealer=='all'){ $tgl = bln($bulan)." ".$tahun;         ?>        
        <style>
          @media print {
          @page {
            sheet-size: 330mm 216mm;
            margin-left: 0.1cm;
            margin-right: 0.1cm;
            margin-bottom: 0.5cm;
            margin-top: 0.5cm;
          }
        }
        

        </style>
        <div style="text-align: center;font-size: 13pt"><b>Sales Stock <?php echo $tgl ?></b></div>                
        <hr>      
        
        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <tr text-rotate='90'>                
            <td bgcolor='yellow' text-rotate='0' colspan="2" valign='bottom'>Nama Dealer</td>
            <?php 
            $sql_tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active=1 ORDER BY id_tipe_kendaraan ASC");
            foreach ($sql_tipe->result() as $isi) {
              echo "<td width='1%' valign='bottom'>$isi->tipe_ahm</td>";
            }
            ?>
            <td valign='bottom'>T O T A L</td>
          </tr> 
          <?php 
          $sql_dealer = $this->db->query("SELECT * FROM ms_dealer WHERE active=1 ORDER BY kode_dealer_md ASC");
          foreach ($sql_dealer->result() as $isi) {          
            echo "
            <tr>
              <td rowspan='3' valign='bottom'>$isi->nama_dealer</td>";            
            ?>  
              <td>Sales</td>
            <?php 
            $sql_tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active=1 ORDER BY id_tipe_kendaraan ASC");
            $j1=0;
            foreach ($sql_tipe->result() as $amb) {
              $bln = sprintf("%'.02d",$bulan);              
              $tgl_surat = $tahun."-".$bln;          
              $sql_tipe = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) as jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
                          INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                          WHERE tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'
                          AND tr_sales_order.id_dealer = '$isi->id_dealer'
                          AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat'")->row()->jum;
              if(isset($sql_tipe) AND $sql_tipe != 0){
                $jum = $sql_tipe;
              }else{
                $jum = 0;
              }              
              echo "<td width='1%' valign='bottom'>$jum</td>";              
              $j1 += $jum;
            }
          echo "
            <td>$j1</td>
          </tr>";  

          echo "
            <tr>              
              <td>Dist</td>";
            
            $sql_tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active=1 ORDER BY id_tipe_kendaraan ASC");
            $j2=0;
            foreach ($sql_tipe->result() as $isi) {
              $bln = sprintf("%'.02d",$bulan);              
              $tgl_surat = $tahun."-".$bln;          
              $cek_sj = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
                  INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin 
                  WHERE tr_surat_jalan.id_dealer = '$isi->id_dealer' AND tr_surat_jalan_detail.ceklist = 'ya' 
                  AND tr_scan_barcode.tipe_motor = '$amb->tipe_motor' AND LEFT(tr_surat_jalan.tgl_surat,7) = '$tgl_surat'")->row()->jum;
                if(isset($cek_sj) AND $cek_sj != 0) $jumlah = $cek_sj;
                  else $jumlah = 0;
              echo "<td width='1%' valign='bottom'>$jumlah</td>";
              $j2 += $jumlah;
            }
          echo "
            <td>$j2</td>
          </tr>";

          echo "
            <tr>              
              <td>Stok</td>";
            
            $sql_tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active=1 ORDER BY id_tipe_kendaraan ASC");
            $j3=0;
            foreach ($sql_tipe->result() as $isi) {
                $bln = sprintf("%'.02d",$bulan);              
                $tgl_surat = $tahun."-".$bln;          
                $cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
                  LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
                  LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                  LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                  LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                  LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
                  WHERE tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan' AND tr_penerimaan_unit_dealer.id_dealer = '$isi->id_dealer' 
                  AND (tr_scan_barcode.status = '4' OR tr_scan_barcode.status = '5') AND LEFT(tr_penerimaan_unit_dealer.tgl_penerimaan,7) < '$tgl_surat'")->row()->jum;
                if(isset($cek_qty) AND $cek_qty != 0) $isi_stok = $cek_qty;
                  else $isi_stok = 0;

              echo "<td width='1%' valign='bottom'>$isi_stok</td>";
              $j3 += $isi_stok;
            }
          echo "
            <td>$isi_stok</td>
          </tr>
          <tr>
            <td></td>
          </tr>";      
          
          } ?>
        </table>
        <?php
      }elseif($tipe=='rekap' AND $id_dealer!='all'){ $tgl = bln($bulan)." ".$tahun;         ?>        
        <style>
          @media print {
          @page {
            sheet-size: 330mm 216mm;
            margin-left: 0.1cm;
            margin-right: 0.1cm;
            margin-bottom: 0.5cm;
            margin-top: 0.5cm;
          }
        }
        </style>
        <div style="text-align: center;font-size: 13pt"><b>Sales Stock <?php echo $tgl ?></b></div>                
        <hr>      
        
        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <tr text-rotate='90'>                
            <td bgcolor='yellow' text-rotate='0' colspan="2" valign='bottom' width="10px">Nama Dealer</td>
            <?php 
            $sql_tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active=1 ORDER BY id_tipe_kendaraan ASC LIMIT 0,100");
            //$sql_tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active=1 ORDER BY id_tipe_kendaraan ASC");
            foreach ($sql_tipe->result() as $isi) {
              echo "<td width='1%' valign='bottom'>$isi->tipe_ahm</td>";
            }
            ?>
            <td valign='bottom'>T O T A L</td>
          </tr> 
          <?php 
          $sql_dealer = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer='$id_dealer' ORDER BY kode_dealer_md ASC");
          foreach ($sql_dealer->result() as $isi) {          
            echo "
            <tr>
              <td rowspan='3' width='5px'>$isi->nama_dealer</td>";            
            ?>  
              <td width='5px'>Sales</td>
            <?php 
            $sql_tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active=1 ORDER BY id_tipe_kendaraan ASC LIMIT 0,100");
            //$sql_tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active=1 ORDER BY id_tipe_kendaraan ASC");
            $j1=0;
            foreach ($sql_tipe->result() as $amb) {
              $bln = sprintf("%'.02d",$bulan);              
              $tgl_surat = $tahun."-".$bln;          
              $sql_tipe = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) as jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
                          INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                          WHERE tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'
                          AND tr_sales_order.id_dealer = '$isi->id_dealer'
                          AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat'")->row()->jum;
              if(isset($sql_tipe) AND $sql_tipe != 0){
                $jum = $sql_tipe;
              }else{
                $jum = 0;
              }              
              echo "<td width='1%' valign='bottom'>$jum</td>";              
              $j1 += $jum;
            }
          echo "
            <td>$j1</td>
          </tr>";  

          echo "
            <tr>              
              <td>Dist</td>";
            $sql_tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active=1 ORDER BY id_tipe_kendaraan ASC LIMIT 0,100");
            //$sql_tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active=1 ORDER BY id_tipe_kendaraan ASC");
            $j2=0;
            foreach ($sql_tipe->result() as $isi) {
              $bln = sprintf("%'.02d",$bulan);              
              $tgl_surat = $tahun."-".$bln;          
              $cek_sj = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
                  INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin 
                  WHERE tr_surat_jalan.id_dealer = '$isi->id_dealer' AND tr_surat_jalan_detail.ceklist = 'ya' 
                  AND tr_scan_barcode.tipe_motor = '$amb->tipe_motor' AND LEFT(tr_surat_jalan.tgl_surat,7) = '$tgl_surat'")->row()->jum;
                if(isset($cek_sj) AND $cek_sj != 0) $jumlah = $cek_sj;
                  else $jumlah = 0;
              echo "<td width='1%' valign='bottom'>$jumlah</td>";
              $j2 += $jumlah;
            }
          echo "
            <td>$j2</td>
          </tr>";

          echo "
            <tr>              
              <td>Stok</td>";
            $sql_tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active=1 ORDER BY id_tipe_kendaraan ASC LIMIT 0,100");
            //$sql_tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active=1 ORDER BY id_tipe_kendaraan ASC");
            $j3=0;
            foreach ($sql_tipe->result() as $isi) {
                $bln = sprintf("%'.02d",$bulan);              
                $tgl_surat = $tahun."-".$bln;          
                $cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
                  LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
                  LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                  LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                  LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                  LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
                  WHERE tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan' AND tr_penerimaan_unit_dealer.id_dealer = '$isi->id_dealer' 
                  AND (tr_scan_barcode.status = '4' OR tr_scan_barcode.status = '5') AND LEFT(tr_penerimaan_unit_dealer.tgl_penerimaan,7) < '$tgl_surat'")->row()->jum;
                if(isset($cek_qty) AND $cek_qty != 0) $isi_stok = $cek_qty;
                  else $isi_stok = 0;

              echo "<td width='1%' valign='bottom'>$isi_stok</td>";
              $j3 += $isi_stok;
            }
          echo "
            <td>$isi_stok</td>
          </tr>
          <tr>
            <td></td>
          </tr>";      
          
          } ?>
        </table>
      <?php        
      }
    ?>
    </body>
  </html>
  <?php } ?>

  </section>
</div>


<script>
    function getReport()
    {
      var value={tipe:getRadioVal(document.getElementById("frm"),"tipe"),
                bulan:document.getElementById("bulan").value,
                tahun:document.getElementById("tahun").value,
                id_dealer:document.getElementById("id_dealer").value,
                cetak:'cetak',
                }

      if (value.tipe == '') {
        alert('Isi data dengan lengkap ..!');
        return false;
      }else{
        //alert(value.tipe);
        $('.loader').show();
        $('#btnShow').disabled;
        $("#showReport").attr("src",'<?php echo site_url("h1/laporan_transaksi_harian?") ?>tipe='+value.tipe+'&cetak='+value.cetak+'&tahun='+value.tahun+'&bulan='+value.bulan+'&id_dealer='+value.id_dealer);
        document.getElementById("showReport").onload = function(e){          
        $('.loader').hide();       
        };
      }
    }
function getRadioVal(form, name) {
  var val;  
  var radios = form.elements[name];
  for (var i=0, len=radios.length; i<len; i++) {
      if ( radios[i].checked ) { // radio checked?
          val = radios[i].value; // if so, hold its value in val
          break; // and break out of for loop
      }
  }
  return val; // return value of checked radio or undefined if none checked
}
</script>