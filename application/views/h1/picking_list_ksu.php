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
    <li class="">Pengeluaran</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    
    <?php 
    if($set=="cetak_terima"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/picking_list_ksu">
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
            <form class="form-horizontal" action="h1/picking_list_ksu/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Biro Jasa</label>
                  <div class="col-sm-4">
                    <select class="form-control select2">
                      <option>- choose -</option>
                    </select>
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Biro Jasa</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" placeholder="Alamat Biro Jasa" class="form-control">
                  </div>                                
                </div>
                
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
    }elseif($set=='ksu'){
      $row = $dt_sj->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/picking_list_ksu">
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
            <form class="form-horizontal" action="h1/surat_jalan/save_ksu" method="post" enctype="multipart/form-data">
              <div class="box-body">                                       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Picking List</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="no_pl" value="<?php echo $row->no_surat_jalan ?>" readonly placeholder="No Picking List" name="no_picking_list">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Picking List</label>
                  <div class="col-sm-4">
                    <input type="text" readonly value="<?php echo $row->tgl_pl ?>" class="form-control" name="tgl_pl">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Do</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="no_do" value="<?php echo $row->no_do ?>" readonly placeholder="No DO" name="no_do">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Do</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="tgl_do" value="<?php echo $row->tgl_do ?>" readonly placeholder="Tgl DO" name="tgl_do">                    
                  </div>                  
                </div>                       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="kode_dealer" value="<?php echo $row->kode_dealer_md ?>" readonly placeholder="Kode Dealer" name="kode_dealer">
                    <input type="hidden" name="id_dealer" id="id_dealer">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="nama_dealer" value="<?php echo $row->nama_dealer ?>" readonly placeholder="Nama Dealer" name="nama_dealer">                    
                  </div>
                </div>                                       
                
                <hr>                
                <div class="form-group">                                                      
                  <table id="example2" class="table myTable1 table-bordered table-hover">
                    <thead>
                      <tr>                                        
                        <th width="5%">No</th>            
                        <th>ID KSU</th>            
                        <th>Nama KSU</th>            
                        <th>Qty</th>                                          
                      </tr>    
                    </thead>
                    <tbody>
                    <?php 
                    $no=1;    
                    $dt = $this->db->query("SELECT * FROM tr_surat_jalan_ksu INNER JOIN 
                        tr_surat_jalan_ksu_pl ON tr_surat_jalan_ksu.no_surat_jalan=tr_surat_jalan_ksu_pl.no_surat_jalan
                        INNER JOIN ms_ksu ON tr_surat_jalan_ksu.id_ksu=ms_ksu.id_ksu            
                        WHERE tr_surat_jalan_ksu_pl.no_pl_ksu = '$id'");
                    foreach ($dt->result() as $isi) {            
                      echo "
                      <tr>
                        <td>$no</td>
                        <td>$isi->id_ksu</td>
                        <td>$isi->ksu</td>
                        <td>$isi->qty</td>
                      </tr>
                      ";                      
                      $no++;    
                    } 
                    ?>
                    </tbody> 
                  </table>
                      
                  
                  
                </div>                
              </div><!-- /.box-body -->              
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php
    }elseif($set=="view"){
    ?>

<div class="box">
      <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
    
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
        <table id="table_picking_list_ksu" class="table table-bordered table-hover">
          <thead>
          <tr>
          <th width="5%">No</th>                          
              <th>No Picking List KSU</th>
              <th>Tgl Picking List KSU</th>
              <th>No Picking List Unit</th>              
              <th>Tgl Picking List Unit</th>            
              <th>No DO</th>
              <th>Dealer</th>              
            </tr>
          </thead>
          <tbody>   
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }
    ?>
  </section>
</div>

<script>
  $( document ).ready(function() {
   tabless = $('#table_picking_list_ksu').DataTable({
	      "scrollX": true,
        "processing": true, 
        "bDestroy": true,
        "serverSide": true, 
        "order": [],
        "ajax": {
          "url": "<?php  echo site_url('h1/picking_list_ksu/fetch_data_picking_list_ksu_datatables')?>",
            "type": "POST"
        },  
              
        "columnDefs": [
        {
            "targets": [ 0,5 ],
            "orderable": false, 
        },
        ],
        });
});
</script>
