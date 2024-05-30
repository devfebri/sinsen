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
    <li class="">Polreg</li>
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
          <a href="h1/polreg">
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
            <form class="form-horizontal" action="h1/polreg/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan - Tahun</label>
                  <div class="col-sm-4">
                    <input type="text" name="bulan" id="format_bulan" placeholder="Bulan - Tahun" value="<?php echo date("Y-m") ?>" id="tanggal" class="form-control">
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Segment</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_segment" id="id_segment">
                      <option value="">- choose -</option>
                      <?php 
                      $segment = $this->m_admin->getSortCond("ms_segment","segment","ASC");
                      foreach ($segment->result() as $isi) {
                        echo "<option value='$isi->id_segment'>$isi->id_segment | $isi->segment</option>";
                      }
                      ?>
                    </select>
                  </div>                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Kendaraan</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_kategori" id="id_kategori">
                      <option value="">- choose -</option>
                      <?php 
                      $kategori = $this->m_admin->getSortCond("ms_kategori","kategori","ASC");
                      foreach ($kategori->result() as $isi) {
                        echo "<option value='$isi->id_kategori'>$isi->id_kategori | $isi->kategori</option>";
                      }
                      ?>
                    </select>
                  </div>              
                </div>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>
                  <div class="col-sm-4">
                    <button type="button" onclick="kirim_data_polreg()" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Generate</button>
                  </div>  
                </div>

                <span id="tampil_polreg"></span>
                
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
    }elseif($set=="detail"){
      $row = $dt_polreg->row();
      $row2 = $dt_polreg2->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/polreg">
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
        <div class="row">
          <div class="col-md-12">            
            <form class="form-horizontal" action="h1/polreg/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan - Tahun</label>
                  <div class="col-sm-4">
                    <input type="text" name="bulan" id="format_bulan" placeholder="Bulan - Tahun" value="<?php echo $row->bulan ?>" id="tanggal" class="form-control">
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Segment</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_segment">
                      <option value="<?php echo $row->id_segment ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_segment","id_segment",$row->id_segment)->row();                                 
                        if(isset($dt_cust)){
                          echo "$dt_cust->id_segment | $dt_cust->segment";
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $segment = $this->m_admin->kondisiCond("ms_segment","id_segment != '$row->id_segment'");                                                
                      foreach ($segment->result() as $isi) {
                        echo "<option value='$isi->id_segment'>$isi->id_segment | $isi->segment</option>";
                      }
                      ?>
                    </select>
                  </div>                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Kendaraan</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_kategori">
                      <option value="<?php echo $row->id_kategori ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_kategori","id_kategori",$row->id_kategori)->row();                                 
                        if(isset($dt_cust)){
                          echo "$dt_cust->id_kategori | $dt_cust->kategori";
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $kategori = $this->m_admin->kondisiCond("ms_kategori","id_kategori != '$row->id_kategori'");                                                
                      foreach ($kategori->result() as $isi) {
                        echo "<option value='$isi->id_kategori'>$isi->id_kategori | $isi->kategori</option>";
                      }
                      ?>
                    </select>
                  </div>              
                </div>                

                <table class='table table-bordered table-hover' id="example4">
                  <thead>
                    <tr>
                      <th>Kode Tipe Kendaraan Honda</th>
                      <th>Nama Tipe Kendaraan Honda</th>
                      <th>Qty Penjualan Honda</th>
                      <th>Tipe Kendaraan Yamaha</th>
                      <th>Qty Penjualan Yamaha</th>
                      <th>Tipe Kendaraan Suzuki</th>                    
                      <th>Qty Penjualan Suzuki</th>                    
                      <th>Tipe Kendaraan Kawasaki</th>                    
                      <th>Qty Penjualan Kawasaki</th>                    
                    </tr>                  
                  </thead>
                  <tbody>
                    <?php $detail = $this->db->query("SELECT * FROM tr_polreg_detail WHERE id_polreg='$row->id_polreg'") ?>
                    <?php foreach ($detail->result() as $rs): ?>
                      <tr>
                      <td>
                        <select class="form-control select2 isi_combo" id="id_tipe_kendaraan" name="id_tipe_kendaraan" onchange="cek_tipe()">
                          <option value="<?php  echo $rs->id_tipe_kendaraan  ?>"><?php  echo $rs->id_tipe_kendaraan  ?></option>                          
                        </select> 
                      </td>
                      <td>
                        <?php   
                        $ahm = $this->m_admin->getByID("ms_tipe_kendaraan","id_tipe_kendaraan",$rs->id_tipe_kendaraan);
                        ?>
                        <input type="text" readonly placeholder="Nama Tipe" id="tipe_ahm" name="tipe_ahm" value="<?php  echo $ahm->num_rows()?$ahm->row()->tipe_ahm:''  ?>" class="form-control isi">
                      </td>
                      <td>
                        <input type="text" placeholder="Qty Honda" value="<?php  echo $rs->qty_honda ?>" name="qty_honda" class="form-control isi">
                      </td>
                      <td>
                        <input type="text" placeholder="Tipe Yamaha" value="<?php  echo $rs->tipe_yamaha   ?>" name="tipe_yamaha" class="form-control isi">
                      </td>
                      <td>
                        <input type="text" placeholder="Qty Yamaha" name="qty_yamaha" value="<?php  echo $rs->qty_yamaha   ?>" class="form-control isi">
                      </td>
                      <td>
                        <input type="text" placeholder="Tipe Suzuki" name="tipe_suzuki" value="<?php  echo $rs->tipe_suzuki  ?>" class="form-control isi">
                      </td>
                      <td>
                        <input type="text" placeholder="Qty Suzuki" name="qty_suzuki" value="<?php  echo $rs->qty_suzuki   ?>" class="form-control isi">
                      </td>
                      <td>
                        <input type="text" placeholder="Tipe Kawasaki" name="tipe_kawasaki" value="<?php  echo $rs->tipe_kawasaki  ?>" class="form-control isi">
                      </td>
                      <td>
                        <input type="text" placeholder="Qty Kawasaki" name="qty_kawasaki" value="<?php  echo $rs->qty_kawasaki   ?>" class="form-control isi">
                      </td>
                    </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>
                
              </div><!-- /.box-body -->              
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="edit"){
      $row = $dt_polreg->row();
      $row2 = $dt_polreg2->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/polreg">
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
        <div class="row">
          <div class="col-md-12">            
            <form class="form-horizontal" action="h1/polreg/update" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <input type="hidden" name="id" value="<?php echo $row->id_polreg ?>">
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan - Tahun</label>
                  <div class="col-sm-4">
                    <input type="text" name="bulan" id="format_bulan" placeholder="Bulan - Tahun" value="<?php echo $row->bulan ?>" id="tanggal" class="form-control">
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Segment</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_segment">
                      <option value="<?php echo $row->id_segment ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_segment","id_segment",$row->id_segment)->row();                                 
                        if(isset($dt_cust)){
                          echo "$dt_cust->id_segment | $dt_cust->segment";
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $segment = $this->m_admin->kondisiCond("ms_segment","id_segment != '$row->id_segment'");                                                
                      foreach ($segment->result() as $isi) {
                        echo "<option value='$isi->id_segment'>$isi->id_segment | $isi->segment</option>";
                      }
                      ?>
                    </select>
                  </div>                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Kendaraan</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_kategori">
                      <option value="<?php echo $row->id_kategori ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_kategori","id_kategori",$row->id_kategori)->row();                                 
                        if(isset($dt_cust)){
                          echo "$dt_cust->id_kategori | $dt_cust->kategori";
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $kategori = $this->m_admin->kondisiCond("ms_kategori","id_kategori != '$row->id_kategori'");                                                
                      foreach ($kategori->result() as $isi) {
                        echo "<option value='$isi->id_kategori'>$isi->id_kategori | $isi->kategori</option>";
                      }
                      ?>
                    </select>
                  </div>              
                </div>                

                <table class='table table-bordered table-hover' id="example4">
                  <thead>
                    <tr>
                      <th>Kode Tipe Kendaraan Honda</th>
                      <th>Nama Tipe Kendaraan Honda</th>
                      <th>Qty Penjualan Honda</th>
                      <th>Tipe Kendaraan Yamaha</th>
                      <th>Qty Penjualan Yamaha</th>
                      <th>Tipe Kendaraan Suzuki</th>                    
                      <th>Qty Penjualan Suzuki</th>                    
                      <th>Tipe Kendaraan Kawasaki</th>                    
                      <th>Qty Penjualan Kawasaki</th>                    
                    </tr>                  
                  </thead>
                   <tbody>
                    <?php $detail = $this->db->query("SELECT * FROM tr_polreg_detail WHERE id_polreg='$row->id_polreg'") ?>
                    <?php foreach ($detail->result() as $rs): ?>
                      <tr>
                      <td>
                        <select class="form-control select2 isi_combo" id="id_tipe_kendaraan" name="id_tipe_kendaraan[]" onchange="cek_tipe()">
                          <option value="<?php  echo $rs->id_tipe_kendaraan  ?>"><?php  echo $rs->id_tipe_kendaraan  ?></option>                          
                        </select> 
                      </td>
                      <td>
                        <?php   
                        $ahm = $this->m_admin->getByID("ms_tipe_kendaraan","id_tipe_kendaraan",$rs->id_tipe_kendaraan);
                        ?>
                        <input type="text" readonly placeholder="Nama Tipe" id="tipe_ahm" name="tipe_ahm[]" value="<?php  echo $ahm->num_rows()?$ahm->row()->tipe_ahm:''  ?>" class="form-control isi">
                      </td>
                      <td>
                        <input type="text" placeholder="Qty Honda" value="<?php  echo $rs->qty_honda ?>" name="qty_honda[]" class="form-control isi">
                      </td>
                      <td>
                        <input type="text" placeholder="Tipe Yamaha" value="<?php  echo $rs->tipe_yamaha   ?>" name="tipe_yamaha[]" class="form-control isi">
                      </td>
                      <td>
                        <input type="text" placeholder="Qty Yamaha" name="qty_yamaha[]" value="<?php  echo $rs->qty_yamaha   ?>" class="form-control isi">
                      </td>
                      <td>
                        <input type="text" placeholder="Tipe Suzuki" name="tipe_suzuki[]" value="<?php  echo $rs->tipe_suzuki  ?>" class="form-control isi">
                      </td>
                      <td>
                        <input type="text" placeholder="Qty Suzuki" name="qty_suzuki[]" value="<?php  echo $rs->qty_suzuki   ?>" class="form-control isi">
                      </td>
                      <td>
                        <input type="text" placeholder="Tipe Kawasaki" name="tipe_kawasaki[]" value="<?php  echo $rs->tipe_kawasaki  ?>" class="form-control isi">
                      </td>
                      <td>
                        <input type="text" placeholder="Qty Kawasaki" name="qty_kawasaki[]" value="<?php  echo $rs->qty_kawasaki   ?>" class="form-control isi">
                      </td>
                    </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>
                
              </div><!-- /.box-body -->              
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to update all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update All</button>
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
          <a href="h1/polreg/add">            
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
              <th>Bulan</th>              
              <th>Tahun</th>            
              <th>Jenis Kendaraan</th>
              <th>Segment</th>
              <th width="10%">Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_polreg->result() as $row) {     
            $bulan = substr($row->bulan,5,2);                                    
            $tahun = substr($row->bulan,0,4);  
            $edit = $this->m_admin->set_tombol($id_menu,$group,'edit');

          echo "          
            <tr>
              <td>$no</td>
              <td>$bulan</td>
              <td>$tahun</td>                                         
              <td>$row->id_kategori</td>
              <td>$row->id_segment</td>                            
              <td>                
                <a $edit href='h1/polreg/edit?id=$row->id_polreg' class='btn btn-primary btn-flat btn-xs'>Edit</a>                                                
                <a href='h1/polreg/view?id=$row->id_polreg' class='btn btn-warning btn-flat btn-xs'>View</a>                                                
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
function cek_tipe(){
  var id_tipe_kendaraan = document.getElementById("id_tipe_kendaraan").value;
  $.ajax({
      url : "<?php echo site_url('h1/polreg/cari_tipe')?>",
      type:"POST",
      data:"id_tipe_kendaraan="+id_tipe_kendaraan,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#tipe_ahm").val(data[0]);                
      }        
  })
}
function kirim_data_polreg(){    
  $("#tampil_polreg").show();
  var id_segment  = document.getElementById("id_segment").value;   
  var id_kategori = document.getElementById("id_kategori").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_segment="+id_segment+"&id_kategori="+id_kategori;                           
     xhr.open("POST", "h1/polreg/t_polreg", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_polreg").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    }   
}
</script>