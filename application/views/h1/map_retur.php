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
    <li class="">H1</li>
    <li class="">Faktur STNK</li>
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
          <a href="h1/map_retur">
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
            <form class="form-horizontal" autocomplete="off" action="h1/map_retur/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Retur</label>
                  <div class="col-sm-3">
                    <input type="text" name="tgl_retur" placeholder="Tgl Retur" id="tanggal" class="form-control">
                  </div>                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="id_dealer" name="id_dealer">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_dealer->result() as $isi) {
                        echo "<option value='$isi->id_dealer'>$isi->kode_dealer_md | $isi->nama_dealer</option>";
                      }
                      ?>
                    </select>
                  </div>              
                  <div class="col-sm-1">
                    <button type='button' onclick="generate()" class="btn btn-flat btn-primary btn-sm">Generate</button> 
                  </div>
                </div>                
                <div>
                  <span id="tampil_data"></span>
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
    }elseif($set=='terima'){
      $row = $dt_retur->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/map_retur">
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
            <form class="form-horizontal" action="h1/map_retur/save_terima" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Retur</label>
                  <div class="col-sm-3">
                    <input type="text" readonly name="tgl_retur" placeholder="Tgl Retur" value="<?php echo $row->tgl_retur ?>" class="form-control">
                  </div>                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-5">
                    <?php  
                    $isi = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row();
                    ?>
                    <input type="text" readonly name="nama_dealer" placeholder="Nama Dealer" value="<?php echo $isi->nama_dealer ?>" class="form-control">
                  </div>                                
                </div>                
                <div>
                  <table id="example2" class="table myTable1 table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>No BASTD</th>
                        <th>Nama Konsumen</th>
                        <th>Alamat</th>
                        <th>No Mesin</th>
                        <th>No Rangka</th>
                        <th>No Faktur AHM</th>      
                        <th>Tipe</th>
                        <th>Warna</th>
                        <th>Tahun</th>                    
                        <th>Aksi</th>                    
                      </tr>
                    </thead>
                   
                    <tbody>                    
                      <?php   
                      $no = 1;
                      $dt_b = $this->db->query("SELECT * FROM tr_map_retur_detail WHERE no_map_retur = '$row->no_map_retur'");        
                      foreach($dt_b->result() as $r) {                             
                        $jum = $dt_b->num_rows();
                        $isi = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail WHERE no_mesin = '$r->no_mesin'")->row();
                        $checked = $r->status_nosin=='terima'?'checked disabled':'';
                        echo "
                        <tr>                     
                          <td>$isi->no_bastd</td> 
                          <td>$isi->nama_konsumen</td> 
                          <td>$isi->alamat</td> 
                          <td>$isi->no_mesin</td> 
                          <td>$isi->no_rangka</td>       
                          <td>$isi->no_faktur</td>                 
                          <td>$isi->id_tipe_kendaraan</td>                 
                          <td>$isi->id_warna</td>                 
                          <td>$isi->tahun</td>
                          <td align='center'>
                            <input type='hidden' value='$jum' name='jum'>        
                            <input type='hidden' value='$isi->no_mesin' name='no_mesin_$no'>        
                            <input type='checkbox' name='cek_retur_$no' $checked>
                          </td>              
                        </tr>";
                        $no++;
                        }
                      ?>
                    </tbody>
                  </table>     
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
          <a href="h1/map_retur/add">            
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
              <th>No Retur</th>              
              <th>Tgl Retur</th>            
              <th>Nama Dealer</th>
              <th>Jumlah Unit</th>
              <th width="15%">Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_retur->result() as $row) {                                         
            $print = $this->m_admin->set_tombol($id_menu,$group,'print');
          echo "          
            <tr>
              <td>$no</td>
              <td>$row->no_map_retur</td>
              <td>$row->tgl_retur</td>                                         
              <td>$row->nama_dealer</td>
              <td>$row->jumlah_unit Unit</td>                            
              <td>                
                <a $print href='h1/map_retur/cetak?id=$row->no_map_retur' class='btn btn-primary btn-flat btn-xs'>Cetak Map Retur</a>                                                
                <a href='h1/map_retur/terima?id=$row->no_map_retur' class='btn btn-primary btn-flat btn-xs'>Terima</a>                                                
              </td>";                                      
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
<script type="text/javascript">
function generate(){    
  $("#tampil_data").show();
  var tgl_retur  = document.getElementById("tanggal").value;     
  var id_dealer  = document.getElementById("id_dealer").value;     
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_dealer="+id_dealer+"&tanggal="+tanggal;
     xhr.open("POST", "h1/map_retur/t_data", true); 
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
</script>