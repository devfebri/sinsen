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
<base href="<?php echo base_url(); ?>" />
<body onload="kirim_data_pl()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
  
    <li class="">KSU</li>
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
          <a href="dealer/pemenuhan_hutang_ksu">
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
            <form class="form-horizontal" autocomplete="off" action="dealer/pemenuhan_hutang_ksu/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No SO</label>
                  <div class="col-sm-4">
                    <input id="id_sales_order" readonly type="text" data-toggle="modal" data-target="#Somodal" name="id_sales_order" class="form-control isi" placeholder="ID Sales Order">
                  </div>


                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl SO</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_so" placeholder="Tgl SO" id="tgl_so" readonly class="form-control">
                  </div>                                
                </div>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_so" placeholder="Nama Konsumen" id="nama_konsumen" readonly class="form-control">
                  </div>                                
                  <label for="inputEmail3" class="col-sm-2 control-label">No Invoice</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_invoice" placeholder="No Invoice" id="no_invoice" readonly class="form-control">
                  </div>                                
                </div>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="No Mesin" id="no_mesin" readonly class="form-control">
                  </div>                                
                  <label for="inputEmail3" class="col-sm-2 control-label">No Rangka</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_rangka" placeholder="No Rangka" id="no_rangka" readonly class="form-control">
                  </div>                                
                </div>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">
                    <input type="text" name="tipe" placeholder="Tipe" id="tipe" readonly class="form-control">
                  </div>                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" name="warna" placeholder="Warna" id="warna" readonly class="form-control">
                  </div>                                
                </div>                

                <div class="form-group">                  
                  <div id="tampil_data"></div>
                </div>

                
                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>                  
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
          <a href="dealer/pemenuhan_hutang_ksu/add">            
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>          
          <a href="dealer/pemenuhan_hutang_ksu/list_hutang">            
            <button class="btn btn-success btn-flat margin"><i class="fa fa-list"></i> List Hutang</button>
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>                          
              <th>No Surat Pengantar</th>              
              <th>Tgl Cetak</th>            
              <th>Nama Konsumen</th>
              <th>No SO</th>
              <th>No Invoice</th>
              <th width="5%">Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_hutang->result() as $row) {                                         

          echo "          
            <tr>
              <td>$no</td>
              <td>$row->no_surat_pengantar</td>
              <td>$row->tgl_cetak</td>                                         
              <td>$row->nama_konsumen</td>
              <td>$row->id_sales_order</td>                            
              <td>$row->no_invoice</td>                            
              <td>                
                <a href='dealer/pemenuhan_hutang_ksu/cetak?id=$row->no_surat_pengantar' class='btn btn-primary btn-flat btn-xs'>Cetak</a>                                                                
              </td>";                                      
          $no++;
          }
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php 
    }elseif($set=='list_hutang'){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/pemenuhan_hutang_ksu">            
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>                            
              <th>KSU</th>              
              <th>Nama Konsumen</th>            
              <th>Alamat</th>
              <th>Tipe</th>
              <th>Warna</th>
              <th>No Rangka</th>
              <th>No Mesin</th>              
            </tr>
          </thead>
          <tbody>            
          <?php           
          foreach($dt_hutang->result() as $isi) {                                         
            $cek_isi = $this->db->query("SELECT DISTINCT(ms_koneksi_ksu_detail.id_koneksi_ksu),ms_koneksi_ksu_detail.id_koneksi_ksu,ms_koneksi_ksu_detail.id_ksu FROM ms_koneksi_ksu 
                LEFT JOIN ms_koneksi_ksu_detail ON ms_koneksi_ksu.id_koneksi_ksu = ms_koneksi_ksu_detail.id_koneksi_ksu
                LEFT JOIN tr_scan_barcode ON ms_koneksi_ksu.id_tipe_kendaraan = tr_scan_barcode.tipe_motor
                LEFT JOIN tr_sales_order ON tr_scan_barcode.no_mesin = tr_sales_order.no_mesin 
                WHERE tr_sales_order.id_sales_order = '$isi->id_sales_order'");                          
            $cek_ada = $this->db->query("SELECT * FROM tr_sales_order_ksu WHERE id_sales_order = '$isi->id_sales_order'");

            if($cek_isi->num_rows() > $cek_ada->num_rows()){
              foreach ($cek_isi->result() as $row) {           
                $t = $this->m_admin->getByID("ms_ksu","id_ksu",$row->id_ksu)->row();
                
                $row_isi = $this->db->query("SELECT DISTINCT(tr_sales_order.no_mesin),tr_spk.nama_konsumen,tr_spk.alamat,tr_spk.id_warna,tr_sales_order.no_rangka,
                  tr_spk.id_tipe_kendaraan,tr_spk.id_warna
                  FROM tr_sales_order_ksu  
                  LEFT JOIN tr_sales_order ON tr_sales_order_ksu.id_sales_order = tr_sales_order.id_sales_order                 
                  LEFT JOIN ms_ksu ON tr_sales_order_ksu.id_ksu = ms_ksu.id_ksu
                  LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk WHERE 
                  tr_sales_order.id_sales_order = '$isi->id_sales_order'");
                if($row_isi->num_rows() > 0){
                  $row = $row_isi->row();
                  $nama_konsumen =  $row->nama_konsumen;
                  $alamat =  $row->alamat;
                  $id_tipe_kendaraan = $row->id_tipe_kendaraan;
                  $id_warna = $row->id_warna;
                  $no_rangka = $row->no_rangka;
                  $no_mesin = $row->no_mesin;
                }else{
                  $nama_konsumen = "";
                  $alamat = "";
                  $id_tipe_kendaraan = "";
                  $id_warna = "";
                  $no_rangka = "";
                  $no_mesin = "";
                }                
                echo "          
                <tr>                
                  <td>$t->ksu</td>
                  <td>$nama_konsumen</td>
                  <td>$alamat</td>
                  <td>$id_tipe_kendaraan</td>
                  <td>$id_warna</td>
                  <td>$no_mesin</td>
                  <td>$no_rangka</td>
                </tr>
                ";                                                      
              }
            }
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
<div class="modal fade" id="Somodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search Sales Order
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>No SO</th>            
              <th>Tgl SO</th>            
              <th>Nama Konsumen</th>
              <th>Tgl Invoice</th>
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1; 
          $id_dealer = $this->m_admin->cari_dealer();
          $dt_so = $this->db->query("SELECT * FROM tr_sales_order LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk WHERE tr_spk.id_dealer = '$id_dealer'");
          foreach ($dt_so->result() as $row) {            
            echo "
            <tr>
              <td>$no</td>
              <td>$row->id_sales_order</td>
              <td>$row->tgl_cetak_so</td>
              <td>$row->nama_konsumen</td>
              <td>$row->tgl_cetak_invoice2</td>";
              ?>
              <td class="center">
                <button title="Choose" data-dismiss="modal" onclick="chooseitem('<?php echo $row->id_sales_order; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
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
<script type="text/javascript">
function generate(){    
  $("#tampil_data").show();  
  var id_sales_order  = document.getElementById("id_sales_order").value;     
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_sales_order="+id_sales_order;
     xhr.open("POST", "dealer/pemenuhan_hutang_ksu/t_data", true); 
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
function chooseitem(id_sales_order){
  document.getElementById("id_sales_order").value = id_sales_order;   
  cek_item();
  $("#Somodal").modal("hide");
}
function cek_item(){
  var id_sales_order  = document.getElementById("id_sales_order").value;        
  $.ajax({
      url: "<?php echo site_url('dealer/pemenuhan_hutang_ksu/cek_item')?>",
      type:"POST",
      data:"id_sales_order="+id_sales_order,
      cache:false,
      success:function(msg){                
          data=msg.split("|");
          if(data[0]=="ok"){          
            generate();
            $("#tgl_so").val(data[1]);                
            $("#nama_konsumen").val(data[2]);                
            $("#no_invoice").val(data[3]);
            $("#no_mesin").val(data[4]);                        
            $("#no_rangka").val(data[5]);                        
            $("#tipe").val(data[6]);
            $("#warna").val(data[7]);            
          }else{
            alert(data[0]);
          }
      } 
  })      
}
</script>