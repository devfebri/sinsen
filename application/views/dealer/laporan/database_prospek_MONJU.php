<?php 
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
    <li class="">Report</li>
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
            <form class="form-horizontal" action="h1/ssu/create" id="frm" method="post" enctype="multipart/form-data">
              <div class="box-body">                                                              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Periode Prospek Awal</label>
                  <div class="col-sm-4">
                    <input type="text" id="tanggal2" name="spk_awal" class="form-control" placeholder="Periode Prospek Awal" autocomplete="off">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Periode Prospek Akhir</label>
                  <div class="col-sm-4">
                    <input type="text" id="tanggal3" name="spk_akhir" class="form-control" placeholder="Periode Prospek Akhir" autocomplete="off">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan Lahir</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="bulan_lahir" id="bulan_lahir">
                      <option value="all">All</option>
                      <option value="1">Januari</option>
                      <option value="2">Februari</option>
                      <option value="3">Maret</option>
                      <option value="4">April</option>
                      <option value="5">Mei</option>
                      <option value="6">Juni</option>
                      <option value="7">Juli</option>
                      <option value="8">Agustus</option>
                      <option value="9">September</option>
                      <option value="10">Oktober</option>
                      <option value="11">November</option>
                      <option value="12">Desember</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Salesman</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_karyawan_dealer" id="id_karyawan_dealer">
                      <option value="all">All</option>
                      <?php 
                      $id_dealer = $this->m_admin->cari_dealer();
                      $sql = $this->db->query("SELECT * FROM ms_karyawan_dealer WHERE id_dealer = '$id_dealer'");
                      foreach ($sql->result() as $isi) {
                        echo "<option value='$isi->id_karyawan_dealer'>$isi->nama_lengkap</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Prospek</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_prospek" id="id_prospek">
                      <option value="all">All</option>
                      <?php                       
                      $sql = $this->db->query("SELECT * FROM tr_prospek WHERE id_dealer = '$id_dealer'");
                      foreach ($sql->result() as $isi) {
                        echo "<option value='$isi->id_prospek'>$isi->id_prospek | $isi->nama_konsumen</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kota Prospek</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="kota_prospek" id="kota_prospek">
                      <option value="all">All</option>
                      <?php                       
                      $sql = $this->db->query("SELECT * FROM tr_prospek INNER JOIN ms_kabupaten ON tr_prospek.id_kabupaten = ms_kabupaten.id_kabupaten 
                          WHERE tr_prospek.id_dealer = '$id_dealer' GROUP BY tr_prospek.id_kabupaten");
                      foreach ($sql->result() as $isi) {
                        echo "<option value='$isi->id_kabupaten'>$isi->kabupaten</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Kelamin</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                      <option value="all">All</option>
                      <option>Pria</option>
                      <option>Wanita</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Status Customer</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="status_customer" id="status_customer">
                      <option value="all">All</option>                      
                      <option>Cold Prospect</option>
                      <option>Medium Prospect</option>
                      <option>Hot Prospect</option>
                      <option>Deal</option>
                      <option>Closing</option>
                      <option>Loss</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>
                  <div class="col-sm-2">
                    <button type="button" onclick="getReport()" name="process" value="edit" class="btn bg-maroon btn-flat"><i class="fa fa-print"></i> Preview</button>                                  
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
            sheet-size: 330mm 210mm;
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
      <?php if($tanggal2 != ''){ ?>
        <table>
          <tr>
            <td><?= kop_surat_dealer($this->m_admin->cari_dealer()); ?></td>
          </tr>
        </table>
        <div style="text-align: center;font-size: 13pt"><b>Laporan Database Prospek</b></div>        
        <!-- <div style="text-align: center; font-weight: bold;">Bulan : <?php echo $tgl ?></div> -->
        <hr>      
        <table border="0" width="100%">
          <tr>
            <td>Tgl Tercatat</td>
            <td>: <?php echo tgl_indo($tanggal2,' ') ?> s/d <?php echo tgl_indo($tanggal3,' ') ?></td>
            <td></td>
            <td>Kota Prospek</td>
            <td>: 
              <?php 
              if($kota_prospek != 'all'){
                $kota_prospek = $this->m_admin->getByID("ms_kabupaten","id_kabupaten",$kota_prospek)->row()->kabupaten; 
                echo $kota_prospek;
              }else{
                echo "all";
              }
              ?>      
            </td>
          </tr>
          <tr>
            <td>ID Prospek</td>
            <td>: <?php echo $id_prospek ?></td>
            <td></td>
            <td>Sex</td>
            <td>: <?php echo $jenis_kelamin ?></td>
          </tr>
          <tr>
            <td>ID Salesman</td>
            <td>: 
              <?php 
              if($id_karyawan_dealer != 'all'){
                $nama_lengkap = $this->m_admin->getByID("ms_karyawan_dealer","id_karyawan_dealer",$id_karyawan_dealer)->row()->nama_lengkap; 
                echo $nama_lengkap;
              }else{
                echo "all";
              }
              ?>              
            </td>
            <td></td>
            <td>Status Customer</td>
            <td>: <?php echo $status_customer ?></td>
          </tr>
          <tr>
            <td>Bulan Lahir</td>
            <td>: <?php echo bln($bulan_lahir) ?></td>            
          </tr>
        </table>
        <br>
        <table class='table table-bordered' style='font-size: 10pt' width='100%'>
          <tr>                          
            <td bgcolor='yellow' class='bold text-center'>Tgl</td>
            <td bgcolor='yellow' class='bold text-center'>Sales</td>                
            <td bgcolor='yellow' class='bold text-center'>Nama Prospek</td>                
            <td bgcolor='yellow' class='bold text-center'>Alamat</td>                
            <td bgcolor='yellow' class='bold text-center'>Kota Prospek</td>                
            <td bgcolor='yellow' class='bold text-center'>No.Telp</td>                
            <td bgcolor='yellow' class='bold text-center'>Tgl.Lahir</td>                
            <td bgcolor='yellow' class='bold text-center'>Sex</td>                            
          </tr>
          <tr>
          <?php           
          $query = "";
          if($bulan_lahir != 'all') $bulan_lahir = sprintf("%'.02d",$bulan_lahir);    
          if($bulan_lahir != 'all' AND $id_karyawan_dealer != 'all' AND $id_prospek != 'all' AND $kota_prospek != 'all' AND $jenis_kelamin != 'all' AND $status_customer != 'all'){
            $query = "AND MID(tr_prospek.tgl_lahir,6,2) = '$bulan_lahir' AND ms_karyawan_dealer.id_karyawan_dealer = '$id_karyawan_dealer' AND tr_prospek.id_prospek = '$id_prospek' 
            AND tr_prospek.id_kabupaten = '$kota_prospek' AND tr_prospek.jenis_kelamin = '$jenis_kelamin' AND tr_prospek.status_prospek = '$status_customer'";
          }elseif($id_karyawan_dealer != 'all' AND $id_prospek != 'all' AND $kota_prospek != 'all' AND $jenis_kelamin != 'all' AND $status_customer != 'all'){
            $query = "AND ms_karyawan_dealer.id_karyawan_dealer = '$id_karyawan_dealer' AND tr_prospek.id_prospek = '$id_prospek' 
            AND tr_prospek.id_kabupaten = '$kota_prospek' AND tr_prospek.jenis_kelamin = '$jenis_kelamin' AND tr_prospek.status_prospek = '$status_customer'";
          }elseif($bulan_lahir != 'all' AND $id_prospek != 'all' AND $kota_prospek != 'all' AND $jenis_kelamin != 'all' AND $status_customer != 'all'){
            $query = "AND MID(tr_prospek.tgl_lahir,6,2) = '$bulan_lahir' AND tr_prospek.id_prospek = '$id_prospek' 
            AND tr_prospek.id_kabupaten = '$kota_prospek' AND tr_prospek.jenis_kelamin = '$jenis_kelamin' AND tr_prospek.status_prospek = '$status_customer'";
          }elseif($bulan_lahir != 'all' AND $id_karyawan_dealer != 'all' AND $kota_prospek != 'all' AND $jenis_kelamin != 'all' AND $status_customer != 'all'){
            $query = "AND MID(tr_prospek.tgl_lahir,6,2) = '$bulan_lahir' AND ms_karyawan_dealer.id_karyawan_dealer = '$id_karyawan_dealer' 
            AND tr_prospek.id_kabupaten = '$kota_prospek' AND tr_prospek.jenis_kelamin = '$jenis_kelamin' AND tr_prospek.status_prospek = '$status_customer'";
          }elseif($bulan_lahir != 'all' AND $id_karyawan_dealer != 'all' AND $id_prospek != 'all' AND $jenis_kelamin != 'all' AND $status_customer != 'all'){
            $query = "AND MID(tr_prospek.tgl_lahir,6,2) = '$bulan_lahir' AND ms_karyawan_dealer.id_karyawan_dealer = '$id_karyawan_dealer' AND tr_prospek.id_prospek = '$id_prospek' 
            AND tr_prospek.jenis_kelamin = '$jenis_kelamin' AND tr_prospek.status_prospek = '$status_customer'";
          }elseif($bulan_lahir != 'all' AND $id_karyawan_dealer != 'all' AND $id_prospek != 'all' AND $kota_prospek != 'all' AND $status_customer != 'all'){
            $query = "AND MID(tr_prospek.tgl_lahir,6,2) = '$bulan_lahir' AND ms_karyawan_dealer.id_karyawan_dealer = '$id_karyawan_dealer' AND tr_prospek.id_prospek = '$id_prospek' 
            AND tr_prospek.id_kabupaten = '$kota_prospek' AND tr_prospek.status_prospek = '$status_customer'";
          }elseif($bulan_lahir != 'all' AND $id_karyawan_dealer != 'all' AND $id_prospek != 'all' AND $kota_prospek != 'all' AND $jenis_kelamin != 'all'){
            $query = "AND MID(tr_prospek.tgl_lahir,6,2) = '$bulan_lahir' AND ms_karyawan_dealer.id_karyawan_dealer = '$id_karyawan_dealer' AND tr_prospek.id_prospek = '$id_prospek' 
            AND tr_prospek.id_kabupaten = '$kota_prospek' AND tr_prospek.jenis_kelamin = '$jenis_kelamin'";
          }elseif($id_prospek != 'all' AND $kota_prospek != 'all' AND $jenis_kelamin != 'all' AND $status_customer != 'all'){
            $query = "AND tr_prospek.id_prospek = '$id_prospek' 
            AND tr_prospek.id_kabupaten = '$kota_prospek' AND tr_prospek.jenis_kelamin = '$jenis_kelamin' AND tr_prospek.status_prospek = '$status_customer'";
          }elseif($id_karyawan_dealer != 'all' AND $kota_prospek != 'all' AND $jenis_kelamin != 'all' AND $status_customer != 'all'){
            $query = "AND ms_karyawan_dealer.id_karyawan_dealer = '$id_karyawan_dealer'
            AND tr_prospek.id_kabupaten = '$kota_prospek' AND tr_prospek.jenis_kelamin = '$jenis_kelamin' AND tr_prospek.status_prospek = '$status_customer'";
          }elseif($id_karyawan_dealer != 'all' AND $id_prospek != 'all' AND $jenis_kelamin != 'all' AND $status_customer != 'all'){
            $query = "AND ms_karyawan_dealer.id_karyawan_dealer = '$id_karyawan_dealer' AND tr_prospek.id_prospek = '$id_prospek' 
            AND tr_prospek.jenis_kelamin = '$jenis_kelamin' AND tr_prospek.status_prospek = '$status_customer'";
          }elseif($id_karyawan_dealer != 'all' AND $id_prospek != 'all' AND $kota_prospek != 'all' AND $status_customer != 'all'){
            $query = "AND ms_karyawan_dealer.id_karyawan_dealer = '$id_karyawan_dealer' AND tr_prospek.id_prospek = '$id_prospek' 
            AND tr_prospek.id_kabupaten = '$kota_prospek' AND tr_prospek.status_prospek = '$status_customer'";
          }elseif($id_karyawan_dealer != 'all' AND $id_prospek != 'all' AND $kota_prospek != 'all' AND $jenis_kelamin != 'all'){
            $query = "AND ms_karyawan_dealer.id_karyawan_dealer = '$id_karyawan_dealer' AND tr_prospek.id_prospek = '$id_prospek' 
            AND tr_prospek.id_kabupaten = '$kota_prospek' AND tr_prospek.jenis_kelamin = '$jenis_kelamin'";
          }elseif($kota_prospek != 'all' AND $jenis_kelamin != 'all' AND $status_customer != 'all'){
            $query = "AND tr_prospek.id_kabupaten = '$kota_prospek' AND tr_prospek.jenis_kelamin = '$jenis_kelamin' AND tr_prospek.status_prospek = '$status_customer'";
          }elseif($id_prospek != 'all' AND $jenis_kelamin != 'all' AND $status_customer != 'all'){
            $query = "AND tr_prospek.id_prospek = '$id_prospek' 
            AND tr_prospek.jenis_kelamin = '$jenis_kelamin' AND tr_prospek.status_prospek = '$status_customer'";
          }elseif($id_prospek != 'all' AND $kota_prospek != 'all' AND $status_customer != 'all'){
            $query = "AND tr_prospek.id_prospek = '$id_prospek' 
            AND tr_prospek.id_kabupaten = '$kota_prospek' AND tr_prospek.status_prospek = '$status_customer'";
          }elseif($id_prospek != 'all' AND $kota_prospek != 'all' AND $jenis_kelamin != 'all'){
            $query = "AND tr_prospek.id_prospek = '$id_prospek' 
            AND tr_prospek.id_kabupaten = '$kota_prospek' AND tr_prospek.jenis_kelamin = '$jenis_kelamin'";
          }elseif($id_karyawan_dealer != 'all' AND $jenis_kelamin != 'all' AND $status_customer != 'all'){
            $query = "AND ms_karyawan_dealer.id_karyawan_dealer = '$id_karyawan_dealer' AND tr_prospek.jenis_kelamin = '$jenis_kelamin' AND tr_prospek.status_prospek = '$status_customer'";
          }elseif($id_karyawan_dealer != 'all' AND $id_prospek != 'all' AND $kota_prospek != 'all'){
            $query = "AND ms_karyawan_dealer.id_karyawan_dealer = '$id_karyawan_dealer' AND tr_prospek.id_prospek = '$id_prospek' 
            AND tr_prospek.id_kabupaten = '$kota_prospek'";
          }elseif($bulan_lahir != 'all' AND $id_karyawan_dealer != 'all'){
            $query = "AND MID(tr_prospek.tgl_lahir,6,2) = '$bulan_lahir' AND ms_karyawan_dealer.id_karyawan_dealer = '$id_karyawan_dealer'";
          }elseif($bulan_lahir != 'all' AND $id_prospek != 'all'){
            $query = "AND MID(tr_prospek.tgl_lahir,6,2) = '$bulan_lahir' AND tr_prospek.id_prospek = '$id_prospek'";
          }elseif($bulan_lahir != 'all' AND $kota_prospek != 'all'){
            $query = "AND MID(tr_prospek.tgl_lahir,6,2) = '$bulan_lahir' AND tr_prospek.id_kabupaten = '$kota_prospek'";
          }elseif($bulan_lahir != 'all' AND $jenis_kelamin != 'all'){
            $query = "AND MID(tr_prospek.tgl_lahir,6,2) = '$bulan_lahir' AND tr_prospek.jenis_kelamin = '$jenis_kelamin'";
          }elseif($bulan_lahir != 'all' AND $status_customer != 'all'){
            $query = "AND MID(tr_prospek.tgl_lahir,6,2) = '$bulan_lahir' AND tr_prospek.status_prospek = '$status_customer'";
          }elseif($bulan_lahir != 'all'){
            $query = "AND MID(tr_prospek.tgl_lahir,6,2) = '$bulan_lahir'";
          }elseif($id_karyawan_dealer != 'all'){
            $query = "AND ms_karyawan_dealer.id_karyawan_dealer = '$id_karyawan_dealer'";
          }elseif($id_prospek != 'all'){
            $query = "AND tr_prospek.id_prospek = '$id_prospek'";
          }elseif($kota_prospek != 'all'){
            $query = "AND tr_prospek.id_kabupaten = '$kota_prospek'";
          }elseif($jenis_kelamin != 'all'){
            $query = "AND tr_prospek.jenis_kelamin = '$jenis_kelamin'";
          }elseif($status_customer != 'all'){
            $query = "AND tr_prospek.status_prospek = '$status_customer'";
          }



          $sql = $this->db->query("SELECT tr_prospek.*,ms_karyawan_dealer.nama_lengkap,ms_kabupaten.kabupaten FROM tr_prospek LEFT JOIN ms_kabupaten ON tr_prospek.id_kabupaten = ms_kabupaten.id_kabupaten
              LEFT JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer              
              WHERE tr_prospek.id_dealer = '$id_dealer' AND LEFT(tr_prospek.created_at,10) BETWEEN '$tanggal2' AND '$tanggal3' $query");
          foreach ($sql->result() as $row) {
                        
            echo "
              <tr>                
                <td>".tgl_indo(substr($row->created_at,0,10),' ')."</td>
                <td>$row->nama_lengkap</td>
                <td>$row->nama_konsumen</td>
                <td>$row->alamat</td>
                <td>$row->kabupaten</td>
                <td>$row->no_telp</td>
                <td>".tgl_indo($row->tgl_lahir,' ')."</td>                
                <td>$row->jenis_kelamin</td>                
              </tr>
            ";
            $no++;
          }
          ?>
          </tr>
        </table> <br>
      <?php }else{ ?>
        <p>Tanggal SPK Harus ditentukan dulu.</p>
      <?php } ?>                
    </body>
  </html>
  <?php } ?>
  </section>
</div>


<script>
function getReport(){
  var value={tanggal2:document.getElementById("tanggal2").value,
            tanggal3:document.getElementById("tanggal3").value,
            bulan_lahir:document.getElementById("bulan_lahir").value,
            id_karyawan_dealer:document.getElementById("id_karyawan_dealer").value,
            id_prospek:document.getElementById("id_prospek").value,
            kota_prospek:document.getElementById("kota_prospek").value,
            jenis_kelamin:document.getElementById("jenis_kelamin").value,
            status_customer:document.getElementById("status_customer").value,
            cetak:'cetak',
            //tipe:getRadioVal(document.getElementById("frm"),"tipe"),
            }

  if (value.tanggal2 == '' && value.tanggal3 == '') {
    alert('Isi data dengan lengkap ..!');
    return false;
  }else{
    //alert(value.tipe);
    $('.loader').show();
    $('#btnShow').disabled;
    $("#showReport").attr("src",'<?php echo site_url("dealer/database_prospek?") ?>cetak='+value.cetak+'&tanggal2='+value.tanggal2+'&tanggal3='+value.tanggal3+'&bulan_lahir='+value.bulan_lahir+'&id_karyawan_dealer='+value.id_karyawan_dealer+'&id_prospek='+value.id_prospek+'&kota_prospek='+value.kota_prospek+'&jenis_kelamin='+value.jenis_kelamin+'&status_customer='+value.status_customer);
    document.getElementById("showReport").onload = function(e){          
    $('.loader').hide();       
    };
  }
}
</script>