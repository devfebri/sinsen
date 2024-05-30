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
<body onload="take_kec()">
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
      $row = $dt_faktur->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/pengajuan_bbn_md">
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
            <form class="form-horizontal" action="h1/pengajuan_bbn_md/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" name="nama_dealer" value="<?php echo $row->nama_dealer ?>" readonly placeholder="Nama Dealer" class="form-control">
                  </div>                                    
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No BASTD</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_bastd" value="<?php echo $row->no_bastd ?>" readonly placeholder="NO BASTD" class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl BASTD</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_bastd" placeholder="Tgl BASTD" value="<?php echo $row->tgl_bastd ?>" readonly  class="form-control">
                  </div>                                
                </div>
                

                <table id="example4" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th width="1%">Aksi</th>
                      <th>Nama Konsumen</th>
                      <th>Alamat Konsumen</th>
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                      <th>No Faktur AHM</th>
                      <th>Tipe</th>
                      <th>Warna</th>
                      <th>Tahun</th>
                      <th>Harga BBN</th>
                      <th>Tgl Jual</th>
                      <th>Tgl Mohon Samsat</th>
                      <th>Kesalahan Disengaja</th>                    
                    </tr>
                  </thead>
                  <tbody>                    
                      <?php   
                      $no = 1;                      
                      foreach($dt_stnk->result() as $row) {                                                                                                   
                        $er = $this->db->query("SELECT * FROM tr_spk INNER JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
                            WHERE tr_spk.no_spk = '$row->no_spk'");
                        if($er->num_rows() > 0){
                          $ts = $er->row();
                          $nama_konsumen = $ts->nama_konsumen;
                          $alamat = $ts->alamat;
                        }else{
                          $nama_konsumen = "";
                          $alamat = "";
                        }
                        $re = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$row->no_mesin)->row();

                        $nosin_spasi = substr_replace($row->no_mesin," ", 5, -strlen($row->no_mesin));
                        $rw = $this->m_admin->getByID("tr_fkb","no_mesin",$nosin_spasi);
                        if($rw->num_rows() > 0){
                          $ry = $rw->row();
                          $no_fkb = $ry->nomor_faktur;
                          $tahun_produksi = $ry->tahun_produksi;
                        }else{
                          $no_fkb = "";
                          $tahun_produksi = "";
                        }

                        $tipe = $this->db->query("SELECT * FROM ms_item INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                          INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
                          WHERE ms_item.id_tipe_kendaraan = '$re->tipe_motor'");
                        if($tipe->num_rows() > 0){
                          $rq = $tipe->row();
                          $tipe_motor = $rq->tipe_ahm;
                          $warna = $rq->warna;
                        }else{
                          $tipe_motor = "";
                          $warna = "";
                        }

                        $ra = $this->m_admin->getByID("tr_sales_order","no_mesin",$row->no_mesin);
                        if($ra->num_rows() > 0){
                          $rp = $ra->row();
                          $tgl_cetak_invoice = $rp->tgl_cetak_invoice;
                        }else{
                          $tgl_cetak_invoice = 0;
                        }

                        $cek  = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail WHERE no_bastd = '$row->no_bastd' AND no_mesin = '$re->no_mesin'");
                        if($cek->num_rows() > 0){ 
                          $isi = $cek->row();

                          $tipe = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_item ON tr_scan_barcode.id_item=ms_item.id_item 
                            INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                            INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
                            WHERE tr_scan_barcode.no_mesin = '$isi->no_mesin'");
                          if($tipe->num_rows() > 0){
                            $rq = $tipe->row();
                            $tipe_motor = $rq->tipe_ahm;
                            $warna = $rq->warna;
                          }else{
                            $tipe_motor = "";
                            $warna = "";
                          }
                          ?>
                          <tr>             
                            <td align="center">
                              <a class="btn btn-flat btn-primary btn-sm" href="h1/pengajuan_bbn_md/edit?id=<?php echo $row->id_sales_order ?>&b=<?php echo $row->no_bastd ?>&no=<?php echo $isi->no_mesin ?>">Edit</a>
                            </td> 
                            <?php
                            echo "
                            <td>$isi->nama_konsumen</td> 
                            <td>$isi->alamat</td> 
                            <td>$isi->no_mesin</td> 
                            <td>$isi->no_rangka</td> 
                            <td>$isi->no_faktur</td> 
                            <td>$tipe_motor</td>
                            <td>$warna</td>
                            <td>$isi->tahun</td> 
                            <td>".mata_uang($isi->biaya_bbn)."</td>                           
                            <td>$tgl_cetak_invoice</td>                                
                            <td>"; ?>
                              <?php echo $isi->tgl_mohon_samsat ?>
                              <!-- <input type="text" placeholder="yyyy-mm-dd" value="<?php echo $isi->tgl_mohon_samsat ?>" name="tgl_mohon_samsat[]" id="tanggal<?php echo $no ?>"  style="width:80px;"> -->
                            </td>      
                            <td align='center'>
                                <?php 
                              if($isi->sengaja=='1'){
                              ?>
                              <input type="checkbox" class="flat-red" name="sengaja[]" value="1" checked disabled>
                              <?php }else{ ?>
                              <input type="checkbox" class="flat-red" name="sengaja[]" value="1">
                              <?php } ?>
                            </td>                                
                                                           
                          </tr>
                        <?php
                        }else{ ?>
                          <tr>             
                            <td align="center">
                              <a class="btn btn-flat btn-primary btn-sm" href="h1/pengajuan_bbn_md/edit?id=<?php echo $row->id_sales_order ?>&b=<?php echo $row->no_bastd ?>&no=<?php echo $re->no_mesin ?>">Edit</a>
                            </td> 
                            <?php
                            echo "
                            <td>$nama_konsumen</td> 
                            <td>$alamat</td> 
                            <td>$re->no_mesin</td> 
                            <td>$re->no_rangka</td> 
                            <td>$no_fkb</td> 
                            <td>$tipe_motor</td>
                            <td>$warna</td>
                            <td>$tahun_produksi</td> 
                            <td>".number_format($row->biaya_bbn, 0, ',', '.')."</td>                           
                            <td>$tgl_cetak_invoice</td>                                
                            <td>"; ?>
                              <!-- <input type="text" placeholder="yyyy-mm-dd" name="tgl_mohon_samsat[]" id="tanggal<?php echo $no ?>"  style="width:80px;"> -->
                            </td>      
                            <td align='center'>                            
                              <!-- <input type='checkbox' name='sengaja[]'> -->
                            </td>                                
                                                           
                          </tr>
                          <?php
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
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="submit" onclick="return confirm('Are you sure to cancel all data?')" name="save" value="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                                  
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=='reject'){
      $row = $dt_faktur->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/pengajuan_bbn_md">
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
            <form class="form-horizontal" action="h1/pengajuan_bbn_md/save_reject" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor Retur</label>
                  <div class="col-sm-4">
                    <input readonly type="text" name="no_retur"  placeholder="Nomor Retur" value="<?php echo $this->m_admin->cari_id('tr_pengajuan_bbn','id_pengajuan_bbn') ?>" class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Retur</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_retur" placeholder="Tgl Retur" value="<?php echo date("Y-m-d") ?>" id="tanggal" class="form-control">
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor BASTD</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_bastd" value="<?php echo $row->no_bastd ?>" placeholder="Nomor BASTD" readonly class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Alasan Retur</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="alasan_retur">
                      <option value="">- choose -</option>
                      <?php 
                      $dt_retur = $this->m_admin->getSortCond("ms_alasan_return","alasan_return","ASC");
                      foreach ($dt_retur->result() as $isi) {
                        echo "<option>$isi->alasan_return</option>";
                      }
                      ?>
                    </select>
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" name="keterangan" placeholder="Keterangan" class="form-control">
                  </div>                                    
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" name="nama_dealer" value="<?php echo $row->nama_dealer ?>" placeholder="Nama Dealer" readonly class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" name="alamat_dealer" value="<?php echo $row->alamat ?>" placeholder="Alamat Dealer" readonly class="form-control">
                  </div>                  
                </div>
                

                <table class='table table-bordered table-hover' id="example1">
                  <thead>
                    <tr>                    
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                      <th>Nama Konsumen</th>
                      <th>Tipe</th>
                      <th>Warna</th>
                      <th>Tahun</th>                    
                    </tr>
                  </thead>
                  <tbody>
                  <?php 
                  foreach ($dt_stnk->result() as $key) {
                    $re = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
                        INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan = ms_item.id_tipe_kendaraan
                        INNER JOIN ms_warna ON ms_warna.id_warna = ms_item.id_warna WHERE tr_scan_barcode.no_mesin='$key->no_mesin'")->row();
                    $nosin_spasi = substr_replace($key->no_mesin," ", 5, -strlen($key->no_mesin));
                    $rw = $this->m_admin->getByID("tr_fkb","no_mesin",$nosin_spasi)->row();
                  echo "                                      
                    <tr>                                        
                      <td>$key->no_mesin</td>
                      <td>$key->no_rangka</td>
                      <td>$key->nama_konsumen</td>
                      <td>$re->tipe_ahm</td>
                      <td>$re->warna</td>
                      <td>$rw->tahun_produksi</td>                    
                    </tr>";                  
                  }
                  ?>
                  </tbody>
                  
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
    }elseif($set=='cetak_faktur'){
      $row = $dt_faktur->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/pengajuan_bbn_md">
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
            <form class="form-horizontal" action="h1/pengajuan_bbn_md/save_reject" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor BASTD</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_bastd" value="<?php echo $row->no_bastd ?>" placeholder="Nomor BASTD" readonly class="form-control">
                  </div>                                    
                </div>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" name="nama_dealer" value="<?php echo $row->nama_dealer ?>" placeholder="Nama Dealer" readonly class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" name="alamat_dealer" value="<?php echo $row->alamat ?>" placeholder="Alamat Dealer" readonly class="form-control">
                  </div>                  
                </div>
                

                <table class='table table-bordered table-hover' id="example1">
                  <thead>
                    <tr>                    
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                      <th>Nama Konsumen</th>
                      <th>Tipe</th>
                      <th>Warna</th>
                      <th>Tahun</th>
                      <th width="5%">Action</th>                    
                    </tr>
                  </thead>
                  <tbody>
                  <?php 
                  foreach ($dt_stnk->result() as $key) {
                    $re = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
                        INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan = ms_item.id_tipe_kendaraan
                        INNER JOIN ms_warna ON ms_warna.id_warna = ms_item.id_warna WHERE tr_scan_barcode.no_mesin='$key->no_mesin'")->row();
                    $nosin_spasi = substr_replace($key->no_mesin," ", 5, -strlen($key->no_mesin));
                    $rw = $this->m_admin->getByID("tr_fkb","no_mesin",$nosin_spasi)->row();
                    if(isset($rw->tahun_produksi)){
                      $tahun_produksi = $rw->tahun_produksi;
                    }else{
                      $tahun_produksi = "";
                    }
                  echo "                                      
                    <tr>                                        
                      <td>$key->no_mesin</td>
                      <td>$key->no_rangka</td>
                      <td>$key->nama_konsumen</td>
                      <td>$re->tipe_ahm</td>
                      <td>$re->warna</td>
                      <td>$tahun_produksi</td>  
                      <td>
                        <a href='h1/pengajuan_bbn_md/cetak_faktur_act?id=$key->no_mesin&id2=$row->no_bastd' type='button' class='btn btn-flat btn-primary btn-sm' target='_blank'><i class='fa fa-print'></i></a>
                      </td>
                    </tr>";                  
                  }
                  ?>
                  </tbody>
                  
                </table>
                <?php /* ?>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
                </div>
              </div><!-- /.box-footer --> <?php */ ?>
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <?php 
    }elseif($set=='cetak_tagihan_ubahnama_stnk'){
      $row = $dt_faktur->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/pengajuan_bbn_md">
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
            <form class="form-horizontal" action="h1/pengajuan_bbn_md/save_reject" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor BASTD</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_bastd" value="<?php echo $row->no_bastd ?>" placeholder="Nomor BASTD" readonly class="form-control">
                  </div>                                    
                </div>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" name="nama_dealer" value="<?php echo $row->nama_dealer ?>" placeholder="Nama Dealer" readonly class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" name="alamat_dealer" value="<?php echo $row->alamat ?>" placeholder="Alamat Dealer" readonly class="form-control">
                  </div>                  
                </div>
                

                <table class='table table-bordered table-hover' id="example1">
                  <thead>
                    <tr>                    
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                      <th>Nama Konsumen</th>
                      <th>Tipe</th>
                      <th>Warna</th>
                      <th>Tahun</th>
                      <th width="5%">Action</th>                    
                    </tr>
                  </thead>
                  <tbody>
                  <?php 
                  foreach ($dt_tagihan->result() as $key) {
                    if ($key->sengaja=='1') {
                                $re = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
                        INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan = ms_item.id_tipe_kendaraan
                        INNER JOIN ms_warna ON ms_warna.id_warna = ms_item.id_warna WHERE tr_scan_barcode.no_mesin='$key->no_mesin'")->row();
                    $nosin_spasi = substr_replace($key->no_mesin," ", 5, -strlen($key->no_mesin));
                    $rw = $this->m_admin->getByID("tr_fkb","no_mesin",$nosin_spasi)->row();
                  echo "                                      
                    <tr>                                        
                      <td>$key->no_mesin</td>
                      <td>$key->no_rangka</td>
                      <td>$key->nama_konsumen</td>
                      <td>$re->tipe_ahm</td>
                      <td>$re->warna</td>
                      <td>$rw->tahun_produksi</td>  
                      <td>
                        <a href='h1/pengajuan_bbn_md/cetak_tagihan_ubahnama_stnk_act?id=$key->no_mesin' type='button' class='btn btn-flat btn-primary btn-sm' target='_blank'><i class='fa fa-print'></i></a>
                      </td>
                    </tr>";        
                              }          
                  }
                  ?>
                  </tbody>
                  
                </table>
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=='cetak_permohonan'){
      $row = $dt_faktur->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/pengajuan_bbn_md">
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
            <form class="form-horizontal" action="h1/pengajuan_bbn_md/save_reject" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor BASTD</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_bastd" value="<?php echo $row->no_bastd ?>" placeholder="Nomor BASTD" readonly class="form-control">
                  </div>                                    
                </div>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" name="nama_dealer" value="<?php echo $row->nama_dealer ?>" placeholder="Nama Dealer" readonly class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" name="alamat_dealer" value="<?php echo $row->alamat ?>" placeholder="Alamat Dealer" readonly class="form-control">
                  </div>                  
                </div>
                

                <table class='table table-bordered table-hover' id="example1">
                  <thead>
                    <tr>                    
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                      <th>Nama Konsumen</th>
                      <th>Tipe</th>
                      <th>Warna</th>
                      <th>Tahun</th>
                      <th width="5%">Action</th>                    
                    </tr>
                  </thead>
                  <tbody>
                  <?php 
                  foreach ($dt_stnk->result() as $key) {
                    $re = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
                        INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan = ms_item.id_tipe_kendaraan
                        INNER JOIN ms_warna ON ms_warna.id_warna = ms_item.id_warna WHERE tr_scan_barcode.no_mesin='$key->no_mesin'")->row();
                    $nosin_spasi = substr_replace($key->no_mesin," ", 5, -strlen($key->no_mesin));
                    $rw = $this->m_admin->getByID("tr_fkb","no_mesin",$nosin_spasi)->row();
                  echo "                                      
                    <tr>                                        
                      <td>$key->no_mesin</td>
                      <td>$key->no_rangka</td>
                      <td>$key->nama_konsumen</td>
                      <td>$re->tipe_ahm</td>
                      <td>$re->warna</td>
                      <td>$rw->tahun_produksi</td>  
                      <td>
                        <a href='h1/pengajuan_bbn_md/cetak_permohonan_act?id=$key->no_mesin&id2=$key->no_bastd' type='button' class='btn btn-flat btn-primary btn-sm' target='_blank'><i class='fa fa-print'></i></a>
                      </td>
                    </tr>";                  
                  }
                  ?>
                  </tbody>
                  
                </table>
                <?php /* ?>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
                </div>
              </div><!-- /.box-footer --> <?php */ ?>
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=='cetak_pendaftaran_bpkb'){
      $row = $dt_faktur->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/pengajuan_bbn_md">
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
                        
              <div class="box-body">       
                <br>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor BASTD</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_bastd" value="<?php echo $row->no_bastd ?>" placeholder="Nomor BASTD" readonly class="form-control">
                  </div>                                    
                </div>                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" name="nama_dealer" value="<?php echo $row->nama_dealer ?>" placeholder="Nama Dealer" readonly class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" name="alamat_dealer" value="<?php echo $row->alamat ?>" placeholder="Alamat Dealer" readonly class="form-control">
                  </div>                  
                </div>
                

                <table class='table table-bordered table-hover' id="example1">
                  <thead>
                    <tr>                    
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                      <th>Nama Konsumen</th>
                      <th>Tipe</th>
                      <th>Warna</th>
                      <th>Tahun</th>
                      <th width="5%">Action</th>                    
                    </tr>
                  </thead>
                  <tbody>
                  <?php 
                  foreach ($dt_stnk->result() as $key) {
                    $re = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
                        INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan = ms_item.id_tipe_kendaraan
                        INNER JOIN ms_warna ON ms_warna.id_warna = ms_item.id_warna WHERE tr_scan_barcode.no_mesin='$key->no_mesin'")->row();
                    $nosin_spasi = substr_replace($key->no_mesin," ", 5, -strlen($key->no_mesin));
                    $rw = $this->m_admin->getByID("tr_fkb","no_mesin",$nosin_spasi)->row();
                  echo "                                      
                    <tr>                                        
                      <td>$key->no_mesin</td>
                      <td>$key->no_rangka</td>
                      <td>$key->nama_konsumen</td>
                      <td>$re->tipe_ahm</td>
                      <td>$re->warna</td>
                      <td>$rw->tahun_produksi</td>  
                      <td>
                        <a href='h1/pengajuan_bbn_md/cetak_pendaftaran_bpkb_act?id=$key->no_mesin' type='button' class='btn btn-flat btn-primary btn-sm' target='_blank'><i class='fa fa-print'></i></a>
                      </td>
                    </tr>";                  
                  }
                  ?>
                  </tbody>
                  
                </table>
                <?php /* ?>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
                </div>
              </div><!-- /.box-footer --> <?php */ ?>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <?php 
    }elseif($set=='edit'){
      $row = $dt_so->row();      

      $nama_konsumen = $row->nama_konsumen;
      $alamat = $row->alamat;
      $no_telp = $row->no_telp;
      $no_npwp = $row->npwp;      

      $ra = $this->m_admin->getByID("tr_sales_order","no_mesin",$row->no_mesin)->row();
      
      $nosin_spasi = substr_replace($row->no_mesin," ", 5, -strlen($row->no_mesin));
      $rw = $this->m_admin->getByID("tr_fkb","no_mesin",$nosin_spasi);
      if($rw->num_rows() > 0){
        $ry = $rw->row();
        $no_fkb = $ry->nomor_faktur;
        $tahun_produksi = $ry->tahun_produksi;
      }else{
        $no_fkb = "";
        $tahun_produksi = "";
      }
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/pengajuan_bbn_md/check?id=<?php echo $no_bastd ?>">
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
            <form class="form-horizontal" action="h1/pengajuan_bbn_md/update" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_tipe_kendaraan" value="<?php echo $row->id_tipe_kendaraan ?>">
                    <input type="hidden" name="id_warna" value="<?php echo $row->id_warna ?>">
                    <input type="hidden" name="no_bastd" value="<?php echo $row->no_bastd ?>">
                    <input type="hidden" name="tgl_jual" value="<?php echo $ra->tgl_cetak_invoice ?>">
                    <input type="hidden" name="tahun" value="<?php echo $tahun_produksi ?>">
                    <input type="hidden" name="no_faktur" value="<?php echo $no_fkb ?>">
                    <input type="hidden" name="biaya_bbn" value="<?php echo $row->biaya_bbn ?>">
                    <input type="text"  readonly name="no_mesin" value="<?php echo $row->no_mesin ?>" placeholder="No Mesin" class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Rangka</label>
                  <div class="col-sm-4">
                    <input type="text"  readonly name="no_rangka" value="<?php echo $row->no_rangka ?>" placeholder="No Rangka" value="" class="form-control">
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">
                    <input type="text" name="tipe" placeholder="Tipe" value="<?php echo $row->tipe_ahm ?>" readonly class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" name="warna" autocomplete="false" value="<?php echo $row->warna ?>" placeholder="Warna"  class="form-control">
                  </div>                  
                </div>
                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $nama_konsumen ?>" name="nama_konsumen" placeholder="Nama Konsumen" class="form-control">
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tempat/Tanggal Lahir</label>
                  <div class="col-sm-4">
                    <input type="text" name="tempat_lahir" value="<?php echo $row->tempat_lahir ?>" placeholder="Tempat Lahir" class="form-control">
                  </div>                  
                  <div class="col-sm-4">
                    <input type="text" name="tgl_lahir" placeholder="Tanggal Lahir" value="<?php echo $row->tgl_lahir ?>" id="tanggal2" class="form-control">
                  </div>                  
                </div>                               
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required name="id_kelurahan" id="id_kelurahan" onchange="take_kec()">
                      <option value="<?php echo $row->id_kelurahan   ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_kelurahan","id_kelurahan",$row->id_kelurahan)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->kelurahan;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      foreach($dt_kelurahan->result() as $val) {
                        echo "
                        <option value='$val->id_kelurahan'>$val->kelurahan</option>;
                        ";
                      }
                      ?>
                    </select>
                    <!-- <input type="hidden" name="id_kelurahan" id="id_kelurahan">
                    <input type="text" class="form-control"  id="kelurahan" placeholder="Kelurahan Domisili"  name="kelurahan">                                         -->
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kecamatan" id="id_kecamatan">
                    <input type="text" class="form-control" id="kecamatan" placeholder="Kecamatan"  name="kecamatan">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kabupaten" id="id_kabupaten">
                    <input type="text" class="form-control" placeholder="Kota/Kabupaten" id="kabupaten" name="kabupaten">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_provinsi" id="id_provinsi">
                    <input type="text" class="form-control" placeholder="Provinsi" id="provinsi" name="provinsi">                                        
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-10">
                    <input type="text" name="alamat" placeholder="Alamat" value="<?php echo $row->alamat ?>" class="form-control">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control"  placeholder="No HP" value="<?php echo $row->no_hp ?>" id="no_hp"  name="no_hp">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control"  placeholder="No Telp" value="<?php echo $no_telp ?>"  name="no_telp">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No KTP</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No KTP" value="<?php echo $row->no_ktp ?>" name="no_ktp">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No KK</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No KK" value="<?php echo $row->no_kk ?>" name="no_kk">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No NPWP</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No NPWP" value="<?php echo $no_npwp ?>" name="no_npwp">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Gadis Ibu Kandung</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Nama Gadis Ibu Kandung" value="<?php echo $row->nama_ibu ?>" name="nama_ibu">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Lahir Ibu Kandung</label>
                  <div class="col-sm-4">
                    <input id="tanggal3" type="text" class="form-control" placeholder="Tgl Lahir Ibu Kandung" value="<?php echo $row->tgl_ibu ?>" name="tgl_ibu">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>
                  <div class="col-sm-4">
                  <!--	<select class="form-control select2">
                  		<option>--Choose--</option>
                  		<?php $pn = $this->db->query("SELECT * FROM ms_pekerjaan where active=1 order by pekerjaan ASC"); ?>
                  		<?php foreach ($pn->result() as $pn): ?>

                  			<option value="<?php echo $pn->id_pekerjaan ?>"><?php echo $pn->pekerjaan ?></option>
                  		<?php endforeach ?>
                  	</select> -->
                    <input type="text" class="form-control" placeholder="Pekerjaan" value="<?php echo $row->pekerjaan ?>" name="pekerjaan">                                      
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Pengeluaran 1 Bulan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Pengeluaran 1 Bulan" value="<?php echo $row->penghasilan ?>" name="penghasilan">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Mohon Samsat</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Tgl Mohon Samsat" id="tanggal4" name="tgl_mohon_samsat" value="<?php if(isset($row->tgl_mohon_samsat)){echo $row->tgl_mohon_samsat; } ?>"	>                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kesalahan Disengaja</label>
                  <div class="col-sm-4">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="sengaja" value="1">                    
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Keterangan" id="keterangan_d" name="keterangan_d" value="<?php if(isset($row->keterangan_d)){ echo $row->keterangan_d; } ?>"	>                    
                  </div>
                </div>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=='generate'){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/pengajuan_bbn_md">
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
            <form class="form-horizontal" action="h1/pengajuan_bbn_md/download" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Mohon Samsat</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_mohon_samsat" placeholder="Tgl Mohon Samsat" value="<?php echo date("Y-m-d") ?>" id="tanggal" class="form-control tgl_mohon_samsat">
                  </div> 
                </div>                     
                <div class="form-group">                                              
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Biro Jasa</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required name="nama_biro_jasa" id="nama_biro_jasa">
                      <option value="">- choose -</option>                      
                      <?php 
                      $biro = $this->db->query("SELECT * FROM ms_vendor INNER JOIN ms_vendor_type ON 
                          ms_vendor.id_vendor_type = ms_vendor_type.id_vendor_type
                          WHERE ms_vendor_type.vendor_type = 'Biro Jasa' OR ms_vendor_type.vendor_type = 'biro jasa' OR ms_vendor_type.vendor_type = 'Biro_Jasa'
                          OR ms_vendor_type.vendor_type = 'BiroJasa'");
                      foreach ($biro->result() as $row) {
                        echo "<option value='$row->id_vendor'>$row->vendor_name</option>";
                      }
                      ?>
                    </select>
                  </div>  
                  <div class="col-sm-2">
                    <button class="btn btn-primary" type="button" onclick="generateDetail()">Cek</button>
                    
                  </div>                              
                </div>          
                <div id="showGenerateDetail"></div>
                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to generate all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Generate All</button>
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
          <a href="h1/pengajuan_bbn_md/generate">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-download"></i> Generate File TXT Samsat</button>
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
              <th>No BASTD</th>
              <th>Tgl BASTD</th>
              <th>Nama Dealer</th>              
              <th>Status</th>
              <th width="20%">Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_bbn->result() as $row) {
            $st = $row->status_faktur;
            // <a href='h1/pengajuan_bbn_md/reject?id=$row->no_bastd' class='btn btn-danger btn-flat btn-xs'>Reject</a>
            if($st=='approved'){
              $tombol = "<a href='h1/pengajuan_bbn_md/check?id=$row->no_bastd' class='btn btn-primary btn-flat btn-xs'>Check</a>                
                <a href='h1/pengajuan_bbn_md/cetak_faktur?id=$row->no_bastd' class='btn btn-warning btn-flat btn-xs'>Cetak Faktur</a>
                <a href='h1/pengajuan_bbn_md/cetak_tagihan_ubahnama_stnk?id=$row->no_bastd' class='btn btn-info btn-flat btn-xs'>Cetak Tagihan Permohonan Ubah Nama STNK</a>
                <a href='h1/pengajuan_bbn_md/cetak_permohonan?id=$row->no_bastd' class='btn btn-warning btn-flat btn-xs'>Cetak Permohonan STNK</a>
                <a href='h1/pengajuan_bbn_md/cetak_pendaftaran_bpkb?id=$row->no_bastd' class='btn btn-success btn-flat btn-xs'>Cetak Pendaftaran BPKB</a>";
            }elseif($st=='rejected'){
              $tombol = "";
            }else{
              $tombol = "<a href='h1/pengajuan_bbn_md/cek_approval?id=$row->no_bastd' class='btn btn-primary btn-flat btn-xs'>Approve/Reject</a>";
            }
            $cek = $this->m_admin->getByID("tr_pengajuan_bbn","no_bastd",$row->no_bastd);
            $cek2 = $this->m_admin->getByID("tr_faktur_stnk","no_bastd",$row->no_bastd);            
            if($cek->num_rows() > 0){
              $id_    = $cek->row();
              $status = "<span class='label label-danger'>$id_->status_pengajuan</span>";
            }else{
              $id2    = $cek2->row();              
              if($id2->status_faktur == 'input'){
                $status = "<span class='label label-danger'>$id2->status_faktur</span>";
              }else{
                $status = "<span class='label label-primary'>$id2->status_faktur</span>";
              }              
            }                                         
          echo "          
            <tr>
              <td>$no</td>
              <td>
                <a href='h1/pengajuan_bbn_md/detail?id=$row->no_bastd'>
                  $row->no_bastd
                </a>
              </td> 
              <td>$row->tgl_bastd</td>                           
              <td>$row->nama_dealer</td>                            
              <td>
                $status
              </td>                            
              <td>";
              echo $tombol."</td>";                                      
          $no++;
          }
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php 
    }elseif($set=='detail'){
      $row = $dt_faktur->row();
    ?>
    
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/pengajuan_bbn_md">
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
          <div class="col-md-12">            
            <form class="form-horizontal" enctype="multipart/form-data" action="h1/pengajuan_bbn_md/save_approval" method="post">
              <div class="box-body">                              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No BASTD</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" value="<?php echo $row->no_bastd ?>" placeholder="No BASTD" name="no_bastd">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" value="<?php echo $row->start_date ?>"  placeholder="Start Date" name="start_date">
                  </div>                                  
                </div>                                              
                <div class="form-group">
                  <input type="hidden" name="no_bastd" id="no_bastd" value="<?php echo $row->no_bastd ?>">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl BASTD</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" value="<?php echo $row->tgl_bastd ?>"  placeholder="Tgl BASTD" name="tgl_bastd">
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" value="<?php echo $row->end_date ?>" placeholder="End Date" name="end_date">                    
                  </div>
                </div>                                              
              <div id="row">
                <div class="form-group">                  
                  <table id="example4" class="table myTable1 table-bordered table-hover">
                    <thead>
                      <tr>      
                        <th>No Mesin</th>              
                        <th>No Rangka</th>              
                        <th>Nama Konsumen</th>
                        <th>Alamat</th>
                        <th>Biaya BBN</th>
                        <th>Fotocopy KTP (5)</th>
                        <th>Cek Fisik Kendaraan (2)</th>
                        <th>Hasil Cek Fisik STNK (1)</th>
                        <th>Formulir Data BPKB (1)</th>
                        <th>Surat Kuasa (2)</th>
                        <th>CKD STNK & BPKB (2)</th>
                        <th>Form Permohonan STNK (1)</th>                          
                      </tr>
                    </thead>
                   
                    <tbody>                    
                      <?php   
                      $no = 1;
                      foreach($dt_stnk->result() as $row) {                                                   
                        if($row->ktp == 'ya') $ktp = "checked";      
                          else $ktp = "";        
                        if($row->fisik == 'ya') $fisik = "checked";      
                          else $fisik = "";        
                        if($row->stnk == 'ya') $stnk = "checked";      
                          else $stnk = "";        
                        if($row->bpkb == 'ya') $bpkb = "checked";      
                          else $bpkb = "";        
                        if($row->kuasa == 'ya') $kuasa = "checked";      
                          else $kuasa = "";        
                        if($row->ckd == 'ya') $ckd = "checked";      
                          else $ckd = "";        
                        if($row->permohonan == 'ya') $pem = "checked";      
                          else $pem = "";             
                                    

                        $er = $this->db->query("SELECT * FROM tr_spk INNER JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
                            WHERE tr_spk.no_spk = '$row->no_spk'");
                        if($er->num_rows() > 0){
                          $ts = $er->row();
                          $nama_konsumen = $ts->nama_bpkb;
                          $alamat = $ts->alamat;
                        }else{
                          $nama_konsumen = "";
                          $alamat = "";
                        }
                        $re = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$row->no_mesin)->row();
                        echo "
                        <tr>             
                          <td>$row->no_mesin</td> 
                          <td>$row->no_rangka</td> 
                          <td>$row->nama_konsumen</td> 
                          <td>$row->alamat</td> 
                          <td>".number_format($row->biaya_bbn, 0, ',', '.')."</td> 
                          <td align='center'>                            
                            <input type='checkbox' name='check_ktp[]' $ktp disabled>
                          </td>      
                          <td align='center'>
                            <input type='checkbox' name='check_fisik[]' $fisik disabled>
                          </td>      
                          <td align='center'>
                            <input type='checkbox' name='check_stnk[]' $stnk disabled>
                          </td>      
                          <td align='center'>
                            <input type='checkbox' name='check_bpkb[]' $bpkb disabled>
                          </td>      
                          <td align='center'>
                            <input type='checkbox' name='check_kuasa[]' $kuasa disabled>
                          </td>      
                          <td align='center'>
                            <input type='checkbox' name='check_ckd[]' $ckd disabled>
                          </td>      
                          <td align='center'>
                            <input type='checkbox' name='check_permohonan[]' $pem disabled>
                          </td>      
                        </tr>";
                        $no++;
                        }
                      ?>
                    </tbody>
                  </table>  
                </div>  
              </div>
          </div>                       
        </form>
      </div>
    </div>

    <?php 
    }elseif($set=='cek_approval'){
      $row = $dt_faktur->row();
      $row2 = $dt_stnk->row();
    ?>
    
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/pengajuan_bbn_md">
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
          <div class="col-md-12">            
            <form class="form-horizontal" enctype="multipart/form-data" action="h1/pengajuan_bbn_md/save_approval" method="post">
              <div class="box-body">                              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No BASTD</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" value="<?php echo $row->no_bastd ?>" placeholder="No BASTD" name="no_bastd">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" value="<?php echo $row->start_date ?>"  placeholder="Start Date" name="start_date">
                  </div>                                  
                </div>                                              
                <div class="form-group">
                  <input type="hidden" name="no_bastd" id="no_bastd" value="<?php echo $row->no_bastd ?>">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl BASTD</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" value="<?php echo $row->tgl_bastd ?>"  placeholder="Tgl BASTD" name="tgl_bastd">
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" value="<?php echo $row->end_date ?>" placeholder="End Date" name="end_date">                    
                  </div>
                </div>                                    
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" value="<?php echo $row2->nama_dealer ?>"  placeholder="Nama Dealer" name="nama_dealer">
                  </div>                                  
                </div>          
              <div id="row">
                <div class="form-group">                  
                  <table id="example4" class="table myTable1 table-bordered table-hover">
                    <thead>
                      <tr>    
                        <th width="2%">No</th>  
                        <th>No Mesin</th>              
                        <th>No Rangka</th>              
                        <th>Nama Konsumen</th>
                        <th>Alamat</th>
                        <th>Biaya BBN</th>                        
                      </tr>
                    </thead>
                   
                    <tbody>                    
                      <?php   
                      $no = 1;
                      
                      foreach($get_nosin->result() as $row) {                                                   
                        $cek_pik = $this->db->query("SELECT tr_faktur_stnk_detail.*,tr_scan_barcode.no_rangka,tr_scan_barcode.tipe_motor,tr_spk.tipe_customer
                            FROM tr_faktur_stnk_detail INNER JOIN tr_scan_barcode ON tr_faktur_stnk_detail.no_mesin = tr_scan_barcode.no_mesin                
                            LEFT JOIN tr_spk ON tr_faktur_stnk_detail.no_spk = tr_spk.no_spk
                            WHERE tr_faktur_stnk_detail.no_mesin = '$row->no_mesin'")->row(); 
                        echo "
                        <tr>        
                          <td>$no</td>     
                          <td>$row->no_mesin</td> 
                          <td>$cek_pik->no_rangka</td> 
                          <td>$row->nama_konsumen</td> 
                          <td>$row->alamat</td> 
                          <td>".number_format($cek_pik->biaya_bbn, 0, ',', '.')."</td>                           
                        </tr>";
                        $no++;
                        }
                      ?>
                    </tbody>
                  </table>  
                </div>  
              </div>
          </div>             
          <div class="box-footer">
            <div class="col-sm-2">
            </div>
            <div class="col-sm-10">                  
              <button type="submit" onclick="return confirm('Are you sure to approve all data?')" name="save" value="approve" class="btn btn-info btn-flat"><i class="fa fa-check"></i> Approve All</button>
              <button type="submit" onclick="return confirm('Are you sure to danger all data?')" name="save" value="danger" class="btn btn-danger btn-flat"><i class="fa fa-close"></i> Reject All</button>
            </div>
          </div><!-- /.box-footer -->
        </form>
      </div>
    </div>          

    <?php
    }
    ?>
  </section>
</div>
<script type="text/javascript">
  
function take_kec(){
  var id_kelurahan = $("#id_kelurahan").val();                       
  $.ajax({
      url: "<?php echo site_url('h1/pengajuan_bbn_md/take_kec')?>",
      type:"POST",
      data:"id_kelurahan="+id_kelurahan,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");                    
          $("#id_kecamatan").val(data[0]);                                                    
          $("#kecamatan").val(data[1]);                                                    
          $("#id_kabupaten").val(data[2]);                                                    
          $("#kabupaten").val(data[3]);                                                    
          $("#id_provinsi").val(data[4]);                                                    
          $("#provinsi").val(data[5]);                                                    
          $("#kelurahan").val(data[6]);                                                    
      } 
  })
}
function generateDetail()
{
  $("#showGenerateDetail").show();

  var tgl_mohon_samsat  = $('.tgl_mohon_samsat').val(); 

  var nama_biro_jasa    = document.getElementById("nama_biro_jasa").value;   

  var xhr;

  if (window.XMLHttpRequest) { // Mozilla, Safari, ...

    xhr = new XMLHttpRequest();

  }else if (window.ActiveXObject) { // IE 8 and older

    xhr = new ActiveXObject("Microsoft.XMLHTTP");

  } 

   //var data = "birthday1="+birthday1_js;          

    var data = "tgl_mohon_samsat="+tgl_mohon_samsat+"&nama_biro_jasa="+nama_biro_jasa;                           

     xhr.open("POST", "h1/pengajuan_bbn_md/generateDetail", true); 

     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  

     xhr.send(data);

     xhr.onreadystatechange = display_data;

     function display_data() {

        if (xhr.readyState == 4) {

            if (xhr.status == 200) {       

                document.getElementById("showGenerateDetail").innerHTML = xhr.responseText;

            }else{

                alert('There was a problem with the request.');

            }

        }

    }   
}
</script>