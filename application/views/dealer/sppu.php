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
    <li class="">Customer</li>
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
          <a href="dealer/sppu">
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
            <form class="form-horizontal" action="dealer/sppu/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Perintah</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly id="no_sppu" placeholder="No Surat Perintah" name="no_sppu">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Surat Perintah</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="tanggal"  placeholder="Tgl Surat Perintah" name="tgl_sppu">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Warehouse Head</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="warehouse_head">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_karyawan->result() as $isi) {
                        echo "<option value='$isi->nama_lengkap'>$isi->nama_lengkap</option>";
                      }
                      ?>
                    </select>
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Security</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="security">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_karyawan->result() as $isi) {
                        echo "<option value='$isi->nama_lengkap'>$isi->nama_lengkap</option>";
                      }
                      ?>
                    </select>
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Delivery Man</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="delivery_man">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_karyawan->result() as $isi) {
                        echo "<option value='$isi->nama_lengkap'>$isi->nama_lengkap</option>";
                      }
                      ?>
                    </select>
                  </div>                  
                </div>     
                <br>       
                <span id="tampil_sppu"></span>  
                
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
    }elseif($set=="konfirmasi"){
      $row = $dt_sppu->row();
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/sppu">
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
            <form class="form-horizontal" action="dealer/sppu/save_konfirmasi" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Perintah</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $row->no_sppu ?>" placeholder="No Surat Perintah" name="no_sppu">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Surat Perintah</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $row->tgl_sppu ?>" placeholder="Tgl Surat Perintah" name="tgl_sppu">
                  </div>                  
                </div>                
                <br>          
                <button class="btn btn-primary btn-block btn-flat" disabled>Detail Unit</button>
                <table id="" class="table table-bordered table-hover">
                <thead>
                  <tr>                    
                    <th>No Mesin</th>              
                    <th>No Rangka</th>              
                    <th>Nama Konsumen</th>              
                    <th>Alamat</th>              
                    <th>Kode Item</th>
                    <th>Tipe</th>                            
                    <th>Warna</th>
                    <th>Action</th>              
                    <th width='10%'>Tgl Terima Konsumen</th>
                  </tr>
                </thead>
                <tbody> 
                  <?php 
                  $e = $this->m_admin->getByID("tr_sppu_detail","no_sppu",$row->no_sppu)->row();
                  $sql = $this->db->query("SELECT tr_sales_order.*,tr_prospek.nama_konsumen,tr_spk.alamat,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka,tr_scan_barcode.id_item FROM tr_sales_order 
                        INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                        INNER JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
                        INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                        INNER JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                        INNER JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna      
                        WHERE tr_scan_barcode.no_mesin = '$e->no_mesin'");
                  foreach ($sql->result() as $isi) {
                    $item = $this->db->query("SELECT * FROM tr_sppu_detail WHERE no_mesin = '$isi->no_mesin' AND no_sppu = '$row->no_sppu'")->row();
                    if($item->konfirmasi == 'ya'){
                      $is = "checked";
                      $tgl = $item->tgl_terima;
                    }else{
                      $is = "";
                      $tgl = "";
                    }
                    echo "
                    <tr>
                      <td>$isi->no_mesin</td>
                      <td>$isi->no_rangka</td>
                      <td>$isi->nama_konsumen</td>
                      <td>$isi->alamat</td>
                      <td>$isi->id_item</td>
                      <td>$isi->tipe_ahm</td>
                      <td>$isi->warna</td>
                      <td align='center'>
                        <input type='hidden' value='$isi->no_mesin' name='no_mesin[]'>
                        <input type='hidden' value='$row->no_sppu' name='no_sppu[]'>                        
                        <input type='checkbox' name='check_sppu[]' class='flat-red' $is>                        
                      </td>              
                      <td>
                        <input value='$tgl' type='text' required id='tanggal' class='form-control isi' name='tgl_terima[]'>
                      </td>
                      
                    </tr>
                    ";
                  }
                  ?>                 
                </tbody>   
                </table>
              </div>            
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
    </div><!-- /.box -->
    </form>
    <?php 
    }elseif($set=="detail"){
      $row = $dt_sppu->row();
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/sppu">
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
            <form class="form-horizontal" action="dealer/sppu/save_konfirmasi" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Perintah</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $row->no_sppu ?>" placeholder="No Surat Perintah" name="no_sppu">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Surat Perintah</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $row->tgl_sppu ?>" placeholder="Tgl Surat Perintah" name="tgl_sppu">
                  </div>                  
                </div>                
                <br>          
                <button class="btn btn-primary btn-block btn-flat" disabled>Detail Unit</button>
                <table id="" class="table table-bordered table-hover">
                <thead>
                  <tr>                    
                    <th>No Mesin</th>              
                    <th>No Rangka</th>              
                    <th>Nama Konsumen</th>              
                    <th>Alamat</th>              
                    <th>Kode Item</th>
                    <th>Tipe</th>                            
                    <th>Warna</th>
                    <th>Konfirmasi</th>              
                    <th width='10%'>Tgl Terima Konsumen</th>
                  </tr>
                </thead>
                <tbody> 
                  <?php 
                  $e = $this->m_admin->getByID("tr_sppu_detail","no_sppu",$row->no_sppu)->row();
                  $sql = $this->db->query("SELECT tr_sales_order.*,tr_prospek.nama_konsumen,tr_spk.alamat,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka,tr_scan_barcode.id_item FROM tr_sales_order 
                        INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                        INNER JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
                        INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                        INNER JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                        INNER JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna      
                        WHERE tr_scan_barcode.no_mesin = '$e->no_mesin'");
                  foreach ($sql->result() as $isi) {
                    $item = $this->db->query("SELECT * FROM tr_sppu_detail WHERE no_mesin = '$isi->no_mesin' AND no_sppu = '$row->no_sppu'")->row();
                    if($item->konfirmasi == 'ya'){
                      $is = "<i class='fa fa-check'></i>";
                      $tgl = $item->tgl_terima;
                    }else{
                      $is = "";
                      $tgl = "";
                    }
                    echo "
                    <tr>
                      <td>$isi->no_mesin</td>
                      <td>$isi->no_rangka</td>
                      <td>$isi->nama_konsumen</td>
                      <td>$isi->alamat</td>
                      <td>$isi->id_item</td>
                      <td>$isi->tipe_ahm</td>
                      <td>$isi->warna</td>
                      <td>$is</td>              
                      <td>$tgl</td>
                      
                    </tr>
                    ";
                  }
                  ?>                 
                </tbody>   
                </table>
              </div>            
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
         <!-- <a href="dealer/sppu/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>          
          button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->                  
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
              <th>No. SO</th>
              <th>Delivery Document ID</th>
              <th>No. Mesin</th>              
              <th>No. Rangka</th>                            
              <th>Tipe</th>
              <th>Warna</th>                            
              <th>Nama Konsumen</th>                            
              <th>Alamat Konsumen</th>   
              <th>Sales ID</th>
              <th>Sales Name</th>
              <th>Status Delivery Document</th>                         
              <th>Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_sppu->result() as $row) {     
            //$s = $this->db->query("SELECT * FROM ms_vendor WHERE id_vendor = '$row->ekspedisi'")->row();         
            $sales_id=''; 
            $sales_name=''; 
            $prospek = $this->db->query("SELECT * FROM tr_prospek 
              JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
              WHERE id_customer='$row->id_customer' ORDER BY tr_prospek.created_at DESC LIMIT 1");
            if ($prospek->num_rows()>0) {
              $pr = $prospek->row();
              $sales_id = $pr->id_flp_md;
              $sales_name=$pr->nama_lengkap;
            }
            $print = $this->m_admin->set_tombol($id_menu,$group,'print');
            $status_delivery=$row->status_delivery;
            // if ($row->tgl_cetak_invoice<>null) {
            $btn_print = "<a href='dealer/sppu/cetak_sppu?id=$row->id_sales_order' target='_blank'>
                    <button $print class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak</button>
                  </a>";
            $button = '';
              if ($row->status_delivery=='in_progress') {
                $status_delivery = "<label class='label label-warning'>".ucwords(str_replace('_', ' ', $row->status_delivery))."</label>";
                $button .=$btn_print;
              }
              if ($row->status_delivery=='delivered') {
                $status_delivery = "<label class='label label-success'>".ucwords(str_replace('_', ' ', $row->status_delivery))."</label>";
                $button .=$btn_print;
              }
              if ($row->status_delivery=='ready') {
                $status_delivery = "<label class='label label-success'>".ucwords(str_replace('_', ' ', $row->status_delivery))."</label>";
                $button .=$btn_print;
              }
              if ($row->status_delivery=='back_to_dealer') {
                $status_delivery = "<label class='label label-primary'>".ucwords(str_replace('_', ' ', $row->status_delivery))."</label>";
                $button .=$btn_print;
              }
            if ($row->tgl_cetak_invoice<>null) {
              echo "
              <tr>
                <td>$no</td>              
                <td>$row->id_sales_order</td>
                <td>$row->delivery_document_id</td>
                <td><a data-toggle='tooltip' href='dealer/sppu/detail_no_mesin?id=$row->no_mesin'>$row->no_mesin</a></td>
                <td>$row->no_rangka</td>
                <td>$row->tipe_ahm</td>
                <td>$row->warna</td>
                <td>$row->nama_konsumen</td>
                <td>$row->alamat</td>
                <td>$sales_id</td>
                <td>$sales_name</td>
                <td>$status_delivery</td>
                <td>  
                  $button
                </td>
              </tr>
              ";
              $no++;
            }
          }
          foreach($dt_sppu2->result() as $row) {     
            //$s = $this->db->query("SELECT * FROM ms_vendor WHERE id_vendor = '$row->ekspedisi'")->row();          
            $print = $this->m_admin->set_tombol($id_menu,$group,'print');
            $dt_nomesin = $this->db->query("SELECT tr_scan_barcode.no_mesin,no_rangka 
              FROM tr_sales_order_gc_nosin 
              JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_sales_order_gc_nosin.no_mesin
              WHERE id_sales_order_gc='$row->id_sales_order_gc'
              ");

            $dt_tipe = $this->db->query("SELECT tipe_ahm,ms_warna.warna FROM tr_sales_order_gc_nosin 
              JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_sales_order_gc_nosin.no_mesin 
              JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
              JOIN ms_warna ON ms_warna.id_warna=tr_scan_barcode.warna
              WHERE id_sales_order_gc='$row->id_sales_order_gc' GROUP BY tipe_motor,warna
              ");

            $tipe=array();
            $warna=array();
            foreach ($dt_tipe->result() as $tp) {
              $tipe[] = $tp->tipe_ahm; 
              $warna[] = $tp->warna; 
            }

            $nomesin=array();
            $norangka=array();
            foreach ($dt_nomesin->result() as $rs) {
              $nomesin[] = $rs->no_mesin; 
              $norangka[] = $rs->no_rangka; 
            }
              echo "
              <tr>
                <td>$no</td> 
                <td>$row->id_sales_order_gc</td>             
                <td></td>
                <td>".implode(', </br>', $nomesin)."</td>
                <td>".implode(', </br>', $norangka)."</td>
                <td>".implode(', </br>', $tipe)."</td>
                <td>".implode(', </br>', $warna)."</td>
                <td>$row->nama_npwp</td>
                <td>$row->alamat</td>
                <td></td>
                <td></td>
                <td></td>
                <td>  
                  <a href='dealer/sppu/cetak_sppu_gc?id=$row->id_sales_order_gc' target='_blank'>
                    <button $print class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak</button>
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
    }elseif($set=="view_new"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
         <!-- <a href="dealer/sppu/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>          
          button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->                  
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
        <table id="datatable" class="table table-bordered table-hover">
          <thead>
            <tr>            
              <th width="5%">No</th>              
              <th>No. SO</th>
              <th>No. Mesin</th>              
              <th>No. Rangka</th>                            
              <th>Tipe</th>
              <th>Warna</th>                            
              <th>Nama Konsumen</th>                            
              <th>Alamat Konsumen</th>   
              <th>Sales Name</th>
              <th>Status Delivery Document</th>                         
              <th>Action</th>              
            </tr>
          </thead>
          <tbody>            
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

	<script type="text/javascript">
              $(document).ready(function(e){
                $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
                {
                    return {
                        "iStart": oSettings._iDisplayStart,
                        "iEnd": oSettings.fnDisplayEnd(),
                        "iLength": oSettings._iDisplayLength,
                        "iTotal": oSettings.fnRecordsTotal(),
                        "iFilteredTotal": oSettings.fnRecordsDisplay(),
                        "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                        "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                    };
                };

                var base_url = "<?php echo base_url();?>/"; // You can use full url here but I prefer like this
                $('#datatable').DataTable({
                   "pageLength" : 10,
                   "serverSide": true,
                   "ordering": false, // Set true agar bisa di sorting
                    "processing": true,
                    "language": {
                      processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                      searchPlaceholder: "Pencarian..."
                    },

                   "order": [[1, "desc" ]],
                   "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    },
                   "ajax":{
                            url :  base_url+'dealer/sppu/getAllData',
                            type : 'POST'
                          },
                }); // End of DataTable
              }); 
            </script>
 <?php
    }elseif($set=="monitor"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
         <!-- <a href="dealer/sppu/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>          
          button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->                  
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
	<div class="table-responsive">
        <table id="datatable" class="table table-bordered table-hover">
          <thead>
            <tr>            
              <th width="5%">No</th>     
              <th>No. SO</th>        
              <th>No. Mesin</th>              
              <th>No. Rangka</th>                            
              <th>Tipe</th>
              <th>Warna</th>                            
              <th>Nama Konsumen</th>                            
              <th>Alamat Konsumen</th>
              <th>Tgl Penjualan</th>     
              <th>Tgl Rencana Pengiriman</th>
              <th>Tgl BASTK</th>   
              <th>Tgl Pengiriman Unit</th>   
              <th>Tgl Terima Unit ke Konsumen</th>
              <th>Status Delivery</th>     
              <th>Aksi</th>             
            </tr>
          </thead>
          <tbody>            
          </tbody>
        </table>
	</div>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

	<script type="text/javascript">
              $(document).ready(function(e){
                $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
                {
                    return {
                        "iStart": oSettings._iDisplayStart,
                        "iEnd": oSettings.fnDisplayEnd(),
                        "iLength": oSettings._iDisplayLength,
                        "iTotal": oSettings.fnRecordsTotal(),
                        "iFilteredTotal": oSettings.fnRecordsDisplay(),
                        "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                        "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                    };
                };

                var base_url = "<?php echo base_url();?>/"; // You can use full url here but I prefer like this
                $('#datatable').DataTable({
                   "pageLength" : 10,
                   "serverSide": true,
                   "ordering": false, // Set true agar bisa di sorting
                    "processing": true,
                    "language": {
                      processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                      searchPlaceholder: "Pencarian..."
                    },

                   "order": [[1, "desc" ]],
                   "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    },
                   "ajax":{
                            url :  base_url+'dealer/sppu/getAllDataMonitor',
                            type : 'POST'
                          },
                }); // End of DataTable
              }); 
            </script>

<?php 
    }elseif($set=="detail_no_mesin"){ 
      $sopir = $this->db->get_where('ms_plat_dealer',['id_master_plat'=>$row->id_master_plat]);
      $sopir = $sopir->num_rows()>0?$sopir->row()->driver:'';
      $sales_ = $this->db->query("SELECT nama_lengkap,ms_karyawan_dealer.id_flp_md FROM tr_prospek 
        JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
        WHERE id_customer='$row->id_customer' 
        ORDER BY tr_prospek.created_at DESC LIMIT 1
        ");
      $sales='';$sales_id='';
      if ($sales_->num_rows()>0) {
        $sl = $sales_->row();
        $sales = $sl->nama_lengkap;
        $sales_id= $sl->id_flp_md;
      }
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/sppu">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Delivery Document ID</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $row->delivery_document_id ?>">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID SPK</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $row->no_spk ?>">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No. Sales Order</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $row->id_sales_order ?>">
                  </div>                  
                </div>
                 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $row->nama_konsumen ?>">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi Tipe Unit</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $row->id_tipe_kendaraan ?>">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi Warna</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $row->id_warna ?>">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor Rangka</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $row->no_rangka ?>">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor Mesin</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $row->no_mesin ?>">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $row->alamat ?>">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Latitude</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $row->latitude ?>">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Longitude</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $row->longitude ?>">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor Kontak</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $row->no_hp ?>">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Pengiriman</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $row->tgl_pengiriman ?>">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Waktu Pengiriman</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $row->waktu_pengiriman ?>">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Checklist Kelengkapan Unit</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Lokasi Pengiriman</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $row->lokasi_pengiriman ?>">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Penerima</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $row->nama_penerima ?>">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Kontak Penerima</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $row->no_hp_penerima ?>">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Driver</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $sopir ?>">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Sales People ID</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $sales_id ?>">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Sales</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $sales ?>">
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Status Delivery Document</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?= ucwords(str_replace('_', ' ', $row->status_delivery)) ?>">
                  </div>                  
                </div>
              </div>            
          </div>
        </div>
      </div>      
    </div><!-- /.box -->  
    <?php
    }
    ?>
  </section>
</div>
<div class="modal fade" id="Nosinmodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Cari No Mesin
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="10%"></th>              
              <th>No Mesin</th>
              <th>No Rangka</th>                                    
              <th>Tipe Motor</th>                                               
              <th>Warna</th>              
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1; 
          $id_dealer = $this->m_admin->cari_dealer();
         /* $dt_nosin = $this->db->query("SELECT tr_scan_barcode.no_mesin,tr_scan_barcode.no_rangka,ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,ms_warna.id_warna,ms_warna.warna FROM tr_sales_order 
            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
            INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan             
            INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna WHERE tr_scan_barcode.status = 1
            ORDER BY tr_scan_barcode.no_mesin ASC"); */
            $nosin = $this->db->query("
              SELECT tr_sales_order.*,tr_prospek.nama_konsumen,tr_spk.alamat,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka FROM tr_sales_order 
      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
      INNER JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
      INNER JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
      INNER JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna
      WHERE tr_sales_order.id_dealer = '$id_dealer' and tgl_cetak_invoice <> NULL
      ORDER BY tr_sales_order.id_sales_order ASC");
          foreach ($nosin->result() as $ve2) {
            echo "
            <tr>"; ?>
              <td class="center">
                <button title="Choose" data-dismiss="modal" onclick="chooseitem('<?php echo $ve2->no_mesin; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
              </td>
              <?php echo "
              <td>$ve2->no_mesin</td>
              <td>$ve2->no_rangka</td>            
              <td>$ve2->tipe_ahm</td>
              <td>$ve2->id_warna | $ve2->warna</td>";
              ?>                         
            </tr>
            <?php
            $no++;
          }
          ?>
          </tbody>
        </table>
      </div>      
    </div>
  </div>
</div>
<script type="text/javascript">
function auto(){
  var tgl_js = "1"; 
  $.ajax({
      url : "<?php echo site_url('dealer/sppu/cari_id')?>",
      type:"POST",
      data:"tgl="+tgl_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#no_sppu").val(data[0]);                        
        kirim_data_sppu();
      }        
  })
}
function kirim_data_sppu(){    
  $("#tampil_sppu").show();
  var no_sppu = document.getElementById("no_sppu").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "no_sppu="+no_sppu;                           
     xhr.open("POST", "dealer/sppu/t_sppu", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_sppu").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    }   
}
function chooseitem(no_mesin){
  document.getElementById("no_mesin").value = no_mesin; 
  cek_nosin();
  $("#Nosinmodal").modal("hide");
}
function cek_nosin(){
  var no_mesin = $("#no_mesin").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/sppu/cek_nosin')?>",
      type:"POST",
      data:"no_mesin="+no_mesin,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");
          if(data[0]=="ok"){          
            $("#no_mesin").val(data[1]);                
            $("#no_rangka").val(data[2]);                                        
            $("#kode_item").val(data[3]);                                        
            $("#tipe").val(data[4]);                                        
            $("#warna").val(data[5]);                                        
            $("#nama_lengkap").val(data[6]);                                        
            $("#alamat").val(data[7]);                                        
          }else{
            alert(data[0]);
          }
      } 
  })
}
function simpan_sppu(){  
  var no_sppu       = document.getElementById("no_sppu").value;    
  var no_mesin      = document.getElementById("no_mesin").value;    
  
  if (no_mesin == "" || no_sppu == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('dealer/sppu/save_sppu')?>",
          type:"POST",
          data:"no_sppu="+no_sppu+"&no_mesin="+no_mesin,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="nihil"){
                  kirim_data_sppu();
                  kosong();                
              }else{
                  alert("Gagal Simpan, NIK ini sudah dimasukkan");
                  kosong();                  
              }                
          }
      })    
  }
}
function kosong(args){
  $("#no_rangka").val("");
  $("#no_mesin").val("");     
  $("#nama_lengkap").val("");     
  $("#alamat").val("");     
  $("#kode_item").val("");     
  $("#tipe_ahm").val("");     
  $("#warna").val("");       
}
function hapus_sppu(a,b){ 
    var no_mesin  = a;       
    var id_sppu_detail  = b;       
    $.ajax({
        url : "<?php echo site_url('dealer/sppu/delete_sppu')?>",
        type:"POST",
        data:"id_sppu_detail="+id_sppu_detail,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data_sppu();
            }
        }
    })
}
</script>