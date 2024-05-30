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
    <li class="">Penerimaan</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    

    <?php 
    if($set == 'insert'){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/kekurangan_ksu">
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
        <form action="h1/kekurangan_ksu/save_ksu" method="post">          
        <button type="submit" onclick="return confirm('Are you sure to save all data?')" class="btn btn-info btn-flat pull-right"><i class="fa fa-save"></i> Save All</button>          
        <input type="hidden" value="<?php echo $_GET['id'] ?>" name="no_sl">
        <br><br>
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>
              <th>No SL</th>
              <th>No Mesin</th>              
              <th>Tipe</th>              
              <th>Warna</th>              
              <th>Kode Item</th>
              <th>KSU</th>
            </tr>
          </thead>
          <tbody>            
            <?php 
            $no = 1;
            foreach ($dt_rfs->result() as $row) {
              echo "
              <tr>
                <td>$no</td>
                <td>$row->no_shipping_list</td>
                <td>$row->no_mesin</td>
                <td>$row->tipe_ahm</td>                
                <td>$row->warna</td>                
                <td>$row->id_item</td>                
                <td>";
                $cek = $this->db->query("SELECT id_ksu FROM ms_koneksi_ksu WHERE id_tipe_kendaraan = '$row->tipe_motor'");
                if(count($cek) > 0){
                  $isi = $cek->row();
                  $tr = $isi->id_ksu;
                  $arr=explode(",",$tr);                  
                  foreach($arr as $i){
                    $cek2 = $this->db->query("SELECT id_ksu,ksu FROM ms_ksu WHERE id_ksu = '$i'");
                    if(count($cek2) > 0){
                      $rd = $cek2->row();
                      $rty = $this->db->query("SELECT * FROM tr_kekurangan_ksu WHERE id_ksu = '$rd->id_ksu' AND no_mesin = '$row->no_mesin'");
                      if($rty->num_rows() == 0){
                        echo "
                           <div class='input-group'>
                            <span class='input-group-addon bg-maroon'>$rd->ksu</span>                         
                            <input type='hidden' name='id_ksu[]' value='$rd->id_ksu'>
                            <input type='hidden' name='no_mesin[]' value='$row->no_mesin'>
                            <input type='text' onkeypress='return number_only(event)' value='1' name='qty[]' class='input-group-addon input-block' style='width:50px;'>
                          </div> <br>";                        
                      }else{
                        $ui = $rty->row();
                        echo "
                           <div class='input-group'>
                            <span class='input-group-addon bg-maroon'>$rd->ksu</span>                         
                            <input type='hidden' name='id_ksu[]' value='$rd->id_ksu'>
                            <input type='hidden' name='no_mesin[]' value='$row->no_mesin'>
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
    }elseif($set=="view"){
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
              <th width="5%">No</th>
              <th>ID Penerimaan Unit</th>              
              <th>Total Unit</th>              
              <th>Total Penerimaan KSU</th>  
              <th width="5%"></th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1;$sisa2=0;$sisa=0;$jum_ksu=0;
          foreach($dt_ksu->result() as $row) {              
            $unit = $this->db->query("SELECT COUNT(no_mesin) AS nosin FROM tr_scan_barcode 
                INNER JOIN tr_penerimaan_unit_detail ON tr_scan_barcode.no_shipping_list = tr_penerimaan_unit_detail.no_shipping_list
                WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$row->id_penerimaan_unit'");
            if($unit->num_rows() > 0){
              $jum = $unit->row();
              $total_unit = $jum->nosin;
            }else{
              $total_unit = 0;
            }

            $ksu = $this->db->query("SELECT SUM(qty) AS t_ksu FROM tr_penerimaan_ksu 
                  WHERE tr_penerimaan_ksu.id_penerimaan_unit = '$row->id_penerimaan_unit'");
            if($ksu->num_rows() > 0){
              $jum = $ksu->row();
              $total_ksu = $jum->t_ksu;
            }else{
              $total_ksu = 0;
            }

            $ksu2 = $this->db->query("SELECT * FROM tr_penerimaan_ksu 
                  WHERE tr_penerimaan_ksu.id_penerimaan_unit = '$row->id_penerimaan_unit'");
            $r = $ksu2->row();
            $cek_=0;
            foreach ($ksu2->result() as $isi) {
              if($isi->qty < $total_unit){
                $cek_ = $cek_ + 1;
              }
            }

            $cek_tipe = $this->db->query("SELECT DISTINCT(id_tipe_kendaraan) AS tipe FROM tr_penerimaan_ksu 
                  WHERE tr_penerimaan_ksu.id_penerimaan_unit = '$row->id_penerimaan_unit'");
            $tot_ks=0;$id=0;$hasil=0;$jum_ksu=0; 
            foreach ($cek_tipe->result() as $od) {
              $cek_tipe = $this->db->query("SELECT count(no_mesin) AS jumlah FROM tr_scan_barcode 
                  INNER JOIN tr_penerimaan_unit_detail ON tr_scan_barcode.no_shipping_list = tr_penerimaan_unit_detail.no_shipping_list
                  WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$row->id_penerimaan_unit' AND tipe_motor = '$od->tipe'")->row();
              $kol = $cek_tipe->jumlah;
              $cek = $this->db->query("SELECT id_ksu FROM ms_koneksi_ksu_detail INNER JOIN ms_koneksi_ksu 
                  ON ms_koneksi_ksu.id_koneksi_ksu = ms_koneksi_ksu_detail.id_koneksi_ksu 
                  WHERE ms_koneksi_ksu.id_tipe_kendaraan = '$od->tipe'");
              if(count($cek) > 0){
                $isi = $cek->row();
                $jml_ksu = $cek->num_rows();            
                foreach ($cek->result() as $isi) {                    
                  $cek2 = $this->db->query("SELECT id_ksu,ksu FROM ms_ksu WHERE id_ksu = '$isi->id_ksu'");
                  if(count($cek2) > 0){
                    $rd = $cek2->row();
                    $rty = $this->db->query("SELECT * FROM tr_penerimaan_ksu WHERE id_ksu = '$rd->id_ksu' AND no_sl = '$r->no_sl' 
                        AND id_tipe_kendaraan = '$od->tipe'");
                    if($rty->num_rows() == 0){
                      $jum_ksu = 0;
                    }else{
                      $ui = $rty->row();
                      $jum_ksu = $ui->qty;
                    }                  
                    //$sisaw   = $jum_ksu;
                    if($jum_ksu <= 0){
                      $er = "readonly";
                    }else{
                      $er = "";
                    }                                                      
                  }
                }
                $sisa   = $sisa + $jum_ksu;              
                $tot_ks = $kol * $jml_ksu;              
                $hasil = $hasil + $tot_ks;
              }
            }
           
            if($total_ksu < $hasil AND $sisa != 0){              

              echo "  
              <tr>              
                <td>$no</td>
                <td>$row->id_penerimaan_unit</td>              
                <td>$total_unit Unit</td>              
                <td>$total_ksu Item</td>              
                <td>";                
                $cek_status = $this->db->query("SELECT * FROM tr_penerimaan_unit WHERE id_penerimaan_unit = '$row->id_penerimaan_unit'")->row();
                if(isset($cek_status->status) AND $cek_status->status != 'input'){ 
                  echo "
                  <a href='h1/kekurangan_ksu/terima?id=$row->id_penerimaan_unit'>
                    <button type='button' class='btn btn-flat btn-sm btn-success'><i class='fa fa-download'></i> Terima</button>
                  </a>
                  <a>
                    <button type='button' class='btn btn-flat btn-sm btn-primary'><i class='fa fa-print'></i> Cetak Sisa Oustanding</button>
                  </a>";
                }
                
                echo "</td>
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

    <?php
    }elseif($set=="detail"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/kekurangan_ksu">
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
                
        ?>
        <form action="h1/kekurangan_ksu/save_ksu" method="post">          
        <button type="submit" onclick="return confirm('Are you sure to save all data?')" class="btn btn-info btn-flat pull-right"><i class="fa fa-save"></i> Save All</button>          
        <input type="hidden" value="<?php echo $_GET['id'] ?>" name="id_pu">
        <br><br>
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>
              <th>No SL</th>
              <th>Tipe</th>              
              <th>Warna</th>              
              <th>Kode Item</th>
              <th>Total Unit</th>
              <th>Penerimaan KSU</th>
              <th>Kekurangan KSU</th>
              <!-- <th>KSU yang Diterima</th> -->
              <th>Terima Kekurangan AHM</th>
              <th>Terima Kekurangan Ekspedisi</th>
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
              }else{
                $total_unit = 0;
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
                            <input type='text' readonly onkeypress='return number_only(event)' value='0' class='input-group-addon input-block' style='width:50px;'>
                          </div> <br>";                        
                      }else{
                        $ui = $rty->row();
                        echo "
                           <div class='input-group'>
                            <span class='input-group-addon bg-maroon'>$rd->id_ksu $rd->ksu</span>                                                   
                            <input type='text' readonly onkeypress='return number_only(event)' value='$ui->qty' class='input-group-addon input-block' style='width:50px;'>
                          </div> <br>";                        
                      }
                    }
                  }                  
                }
                echo "
                </td>    
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
                        $jum_ksu = 0;
                      }else{
                        $ui = $rty->row();
                        $jum_ksu = $ui->qty;
                      }
                        $semua  = $total_unit;                      
                        $sisa   = $total_unit -  $jum_ksu;

                      echo "
                         <div class='input-group'>
                          <span class='input-group-addon bg-blue'>$rd->ksu</span>                                                   
                          <input type='text' readonly onkeypress='return number_only(event)' value='$sisa' class='input-group-addon input-block' style='width:50px;'>
                        </div> <br>";                                              
                    }
                  }                  
                }
                echo "
                </td>   
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
                        $jum_ksu = 0;
                      }else{
                        $ui = $rty->row();
                        $jum_ksu = $ui->qty;
                      }
                        $semua  = $total_unit;                      
                        $sisa   = $total_unit -  $jum_ksu;
                      if($sisa <= 0){
                        $er = "";
                      }else{
                        $er = "";
                      }
                      echo "
                         <div class='input-group'>
                          <span class='input-group-addon bg-green'>$rd->ksu</span>                         
                          <input type='hidden' name='id_ksu[]' value='$rd->id_ksu'>
                          <input type='hidden' name='tipe_motor[]' value='$row->tipe_motor'>
                          <input type='hidden' name='no_sl[]' value='$row->no_shipping_list'>
                          <input type='text' $er onkeypress='return number_only(event)' value='$sisa' name='qty[]' class='input-group-addon input-block' style='width:50px;'>
                        </div> <br>";                                              
                    }
                  }                  
                }
                echo "
                </td>
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
                        $jum_ksu = 0;
                      }else{
                        $ui = $rty->row();
                        $jum_ksu = $ui->qty;
                      }
                        $semua  = $total_unit;                      
                        $sisa   = $total_unit -  $jum_ksu;
                      if($sisa <= 0){
                        $er = "";
                      }else{
                        $er = "";
                      }
                      echo "
                         <div class='input-group'>
                          <span class='input-group-addon bg-green'>$rd->ksu</span>                                                   
                          <input type='text' $er onkeypress='return number_only(event)' value='$sisa' name='qty[]' class='input-group-addon input-block' style='width:50px;'>
                        </div> <br>";                                              
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
    }
    ?>
  </section>
</div>


