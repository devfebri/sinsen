<?php 
function bln(){
  $bulan=$bl=$month=date("m");
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
<?php 
if(isset($_GET['id'])){
?>
<body onload="kirim_data_niguri_v()">
<?php }else{ ?>
<body onload="auto()">
<?php } ?>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H2</li>
    <li class="">KPB</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
    if($set=="insert"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h2/claim_kpb">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php                       
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    
        ?>                  
        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>  
            </button>
        </div>
        <?php
        }
            $_SESSION['pesan'] = '';                        
                
        ?>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="h2/claim_kpb/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode AHASS</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="Kode AHASS" name="tipe" id="tipe">                    
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama AHASS</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="nama_ahass"  placeholder="Warna" name="warna">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Periode Claim</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="lokasi_s" placeholder="Periode Claim" name="lokasi_suggest">                                        
                  </div>
                </div><br>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="lokasi_s" placeholder="No Mesin" name="lokasi_suggest">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Rangka</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="lokasi_s" placeholder="No Rangka" name="lokasi_suggest">                                        
                  </div>
                </div>   
                <button class="btn btn-primary btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled>Detail</button>
                <br><br><br>
               <table id="example4" class="table table-hover">
                  <thead>
                    <th>No Mesin</th>
                    <th>No Rangka</th>
                    <th>Tipe Kendaraan</th>
                    <th>No KPB</th>
                    <th>KPB Ke-</th>
                    <th>Tanggal Beli SMH</th>
                    <th>KM Service</th>
                    <th>Tanggal Sevice</th>
                    <th>Aksi</th>
                  </thead>
                  <tbody>
                    <tr>
                    <td>12345</td>
                    <td>121233333</td>
                    <td>VERZA</td>
                    <td>12345</td>
                    <td>1</td>
                    <td>2018-10-10</td>
                    <td>2000</td>
                    <td>2018-10-01</td>
                    <td>
                      <button  type="button" class="btn btn-warning btn-flat btn-xs"  data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></button>
                      <button type="button" class="btn btn-danger btn-flat btn-xs"  data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></button>
                      <button  type="button" class="btn btn-success btn-flat btn-xs"  data-toggle="tooltip" data-placement="top" title="Approve"><i class="fa fa-check"></i></button>
                    </td>
                  </tr>
                  </tbody>
               </table>                                                          
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    
    

    

    <?php
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
   <?php /* ?>       <a href="h2/claim_kpb/generate_skpb">
            <button class="btn bg-maroon btn-flat margin"></i> Generate SKPB</button>
          </a> 
          <a href="h2/claim_kpb/add">
            <button class="btn bg-blue btn-flat margin"></i> Add New</button>
          </a> 
          <a href="h2/claim_kpb/">
            <button class="btn bg-green btn-flat margin"></i> Verifikasi</button>
          </a> 
          <a href="h2/claim_kpb/">
            <button class="btn btn-warning btn-flat margin"></i> Create Tagihan Dealer</button>
          </a>           <?php */ ?>             
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php                       
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    
        ?>                  
        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>  
            </button>
        </div>
        <?php
        }
            $_SESSION['pesan'] = '';                        
                
        ?>
        <table id="example4" class="table table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>              
              <th>Kode AHASS</th>              
              <th>Nama AHASS</th>
              <th>No Mesin</th>
              <th>No Rangka</th>
              <th>Tipe Kendaraan</th>  
              <th>KPB Ke-</th>
              <th>Tanggal SSU + Batas KPB</th>
              <th>Tanggal Penjualan + Batas KPB</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_result->result() as $rs) {
            $dealer      = '';
            $kode_dealer = '';
            $no_rangka   = '';
            $tgl_ssu     = '';
            $tgl_ssu_kpb = '';
            $tgl_penjualan_kpb = '';
            $so = $this->db->query("SELECT *,LEFT(tr_sales_order.tgl_create_ssu,10) AS tgl_ssu FROM tr_sales_order 
              JOIN ms_dealer ON tr_sales_order.id_dealer=ms_dealer.id_dealer
              WHERE no_mesin='$rs->no_mesin'");
            if ($so->num_rows()>0) {
              $so          = $so->row();
              $dealer      = $so->nama_dealer;
              $kode_dealer = $so->kode_dealer_md;
              $no_rangka   = $so->no_rangka;
              $tgl_ssu     = $so->tgl_ssu;
            }
            $cek_jual=0;
            if ($rs->tgl_beli_smh!=null) {
              if ($rs->tgl_beli_smh!='') {
                $tgl_penjualan_kpb = tambah_dmy('tanggal',$rs->batas_maks_kpb,$rs->tgl_beli_smh);
                $tgl_penjualan_kpb = $tgl_penjualan_kpb['tahun'].'-'.$tgl_penjualan_kpb['bulan'].'-'.$tgl_penjualan_kpb['tanggal'];
                $cek_jual = bandingTgl(date('Y-m-d'), $tgl_penjualan_kpb);
              }
            }
            $cek_ssu = 0;
            if ($tgl_ssu!=null) {
              if ($tgl_ssu!='') {
                $tgl_ssu_kpb = tambah_dmy('tanggal',$rs->batas_maks_kpb,$tgl_ssu);
                $tgl_ssu_kpb = $tgl_ssu_kpb['tahun'].'-'.$tgl_ssu_kpb['bulan'].'-'.$tgl_ssu_kpb['tanggal'];
                $cek_ssu = bandingTgl(date('Y-m-d'), $tgl_ssu_kpb);
              }
            }

            if ($cek_jual==1 OR $cek_ssu==1) {
              echo "
              <tr>
                <td>$no</td>
                <td>$kode_dealer</td>              
                <td>$dealer</td>
                <td>$rs->no_mesin</td>
                <td>$rs->no_rangka</td>
                <td>$rs->tipe_ahm</td>  
                <td>$rs->kpb_ke</td>
                <td>$tgl_ssu_kpb</td>
                <td>$tgl_penjualan_kpb</td>"; 
              $no++;
            }
          }
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php 
    }elseif($set=="generate_skpb"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h2/claim_kpb">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php                       
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    
        ?>                  
        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>  
            </button>
        </div>
        <?php
        }
            $_SESSION['pesan'] = '';                        
                
        ?>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="h2/claim_kpb/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                  <div class="col-sm-2">
                    <input type="text" id="tanggal" required class="form-control" placeholder="Start Date" name="tipe" id="tipe">                    
                  </div>
                  <div class="col-sm-2"></div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">5 Digit Nomor Mesin</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="5_digit">
                      <option value="">--Pilih--</option>
                    </select>                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
                  <div class="col-sm-2">
                    <input type="text" class="form-control" id="tanggal1" placeholder="End Date" name="">                                        
                  </div>
                  <div class="col-sm-2"></div>               
                  <label for="inputEmail3" class="col-sm-2 control-label">Service Ke-</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="5_digit">
                      <option value="">--Pilih--</option>
                    </select>                                        
                  </div>


                </div>
                <div class="form-group">
                  <p align="center">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-2"><button class="btn btn-primary btn-flat form-control">Generate</button></div>
                  </p>
                </div>   
                <button class="btn btn-success btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled>Detail</button>
                <br><br><br>
               <table id="example4" class="table table-hover">
                  <thead>
                    <th>No Mesin</th>
                    <th>No KPB</th>
                    <th>Tanggal Beli SMH</th>
                    <th>KM Service</th>
                    <th>Tanggal Sevice</th>
                    <th>No Surat Claim</th>
                  </thead>
                  <tbody>
                     <td>12345</td>
                    <td>12345</td>
                    <td>2018-10-10</td>
                    <td>2.000</td>
                    <td>2018-10-10</td>
                    <td>12345</td>
                  </tbody>
               </table>                                                          
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-4">
                </div>
                <div class="col-sm-4">
                  <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-download"></i> Download File .SKPB</button>           
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php
    }
    ?>
  </section>
</div>

<div class="modal fade" id="nosin_modal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search No Mesin
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="example5" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>No Mesin</th>            
              <th>Tipe</th>
              <th>Warna</th>
              <th>Lokasi</th>
              <th>Status</th>
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1;                 
          $dt_nosin = $this->db->query("SELECT tr_scan_barcode.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,ms_warna.id_warna 
                  FROM tr_scan_barcode INNER JOIN ms_tipe_kendaraan ON 
                  tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna ON
                  tr_scan_barcode.warna = ms_warna.id_warna WHERE tr_scan_barcode.status = '1' ORDER BY tr_scan_barcode.no_mesin,tr_scan_barcode.tipe ASC");
          foreach ($dt_nosin->result() as $ve2) {            
            echo "
            <tr>
              <td>$no</td>
              <td>$ve2->no_mesin</td>
              <td>$ve2->tipe_motor-$ve2->tipe_ahm</td>
              <td>$ve2->id_warna-$ve2->warna</td>
              <td>$ve2->lokasi-$ve2->slot</td>
              <td>$ve2->tipe</td>";
              ?>
              <td class="center">
                <button title="Choose" data-dismiss="modal" onclick="choose_nosin('<?php echo $ve2->no_mesin; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
              </td>           
            </tr>
            <?php
            $no++;
          }
          ?>
          </tbody>
        </table>
      </div>      
    </div>
  </div>
</div>

<script>
function ambil_slot(){
  var lokasi_baru = $("#lokasi_baru").val(); 
  $.ajax({
    url : "<?php echo site_url('h2/claim_kpb/get_slot')?>",
    type:"POST",
    data:"lokasi_baru="+lokasi_baru,
    cache:false,   
    success:function(msg){            
      $("#slot").html(msg);      
    }
  })  
}
function ambil_slot_new(){
  var lokasi_s = $("#lokasi_s").val(); 
  $.ajax({
    url : "<?php echo site_url('h2/claim_kpb/get_slot_new')?>",
    type:"POST",
    data:"lokasi_s="+lokasi_s,
    cache:false,   
    success:function(msg){            
      $("#lokasi_baru").html(msg);      
    }
  })  
}
function ambil_slot_new2(){
  var lokasi_s = $("#lokasi_s").val(); 
  $.ajax({
    url : "<?php echo site_url('h2/claim_kpb/get_slot_new2')?>",
    type:"POST",
    data:"lokasi_s="+lokasi_s,
    cache:false,   
    success:function(msg){            
      $("#slot").html(msg);      
    }
  })  
}
</script>
<script type="text/javascript">
function choose_nosin(nosin){
  document.getElementById("no_mesin").value = nosin;   
  cek_nosin();
  $("#nosin_modal").modal("hide");
}
function cek_nosin(){
  var no_mesin  = document.getElementById("no_mesin").value;    
  //alert(id_po);  
  $.ajax({
      url : "<?php echo site_url('h2/claim_kpb/cek_nosin')?>",
      type:"POST",
      data:"no_mesin="+no_mesin,
      cache:false,
      success:function(msg){            
        data=msg.split("|");                            
        $("#kode_item").val(data[1]);
        $("#tipe").val(data[2]);
        $("#warna").val(data[3]);
        $("#lokasi_l").val(data[4]);
        $("#lokasi_s").val(data[5]); 
        ambil_slot_new();    
        ambil_slot_new2();           
      }
  })      
}


</script>