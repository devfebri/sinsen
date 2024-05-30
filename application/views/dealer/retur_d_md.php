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
<?php if(isset($_GET['id'])){ ?>
<body onload="tampilkan()">
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
    <li class="">Retur Unit</li>
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
          <a href="dealer/retur_d_md">
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
            <form class="form-horizontal" action="dealer/retur_d_md/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Retur Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control"  placeholder="No Retur Dealer" name="no_retur_d" id="no_retur_d" readonly>
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Retur</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control"  placeholder="Tgl Retur" name="tgl_retur" id="tanggal" value="<?php echo date("Y-m-d") ?>">
                  </div>                  
                </div>                
                <div class="form-group">
                  <span id="tampil_data"></span>
                </div>
              </div>
          </div>
        </div>
      </div>
      <div class="box-footer">
        <div class="col-sm-2">
        </div>
        <div class="col-sm-10">
          <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
          <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
        </div>
      </div><!-- /.box-footer -->
    </div><!-- /.box -->
            </form>


    <?php 
    }elseif($set=='detail'){
      $row = $dt_retur->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/retur_d_md">
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
            <form class="form-horizontal" action="dealer/retur_d_md/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Retur Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" value="<?php echo $row->no_retur_dealer ?>" placeholder="No Retur Dealer" name="no_retur_d" id="no_retur_d" readonly>
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Retur</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control"  placeholder="Tgl Retur" name="tgl_retur" id="tanggal" value="<?php echo $row->tgl_retur ?>">
                  </div>                  
                </div>                
                <div class="form-group">
                  <button class="btn btn-primary btn-block btn-flat" disabled>Detail Unit</button>
                  <table id="" class="table table-bordered table-hover">
                    <thead>
                      <tr>                    
                        <th width="15%">No Mesin</th>              
                        <th width="15%">No Rangka</th>              
                        <th width="10%">Kode Item</th>              
                        <th width="15%">Tipe</th>              
                        <th width="10%">Warna</th>
                        <th width="10%">Tahun Produksi</th>                            
                        <th width="10%">Tgl Penerimaan</th>
                        <th width="15%">Keterangan</th>              
                      </tr>
                    </thead>
                  
                    <?php   
                    
                    $dt_data = $this->db->query("SELECT tr_scan_barcode.no_rangka,tr_scan_barcode.no_mesin,tr_scan_barcode.id_item,tr_fkb.tahun_produksi, ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_retur_dealer_detail.* FROM tr_retur_dealer_detail
                          LEFT JOIN tr_scan_barcode ON tr_retur_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                          LEFT JOIN tr_fkb ON tr_scan_barcode.no_mesin = tr_fkb.no_mesin_spasi
                          INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                          INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                          WHERE tr_retur_dealer_detail.no_retur_dealer = '$row->no_retur_dealer'");
                    foreach($dt_data->result() as $row) {               
                      $tgl = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
                            WHERE tr_penerimaan_unit_dealer_detail.no_mesin = '$row->no_mesin'")->row()->tgl_penerimaan;
                      echo "   
                      <tr>                    
                        <td width='15%'>$row->no_mesin</td>
                        <td width='15%'>$row->no_rangka</td>      
                        <td width='10%'>$row->id_item</td>            
                        <td width='15%'>$row->tipe_ahm</td>            
                        <td width='10%'>$row->warna</td>            
                        <td width='10%'>$row->tahun_produksi</td>            
                        <td width='10%'>$tgl</td>            
                        <td width='15%'>$row->keterangan</td>
                      </tr>";                    
                      }
                    ?>  
                  </table>
                </div>
              </div>
          </div>
        </div>
      </div>
      
    </div><!-- /.box -->
            </form>

    <?php
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/retur_d_md/add">
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
              <th>No Retur Dealer</th>              
              <th>Tgl Retur</th>              
              <th>Qty Retur</th>                            
              <th>Status</th>
              <th>Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_retur->result() as $row) {
            $print = $this->m_admin->set_tombol($id_menu,$group,'print');      
            $s = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_retur_dealer_detail WHERE no_retur_dealer = '$row->no_retur_dealer'")->row();          
            echo "
            <tr>
              <td>$no</td>
              <td>$row->no_retur_dealer</td>              
              <td>$row->tgl_retur</td>
              <td>$s->jum unit</td>            
              <td>$row->status_retur_d</td>                                                                      
              <td>                                                
                <a href='dealer/retur_d_md/detail?id=$row->no_retur_dealer'>
                  <button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-eye'></i> Detail</button>
                </a>
                <a href='dealer/retur_d_md/cetak?id=$row->no_retur_dealer'>
                  <button $print class='btn btn-flat btn-xs btn-primary'><i class='fa fa-print'></i> Cetak Memo</button>
                </a>
              </td>
            </tr>
            ";
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
<div class="modal fade" id="Nosinmodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Cari No Mesin
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="10%"></th>              
              <th>No Mesin</th>
              <th>No Rangka</th>                                    
              <th>Tipe Motor</th>                                               
              <th>Warna</th>              
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1; 
          $id_dealer = $this->m_admin->cari_dealer();
          // $dt_nosin = $this->db->query("SELECT tr_scan_barcode.no_mesin,tr_scan_barcode.no_rangka,ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,ms_warna.id_warna,ms_warna.warna,tr_retur_konsumen.* FROM tr_sales_order 
          //   INNER JOIN tr_retur_konsumen ON tr_sales_order.no_mesin = tr_retur_konsumen.no_mesin
          //   INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
          //   INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan             
          //   INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna WHERE tr_retur_konsumen.status_retur_k = 'approved'
          //   AND tr_retur_konsumen.id_dealer = '$id_dealer' ORDER BY tr_scan_barcode.no_mesin ASC");
          $dt_nosin = $this->db->query("SELECT tr_scan_barcode.no_mesin,tr_scan_barcode.no_rangka,ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,ms_warna.id_warna,ms_warna.warna FROM tr_penerimaan_unit_dealer_detail
            INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
            INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
            INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan             
            INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna WHERE tr_penerimaan_unit_dealer.status = 'close'
            AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' ORDER BY tr_scan_barcode.no_mesin ASC");
          foreach ($dt_nosin->result() as $ve2) {
            echo "
            <tr>"; ?>
              <td class="center">
                <button title="Choose" data-dismiss="modal" onclick="chooseitem('<?php echo $ve2->no_mesin; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
              </td>
              <?php echo "
              <td>$ve2->no_mesin</td>
              <td>$ve2->no_rangka</td>            
              <td>$ve2->id_tipe_kendaraan | $ve2->tipe_ahm</td>
              <td>$ve2->id_warna | $ve2->warna</td>";
              ?>                         
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


<script type="text/javascript">
function auto(){
  var tgl = 1;
  $.ajax({
      url : "<?php echo site_url('dealer/retur_d_md/cari_id')?>",
      type:"POST",
      data:"tgl="+tgl,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#no_retur_d").val(data[0]);  
        tampilkan();      
      }        
  })
}
function tampilkan(){    
  $("#tampil_data").show();
  var no_retur_d  = document.getElementById("no_retur_d").value;     
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "no_retur_d="+no_retur_d;
     xhr.open("POST", "dealer/retur_d_md/t_data", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_data").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    }   
}
function generate(){
  var no_mesin = $("#no_mesin").val();
  $.ajax({
      url : "<?php echo site_url('dealer/retur_d_md/cari_nosin')?>",
      type:"POST",
      data:"no_mesin="+no_mesin,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        if(data[0]=='ok'){
          $("#no_rangka").val(data[1]);        
          $("#id_item").val(data[2]);        
          $("#id_tipe_kendaraan").val(data[3]);        
          $("#tipe_ahm").val(data[4]);                  
          $("#warna").val(data[5]);        
          $("#tahun_produksi").val(data[6]);        
          $("#tgl_terima").val(data[7]);                  
        }else{
          alert(data[0]);
        }
      }        
  })
}
function chooseitem(no_mesin){
  document.getElementById("no_mesin").value = no_mesin; 
  cek_nosin();
  $("#Nosinmodal").modal("hide");
}
function cek_nosin(){
  var no_mesin = $("#no_mesin").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/retur_d_md/cek_nosin')?>",
      type:"POST",
      data:"no_mesin="+no_mesin,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");
          if(data[0]=="ok"){          
            $("#no_rangka").val(data[1]);                                        
            $("#tipe").val(data[2]);                                        
            $("#warna").val(data[3]);                                        
            $("#tahun").val(data[4]);                                        
            $("#tgl_terima").val(data[5]);                                                    
            $("#id_item").val(data[6]);                                                    
            $("#no_retur_konsumen").val(data[7]);                                                    
          }else{
            alert(data[0]);
          }
      } 
  })
}
function simpan_data(){  
  var no_retur_d        = document.getElementById("no_retur_d").value;    
  var no_mesin          = document.getElementById("no_mesin").value;    
  var keterangan        = document.getElementById("keterangan").value;    
  
  if (no_retur_d == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('dealer/retur_d_md/save_data')?>",
          type:"POST",
          data:"no_retur_d="+no_retur_d+"&keterangan="+keterangan+"&no_mesin="+no_mesin,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="nihil"){
                  tampilkan();
                  kosong();                
              }else{
                  alert("Gagal Simpan, No Mesin ini sudah dimasukkan");
                  kosong();                  
              }                
          }
      })    
  }
}
function kosong(args){
  $("#no_rangka").val("");
  $("#no_mesin").val("");     
  $("#tipe").val("");     
  $("#warna").val("");     
  $("#id_item").val("");     
  $("#keterangan").val("");     
  $("#tahun").val("");       
  $("#tgl_terima").val("");       
}
function hapus_data(a,b){ 
    var id_retur_dealer_detail  = a;           
    $.ajax({
        url : "<?php echo site_url('dealer/retur_d_md/delete_data')?>",
        type:"POST",
        data:"id_retur_dealer_detail="+id_retur_dealer_detail,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              tampilkan();
            }
        }
    })
}
</script>