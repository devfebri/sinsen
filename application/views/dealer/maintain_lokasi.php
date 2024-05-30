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
    <li class="">H1</li>
    <li class="">Inventory</li>
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
          <a href="h1/maintain_lokasi">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
            <form class="form-horizontal" action="h1/maintain_lokasi/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-3">
                    <input type="text" required class="form-control" placeholder="No Mesin" readonly name="no_mesin" id="no_mesin">
                    <input type="hidden" id="tgl" value="<?php echo date("d-M-y") ?>">                                        
                  </div>
                  <div class="col-sm-1">                  
                    <button data-toggle="modal" type="button" data-target="#nosin_modal" class="btn btn-primary btn-flat btn-md pull-left"><i class="fa fa-check"></i> </button>                                
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Item</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="Kode Item" readonly name="kode_item" id="kode_item">                    
                  </div>                
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="Tipe" readonly name="tipe" id="tipe">                    
                  </div>                
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="warna"  readonly placeholder="Warna" name="warna">                                        
                  </div>
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Lokasi Lama</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly placeholder="Lokasi Lama" name="lokasi_lama" id="lokasi_l">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Lokasi Suggest</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="lokasi_s" readonly placeholder="Lokasi Suggest" name="lokasi_suggest">                                        
                  </div>
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Lokasi Baru</label>
                  <div class="col-sm-4">
                    <select class="form-control" required onchange="ambil_slot()" id="lokasi_baru" name="lokasi_baru">
                      <option value="">- choose -</option>                      
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Slot</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="slot" id="slot">
                      <option value="">- choose -</option>
                    </select>
                  </div>
                </div>                                                    
                <div class="form-group">                                 
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" required class="form-control" placeholder="Keterangan" name="ket">                                        
                  </div>
                </div>                                                                      
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
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
          <a href="h1/maintain_lokasi/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>          
          <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->                  
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
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>              
              <th>Tgl Maintain</th>              
              <th>No Mesin</th>
              <th>Tipe</th>
              <th>Warna</th>
              <th>Lokasi Lama</th>              
              <th>Lokasi Baru</th>         
              <th>Action</th>     
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_maintain_lokasi->result() as $row) {  
            $tgl      = date_create($row->created_at);
            $tgl2     = date_format($tgl,"d-m-Y");               
            $rt = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_tipe_kendaraan 
                ON tr_scan_barcode.tipe_motor=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
                ON tr_scan_barcode.warna=ms_warna.id_warna WHERE tr_scan_barcode.no_mesin='$row->no_mesin'");
            if($rt->num_rows() > 0){
              $is = $rt->row();
              $tipe = $is->id_tipe_kendaraan." - ".$is->tipe_ahm;
              $warna = $is->id_warna." - ".$is->warna;
            }else{
              $tipe = "";
              $warna = "";
            }
            
            echo "
            <tr>
              <td>$no</td>
              <td>$tgl2</td>
              <td>$row->no_mesin</td>
              <td>$tipe</td>
              <td>$warna</td>
              <td>$row->lokasi_lama</td>
              <td>$row->lokasi_baru</td>            
              <td>"; ?>
                <button name="cetak" type="button" class="btn bg-maroon btn-flat btn-sm" 
                  onclick="javascript:wincal=window.open('h1/maintain_lokasi/cetak_s?id=<?php echo $row->no_mesin; ?>',
                  'Set Bayar','width=600,height=400');">
                <i class="fa fa-print"></i></button>
              </td>            
            </tr>
          <?php
          $no++;
          }
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
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
                  tr_scan_barcode.warna = ms_warna.id_warna WHERE tr_scan_barcode.status = '1' AND tr_scan_barcode.tipe = 'RFS'
                  ORDER BY tr_scan_barcode.no_mesin,tr_scan_barcode.tipe ASC");
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
    url : "<?php echo site_url('h1/maintain_lokasi/get_slot')?>",
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
    url : "<?php echo site_url('h1/maintain_lokasi/get_slot_new')?>",
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
    url : "<?php echo site_url('h1/maintain_lokasi/get_slot_new2')?>",
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
      url : "<?php echo site_url('h1/maintain_lokasi/cek_nosin')?>",
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