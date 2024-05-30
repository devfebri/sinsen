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
if(isset($_GET['k'])){
?>
  <body onload="kirim_data_sj()">
<?php
}elseif(isset($_GET['s'])){
?>
  <body onload="kirim_data_ksu()">
<?php 
}else{
?>
  <body>
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
    <li class="">Pengeluaran</li>
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
          <a href="h1/surat_jalan">
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
            <form class="form-horizontal" action="h1/surat_jalan/save" method="post" enctype="multipart/form-data">
              <div class="box-body">       
                <div class="form-group">
                  <?php 
                  if(isset($_GET["k"])){
                    $k = "konfirm";
                  }else{
                    $k = "";
                  }
                  ?>
                  <input id="k" value="<?php echo $k ?>" type="hidden">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="no_surat_jalan" placeholder="No Surat Jalan" name="no_surat_jalan">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Surat</label>
                  <div class="col-sm-4">
                    <input type="text" id="tanggal" value="<?php echo date("Y-m-d") ?>" class="form-control" name="tgl_surat">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat SPPM</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="no_surat_sppm" name="no_surat_sppm">
                      <option>- choose -</option>
                      <?php 
                      $dt = $this->db->query("SELECT * FROM tr_sppm WHERE status = 'input'");
                      foreach($dt->result() as $val) {
                        echo "
                        <option value='$val->no_surat_sppm'>$val->no_surat_sppm</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-sm-1">                  
                    <button onclick="kirim_data_sj()" type="button" class="btn btn-flat btn-primary btn-sm">Generate</button>
                  </div>                  
                </div>       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Picking List</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="no_pl" readonly placeholder="No Picking List" name="no_pl">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No DO</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="no_do" readonly placeholder="No DO" name="no_do">                    
                  </div>
                </div>       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="nama_dealer" readonly placeholder="Nama Dealer" name="nama_dealer">
                    <input type="hidden" name="id_dealer" id="id_dealer">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Cara Pengambilan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="cara_ambil">
                      <option>Dijemput</option>
                      <option>Dikirim</option>
                    </select>
                  </div>
                </div>                       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Keterangan" name="ket">                    
                  </div>                  
                </div>                       
                
                
                <hr>                
                <div class="form-group">
                                    
                  
                  <span id="tampil_sj"></span>                                                                                  
                  
                  
                </div>                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="button" onClick="cancel_tr()" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="edit"){
      $row = $dt_sj->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/surat_jalan">
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
            <form class="form-horizontal" action="h1/surat_jalan/update" method="post" enctype="multipart/form-data">
              <div class="box-body">       
                <?php 
                if(isset($_GET["k"])){
                  $k = "konfirm";
                }else{
                  $k = "";
                }
                ?>
                <input id="k" value="<?php echo $k ?>" type="hidden">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="no_surat_jalan" value="<?php echo $row->no_surat_jalan ?>" readonly placeholder="No Surat Jalan" name="no_surat_jalan">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Surat</label>
                  <div class="col-sm-4">
                    <input type="text" id="tanggal" value="<?php echo $row->tgl_surat ?>" class="form-control" name="tgl_surat">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat SPPM</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="no_surat_sppm" value="<?php echo $row->no_surat_sppm ?>" readonly placeholder="No Surst SPPM" name="no_surat_sppm">                    
                  </div>                  
                </div>       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Picking List</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="no_pl" readonly value="<?php echo $row->no_picking_list?>" placeholder="No Picking List" name="no_pl">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No DO</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="no_do" readonly value="<?php echo $row->no_do ?>" placeholder="No DO" name="no_do">                    
                  </div>
                </div>       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="nama_dealer" value="<?php echo $row->nama_dealer ?>" readonly placeholder="Nama Dealer" name="nama_dealer">
                    <input type="hidden" name="id_dealer" id="id_dealer">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Cara Pengambilan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="cara_ambil">
                      <option><?php echo $row->cara_ambil ?></option>
                      <option>Dijemput</option>
                      <option>Dikirim</option>
                    </select>
                  </div>
                </div>                       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" value="<?php echo $row->ket ?>" placeholder="Keterangan" name="ket">                    
                  </div>                  
                </div>                       
                
                
                <hr>                
                <div class="form-group">
                                    
                  
                  <span id="tampil_sj"></span>                                                                                  
                  
                  
                </div>                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <?php 
                  $cek = $this->db->query("SELECT * FROM tr_surat_jalan_detail WHERE no_surat_jalan = '$row->no_surat_jalan' AND status_nosin = 'waiting'");
                  if($cek->num_rows() == 0){
                  ?>
                    <button type="submit" onclick="return confirm('Are you sure to update all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update All</button>
                  <?php
                  }else{
                  ?>
                    <a href="h1/surat_jalan/approval?id=<?php echo $row->no_surat_jalan ?>">
                      <button type="button" name="approve" value="approve" class="btn btn-warning btn-flat"><i class="fa fa-check"></i> Set Approval</button>                    
                    </a>
                  <?php } ?>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="edit_nosin"){
      $row = $dt_sj->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/surat_jalan/edit?k=konfirm&id=<?php echo $no_sj ?>">
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
            <form class="form-horizontal" action="h1/surat_jalan/update" method="post" enctype="multipart/form-data">
              <div class="box-body">                       
                <div class="form-group">
                  <input type="hidden" id="no_sj" name="no_sj" value="<?php echo $no_sj ?>">
                  <input type="hidden" id="id" name="id" value="<?php echo $_GET['id'] ?>">
                  <label for="inputEmail3" class="col-sm-2 control-label">No DO</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="no_do" id="no_do">
                      <option value=''>- choose -</option>
                      <?php
                      $no_do = $row->no_do;
                      $ambil = $this->db->query("SELECT * FROM tr_picking_list WHERE status = 'proses' AND no_do != '$no_do'");
                      foreach ($ambil->result() as $isi) {
                        echo "                        
                        <option value='$isi->no_do'>$isi->no_do</option>
                        ";
                      }
                      ?>                      
                    </select>
                  </div>               
                  <div class="col-sm-1">                  
                    <button onclick="kirim_data_nosin()" type="button" class="btn btn-flat btn-primary btn-sm">Generate</button>
                  </div>   
                </div>                
                
                
                <hr>                
                
                                    
                  
                  <span id="tampil_nosin"></span>                                                                                  
                  
                  
                
              </div><!-- /.box-body -->              
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="approval"){
      $row = $dt_sj->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/surat_jalan/edit?k=konfirm&id=<?php echo $no_sj ?>">
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
            <form class="form-horizontal" action="h1/surat_jalan/save_approval" method="post" enctype="multipart/form-data">
              <div class="box-body">                       
                <div class="form-group">
                  <input type="hidden" id="no_sj" name="no_sj" value="<?php echo $no_sj ?>">                  
                </div>             

                <table id="example2" class="table myTable1 table-bordered table-hover">
                  <thead>
                    <tr>            
                      <th width="1%">No</th>            
                      <th width="20%">No Mesin Awal</th>
                      <th width="10%">Tipe</th>
                      <th width="10%">Warna</th>            
                      <th width="20%">No Mesin Pengganti</th>
                      <th width="10%">No DO</th>                                  
                      <th width="10%">Tipe</th>
                      <th width="10%">Warna</th>            
                      <th width="1%">Checklist</th>           
                    </tr>    
                  </thead>
                  <tbody>
                    <?php 
                    $no=1;
                    foreach ($dt_sj->result() as $isi) {
                      $item = $this->db->query("SELECT * FROM ms_item INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
                          INNER JOIN ms_warna ON ms_item.id_warna=ms_warna.id_warna
                          WHERE ms_item.id_item = '$isi->id_item'")->row();

                      $scan = $this->db->query("SELECT tr_scan_barcode.id_item,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
                          INNER JOIN ms_warna ON ms_item.id_warna=ms_warna.id_warna INNER JOIN tr_scan_barcode ON ms_item.id_item=tr_scan_barcode.id_item
                          WHERE tr_scan_barcode.no_mesin = '$isi->pengganti'")->row();
                      echo "
                        <td>$no</td>
                        <td>$isi->no_mesin</td>
                        <td>$item->tipe_ahm</td>
                        <td>$item->warna</td>
                        <td>$isi->pengganti</td>
                        <td>$isi->no_do</td>
                        <td>$scan->tipe_ahm</td>
                        <td>$scan->warna</td>
                        <td align='center'>
                          <input type='hidden' value='$isi->id_surat_jalan_detail' name='id_surat_jalan_detail[]'>        
                          <input type='hidden' value='$isi->no_mesin' name='no_mesin[]'>        
                          <input type='hidden' value='$isi->pengganti' name='pengganti[]'>        
                          <input type='checkbox' name='check_nosin[]'>
                        </td>
                      ";
                      $no++;
                    }
                    ?>
                  </tbody>
                </table>
                
                <div class="box-footer">
                  <div class="col-sm-2">
                  </div>
                  <div class="col-sm-10">
                    <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                    <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                
                  </div>
                </div><!-- /.box-footer -->

              </div><!-- /.box-body -->              
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="ksu"){
      $row = $dt_sj->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/surat_jalan">
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
                <?php 
                if(isset($_GET["k"])){
                  $k = "konfirm";
                }else{
                  $k = "";
                }
                ?>
                <input id="k" value="<?php echo $k ?>" type="hidden">
                <input id="no_sj" name="no_sj" value="<?php echo $_GET['id'] ?>" type="hidden">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Picking List</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="no_pl" value="<?php echo $row->no_picking_list ?>" readonly placeholder="No Picking List" name="no_picking_list">                    
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="gudang" readonly value="<?php echo $row->gudang?>" placeholder="Gudang" name="gudang">                    
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
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan DO</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" readonly value="<?php echo $row->ket ?>" placeholder="Keterangan" name="ket">                    
                  </div>                  
                </div>                       
                
                
                <hr>                
                <div class="form-group">
                                    
                  
                  <span id="tampil_ksu"></span>                                                                                  
                  
                  
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
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/surat_jalan/add">            
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
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>No Picking List</th> 
              <th>Tanggal Picking List</th>             
              <th>No Surat Jalan</th>
              <th>Tgl Surat Jalan</th>
              <th>No.Do</th>              
              <th>Nama Dealer</th>
              <th>Status</th>
              <th width="15%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_sj->result() as $row) {  
            $cari = $this->db->query("SELECT * FROM tr_picking_list WHERE no_picking_list = '$row->no_picking_list'")->row();                 

            if($row->status=='input'){
              $status = "<span class='label label-danger'>Input</span>";            
              $t1 = "<a data-toggle=\"tooltip\" title=\"Edit\" class=\"btn btn-primary btn-sm btn-flat\" href=\"h1/surat_jalan/edit?k=konfirm&id=$row->no_surat_jalan\"><i class=\"fa fa-edit\"></i></a>";
              $t2 = "<a data-toggle=\"tooltip\" title=\"Cetak SL\" onclick=\"return confirm('Are you sure to print this data?')\" class=\"btn btn-warning btn-sm btn-flat\" href=\"h1/surat_jalan/cetak?id=$row->no_surat_jalan\"><i class=\"fa fa-print\"></i></a>";
              $t3 = "<a data-toggle=\"tooltip\" title=\"PL KSU\" class=\"btn btn-success btn-sm btn-flat\" href=\"h1/surat_jalan/ksu?s=konfirm&id=$row->no_surat_jalan\"><i class=\"fa fa-download\"></i></a>";              
            }elseif($row->status=='proses'){
              $status = "<span class='label label-primary'>Proses</span>";
              $t1 = "";
              $t2 = "<a data-toggle=\"tooltip\" title=\"Cetak SL\" onclick=\"return confirm('Are you sure to print this data?')\" class=\"btn btn-warning btn-sm btn-flat\" href=\"h1/surat_jalan/cetak?id=$row->no_surat_jalan\"><i class=\"fa fa-print\"></i></a>";
              $t3 = "<a data-toggle=\"tooltip\" title=\"PL KSU\" class=\"btn btn-success btn-sm btn-flat\" href=\"h1/surat_jalan/ksu?s=konfirm&id=$row->no_surat_jalan\"><i class=\"fa fa-download\"></i></a>";              
            }elseif($row->status=='close'){
              $status = "<span class='label label-success'>Close</span>";
              $t1 = "";
              $t3 = "";
              $t2 = "";

            }

            echo "          
            <tr>
              <td>$no</td>
              <td>$row->no_picking_list</td>
              <td>$cari->tgl_pl</td>
              <td>$row->no_surat_jalan</td>
              <td>$row->tgl_surat</td>
              <td>$cari->no_do</td>
              <td>$row->nama_dealer</td>
              <td>$status</td>
              <td>";
              echo $t1;
              echo $t2;
              echo $t3;
              ?>
                
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
function kirim_data_sj(){    
  $("#tampil_sj").show();
  cari_lain();
  var no_surat_sppm = document.getElementById("no_surat_sppm").value;
  var k     = document.getElementById("k").value;

  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "no_pl="+birthday1_js;          
    var data = "no_surat_sppm="+no_surat_sppm+"&k="+k;                           
     xhr.open("POST", "h1/surat_jalan/t_sj", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_sj").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function cari_lain(){
  var no_surat_sppm  = $("#no_surat_sppm").val();                         
  $.ajax({
      url: "<?php echo site_url('h1/surat_jalan/cari_lain')?>",
      type:"POST",
      data:"no_surat_sppm="+no_surat_sppm,
      cache:false,
      success:function(msg){                
          data=msg.split("|");
          if(data[0]=="ok"){          
            $("#no_pl").val(data[1]);                
            $("#no_do").val(data[2]);                            
            $("#nama_dealer").val(data[3]);                            
            $("#id_dealer").val(data[4]);                            
          }else{
            alert(data[0]);
          }
      } 
  })
}
function kirim_data_nosin(){    
  $("#tampil_nosin").show();
  var no_do = document.getElementById("no_do").value;  
  var no_sj = document.getElementById("no_sj").value;  
  var id    = document.getElementById("id").value;  

  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "no_pl="+birthday1_js;          
    var data = "no_do="+no_do+"&no_sj="+no_sj+"&id="+id;                           
     xhr.open("POST", "h1/surat_jalan/t_sj_nosin", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_nosin").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function kirim_data_ksu(){    
  $("#tampil_ksu").show();
  var no_pl = document.getElementById("no_pl").value;  
  //var no_sj = document.getElementById("no_sj").value;    

  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "no_pl="+birthday1_js;          
    var data = "no_pl="+no_pl;
     xhr.open("POST", "h1/surat_jalan/t_sj_ksu", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_ksu").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
</script>