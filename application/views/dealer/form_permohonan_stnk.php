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
<body onload="auto()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
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
          <a href="dealer/form_permohonan_stnk">
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
        <div id="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="dealer/form_permohonan_stnk/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Permohonan</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly id="no_permohonan" placeholder="No Permohonan" name="no_permohonan">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Permohonan</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="tanggal"  placeholder="Tgl Permohonan" name="tgl_permohonan">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen Lama</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control"  placeholder="Nama Konsumen Lama" name="nama_konsumen_lama">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen Baru</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control"  placeholder="Nama Konsumen Baru" name="nama_konsumen_baru">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control"  placeholder="No Mesin" name="no_mesin">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No STNK</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="No STNK" name="no_stnk">                    
                  </div>
                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Polisi</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="No Polisi" name="no_polisi">                    
                  </div>                  
                </div>                
              </div>
          </div>
        </div>         
      </div><!-- /.box-body -->
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
    }elseif($set=="reject"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/form_permohonan_stnk">
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
            <form class="form-horizontal" action="dealer/form_permohonan_stnk/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Spk</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="No SPK" name="nama_konsumen">                    
                  </div>                                    
                </div>                
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                  <div class="col-sm-10">
                    <input type="text" required class="form-control" placeholder="Nama Konsumen" name="nama_konsumen">                    
                  </div>                  
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-10">
                    <input type="text" required class="form-control" placeholder="Nama Konsumen" name="nama_konsumen">                    
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="Warna" name="nama_konsumen">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Motor</label>
                  <div class="col-sm-4">                    
                    <input type="text" required class="form-control" placeholder="Tipe Motor" name="nama_konsumen">                    
                  </div>
                  
                </div>
                <div class="form-group">                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Alasan Reject</label>
                  <div class="col-sm-10">
                    <input type="text" required class="form-control" placeholder="Alasan Reject" name="nama_konsumen">                    
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
          <a href="dealer/form_permohonan_stnk/add">
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
              <th>No Permohonan</th>              
              <th>Tgl Permohonan</th>              
              <th>Nama Konsumen (Lama)</th>              
              <th>Nama Konsumen (Baru)</th>
              <th>No Mesin</th>              
              <th>No STNK</th>
              <th>No Polisi</th>
              <th>Status</th>
              <th>Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_stnk->result() as $row) {     
            if($row->status_stnk =='open'){
              $status = "<span class='label label-warning'>$row->status_stnk</span>";
            }else{
              $status = "<span class='label label-success'>$row->status_stnk</span>";
            }
            echo "
            <tr>
              <td>$no</td>
              <td>$row->no_permohonan</td>              
              <td>$row->tgl_permohonan</td>
              <td>$row->nama_konsumen_lama</td>
              <td>$row->nama_konsumen_baru</td>
              <td>$row->no_mesin</td>                            
              <td>$row->no_stnk</td>                                          
              <td>$row->no_polisi</td>                                                        
              <td>$status</td>                                                                                  
              <td>                                
                <a href='dealer/form_permohonan_stnk/cetak?id=$row->no_permohonan'>
                  <button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-print'></i> Cetak Permohonan</button>
                </a>"; ?>
                <a href='dealer/form_permohonan_stnk/close?id=<?php echo $row->no_permohonan ?>' onclick="return confirm('Are you sure to close this data?')">
                  <button class='btn btn-flat btn-xs btn-danger'><i class='fa fa-close'></i> Close</button>
                </a>
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



<script type="text/javascript">
function auto(){
  var tgl_js = "1";
  $.ajax({
      url : "<?php echo site_url('dealer/form_permohonan_stnk/cari_id')?>",
      type:"POST",
      data:"tgl="+tgl_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#no_permohonan").val(data[0]);                        
      }        
  })
}
</script>