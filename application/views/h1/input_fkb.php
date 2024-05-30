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
<body">
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
    if($set=="check"){
      $row = $dt_fkb->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/input_fkb">
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
            <form class="form-horizontal" action="h1/input_fkb/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                   
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama File</label>
                  <div class="col-sm-6">
                    <input type="text" name="nama_file" value="<?php echo $row->file_name ?>" placeholder="Nama File" readonly class="form-control">
                  </div>                                    
                </div>
                <button class="btn btn-primary btn-flat btn-block" type="button" disabled="">Detail Faktur</button>                
                <table class="table table-bordered table-hover" id="example2">
                  <tr>
                    <th>No Faktur</th>
                    <th>No Mesin</th>
                    <th>No Rangka</th>
                    <th>Kode Tipe</th>
                    <th>Kode Warna</th>
                    <th>Tahun Produksi</th>
                    <th>Harga Di STNK</th>
                    <th>Nama Kapal</th>
                    <th>No SIPB</th>
                    <th>NO SL</th>
                    <th>Tgl SL</th>
                    <th>Aksi</th>
                  </tr>
                  <?php
                  foreach ($dt_fkb->result() as $isi) {
                    $row = $this->m_admin->getByID("tr_shipping_list","no_shipping_list",$isi->no_shipping_list)->row();
                    $bulan = substr($row->tgl_sl, 2,2);
                    $tahun = substr($row->tgl_sl, 4,4);
                    $tgl = substr($row->tgl_sl, 0,2);
                    $tanggal = $tgl."-".$bulan."-".$tahun;


                    $cek = $this->db->query("SELECT * FROM tr_input_fkb_detail INNER JOIN tr_input_fkb ON tr_input_fkb_detail.id_input_fkb=tr_input_fkb.id_input_fkb 
                            WHERE tr_input_fkb_detail.no_mesin = '$isi->no_mesin' AND tr_input_fkb.no_surat = '$isi->no_surat'");
                    if($cek->num_rows() > 0){
                      $is = "checked";
                    }else{
                      $is = "";                    
                    }
                    
                    echo "
                    <tr>
                      <td>$isi->nomor_faktur</td>
                      <td>$isi->no_mesin</td>
                      <td>$isi->no_rangka</td>
                      <td>$isi->kode_tipe</td>
                      <td>$isi->kode_warna</td>
                      <td>$isi->tahun_produksi</td>
                      <td>$isi->harga_beli</td>
                      <td>$isi->nama_kapal</td>
                      <td>$isi->no_sipb</td>
                      <td>$isi->no_shipping_list</td>
                      <td>$tanggal</td>
                      <td align='center'>
                        <input type='hidden' value='$isi->no_surat' name='no_surat[]'>
                        <input type='hidden' value='$isi->no_mesin' name=' no_mesin[]'>                                                
                        <input type='checkbox' class='flat-red' name='check_fkb[]' $is>                                               
                      </td>
                    </tr>";
                  }
                  ?>                  
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
    }elseif($set=="edit"){
      $row = $dt_fkb->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/input_fkb">
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
            <form class="form-horizontal" action="h1/input_fkb/update" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">   
                  <input type="hidden" name="id" value="<?php echo $row->id_input_fkb ?>">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama File</label>
                  <div class="col-sm-6">
                    <input type="text" name="nama_file" value="<?php echo $row->file_name ?>" placeholder="Nama File" readonly class="form-control">
                  </div>                                    
                </div>
                <button class="btn btn-primary btn-flat btn-block" type="button" disabled="">Detail Faktur</button>                
                <table class="table table-bordered table-hover" id="example2">
                  <tr>
                    <th>No Faktur</th>
                    <th>No Mesin</th>
                    <th>No Rangka</th>
                    <th>Kode Tipe</th>
                    <th>Kode Warna</th>
                    <th>Tahun Produksi</th>
                    <th>Harga Di STNK</th>
                    <th>Nama Kapal</th>
                    <th>No SIPB</th>
                    <th>NO SL</th>
                    <th>Tgl SL</th>
                    <th>Aksi</th>
                  </tr>
                  <?php
                  foreach ($dt_fkb->result() as $isi) {
                    $row = $this->m_admin->getByID("tr_shipping_list","no_shipping_list",$isi->no_shipping_list)->row();
                    $bulan = substr($row->tgl_sl, 2,2);
                    $tahun = substr($row->tgl_sl, 4,4);
                    $tgl = substr($row->tgl_sl, 0,2);
                    $tanggal = $tgl."-".$bulan."-".$tahun;


                    $cek = $this->db->query("SELECT * FROM tr_input_fkb_detail INNER JOIN tr_input_fkb ON tr_input_fkb_detail.id_input_fkb=tr_input_fkb.id_input_fkb 
                            WHERE tr_input_fkb_detail.no_mesin = '$isi->no_mesin' AND tr_input_fkb.no_surat = '$isi->no_surat'");
                    if($cek->num_rows() > 0){
                      $is = "checked";
                    }else{
                      $is = "";                    
                    }
                    
                    echo "
                    <tr>
                      <td>$isi->nomor_faktur</td>
                      <td>$isi->no_mesin</td>
                      <td>$isi->no_rangka</td>
                      <td>$isi->kode_tipe</td>
                      <td>$isi->kode_warna</td>
                      <td>$isi->tahun_produksi</td>
                      <td>$isi->harga_beli</td>
                      <td>$isi->nama_kapal</td>
                      <td>$isi->no_sipb</td>
                      <td>$isi->no_shipping_list</td>
                      <td>$tanggal</td>
                      <td align='center'>
                        <input type='hidden' value='$isi->no_surat' name='no_surat[]'>
                        <input type='hidden' value='$isi->no_mesin' name=' no_mesin[]'>                                                
                        <input type='checkbox' class='flat-red' name='check_fkb[]' $is>                                               
                      </td>
                    </tr>";
                  }
                  ?>                  
                </table>
                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to update all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update All</button>
                  <button type="submit" onclick="return confirm('Are you sure to verification?')" name="save" value="verify" class="btn btn-success btn-flat"><i class="fa fa-check"></i> Verification</button>
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
          <!--a href="h1/input_fkb/add">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a-->          
                    
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
              <th>Nama File</th>             
              <th>Tanggal Terima</th> 
              <th width="5%">Status</th>              
              <th width='10%'>Aksi</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          $dt_fkb = $this->db->query("SELECT DISTINCT(file_name),LEFT(tgl_upload,10) AS tgl, no_surat FROM tr_fkb");
          foreach($dt_fkb->result() as $row) {            
            $cek = $this->m_admin->getByID("tr_input_fkb","no_surat",$row->no_surat);
            $edit = $this->m_admin->set_tombol($id_menu,$group,'edit');
            if($cek->num_rows() > 0){
              $is = $cek->row();
              if($is->status=='open'){
                $status = "<span class='label label-warning'>$is->status</span>";
                $as = "<a $edit href='h1/input_fkb/edit?id=$row->no_surat' class='btn btn-primary btn-flat btn-xs'>Edit</a>";
              }else{ 
                $status = "<span class='label label-success'>$is->status</span>";                               
                $as = "";
              }
              
            }else{
              $status = "<span class='label label-warning'>input</span>";                               
              $as = "<a href='h1/input_fkb/check?id=$row->no_surat' class='btn btn-success btn-flat btn-xs'>Check</a>";
            }
            
          echo "          
            <tr>
              <td>$no</td>                           
              <td>$row->file_name</td>              
              <td>$row->tgl</td>              
              <td>$status</td>
              <td>                
                $as
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
