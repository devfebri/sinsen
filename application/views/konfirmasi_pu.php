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
    <li class="">Penerimaan Unit</li>    
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?> <span id="ubah_label"></span></li>
  </ol>
  </section>
  <section class="content">
    

    <?php 
    if($set == 'unit'){
      $row = $dt_pu->row();
    ?>
<script>
  $(document).on("keypress", "form", function(event) { 
    return event.keyCode != 13;
});
</script>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/konfirmasi_pu">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
          </a>          

          <button type="button" onclick="set_rfs()" class="btn btn-flat btn-success"><i class="fa fa-qrcode"></i> RFS</button>
          <button type="button" onclick="set_nrfs()" class="btn btn-flat btn-warning"><i class="fa fa-qrcode"></i> NRFS</button>
          
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
            <form class="form-horizontal" action="dealer/konfirmasi_pu/save" method="post" enctype="multipart/form-data" >
              <div class="box-body">                              
                <div class="form-group">                  
                  <input type="hidden" readonly class="form-control" id="id_penerimaan_unit_dealer" placeholder="ID Konfirmasi Unit" name="id_penerimaan_unit_dealer">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <input type="hidden" id="jenis_pu" name="jenis_pu">                    
                    <input type="hidden" value="<?php echo $_GET['id'] ?>" id="id_surat_jalan" name="id_surat_jalan">
                    <input type="text" value="<?php echo $row->nama_dealer ?>" required class="form-control" id="nama_dealer" readonly placeholder="Nama Dealer" name="nama_dealer">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Penerimaan</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="tanggal" value="<?php echo date("Y-m-d") ?>" placeholder="Tgl Penerimaan" name="tgl_penerimaan">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->no_surat_jalan ?>" required class="form-control" id="no_surat_jalan" readonly placeholder="No Surat Jalan" name="no_surat_jalan">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->tgl_surat ?>" required class="form-control" id="tgl_surat" readonly placeholder="Tgl Surat Jalan" name="tgl_surat">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No DO</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->no_do ?>" required class="form-control" id="no_do" readonly placeholder="No DO" name="no_do">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl DO</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->tgl_do ?>" required class="form-control" id="tgl_do" readonly placeholder="Tgl DO" name="tgl_do">                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Lokasi Penyimpanan</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="mode" value="" id="mode">
                    <select class="form-control" name="id_gudang_dealer" required id="id_gudang_dealer">
                      <?php
                     
                     /* $cek = $this->m_admin->getByID("tr_penerimaan_unit_dealer","no_surat_jalan",$row->no_surat_jalan)->row();
                      if(isset($cek->id_gudang_dealer)){
                        echo "<option value='$cek->id_gudang_dealer'>$cek->id_gudang_dealer</option>";
                      }else{
                        echo "<option value=''>- choose -</option>";
                      } */
                      ?>                      
                      <?php 
                      $id_dealer = $this->m_admin->cari_dealer();
                      $dt_gudang = $this->db->query("SELECT * FROM ms_gudang_dealer WHERE id_dealer = '$id_dealer' AND active = 1 ORDER BY gudang ASC");
                      foreach($dt_gudang->result() as $row) {               
                        echo "<option value='$row->gudang'>$row->gudang</option>";
                      }
                      ?>
                    </select>
                  </div>                                  
                  <!-- <div class="col-sm-1">
                    <a href='dealer/konfirmasi_pu/gudang?id=<?php echo $_GET['id'] ?>' class="btn btn-flat btn-primary"><i class='fa fa-plus'></i> Gudang</a>
                  </div> -->
                  <span id="scan_nosin">
                    <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control" id="no_mesin" placeholder="No Mesin" name="no_mesin" maxlength="13" minlength="13">                    
                    </div>
                    <div class="col-sm-1">
                      <!-- <button onclick="simpan_nosin()" type="button" class="btn btn-primary btn-flat btn-md"><i class="fa fa-save"></i> Simpan</button>                       -->
                      <!-- <button type="button" data-toggle="modal" data-target="#Scanmodal" class="btn btn-primary btn-flat btn-md"><i class="fa fa-check"></i> Browse</button>        -->               
                      <button type="button" onclick="getScanModal()" class="btn btn-primary btn-flat btn-md"><i class="fa fa-check"></i> Browse</button>                      
                    </div>
                  </span>
                </div>                
              </div>            
          </div>
        </div>
        <span id="tampil_data"></span>
      </div><!-- /.box-body -->
      <div class="box-footer">
        <div class="col-sm-2">
        </div>
        <div class="col-sm-10">
          <button type="submit" name="save" onclick="return confirm('Are you sure to save all this data?')" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
          <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                
        </div>
      </div><!-- /.box-footer -->
    </div><!-- /.box -->
    </form>

    <?php 
    }elseif($set == 'ksu'){
      $ksu = $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_sppm ON tr_surat_jalan.no_surat_sppm = tr_sppm.no_surat_sppm                 
        WHERE tr_surat_jalan.id_surat_jalan = '$_GET[id]'")->row();
      $sj = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan = '$ksu->no_surat_jalan'")->row();
      if(isset($sj)){
        $tgl = $sj->tgl_penerimaan;
      }else{
        $tgl = "";
      }
      $row = $dt_ksu->row();
      if(isset($row->id_penerimaan_unit_dealer)){
        $id_p = $row->id_penerimaan_unit_dealer;
      }else{
        $id_p = "";
      }
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/konfirmasi_pu">
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
        <div id="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="dealer/konfirmasi_pu/save_ksu" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <input type="hidden" name="id_penerimaan_unit_dealer" value="<?php echo $id_p ?>">
                  <input type="hidden" name="id_sj" value="<?php echo $_GET['id'] ?>">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" value="<?php echo $ksu->no_surat_jalan ?>" readonly placeholder="No Surat Jalan" name="no_surat_jalan">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control" value="<?php echo $ksu->tgl_surat ?>" readonly placeholder="Tgl Surat Jalan" name="tgl_surat">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No DO</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $ksu->no_do ?>" readonly placeholder="No DO" name="no_do">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl DO</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $ksu->tgl_do ?>" readonly placeholder="Tgl DO" name="tgl_do">                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Terima</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control" value="<?php echo $tgl ?>" readonly placeholder="Tgl Terima" name="tgl_penerimaan">                    
                  </div>
                </div>                
              </div>            
          </div>
        </div>
        <table id="" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>Kode KSU</th>              
              <th>Aksesoris</th>              
              <th>Qty Supply MD</th>
              <th width="5%">Qty Penerimaan</th>              
            </tr>
          </thead>
          <tbody>            
            <?php 
            $no = 1;
            foreach ($ksu_d->result() as $row) {                            
              // $item = $this->db->query("SELECT * FROM ms_item WHERE id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND id_warna = '$row->id_warna'")->row();
              $id_ksu = $this->db->query("SELECT * FROM ms_ksu WHERE id_ksu = '$row->id_ksu'")->row();
              $id_p = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer INNER JOIN tr_surat_jalan ON tr_penerimaan_unit_dealer.no_surat_jalan=tr_surat_jalan.no_surat_jalan 
                 WHERE tr_surat_jalan.id_surat_jalan = '$id_surat_jalan'");
              if($id_p->num_rows() > 0){
                $t = $id_p->row();
                $id_penerimaan_unit_dealer = $t->id_penerimaan_unit_dealer;
              }else{
                $id_penerimaan_unit_dealer = "";
              }
              $cek = $this->db->query("SELECT * FROM tr_penerimaan_ksu_dealer WHERE id_penerimaan_unit_dealer = '$id_penerimaan_unit_dealer' 
                    AND id_ksu = '$row->id_ksu'");
              if($cek->num_rows() > 0){
                $i = $cek->row();
                $isi = "value='$i->qty_terima'";
              }else{
                $isi = "";
              }
              if($row->jum > 0){
                echo "
                <tr>
                  <td>$no</td>
                  <td>$row->id_ksu</td>                
                  <td>$row->ksu</td>                
                  <td>$row->jum</td>                                
                  <td>
                    <input type='hidden' name='id_ksu[]' value='$row->id_ksu'>
                    <input type='hidden' name='no_sj[]' value='$ksu->no_surat_jalan'>
                    <input type='hidden' name='qty_md[]' value='$row->jum'>
                    <input type='text' name='qty_terima[]' $isi class='form-control isi' onkeypress=\"return number_only(event)\">
                  </td>
                </tr>
                ";
                $no++;
              }
            }
            ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
      <div class="box-footer">
        <div class="col-sm-2">
        </div>
        <div class="col-sm-10">
          <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
          <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                
        </div>
      </div><!-- /.box-footer -->
    </div><!-- /.box -->
    </form>

    <?php 
    }elseif($set == 'detail'){
      $row = $dt_rfs->row();
      $ro = $dt_pu->row();
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <?php if(isset($_GET['p'])){ ?>
          <a href="dealer/konfirmasi_pu">
          <?php }else{ ?>
          <a href="dealer/konfirmasi_pu/history">
          <?php } ?>
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
          </a>          
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
          <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
          <div class="col-sm-4">            
            <input type="text" class="form-control" disabled value="<?php echo $ro->no_surat_jalan?>" id="tanggal" placeholder="Tanggal penerimaan" id="tgl_penerimaan">                    
          </div>
          <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Penerimaan</label>
          <div class="col-sm-4">
            <?php 
            if(isset($ro->tgl_penerimaan)){
              $tg = $ro->tgl_penerimaan;
            }else{
              $tg = "";
            }
            ?>
            <input type="text" class="form-control" disabled value="<?php echo $tg ?>" id="tanggal" placeholder="Tanggal penerimaan" id="tgl_penerimaan">                    
          </div>                                                                  
      </div>
      <div class="box-body">
          <label for="inputEmail3" class="col-sm-2 control-label">Gudang</label>
          <div class="col-sm-4">            
            <input type="text" class="form-control" disabled value="<?php echo $ro->id_gudang_dealer ?>" id="tanggal" placeholder="Tanggal penerimaan" id="tgl_penerimaan">                    
          </div>          
      </div>
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
        <button class="btn-block btn-primary" disabled> RFS</button>
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>
              <th>No Mesin</th>              
              <th>No Rangka</th>
              <th>Tipe</th>  
              <th>Warna</th>        
              <th>Kode Item</th>    
              <th>FIFO</th>              
            </tr>
          </thead>
          <tbody>            
            <?php 
            $no = 1;
            foreach ($dt_rfs->result() as $row) {      
              echo "
              <tr>
                <td>$no</td>
                <td>$row->no_mesin</td>
                <td>$row->no_rangka</td>
                <td>$row->tipe_ahm</td>
                <td>$row->warna</td>
                <td>$row->id_item</td>
                <td>$row->fifo</td>
              </tr>
              ";
              $no++;
            }
            ?>
          </tbody>
        </table>


        <button class="btn-block btn-warning" disabled> NRFS</button>
        <table id="example3" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>
              <th>No Mesin</th>              
              <th>No Rangka</th>
              <th>Tipe</th>  
              <th>Warna</th>        
              <th>Kode Item</th>    
              <th>FIFO</th>              
            </tr>
          </thead>
          <tbody>            
            <?php 
            $no = 1;
            foreach ($dt_nrfs->result() as $row) {      
              echo "
              <tr>
                <td>$no</td>
                <td>$row->no_mesin</td>
                <td>$row->no_rangka</td>
                <td>$row->tipe_ahm</td>
                <td>$row->warna</td>
                <td>$row->id_item</td>
                <td>$row->fifo</td>
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
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/konfirmasi_pu/history">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-check"></i> Cek History Penerimaan</button>            
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>ID Konfirmasi Unit</th>
              <th>Tgl Penerimaan</th>
              <th>No Surat Jalan MD</th>              
              <th>Tgl Surat Jalan MD</th>              
              <th>No DO</th>
              <th>Tanggal DO</th>
              <?php /*<th>Status</th>               */ ?>
              <th width="20%">Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_sj->result() as $row) {     
            $cek = $this->m_admin->getByID("tr_penerimaan_unit_dealer","no_surat_jalan",$row->no_surat_jalan);
            if($cek->num_rows() > 0){
              $id_ = $cek->row();
              $id_penerimaan_unit_dealer = $id_->id_penerimaan_unit_dealer;
              $tgl_penerimaan = $id_->tgl_penerimaan;
            }else{
              $id_penerimaan_unit_dealer = "";
              $tgl_penerimaan = "";
            }
            if($row->status == 'proses'){
              $status = "<span class='label label-warning'>$row->status</span>";
              $tombol = "<a href=\"dealer/konfirmasi_pu/unit?id=$row->id_surat_jalan\">
                          <button class=\"btn btn-flat btn-xs btn-success\"><i class=\"fa fa-check\"></i> Konfirmasi PU</button>
                        </a>";
              if($id_penerimaan_unit_dealer != ""){
                $tombol .= "                
                  <a href=\"dealer/konfirmasi_pu/ksu?id=$row->id_surat_jalan\">
                    <button class=\"btn btn-flat btn-xs btn-primary\"><i class=\"fa fa-download\"></i> Konfirmasi KSU</button>
                  </a>
                  <a href=\"dealer/konfirmasi_pu/cetak_accu?id=$row->id_surat_jalan\">
                    <button class=\"btn btn-flat btn-xs btn-warning\"><i class=\"fa fa-print\"></i> Cetak Stiker ACCU</button>
                  </a>
                  <a href=\"dealer/konfirmasi_pu/close?id=$row->id_surat_jalan&id_pu=$id_penerimaan_unit_dealer\" onclick=\"return confirm('Are you sure to close this data?')\">
                    <button class=\"btn btn-flat btn-xs btn-danger\"><i class=\"fa fa-close\"></i> Close</button>
                  </a>";
              }
            }elseif($row->status == 'close'){
              $status = "<span class='label label-danger'>$row->status</span>";
              $tombol = "";
            }    

            echo "
            <tr>
              <td>$no</td>
              <td>
                <a href='dealer/konfirmasi_pu/view?id=$id_penerimaan_unit_dealer&p=n'>
                  $id_penerimaan_unit_dealer
                </a>
              </td>              
              <td>$tgl_penerimaan</td>              
              <td>$row->no_surat_jalan</td>              
              <td>$row->tgl_surat</td>
              <td>$row->no_do</td>
              <td>$row->tgl_do</td> " ?>                                          
              
              <?php //<td>$status</td>                                          
              echo "<td>$tombol</td>
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
    }elseif($set=="history"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/konfirmasi_pu">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>ID Konfirmasi Unit</th>
              <th>Tgl Penerimaan</th>
              <th>No Surat Jalan MD</th>              
              <th>Tgl Surat Jalan MD</th>              
              <th>No DO</th>
              <th>Tanggal DO</th>
              <th width="5%">Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_sj->result() as $row) {     
            $cek = $this->m_admin->getByID("tr_penerimaan_unit_dealer","no_surat_jalan",$row->no_surat_jalan);
            if($cek->num_rows() > 0){
              $id_ = $cek->row();
              $id_penerimaan_unit_dealer = $id_->id_penerimaan_unit_dealer;
              $tgl_penerimaan = $id_->tgl_penerimaan;
            }else{
              $id_penerimaan_unit_dealer = "";
              $tgl_penerimaan = "";
            }

            $tombol = "                                
              <a href='dealer/konfirmasi_pu/view?id=$id_penerimaan_unit_dealer'>
                <button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-eye'></i> Detail</button>
              </a>";
            
            echo "
            <tr>
              <td>$no</td>
              <td>$id_penerimaan_unit_dealer</td>              
              <td>$tgl_penerimaan</td>              
              <td>$row->no_surat_jalan</td>              
              <td>$row->tgl_surat</td>
              <td>$row->no_do</td>
              <td>$row->tgl_do</td>                                          
              <td>$tombol</td>
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
    }elseif($set == 'ksu2'){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/penerimaan_unit">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-chevron-left"></i> Back</button>
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
        <form action="h1/penerimaan_unit/save_ksu" method="post">          
        <button type="submit" onclick="return confirm('Are you sure to save all data?')" class="btn btn-info btn-flat pull-right"><i class="fa fa-save"></i> Save All</button>          
        <input type="hidden" value="<?php echo $_GET['id'] ?>" name="id_pu">
        <br><br>
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>              
              <th>No SL</th>
              <th>Tipe</th>              
              <th>Warna</th>              
              <th>Kode Item</th>
              <th>Jumlah Unit</th>
              <th>KSU</th>
            </tr>
          </thead>
          <tbody>            
            <?php 
            $no = 1;
            foreach ($dt_rfs->result() as $row) {
              
              $unit = $this->db->query("SELECT COUNT(no_mesin) AS nosin FROM tr_scan_barcode 
                INNER JOIN tr_penerimaan_unit_detail ON tr_scan_barcode.no_shipping_list = tr_penerimaan_unit_detail.no_shipping_list
                WHERE tr_penerimaan_unit_detail.no_shipping_list = '$row->no_shipping_list' AND tr_scan_barcode.id_item = '$row->id_item'");
              if($unit->num_rows() > 0){
                $jum = $unit->row();
                $total_unit = $jum->nosin;
                $tt = $jum->nosin;
              }else{
                $total_unit = 0;
                $tt = 0;
              }


              echo "
              <tr>
                <td>$no</td>
                <td>$row->no_shipping_list</td>                
                <td>$row->tipe_ahm</td>                
                <td>$row->warna</td>                
                <td>$row->id_item</td>                
                <td>$total_unit Unit</td>                
                <td>";
                $cek = $this->db->query("SELECT id_ksu FROM ms_koneksi_ksu_detail INNER JOIN ms_koneksi_ksu ON ms_koneksi_ksu.id_koneksi_ksu = ms_koneksi_ksu_detail.id_koneksi_ksu WHERE ms_koneksi_ksu.id_tipe_kendaraan = '$row->tipe_motor'");
                if(count($cek) > 0){
                  $isi = $cek->row();
                  foreach ($cek->result() as $isi) {                    
                    $cek2 = $this->db->query("SELECT id_ksu,ksu FROM ms_ksu WHERE id_ksu = '$isi->id_ksu'");
                    if(count($cek2) > 0){
                      $rd = $cek2->row();
                      $rty = $this->db->query("SELECT * FROM tr_penerimaan_ksu WHERE id_ksu = '$rd->id_ksu' AND no_sl = '$row->no_shipping_list' AND id_tipe_kendaraan = '$row->tipe_motor'");
                      if($rty->num_rows() == 0){
                        echo "
                           <div class='input-group'>
                            <span class='input-group-addon bg-maroon'>$rd->ksu</span>                         
                            <input type='hidden' name='id_ksu[]' value='$rd->id_ksu'>
                            <input type='hidden' name='tipe_motor[]' value='$row->tipe_motor'>
                            <input type='hidden' name='no_sl[]' value='$row->no_shipping_list'>
                            <input type='text' onkeypress='return number_only(event)' value='$tt' name='qty[]' class='input-group-addon input-block' style='width:50px;'>
                          </div> <br>";                        
                      }else{
                        $ui = $rty->row();
                        echo "
                           <div class='input-group'>
                            <span class='input-group-addon bg-maroon'>$rd->ksu</span>                         
                            <input type='hidden' name='id_ksu[]' value='$rd->id_ksu'>
                            <input type='hidden' name='tipe_motor[]' value='$row->tipe_motor'>
                            <input type='hidden' name='no_sl[]' value='$row->no_shipping_list'>
                            <input type='text' onkeypress='return number_only(event)' value='$ui->qty' name='qty[]' class='input-group-addon input-block' style='width:50px;'>
                          </div> <br>";                        
                      }
                    }
                  }                  
                }
                echo "
                </td>                
              </tr>
              ";
              $no++;
            }
            ?>
            </form>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php
    }elseif($set == 'cetak_accu'){
      $ksu = $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_sppm ON tr_surat_jalan.no_surat_sppm = tr_sppm.no_surat_sppm                 
        WHERE tr_surat_jalan.id_surat_jalan = '$_GET[id]'")->row();
      $sj = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan = '$ksu->no_surat_jalan'")->row();
      if(isset($sj)){
        $tgl = $sj->tgl_penerimaan;
      }else{
        $tgl = "";
      }
      $row = $dt_ksu->row();
      if(isset($row->id_penerimaan_unit_dealer)){
        $id_p = $row->id_penerimaan_unit_dealer;
      }else{
        $id_p = "";
      }
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/konfirmasi_pu">
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
        <div id="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="dealer/konfirmasi_pu/save_ksu" method="post" enctype="multipart/form-data">              
            </form>                
          </div>
        </div>
        <table id="" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>Kode KSU</th>              
              <th>Aksesoris</th>              
              <th>Qty Terima</th>
              <th width="5%">Aksi</th>              
            </tr>
          </thead>
          <tbody>            
            <?php 
            $no = 1;
            foreach ($ksu_d->result() as $row) {                            
              // $item = $this->db->query("SELECT * FROM ms_item WHERE id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND id_warna = '$row->id_warna'")->row();
              $id_ksu = $this->db->query("SELECT * FROM ms_ksu WHERE id_ksu = '$row->id_ksu'")->row();
              $id_p = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer INNER JOIN tr_surat_jalan ON tr_penerimaan_unit_dealer.no_surat_jalan=tr_surat_jalan.no_surat_jalan 
                 WHERE tr_surat_jalan.id_surat_jalan = '$id_surat_jalan'");
              if($id_p->num_rows() > 0){
                $t = $id_p->row();
                $id_penerimaan_unit_dealer = $t->id_penerimaan_unit_dealer;
              }else{
                $id_penerimaan_unit_dealer = "";
              }
              $cek = $this->db->query("SELECT * FROM tr_penerimaan_ksu_dealer WHERE id_penerimaan_unit_dealer = '$id_penerimaan_unit_dealer' 
                    AND id_ksu = '$row->id_ksu'");
              if($cek->num_rows() > 0){
                $i = $cek->row();
                $isi = $i->qty_terima;
              }else{
                $isi = "";
              }
              if($row->jum > 0){
                echo "
                <tr>
                  <td>$no</td>
                  <td>$row->id_ksu</td>                
                  <td>$row->ksu</td>                
                  <td>$isi</td>
                  <td>
                    <a href='dealer/konfirmasi_pu/cetak_act?id=$row->id_ksu&id_p=$id_penerimaan_unit_dealer' class='btn btn-primary btn-xs btn-flat'><i class='fa fa-print'></i> cetak</a>
                  </td>                                                  
                </tr>
                ";
                $no++;
              }
            }
            ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->      
    </div><!-- /.box -->
    </form>


    <?php
    }elseif($set=="gudang"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/konfirmasi_pu/unit?id=<?php echo $_GET['id'] ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
        <div id="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="dealer/konfirmasi_pu/gudang_save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Gudang</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="Nama Gudang" name="gudang">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kapasitas</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="Kapasitas" name="kapasitas">                    
                  </div>
                </div>                
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label"></label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="active" value="1" checked>
                      Active
                    </div>
                  </div>                  
                </div>                                                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>Gudang</th>              
              <th>Kapasitas</th>              
              <th width="10%">Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          $id_dealer = $this->m_admin->cari_dealer();
          $dt_gudang = $this->db->query("SELECT * FROM ms_gudang_dealer WHERE id_dealer = '$id_dealer' AND active = 1");
          foreach($dt_gudang->result() as $row) {               
            echo "
            <tr>
              <td>$no</td>
              <td>$row->gudang</td>              
              <td>$row->kapasitas </td>            
              <td>";
              ?>
                <a data-toggle='tooltip' <?php echo $this->m_admin->set_tombol($id_menu,$group,"delete"); ?> title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="dealer/konfirmasi_pu/gudang_delete?id=<?php echo $_GET['id'] ?>&idg=<?php echo $row->id_gudang_dealer ?>"><i class="fa fa-trash-o"></i></a>
                <a data-toggle='tooltip' <?php echo $this->m_admin->set_tombol($id_menu,$group,"edit"); ?> title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='dealer/konfirmasi_pu/gudang_edit?id=<?php echo $_GET['id'] ?>&idg=<?php echo $row->id_gudang_dealer ?>'><i class='fa fa-edit'></i></a>
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
    }elseif($set=="gudang_show_on_menu_lokasi_penyimpanan"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/konfirmasi_pu/gudang_show">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
        <div id="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="dealer/konfirmasi_pu/gudang_save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                 
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Gudang</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="Nama Gudang" name="gudang">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kapasitas</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="Kapasitas" name="kapasitas">                    
                  </div>
                </div>                
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label"></label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="active" value="1" checked>
                      Active
                    </div>
                  </div>                  
                </div>                                                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>Gudang</th>              
              <th>Kapasitas</th>              
              <th width="10%">Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          $id_dealer = $this->m_admin->cari_dealer();
          $dt_gudang = $this->db->query("SELECT * FROM ms_gudang_dealer WHERE id_dealer = '$id_dealer' AND active = 1");
          foreach($dt_gudang->result() as $row) {               
            echo "
            <tr>
              <td>$no</td>
              <td>$row->gudang</td>              
              <td>$row->kapasitas </td>            
              <td>";
              ?>
                <a data-toggle='tooltip' <?php echo $this->m_admin->set_tombol($id_menu,$group,"delete"); ?> title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="dealer/konfirmasi_pu/gudang_delete?id=&idg=<?php echo $row->id_gudang_dealer ?>"><i class="fa fa-trash-o"></i></a>
                <a data-toggle='tooltip' <?php echo $this->m_admin->set_tombol($id_menu,$group,"edit"); ?> title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='dealer/konfirmasi_pu/gudang_edit?id=&idg=<?php echo $row->id_gudang_dealer ?>'><i class='fa fa-edit'></i></a>
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
    }elseif($set=="gudang_edit"){
      $row = $dt_gudang->row();
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/konfirmasi_pu/gudang?id=<?php echo $_GET['id'] ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
        <div id="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="dealer/konfirmasi_pu/gudang_update" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <input type="hidden" name="idg" value="<?php echo $row->id_gudang_dealer ?>">
                  <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Gudang</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" value="<?php echo $row->gudang ?>" placeholder="Nama Gudang" name="gudang">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kapasitas</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" value="<?php echo $row->kapasitas ?>" placeholder="Kapasitas" name="kapasitas">                    
                  </div>
                </div>                
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label"></label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->active=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="active" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="active" value="1">
                      <?php } ?>
                      Active
                    </div>
                  </div>                  
                </div>                                                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>Gudang</th>              
              <th>Kapasitas</th>              
              <th width="10%">Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          $id_dealer = $this->m_admin->cari_dealer();
          $dt_gudang = $this->db->query("SELECT * FROM ms_gudang_dealer WHERE id_dealer = '$id_dealer' AND active = 1");
          foreach($dt_gudang->result() as $row) {               
            echo "
            <tr>
              <td>$no</td>
              <td>$row->gudang</td>              
              <td>$row->kapasitas</td>"; ?>            
              <td>                                
                <a data-toggle='tooltip' <?php echo $this->m_admin->set_tombol($id_menu,$group,"delete"); ?> title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="dealer/konfirmasi_pu/gudang_delete?id=<?php echo $_GET['id'] ?>&idg=<?php echo $row->id_gudang_dealer ?>"><i class="fa fa-trash-o"></i></a>
                <a data-toggle='tooltip' <?php echo $this->m_admin->set_tombol($id_menu,$group,"edit"); ?> title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='dealer/konfirmasi_pu/gudang_edit?id=<?php echo $_GET['id'] ?>&idg=<?php echo $row->id_gudang_dealer ?>'><i class='fa fa-edit'></i></a>
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

<div class="modal fade" id="Scanmodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search Item
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body" id="showScan">
        
      </div>      
    </div>
  </div>
</div>

<script type="text/javascript">
function auto(){  
  set_rfs();  
  var no_sj = document.getElementById("id_surat_jalan").value; 
  $.ajax({
      url : "<?php echo site_url('dealer/konfirmasi_pu/cari_id')?>",
      type:"GET",
      data:"no_sj="+no_sj,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");        
        if(data[2] != 'nihil'){
          $("#id_penerimaan_unit_dealer").val(data[2]);                  
          $("#mode").val("edit");   
          $("#jenis_pu").val("rfs");                                       
          kirim_data();
        }else if(data[1] != 'nihil'){
          //alert("Terdapat transaksi data sebelumnya yg belum selesai dg ID Penerimaan "+data[1]+". Hapus data sebelumnya dan mulai transaksi data baru?");
          //hapus_auto(data[1]);                            
          $("#id_penerimaan_unit_dealer").val(data[1]);                            
          $("#mode").val("new");  
          $("#jenis_pu").val("rfs");                        
          kirim_data();
        }else{
          $("#id_penerimaan_unit_dealer").val(data[0]);                  
          $("#jenis_pu").val(""); 
          //$("#tampil_data").hide();
          //$("#scan_nosin").hide();
          $("#mode").val("new");        
          $("#jenis_pu").val("rfs");                                  
          kirim_data();
        }
      }        
  })
}
function set_rfs(){    
  $("#jenis_pu").val("rfs");
  kirim_data();
  $("#scan_nosin").show();
}
function set_nrfs(){    
  $("#jenis_pu").val("nrfs");
  kirim_data();
  $("#scan_nosin").show();
}
function kirim_data(){    
  $("#tampil_data").show();  
  var id_pu = document.getElementById("id_penerimaan_unit_dealer").value;   
  var jenis_pu = document.getElementById("jenis_pu").value;   
  var no_sj = document.getElementById("no_surat_jalan").value;   
  var id_sj = document.getElementById("id_surat_jalan").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_pu="+id_pu+"&jenis_pu="+jenis_pu+"&no_sj="+no_sj+"&id_sj="+id_sj;                           
     xhr.open("POST", "dealer/konfirmasi_pu/t_data", true); 
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
function choose_nosin(no_mesin){
  document.getElementById("no_mesin").value = no_mesin;   
  simpan_nosin();
  $("#Scanmodal").modal("hide");
}
function simpan_nosin(){
  var id_pu       = document.getElementById("id_penerimaan_unit_dealer").value;  
  var no_mesin    = document.getElementById("no_mesin").value;     
  var jenis_pu    = document.getElementById("jenis_pu").value;     
  var id_sj       = document.getElementById("id_surat_jalan").value;     
  //alert(id_po);
  var panjang = no_mesin.length;
  if ((id_pu == "" || no_mesin == "") && panjang != 12) {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else if (panjang != 12) {    
      alert("Tuliskan No Mesin dengan benar...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('dealer/konfirmasi_pu/save_nosin')?>",
          type:"POST",
          data:"no_mesin="+no_mesin+"&id_pu="+id_pu+"&jenis_pu="+jenis_pu+"&id_sj="+id_sj,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="ok"){
                  kirim_data();
                  kosong_nosin();                
              }else{
                  alert("No Mesin tersebut sudah di-scan sebelumnya");
                  kosong_nosin();                                
              }
              // else if(data[0]=="sudah"){
              
              // }                
          }
      })    
  }
}
function kosong_nosin(args){
  $("#no_mesin").val("");  
}
function hapus_data(a,b,c){ 
    var id_pu     = a;       
    var no_mesin  = b;       
    var mode      = c;       
    $.ajax({
        url : "<?php echo site_url('dealer/konfirmasi_pu/delete_data')?>",
        type:"POST",
        data:"id_pu="+id_pu+"&no_mesin="+no_mesin+"&mode="+mode,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data();
            }
        }
    })
}
function hapus_auto(a){
  var id_p = a;
  $.ajax({
      url : "<?php echo site_url('dealer/konfirmasi_pu/hapus_auto')?>",
      type:"POST",
      data:"id_p="+id_p,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        auto();
        window.location.reload();
      }        
  })
}
</script>
<script type="text/javascript">
var no_mesin = document.getElementById("no_mesin");
no_mesin.addEventListener("keydown", function (e) {
    if (e.keyCode === 13) {  //checks whether the pressed key is "Enter"
      simpan_nosin();
      //alert("ok");

    }
});


function getScanModal()
{
    var value={scan:'ya',
               id:$("#id_surat_jalan").val()
      }
  $.ajax({
       beforeSend: function() { $('#loading-status').show(); },
       url:"<?php echo site_url('dealer/konfirmasi_pu/getScanModal')?>",
       type:"POST",
       data:value,
       cache:false,
       success:function(html){
          $('#loading-status').hide();
          $('#Scanmodal #showScan').html(html);
          $('#Scanmodal').modal('show');
          datatables();
       },
       statusCode: {
    500: function() {
      $('#loading-status').hide();
      alert("Something Wen't Wrong");
    }
  }
  });
}

</script>