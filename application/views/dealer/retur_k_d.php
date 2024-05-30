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
          <a href="dealer/retur_k_d">
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
            <form class="form-horizontal" action="dealer/retur_k_d/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Retur Konsumen</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="no_retur_k" readonly placeholder="No Retur Konsumen" name="no_retur_konsumen">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Retur</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="tanggal" value="<?php echo date('Y-m-d') ?>" placeholder="Tgl Retur" name="tgl_retur">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-3">
                    <select class="form-control select2" name="no_mesin" id="no_mesin">
                      <option value="">- choose -</option>
                      <?php 
                      $id_dealer = $this->m_admin->cari_dealer();
                      $dt_ambil = $this->db->query("SELECT * FROM tr_sales_order WHERE id_dealer = '$id_dealer' AND status_so = 'so_invoice'");
                      foreach ($dt_ambil->result() as $row) {
                        echo "<option value='$row->no_mesin'>$row->no_mesin</option>";
                      }
                      ?>
                    </select>
                  </div>                
                  <div class="col-sm-1">  
                    <button type="button" onclick="generate()" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i></a>
                  </div>  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Rangka</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly placeholder="No Rangka" name="no_rangka" id="no_rangka">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Item</label>
                  <div class="col-sm-4">
                    <input type="text" id="id_item"  required class="form-control" readonly  placeholder="Kode Item" name="id_item">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_tipe_kendaraan" id="id_tipe_kendaraan">                    
                    <input type="text" required class="form-control" readonly placeholder="Tipe" name="tipe_ahm" id="tipe_ahm">
                  </div>
                </div>     
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_warna" id="id_warna">
                    <input type="text" required class="form-control" readonly  placeholder="Warna" name="warna" id="warna">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun Produksi</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly placeholder="Tahun Produksi" name="tahun_produksi" id="tahun_produksi">
                  </div>
                </div>     
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Pembelian</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly placeholder="Tgl Pembelian" name="tgl_beli" id="tgl_beli">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly placeholder="Nama Konsumen" name="nama_konsumen" id="nama_konsumen">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly placeholder="No HP" name="no_hp" id="no_hp">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Konsumen</label>
                  <div class="col-sm-10">
                    <input type="text" required class="form-control" readonly placeholder="Alamat Konsumen" name="alamat" id="alamat">
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
    </form>
    </div><!-- /.box -->
    

   
    <?php
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/retur_k_d/add">
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
              <th>No Retur Konsumen</th>              
              <th>Tgl Retur Konsumen</th>              
              <th>Nama Konsumen</th>              
              <th>No Mesin</th>
              <th>No Rangka</th>                            
              <th>Kode Item</th>
              <th>Tipe</th>
              <th>Warna</th>
              <th>Tahun Produksi</th>
              <th>Tgl Pembelian</th>
              <th>Status</th>
              <th>Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_retur_k_d->result() as $row) {     
            //$s = $this->db->query("SELECT * FROM ms_vendor WHERE id_vendor = '$row->ekspedisi'")->row();          
            $print = $this->m_admin->set_tombol($id_menu,$group,'print');
            echo "
            <tr>
              <td>$no</td>
              <td>$row->no_retur_konsumen</td>
              <td>$row->tgl_retur</td>              
              <td>$row->nama_konsumen</td>            
              <td>$row->no_mesin</td>                                                        
              <td>$row->no_rangka</td>                                                                                  
              <td>$row->id_item</td>                                                                                  
              <td>$row->tipe_ahm</td>
              <td>$row->warna</td>                                                                                  
              <td>$row->tahun_produksi</td>                                                                                  
              <td>$row->tgl_beli</td>                                                                                  
              <td>$row->status_retur_k</td>                                                                                  
              <td>                                
                <a href='dealer/retur_k_d/approve?id=$row->no_retur_konsumen'>
                  <button class='btn btn-flat btn-xs btn-success'><i class='fa fa-check'></i> Approve</button>
                </a>
                <a href='dealer/retur_k_d/reject?id=$row->no_retur_konsumen'>
                  <button class='btn btn-flat btn-xs btn-danger'><i class='fa fa-close'></i> Reject</button>
                </a>
                <a href='dealer/retur_k_d/cetak?id=$row->no_retur_konsumen'>
                  <button $print class='btn btn-flat btn-xs btn-primary'><i class='fa fa-print'></i> Cetak Memo Retur</button>
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



<script type="text/javascript">
function auto(){
  var tgl = 1;
  $.ajax({
      url : "<?php echo site_url('dealer/retur_k_d/cari_id')?>",
      type:"POST",
      data:"tgl="+tgl,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#no_retur_k").val(data[0]);        
      }        
  })
}
function generate(){
  var no_mesin = $("#no_mesin").val();
  $.ajax({
      url : "<?php echo site_url('dealer/retur_k_d/cari_nosin')?>",
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
          $("#id_warna").val(data[5]);        
          $("#warna").val(data[6]);        
          $("#tahun_produksi").val(data[7]);        
          $("#tgl_beli").val(data[8]);        
          $("#nama_konsumen").val(data[9]);        
          $("#no_hp").val(data[10]);        
          $("#alamat").val(data[11]);        
        }else{
          alert(data[0]);
        }
      }        
  })
}
</script>