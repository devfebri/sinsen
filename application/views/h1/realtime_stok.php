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
if(isset($_GET['id'])){
?>
<body onload="auto()">
<?php }else{ ?>
<body onload="auto()">
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
    <li class="">Kontrol Unit</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    
    <?php
    if($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <!--a href="h1/kekurangan_ksu/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a-->          
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
              <td width="5%">No</td>
              <td>Action</td>
              <td>Kode Item</td>              
              <td>Tipe</td>              
              <td>Warna</td>              
              <td colspan="3">RFS</td>              
              <td>NRFS</td>              
              <td>Pinjaman</td>              
              <td>Unfill</td>              
              <td>Intransit</td>
              <td>Total</td>              
            </tr>           
            <tr>
              <td colspan="5"></td>
              <td>Ready</td>
              <td>Booking</td>
              <td>PL Approved</td>
              <td colspan="5"></td>              
            </tr> 
          </thead>
          <tbody>            
          <?php 
          $no=1;
          $t_rfs=0;$t_nrfs=0;$t_pinj=0;$t_book=0;$tot=0;$t_unfill=0;$t_in=0; 
          foreach($dt_real_stock->result() as $row) {               

            $cek_ready = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$row->id_item' AND status = '1' AND tipe='RFS'")->row();
            $cek_booking = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$row->id_item' AND status = '2'")->row();
            $cek_pl = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$row->id_item' AND status = '3'")->row();
            $cek_nrfs = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$row->id_item' AND tipe = 'NRFS'")->row();
            $cek_pinjaman = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$row->id_item' AND tipe = 'PINJAMAN'")->row();

            $cek_sl = $this->db->query("SELECT COUNT(id_modell) AS jum FROM tr_shipping_list 
                        WHERE no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_scan_barcode WHERE no_shipping_list IS NOT NULL) 
                        AND id_modell = '$row->id_tipe_kendaraan'")->row();

            $cek_in = $this->db->query("SELECT SUM(tr_sipb.jumlah) AS jum FROM tr_sipb INNER JOIN tr_shipping_list ON tr_sipb.no_sipb  = tr_shipping_list.no_sipb
                        WHERE tr_shipping_list.no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_scan_barcode WHERE no_shipping_list IS NOT NULL) 
                        AND tr_sipb.id_tipe_kendaraan = '$row->id_tipe_kendaraan'")->row();
            $total = $cek_ready->jum + $cek_booking->jum + $cek_pl->jum + $cek_nrfs->jum  + $cek_pinjaman->jum + $cek_sl->jum + $cek_in->jum;


            $cek_th = $this->db->query("SELECT DISTINCT(tahun_produksi) AS tahun FROM tr_fkb WHERE kode_tipe = '$row->id_tipe_kendaraan' AND kode_warna = '$row->id_warna'");
            if($cek_th->num_rows() > 0){
              $th = $cek_th->row();
              $tahun = $th->tahun;
            }else{
              $tahun = "";
            }
            
            echo "
            <tr>
              <td>$no</td>
              <td>
                <a href='h1/realtime_stok/detail?id=$row->id_item'>
                  <button type='button' title='Detail' class='btn bg-maroon btn-flat btn-sm'><i class='fa fa-eye'></i> Detail</button>
                </a>
              </td>
              <td>$row->id_tipe_kendaraan</td>
              <td>$row->tipe_ahm</td>
              <td>$row->warna</td>              
              <td>$cek_ready->jum</td>              
              <td>$cek_booking->jum</td>              
              <td>$cek_pl->jum</td>                                        
              <td>$cek_nrfs->jum</td>              
              <td>$cek_pinjaman->jum</td>                            
              <td>$cek_sl->jum</td>                            
              <td>$cek_sl->jum</td>                            
              <td>$total</td>              
            </tr>
            ";
          $no++;
          $t_rfs  = $t_rfs + $cek_pl->jum + $cek_ready->jum + $cek_booking->jum;
          $t_nrfs = $t_nrfs + $cek_nrfs->jum;
          $t_pinj = $t_pinj + $cek_pinjaman->jum;
          $t_unfill = $t_unfill + $cek_sl->jum;
          $t_in = $t_in + $cek_in->jum;
          $tot    = $tot + $total;
          }
          ?>
          </tbody>    
        </table>  
        <b>    
          QTY RFS : <?php echo $t_rfs ?> <br>
          QTY NRFS : <?php echo $t_nrfs ?> <br>
          QTY Pinjaman : <?php echo $t_pinj ?> <br>
          QTY Unfill : <?php echo $t_unfill ?> <br>
          QTY Intransit : <?php echo $t_in ?> <br>
          Total : <?php echo $tot ?>            
        </b>
                              
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php 
    }elseif($set=='view_final'){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">          
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body" style="min-height: 800px;">
        <table class="table table-bordered table-hovered table-striped " id="showDetailStok">             
        </table>
      </div>                      
    </div>
  

    <?php
    }elseif($set=="view_fix"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <!--a href="h1/kekurangan_ksu/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a-->          
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
        <table id="table" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <td width="5%">No</td>
              <td>Action</td>
              <td>Kode Item</td>              
              <td>Tipe</td>              
              <td>Warna</td>              
              <td colspan="2" align="center">RFS</td>              
              <td>NRFS</td>              
              <td>Pinjaman</td>              
              <td>Unfill</td>              
              <td>Intransit</td>
              <td>Total</td>              
            </tr>           
            <tr>
              <td colspan="5"></td>
              <td>Ready</td>
              <td>Booking</td>              
              <td colspan="5"></td>              
            </tr>             
          </thead>
          <tbody>            
          </tbody>
          <tfoot>
            <tr  bgcolor="yellow">
              <th colspan="5"></th>              
              <th></th>
              <th></th>
              <th></th>
              <th></th>              
              <th></th>
              <th></th>
              <th></th>
            </tr>
            <?php 
            $a1=0;$a2=0;$a3=0;$a4=0;$a5=0;$a6=0;$a7=0;$a8=0;
            $list = $this->m_admin->getAll("tr_real_stock");
            foreach ($list->result() as $isi) {
              $dt = $this->db->query("SELECT ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,ms_item.bundling,ms_item.id_item_lama,ms_item.id_warna_lama FROM ms_item 
                    INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                    INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna             
                    WHERE ms_item.id_item = '$isi->id_item'");
              if($dt->num_rows() > 0){
                $r = $dt->row();
                $tipe_ahm = $r->tipe_ahm;
                $warna = $r->warna;
                $bundling = $r->bundling;
                $id_item_lama = $r->id_item_lama;
                $id_warna_lama = $r->id_warna_lama;
              }else{
                $tipe_ahm="";$warna="";$bundling="";$id_item_lama="";$id_warna_lama="";
              }

              $cek_ready = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND status = '1' AND tipe='RFS'")->row();
              $cek_booking = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND status = '2'")->row();
              $cek_pl = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND status = '3'")->row();
              $cek_nrfs = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND tipe = 'NRFS' AND status < 4")->row();
              $cek_pinjaman = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND tipe = 'PINJAMAN' AND status < 4")->row();
              $cek_sl = $this->db->query("SELECT COUNT(id_modell) AS jum FROM tr_shipping_list 
                                WHERE no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_scan_barcode) 
                                AND id_modell = '$isi->id_tipe_kendaraan' AND id_warna = '$isi->id_warna'")->row();

              if($bundling == 'Ya'){
                $id_tipe = $id_item_lama;
                $id_warna = $id_warna_lama;
              }else{
                $id_tipe  = $isi->id_tipe_kendaraan;
                $id_warna = $isi->id_warna;
              }
              $cek_sl1 = $this->db->query("SELECT COUNT(id_modell) AS jum FROM tr_shipping_list WHERE 
                  tr_shipping_list.id_modell = '$isi->id_tipe_kendaraan' AND tr_shipping_list.id_warna = '$isi->id_warna'")->row();
              if($bundling != 'Ya'){              
        $cek_sl2_1 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode
          LEFT JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item  
          WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' AND tr_scan_barcode.warna = '$isi->id_warna'")->row();      
        $cek_sl2_2 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode
          LEFT JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item  
          WHERE ms_item.id_item_lama = '$isi->id_tipe_kendaraan' AND ms_item.id_warna_lama = '$isi->id_warna'")->row();      
        if(isset($cek_sl2_2->jum)){
          $jumlah_sl = $cek_sl2_1->jum + $cek_sl2_2->jum; 
        }else{
          $jumlah_sl = $cek_sl2_1->jum;
        }
      }else{
        $cek_sl2 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode
          LEFT JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item  
          WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' AND tr_scan_barcode.warna = '$isi->id_warna'")->row();             
        $jumlah_sl = $cek_sl2->jum;
      }
              


              $cek_in1 = $this->db->query("SELECT SUM(tr_sipb.jumlah) AS jum FROM tr_sipb INNER JOIN ms_item ON ms_item.id_tipe_kendaraan = tr_sipb.id_tipe_kendaraan AND ms_item.id_warna = tr_sipb.id_warna 
                WHERE tr_sipb.id_tipe_kendaraan = '$isi->id_tipe_kendaraan' AND tr_sipb.id_warna = '$isi->id_warna'
                AND ms_item.bundling <> 'Ya'")->row();                
              $cek_in2 = $this->db->query("SELECT COUNT(tr_shipping_list.no_mesin) AS jum FROM tr_shipping_list INNER JOIN ms_item ON tr_shipping_list.id_modell = ms_item.id_tipe_kendaraan AND tr_shipping_list.id_warna=ms_item.id_warna
                WHERE tr_shipping_list.id_modell = '$isi->id_tipe_kendaraan' AND tr_shipping_list.id_warna = '$isi->id_warna'
                AND ms_item.bundling <> 'Ya'")->row();
              $cek_item = $this->db->query("SELECT * FROM ms_item WHERE id_item = '$isi->id_item'")->row();
              $sipb = 0;
              $total = $cek_ready->jum + $cek_booking->jum + $cek_pl->jum + $cek_nrfs->jum  + $cek_pinjaman->jum;
              if($cek_in1->jum - $cek_in2->jum > 0 AND $cek_item->bundling != 'Ya'){
                $rr = $cek_in1->jum - $cek_in2->jum;
              }else{
                $rr = 0;
              }

              $cek_sl2_jum=0;$cek_sl1_jum=0;
        if(isset($cek_sl1->jum)) $cek_sl1_jum = $cek_sl1->jum;
        if(isset($jumlah_sl)) $cek_sl2_jum = $jumlah_sl;      
        if($cek_sl1_jum - $cek_sl2_jum >= 0 AND $cek_item->bundling != 'Ya'){            
          $r2 = $cek_sl1_jum - $cek_sl2_jum;     
        }else{
          $r2 = 0;
        }

              $a1 = $a1 + $cek_ready->jum;
              $a2 = $a2 + $cek_booking->jum;
              $a3 = $a3 + $cek_pl->jum;
              $a4 = $a4 + $cek_nrfs->jum;
              $a5 = $a5 + $cek_pinjaman->jum;
              $a6 = $a6 + $rr;
              $a7 = $a7 + $r2;
              $a8 = $a8 + $total;
              
            }
            ?>
            <tr  bgcolor="#00AFEF">
              <th colspan="5">Grand Total</th>              
              <th><?php echo $a1 ?></th>
              <th><?php echo $a2 ?></th>
              <th><?php echo $a3 ?></th>
              <th><?php echo $a4 ?></th>
              <th><?php echo $a5 ?></th>
              <th><?php echo $a6 ?></th>
              <th><?php echo $a7 ?></th>
              <th><?php echo $a8 ?></th>
            </tr>
          </tfoot>    
        </table>  
        
                              
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }elseif($set=="detail"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/realtime_stok">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-chevron-left"></i> Back</button>
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
        
        $row = $dt_real_stock->row();

        $cek_ready = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$row->id_item' AND status = '1' AND tipe='RFS'")->row();
        $cek_booking = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$row->id_item' AND status = '2'")->row();
        $cek_pl = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$row->id_item' AND status = '3'")->row();
        $cek_nrfs = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$row->id_item' AND tipe = 'NRFS' AND status < 4")->row();
        $cek_pinjaman = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$row->id_item' AND tipe = 'PINJAMAN' AND status < 4")->row();
        $total = $cek_ready->jum + $cek_booking->jum + $cek_pl->jum;
        ?>
        <div class="col-md-12">
          <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
            <div class="box-body">              
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Kode Item</label>
                <div class="col-sm-4">
                  <input type="text" required class="form-control" value="<?php echo $row->id_item ?>" id="id_niguri" readonly placeholder="Kode Item" name="kode_item">
                </div>              
                <label for="inputEmail3" class="col-sm-2 control-label">QTY RFS</label>
                <div class="col-sm-4">
                  <input type="text" required class="form-control" value="<?php echo $total ?>" id="id_niguri" readonly placeholder="QTY RFS" name="qty_rfs">                  
                </div>                 
              </div>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                <div class="col-sm-4">
                  <input type="text" required class="form-control" value="<?php echo $row->tipe_ahm ?>" id="id_niguri" readonly placeholder="Tipe" name="kode_item">
                </div>              
                <label for="inputEmail3" class="col-sm-2 control-label">QTY NRFS</label>
                <div class="col-sm-4">
                  <input type="text" required class="form-control" value="<?php echo $cek_nrfs->jum ?>" id="id_niguri" readonly placeholder="QTY NRFS" name="qty_nrfs">                  
                </div>                 
              </div>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                <div class="col-sm-4">
                  <input type="text" required class="form-control" value="<?php echo $row->warna ?>" id="id_niguri" readonly placeholder="Warna" name="kode_item">
                </div>              
                <label for="inputEmail3" class="col-sm-2 control-label">QTY Pinjam</label>
                <div class="col-sm-4">
                  <input type="text" required class="form-control" value="<?php echo $cek_pinjaman->jum ?>" id="id_niguri" readonly placeholder="QTY Pinjaman" name="qty_nrfs">                  
                </div>                 
              </div>
              <!-- <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Tahun Produksi</label>
                <div class="col-sm-4">
                  <input type="text" required class="form-control" value="2015" id="id_niguri" readonly placeholder="Tahun Produksi" name="kode_item">
                </div>              
                <label for="inputEmail3" class="col-sm-2 control-label">QTY Booking SO</label>
                <div class="col-sm-4">
                  <input type="text" required class="form-control" value="<?php echo $row->stok_booking ?>" id="id_niguri" readonly placeholder="QTY Booking" name="qty_nrfs">                  
                </div>                 
              </div> -->
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label"></label>
                <div class="col-sm-4">
                  
                </div>              
                <label for="inputEmail3" class="col-sm-2 control-label">Total QTY</label>
                <div class="col-sm-4">
                  <input type="text" required class="form-control" value="<?php echo $cek_nrfs->jum+$total + $cek_pinjaman->jum ?>" id="id_niguri" readonly placeholder="Total QTY" name="qty_nrfs">                  
                </div>                 
              </div>                                            
              
              <div class="form-group">                
                <button class="btn btn-block btn-success btn-flat" type="button">Detail</button>
                <table id="example1" class="table table-bordered table-hover">
                  <thead>
                    <tr>              
                      <th width="5%">No</th>
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                      <th>No FIFO</th>              
                      <th>Lokasi</th>              
                      <th>Slot</th>
                      <th>Status</th> 
                      <th>Status Stok</th>                                   
                    </tr>            
                  </thead>
                  <tbody>            
                  <?php 
                  $no=1; 
                  foreach($dt_scan_barcode->result() as $row) {   
                    if($row->status=='1'){
                      $status = "<span class='label label-success'>ready</span>";
                    }elseif($row->status=='2'){
                      $status = "<span class='label label-warning'>booking</span>";
                    }elseif($row->status=='3'){
                      $status = "<span class='label label-primary'>process</span>";
                    }elseif($row->status=='4'){
                      $status = "<span class='label label-success'>sold</span>";
                    }


                   
                    echo "
                    <tr>
                      <td>$no</td>                      
                      <td>$row->no_mesin</td>
                      <td>$row->no_rangka</td>
                      <td>$row->fifo</td>
                      <td>$row->lokasi</td>              
                      <td>$row->slot</td>              
                      <td>$row->tipe</td>                                    
                      <td>$status</td>                                    
                    </tr>
                    ";
                  $no++;
                  }
                  ?>
                  </tbody>                  
                </table>
              </div>
            </div>
          </form>
        </div>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }
    ?>
  </section>
</div>

<!-- <script type="text/javascript">

var table;

$(document).ready(function() {
    //datatables   

    table = $('#table').DataTable({
        
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('h1/realtime_stok/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [0,1,4,5,6,7,8,9,10,11,12], //first column / numbering column
            "orderable": false, //set not orderable
            'summary': summary,
        },
        ],
        
            } );        
            
});
</script> -->
<script type="text/javascript">
$(document).ready(function() {
    $('#table').dataTable({
      "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // converting to interger to find total
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // computing column Total of the complete result 
            var monTotal = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        
      var tueTotal = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        
            var wedTotal = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        
       var thuTotal = api
                .column( 8 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        
       var friTotal = api
                .column( 9 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

        var satTotal = api
                .column( 10 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

        var sunTotal = api
                .column( 11 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

        var senTotal = api
                .column( 12 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
      
        
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Sub Total');
            $( api.column( 5 ).footer() ).html(monTotal);
            $( api.column( 6 ).footer() ).html(tueTotal);
            $( api.column( 7 ).footer() ).html(wedTotal);
            $( api.column( 8 ).footer() ).html(thuTotal);
            $( api.column( 9 ).footer() ).html(friTotal);
            $( api.column( 10 ).footer() ).html(satTotal);
            $( api.column( 11 ).footer() ).html(sunTotal);
            $( api.column( 12 ).footer() ).html(senTotal);
        },
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?php echo site_url('h1/realtime_stok/ajax_list')?>",
            "type": "POST"
        },
        "columnDefs": [
        {
            "targets": [0,1,4,5,6,7,8,9,10,11,12], //first column / numbering column
            "orderable": false, //set not orderable            
        },
        ]

    } );
} );
</script>
<script type="text/javascript">
function loadDatatables(el) {
  scrolly=800;
  ordering =false;  
  if (el=='showDetailStok') {
    ordering=true;
  }
  console.log(el);
  console.log(scrolly);
  $('#'+el).DataTable({
      'paging':false,
      'bLengthChange': false,
      "bInfo" : false,
      'searching': true,
      'ordering': ordering,
      'info': false,
      'scrollY': '800px',
      'scrollX':true,
      'scrollCollapse': true,
      'autoWidth': true,

    })
}
function auto() {
  $.ajax({
        beforeSend: function() {
          $('#showDetailStok').html('<tr><td colspan=13 style="font-size:12pt;text-align:center">Processing...</td></tr>');
        },
        url: "<?php echo site_url('dashboard/realtime_stok_md')?>",
        type:"POST",
        data:"",            
        cache:false,
        success:function(response){                
           $('#showDetailStok').html(response);
           loadDatatables('showDetailStok');
        } 
    })
}
</script>