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
<!-- <base href="<?php echo base_url(); ?>" /> -->
<body onload="take_kec();cek_generate();">
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
                      <th>Kekurangan</th>
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
                              <a <?php echo $this->m_admin->set_tombol($id_menu,$group,"edit"); ?> class="btn btn-flat btn-primary btn-sm" href="h1/pengajuan_bbn_md/edit?id=<?php echo $row->id_sales_order ?>&b=<?php echo $row->no_bastd ?>&no=<?php echo $isi->no_mesin ?>">Edit</a>
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
                            <td align='right'>".mata_uang2($isi->biaya_bbn)."</td>                           
                            <td>$tgl_cetak_invoice</td>                                
                            <td>"; ?>
                              <?php echo $isi->tgl_mohon_samsat ?>
                              <!-- <input type="text" placeholder="yyyy-mm-dd" value="<?php echo $isi->tgl_mohon_samsat ?>" name="tgl_mohon_samsat[]" id="tanggal<?php echo $no ?>"  style="width:80px;"> -->
                            </td>   
                            <td><?php echo $isi->kekurangan ?></td>   
                            <td align='center'>
                              <?php 
                              $cek = $this->db->query("SELECT * FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
                                  INNER JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do WHERE tr_picking_list_view.no_mesin = '$isi->no_mesin'");
                              if($cek->num_rows() > 0){
                                $c = $cek->row();
                                $jenis = $c->source;                              
                                if($jenis == 'po_indent'){
                                  if($isi->sengaja=='1'){
                                  ?>
                                   <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                                     <input type="checkbox" class="flat-red" name="sengaja[]" value="1"  checked disabled onclick="return false;">
                                  </div>
                                  <?php }else{ ?>
                                  <input type="checkbox" class="flat-red" name="sengaja[]" value="1"  onclick="return false;">
                                  <?php 
                                  }                                   
                                }
                              } ?>
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
                            <td></td>   
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
                <!-- <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Mohon Samsat</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_mohon_samsat" value="" placeholder="Nomor BASTD" readonly class="form-control" id="tanggal">
                  </div>                                    
                </div>                
                 -->
                <table class='table table-bordered table-hover' id="example1">
                  <thead>
                    <tr>                    
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                      <th>Tgl Mohon Samsat</th>
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
                      <td>$key->tgl_mohon_samsat</td>
                      <td>$key->nama_konsumen</td>
                      <td>$re->deskripsi_ahm</td>
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
                      <td>$re->deskripsi_ahm</td>
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
                      <td>$re->deskripsi_ahm</td>
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
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <!-- <a href="h1/pengajuan_bbn_md/check?id=<?php echo $no_bastd ?>"> -->
          <a href="h1/cetak_faktur">
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
          <?php 
          if($dt_so->num_rows() == 0){
            echo "Data Sales Order atau Data Pengajuan BBN MD tidak tersedia";
          }else{
            $row = $dt_so->row();      
            $nama_konsumen = $row->nama_konsumen;
            $alamat = $row->alamat;
            $no_telp = $row->no_telp;
            $no_npwp = $row->npwp;      
            if($no_npwp == 'on'){
              $no_npwp = $row->no_npwp;
            }
            $ra1 = $this->m_admin->getByID("tr_sales_order","no_mesin",$no_mesin);
            $ra2 = $this->db->query("SELECT * FROM tr_sales_order_gc INNER JOIN tr_sales_order_gc_nosin ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
              INNER JOIN tr_spk_gc_detail ON tr_sales_order_gc_nosin.no_spk_gc = tr_spk_gc_detail.no_spk_gc
              WHERE tr_sales_order_gc_nosin.no_mesin = '$no_mesin'");
            if($ra1->num_rows() > 0){
              $ra = $ra1->row();
              $biaya_bbn = $row->biaya_bbn;
              $tgl_cetak_invoice = $ra->tgl_cetak_invoice;
            }elseif($ra2->num_rows() > 0){
              $ra = $ra2->row();
              $biaya_bbn = $ra->biaya_bbn;
              $tgl_cetak_invoice = $ra->tgl_cetak_invoice;
            } else {
              $biaya_bbn = $row->biaya_bbn;
              $tgl_cetak_invoice = $row->tgl_faktur;
            }

            $biaya_bbn_md_bj = 0; //tidak 0 sudah di kalkulasi ulang di controller
            $nama_ibu = $row->nama_ibu;
            $tgl_ibu = $row->tgl_ibu;
            $nosin_spasi = substr_replace($no_mesin," ", 5, -strlen($no_mesin));
            $rw = $this->m_admin->getByID("tr_fkb","no_mesin",$nosin_spasi);
            $cek_bbn_luar = $this->db->get_where('tr_bantuan_bbn_luar', array('no_mesin'=>$row->no_mesin));
            if($rw->num_rows() > 0){
              $ry = $rw->row();
              $no_fkb = $ry->nomor_faktur;
              $tahun_produksi = $ry->tahun_produksi;
            } elseif($cek_bbn_luar->num_rows() > 0) {
              $no_fkb = $row->no_faktur;
              $tahun_produksi = $row->tahun_produksi;
              $biaya_bbn_md_bj = $row->biaya_bbn_bj;
              $nama_ibu = $row->nama_gadis_ibu;
              $tgl_ibu = $row->tgl_lahir_ibu;
            } else{
              $no_fkb = "";
              $tahun_produksi = "";
	      $nama_ibu = "";
            }
          ?>
            <form class="form-horizontal" action="h1/pengajuan_bbn_md/update" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_tipe_kendaraan" value="<?php echo $row->id_tipe_kendaraan ?>">
                    <input type="hidden" name="id_warna" value="<?php echo $row->id_warna ?>">
                    <input type="hidden" name="no_bastd" value="<?php echo $no_bastd ?>">
                    <input type="hidden" name="tgl_jual" value="<?php echo $tgl_cetak_invoice ?>">
                    <input type="hidden" name="tahun" value="<?php echo $tahun_produksi ?>">
                    <input type="hidden" name="no_faktur" value="<?php echo $no_fkb ?>">
                    <input type="hidden" name="biaya_bbn" value="<?php echo $biaya_bbn ?>">
                    <input type="hidden" name="biaya_bbn_md_bj" value="<?php echo $biaya_bbn_md_bj ?>">
                    <input type="text"  readonly name="no_mesin" value="<?php echo $no_mesin ?>" placeholder="No Mesin" class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Rangka</label>
                  <div class="col-sm-4">
                    <input type="text"  readonly name="no_rangka" value="<?php echo $row->no_rangka ?>" placeholder="No Rangka" value="" class="form-control">
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">
                    <input type="text" name="tipe" placeholder="Tipe" value="<?php echo strtoupper($row->tipe_ahm) ?>" readonly class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" name="warna" onkeydown="upperCaseF(this)" autocomplete="false" value="<?php echo strtoupper($row->warna) ?>" placeholder="Warna"  class="form-control">
                  </div>                  
                </div>
                
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                  <div class="col-sm-10">
                    <input type="text" onkeydown="upperCaseF(this)" value="<?php echo strtoupper($nama_konsumen) ?>" name="nama_konsumen" placeholder="Nama Konsumen" class="form-control">
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tempat/Tanggal Lahir</label>
                  <div class="col-sm-4">
                    <input type="text" onkeydown="upperCaseF(this)" name="tempat_lahir" value="<?php echo strtoupper($row->tempat_lahir) ?>" placeholder="Tempat Lahir" class="form-control">
                  </div>                  
                  <div class="col-sm-4">
                    <input type="text" name="tgl_lahir" placeholder="Tanggal Lahir" value="<?php echo $row->tgl_lahir ?>" id="tanggal2" class="form-control">
                  </div>                  
                </div>                               
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan</label>
                  <div class="col-sm-4">
                    <?php 
                    $dt_cust    = $this->m_admin->getByID("ms_kelurahan","id_kelurahan",$row->id_kelurahan)->row();                                 
                    if(isset($dt_cust)){
                      $kel = $dt_cust->kelurahan;
                    }else{
                      $kel = "";
                    }
                    ?>
                    <input type="hidden" value="<?php echo $row->id_kelurahan ?>" name="id_kelurahan" id="id_kelurahan">                      
                    <input type="text" value="<?php echo strtoupper($kel) ?>" name="kelurahan" data-toggle="modal" placeholder="Kelurahan" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="take_kec()">                               
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kecamatan" id="id_kecamatan">
                    <input type="text" class="form-control" id="kecamatan" readonly placeholder="Kecamatan"  name="kecamatan">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_kabupaten" id="id_kabupaten">
                    <input type="text" class="form-control" readonly placeholder="Kota/Kabupaten" id="kabupaten" name="kabupaten">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                  <div class="col-sm-4">
                    <input type="hidden" name="id_provinsi" id="id_provinsi">
                    <input type="text" class="form-control" readonly placeholder="Provinsi" id="provinsi" name="provinsi">                                        
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-10">
                    <input type="text" name="alamat" onkeydown="upperCaseF(this)" placeholder="Alamat" value="<?php echo strtoupper($row->alamat) ?>" class="form-control">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" onkeydown="upperCaseF(this)" placeholder="No HP" value="<?php echo $row->no_hp ?>" id="no_hp"  name="no_hp">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" onkeydown="upperCaseF(this)" placeholder="No Telp" value="<?php echo $no_telp ?>"  name="no_telp">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No KTP</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" onkeydown="upperCaseF(this)" placeholder="No KTP" value="<?php echo $row->no_ktp ?>" name="no_ktp" maxlength="16">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No KK</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" onkeydown="upperCaseF(this)" placeholder="No KK" value="<?php echo isset($row->no_kk)?$row->no_kk:'-'; ?>" name="no_kk">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No NPWP</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No NPWP" value="<?php echo $no_npwp ?>" name="no_npwp">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No TDP</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No TDP" value="<?php echo isset($row->no_tdp)?$row->no_tdp:'-'; ?>" name="no_tdp">   
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Gadis Ibu Kandung</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Nama Gadis Ibu Kandung" value="<?php echo $nama_ibu ?>" name="nama_ibu">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Lahir Ibu Kandung</label>
                  <div class="col-sm-4">
                    <input id="tanggal3" type="text" class="form-control" placeholder="Tgl Lahir Ibu Kandung" value="<?php echo $tgl_ibu ?>" name="tgl_ibu">                                        
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
                    <?php 
                    if(isset($row->kerja)){
                      $kerja = $row->kerja;
                    }else{
                      $cek = $this->db->query("SELECT * FROM tr_faktur_stnk_detail INNER JOIN tr_sales_order_gc ON tr_faktur_stnk_detail.id_sales_order = tr_sales_order_gc.id_sales_order_gc
                        INNER JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc
                        WHERE tr_faktur_stnk_detail.no_bastd = '$no_bastd'");                      
                      if($cek->num_rows() > 0){                        
                        if($cek->row()->jenis_gc == 'Swasta/BUMN/Koperasi'){
                          $kerja = "SWASTA";
                        }elseif($cek->row()->jenis_gc == "Instansi"){
                          $kerja = "DINAS";
                        }else{
                          $kerja = "N";
                        }                      
                      }else{
                        // $kerja = "N";
                        $kerja=$row->pekerjaan;
                      }
                    } 
                    ?>
                    <input type="text" class="form-control" placeholder="Pekerjaan" value="<?php echo strtoupper($kerja) ?>" name="pekerjaan" id="pekerjaan" onkeydown="upperCaseF(this)">
                    <button type="button" class="btn btn-flat btn-primary" style="margin-top: 4px" onclick="showPekerjaan()"><i class="fa fa-search"></i></button>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Pengeluaran 1 Bulan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" onkeydown="upperCaseF(this)" placeholder="Pengeluaran 1 Bulan" value="<?php echo isset($row->penghasilan)?strtoupper($row->penghasilan):'-'; ?>" name="penghasilan">                                        
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Mohon Samsat</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" autocomplete="off" placeholder="Tgl Mohon Samsat" id="tanggal4" name="tgl_mohon_samsat" value="<?php if(isset($row->tgl_mohon_samsat)){echo $row->tgl_mohon_samsat; } ?>"	>                                        
                  </div>
                  <?php 
                  $cek = $this->db->query("SELECT * FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
                      INNER JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do WHERE tr_picking_list_view.no_mesin = '$no_mesin'");
                  if($cek->num_rows() > 0){
                    $c = $cek->row();
                    $jenis = $c->source;                              
                    if($jenis == 'po_indent'){
                    ?>
                    <label for="inputEmail3" class="col-sm-2 control-label">Kesalahan Disengaja</label>
                    <div class="col-sm-4">
                      <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                        <input type="checkbox" class="flat-red" name="sengaja" value="1" <?php if(isset($row->sengaja)){ echo $sengaja=$row->sengaja==1?'checked':'';}?>>                    
                      </div>
                    </div>
                    <?php } } ?>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kekurangan</label>
                  <div class="col-sm-10">
                    <input type="text" onchange="cek_tombol()" onkeydown="upperCaseF(this)" class="form-control" placeholder="Kekurangan" id="kekurangan" name="kekurangan" value="<?php if(isset($row->kekurangan)){ echo strtoupper($row->kekurangan); } ?>"	>                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Keterangan" onkeydown="upperCaseF(this)" id="keterangan_d" name="keterangan_d" value="<?php if(isset($row->keterangan_d)){ echo strtoupper($row->keterangan_d); } ?>" >                    
                  </div>
                </div>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
                  <button type="submit" onclick="return confirm('Are you sure to reject data?')" name="save" value="reject" class="btn btn-danger btn-flat"><i class="fa fa-close"></i> Reject</button>                  
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
        <?php } ?>
      </div>
    </div><!-- /.box -->
<div class="modal fade modalPekerjaan" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Daftar Pekerjaan</h4>
      </div>
      <div class="modal-body">
        <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_pekerjaan" style="width: 100%">
                  <thead>
                  <tr>
                      <th>No</th>
                      <th>Pekerjaan</th>
                      <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php $pekerjaan = $this->db->query("SELECT * FROM ms_pekerjaan WHERE active=1");
                  $no=1;
                  foreach ($pekerjaan->result() as $rs) { ?>
                    <tr>
                      <td><?= $no ?></td>
                      <td><?= strtoupper($rs->pekerjaan) ?></td>
                      <td><?= '<button data-dismiss=\'modal\' onClick=\'return pilihPekerjaan('.json_encode($rs).')\' class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>' ?></td>
                    </tr>
                  <?php $no++; }
                  ?>
                  </tbody>
              </table>
              <script>
                  $(document).ready(function(){
                      $('#tbl_pekerjaan').DataTable({
                          processing: true,
                          serverSide: false,
                          "language": {                
                                  "infoFiltered": ""
                              },
                          order: [],
                          "columnDefs":[  
                      // { "targets":[4],"orderable":false},
                      { "targets":[2],"className":'text-center'}, 
                      // { "targets":[4], "searchable": false } 
                 ]
                      });
                  });
              </script>
      </div>
    </div>
  </div>
</div>
<script>
  function showPekerjaan() {
    $('.modalPekerjaan').modal('show');
  }
  function pilihPekerjaan(krj)
  {
    let pekerjaan = krj.pekerjaan.toUpperCase();
    $('#pekerjaan').val(pekerjaan);
  }
</script>
    <?php     
    }elseif($set=='generate'){
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/<?= $r ?>">
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
                    <button class="btn btn-primary btn-flat btn-sm" type="button" onclick="generateDetail()">Cek</button>
                    
                  </div>                              
                </div>          
                <div id="showGenerateDetail"></div>
                
              </div><!-- /.box-body -->
              <span id="tombol_generate">
                <div class="box-footer">
                  <div class="col-sm-2">
                  </div>
                  <div class="col-sm-10">                  
                    <button type="submit" onclick="return confirm('Are you sure to generate all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Generate All</button>
                    <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
                  </div>
                </div><!-- /.box-footer -->
              </span>
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
         <!--  <a href="h1/pengajuan_bbn_md/generate">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-download"></i> Generate File TXT Samsat</button>
          </a>   -->        
                    
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
              <th style="display:none;">Nama Dealer</th>                            
              <th>Status</th>
              <th width="20%">Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_bbn->result() as $row) {
            $st = $row->status_faktur;            
            if($st=='approved'){            
                // $tombol = "<a href='h1/pengajuan_bbn_md/check?id=$row->no_bastd' class='btn btn-primary btn-flat btn-xs'>Check</a>                
                //   <a href='h1/pengajuan_bbn_md/cetak_faktur?id=$row->no_bastd' class='btn btn-warning btn-flat btn-xs'>Cetak Faktur</a>
                //   <a href='h1/pengajuan_bbn_md/cetak_permohonan?id=$row->no_bastd' class='btn btn-warning btn-flat btn-xs'>Cetak Permohonan STNK</a>
                //   <a href='h1/pengajuan_bbn_md/cetak_pendaftaran_bpkb?id=$row->no_bastd' class='btn btn-success btn-flat btn-xs'>Cetak Pendaftaran BPKB</a>";
              $tombol="";
            }elseif($st=='rejected'){
              $tombol = "";
            }else{
              $tombol = "<a href='h1/pengajuan_bbn_md/cek_approval?id=$row->no_bastd' class='btn btn-primary btn-flat btn-xs'>Approve/Reject</a>";
            }
            $cek = $this->m_admin->getByID("tr_pengajuan_bbn","no_bastd",$row->no_bastd);
            $cek2 = $this->m_admin->getByID("tr_faktur_stnk","no_bastd",$row->no_bastd);            
            if($cek->num_rows() > 0){
              $id_    = $cek->row();
              if ($id_->status_pengajuan=='checked') {
                $status = "<span class='label label-primary'>approved</span>";
              }else{
                $status = "<span class='label label-danger'>$id_->status_pengajuan</span>";
              }
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
              <td style='display:none;'>";
              $bastd = $this->m_admin->getByID("tr_pengajuan_bbn_detail","no_bastd",$row->no_bastd);
              foreach ($bastd->result() as $isi) {
                echo $isi->no_mesin.",";
              }
              echo "
              </td>                            
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
                        <th>Nama Konsumen</th>
                        <th>Alamat</th>
                        <th>Tipe</th>
                        <th>No Mesin</th>              
                        <th>No Rangka</th>              
                        <th>Biaya BBN</th>                        
                      </tr>
                    </thead>
                   
                    <tbody>                    
                      <?php   
                      $no = 1;
                      $tot = 0;
                     foreach($get_nosin->result() as $row) {                                                   
                        $cek_pik = $this->db->query("SELECT tr_faktur_stnk_detail.*,tr_scan_barcode.no_rangka,tr_scan_barcode.tipe_motor,tr_spk.tipe_customer
                            FROM tr_faktur_stnk_detail INNER JOIN tr_scan_barcode ON tr_faktur_stnk_detail.no_mesin = tr_scan_barcode.no_mesin                
                            LEFT JOIN tr_spk ON tr_faktur_stnk_detail.no_spk = tr_spk.no_spk
                            WHERE tr_faktur_stnk_detail.no_mesin = '$row->no_mesin'")->row(); 
                        $getTipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan='$cek_pik->tipe_motor'");
                        if ($getTipe->num_rows()>0) {
                          $tipe = $getTipe->row()->deskripsi_samsat;
                        }else{
                          $tipe='';
                        }
                        echo "
                        <tr>        
                          <td>$no</td>     
                          <td>$row->nama_konsumen</td> 
                          <td>$row->alamat</td> 
                          <td>$cek_pik->tipe_motor | $tipe</td>
                          <td>$row->no_mesin</td> 
                          <td>$cek_pik->no_rangka</td> 
                          
                          
                          <td>".number_format($cek_pik->biaya_bbn, 0, ',', '.')."</td>                           
                        </tr>";
                        $no++;
                        $tot+=$cek_pik->biaya_bbn;
                        }

                      foreach($get_nosin1->result() as $row) {                                                   
                        $cek_pik = $this->db->query("SELECT tr_faktur_stnk_detail.*,tr_bantuan_bbn_luar.no_rangka,tr_bantuan_bbn_luar.id_tipe_kendaraan
                            FROM tr_faktur_stnk_detail INNER JOIN tr_bantuan_bbn_luar ON tr_faktur_stnk_detail.no_mesin = tr_bantuan_bbn_luar.no_mesin                
                            WHERE tr_faktur_stnk_detail.no_mesin = '$row->no_mesin'")->row(); 
                        $getTipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan='$cek_pik->id_tipe_kendaraan'");
                        if ($getTipe->num_rows()>0) {
                          $tipe = $getTipe->row()->deskripsi_samsat;
                        }else{
                          $tipe='';
                        }
                        echo "
                        <tr>        
                          <td>$no</td>     
                          <td>$row->nama_konsumen</td> 
                          <td>$row->alamat</td> 
                          <td>$cek_pik->id_tipe_kendaraan | $tipe</td>
                          <td>$row->no_mesin</td> 
                          <td>$cek_pik->no_rangka</td> 
                          
                          
                          <td>".number_format($cek_pik->biaya_bbn, 0, ',', '.')."</td>                           
                        </tr>";
                        $no++;
                        $tot+=$cek_pik->biaya_bbn;
                        }

                      ?>
                    </tbody>
                    <tfoot>
                    <tr>
                      <td colspan="6">Total</td>
                      <td><?=number_format($tot, 0, ',', '.')?></td>
                    </tr>
                  </tfoot>
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
<div class="modal fade" id="Kelurahanmodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search Kelurahan
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
       <table id="table" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>              
              <th>Kelurahan</th>
              <th>Kecamatan</th>
              <th>Kabupaten</th>
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>                     
          </tbody>
        </table>        
      </div>      
    </div>
  </div>
</div>

<script type="text/javascript">
  
function take_kec(){
  $("#tombol_generate").hide();  
  var id_kelurahan = $("#id_kelurahan").val();
  // var kelurahan = $("#id_kelurahan").text();
  // kel = kelurahan.split('|');
  // $("#id_kelurahan").text(kel[0]);
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
                cek_generate();
            }else{
                alert('There was a problem with the request.');
            }
        }
    }   
}
function cek_tombol(){
  var kekurangan = document.getElementById("kekurangan").value;   
  if(kekurangan != ""){
    document.getElementById("tom_reject").value = "Muncul";   
  }else{
    document.getElementById("tom_reject").value = "";   
  }
}
function cek_generate(){
  var isi = document.getElementById("isi_generate").value;
  if(isi == 0){
    $("#tombol_generate").hide();  
  }else{
    $("#tombol_generate").show();  
  }
}
function chooseitem(id_kelurahan){
  document.getElementById("id_kelurahan").value = id_kelurahan; 
  take_kec();
  $("#Kelurahanmodal").modal("hide");
}
</script>

<script type="text/javascript">
var table;
$(document).ready(function() {
    //datatables
    table = $('#table').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('h1/pengajuan_bbn_md/ajax_list')?>",
            "type": "POST"
        },
        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
    });
});
</script>

<script type="text/javascript">
function upperCaseF(a){
    setTimeout(function(){
        a.value = a.value.toUpperCase();
    }, 1);
}
</script>