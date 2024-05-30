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
<body onload="kirim_data()">
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
          <a href="dealer/guest_book">
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
            <form class="form-horizontal" action="dealer/guest_book/save_new" method="post" enctype="multipart/form-data">
              <button class="btn btn-block btn-warning btn-flat" disabled> DATA CUSTOMER </button> <br>
              <div class="box-body"> 
                <div class="form-group">               
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="Nama Konsumen" id="nama_konsumen" name="nama_konsumen">                    
                  </div>   

                  <label for="inputEmail3" class="col-sm-2 control-label">No HP *</label>
                  <div class="col-sm-4">                    
                    <input type="number" class="form-control" required placeholder="No HP" id="no_hp" name="no_hp">                                                                           
                  </div>
                </div>   
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat *</label>
                  <div class="col-sm-10">
                    <input type="text"  class="form-control" placeholder="Alamat" name="alamat" id="alamat" required>                    
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Customer </label>
                  <div class="col-sm-4">
                    <select class="form-control" name="id_jenis_customer" >
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_jenis_customer->result() as $val) {
                        echo "
                        <option value='$val->id_jenis_customer'>$val->jenis_customer</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Rencana Pembayaran</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="rencana_bayar">
                      <option value="">- choose -</option>
                      <option>Cash</option>
                      <option>Kredit</option>
                    </select>
                  </div>
                </div>

                 <div class="form-group">               
                  <label for="inputEmail3" class="col-sm-2 control-label">Sales</label>
                  <div class="col-sm-10">
                    <select class="form-control select2" name="sales" id="id_sales" >
                      <option value="">- choose -</option>
                       <?php 
                      $id_dealer = $this->m_admin->cari_dealer();
                      $sales = $this->db->query("SELECT a.id_karyawan_dealer,a.nama_lengkap FROM ms_karyawan_dealer as a, ms_jabatan as b WHERE a.id_jabatan=b.id_jabatan AND a.active =1 AND b.jabatan 
                      IN ('Salesman Junior','Salesman Senior','Sales Wing People','Sales Counter Junior','Salesman','Sales Counter','Sales Counter Senior','Sales SWAT','Salesman Training') AND a.id_dealer = '$id_dealer' AND a.id_flp_md <> '' ORDER BY a.nama_lengkap ASC"); 
                      foreach ($sales->result() as $isi) {
                        echo "<option value='$isi->id_karyawan_dealer'>$isi->nama_lengkap</option>";
                      }
                      ?>  
                    </select>
                  </div>   
                </div>   

                <button class="btn btn-block btn-primary btn-flat" disabled> DATA KENDARAAN </button> <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Type Motor</label>
                  <div class="col-sm-4">                    
                    <select class="form-control select2" name="id_tipe_kendaraan" id="id_tipe_kendaraan" onchange="getWarna()">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_tipe->result() as $val) {
                        echo "
                        <option value='$val->id_tipe_kendaraan'>$val->id_tipe_kendaraan | $val->tipe_ahm</option>;
                        ";
                      }
                      ?>
                    </select>                                                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_warna" id="id_warna" >
                      <!-- <option value="">- choose -</option>
                      <?php 
                      foreach($dt_warna->result() as $val) {
                        echo "
                        <option value='$val->id_warna'>$val->id_warna | $val->warna</option>;
                        ";
                      }
                      ?> -->
                    </select>                                                     
                  </div>
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi Warna Motor</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Deskripsi Warna Motor" name="deskripsi_warna">                    
                  </div>                  
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi Motor MKT</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Deskripsi Motor MKT" name="deskripsi_mkt">                    
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
    }elseif($set=="edit"){
      $row = $dt_guest_book->row();
    ?>
    <!-- <body onload="getWarna('<?= $row->id_warna ?>')"> -->
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/guest_book">
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
            <form class="form-horizontal" action="dealer/guest_book/update_new/<?php echo $row->id_guest_book ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <button class="btn btn-block btn-warning btn-flat" disabled> DATA CUSTOMER </button> <br>
                           
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->nama_konsumen ?>" required  placeholder="Nama Konsumen" id="nama_konsumen" name="nama_konsumen">                    
                  </div>                  
                  <input type="hidden" id="id_guest_book" name="id" value="<?php echo $row->id_guest_book ?>">
                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No HP *</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" value="<?php echo $row->no_telp ?>" required  placeholder="No HP" id="no_hp" name="no_hp">                                                                           
                  </div>
                </div>     
                
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat *</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Alamat" required value="<?php echo $row->alamat ?>" name="alamat" id="alamat">                    
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Customer</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="id_jenis_customer" >
                      <option value="<?php echo $row->id_jenis_customer ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_jenis_customer","id_jenis_customer",$row->id_jenis_customer)->row();                                 
                        if(isset($dt_cust)){
                          echo "$dt_cust->jenis_customer";
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_jenis_customer = $this->m_admin->kondisiCond("ms_jenis_customer","id_jenis_customer != '$row->id_jenis_customer'");                                                
                      foreach($dt_jenis_customer->result() as $val) {
                        echo "
                        <option value='$val->id_jenis_customer'>$val->jenis_customer</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Rencana Pembayaran</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="rencana_bayar">
                      <option value="<?php echo $row->rencana_bayar ?>"><?php echo $row->rencana_bayar ?></option>
                      <option>Cash</option>
                      <option>Kredit</option>
                    </select>
                  </div>
                </div>
                
                <div class="form-group">               
                  <label for="inputEmail3" class="col-sm-2 control-label">Sales</label>
                  <div class="col-sm-10">
                    <select class="form-control select2"  name="sales" id="id_sales" >
                      <option value="<?php echo $row->id_karyawan_dealer ?>">- choose -</option>
                       <?php 
                      $id_dealer = $this->m_admin->cari_dealer();
                      $sales = $this->db->query("SELECT a.id_karyawan_dealer,a.nama_lengkap FROM ms_karyawan_dealer as a, ms_jabatan as b WHERE a.id_jabatan=b.id_jabatan and a.active = 1 AND b.jabatan 
                      IN ('Salesman Junior','Salesman Senior','Sales Wing People','Sales Counter Junior','Salesman','Sales Counter','Sales Counter Senior','Sales SWAT','Salesman Training') AND a.id_dealer = '$id_dealer' AND a.id_flp_md <> '' ORDER BY a.nama_lengkap ASC"); 
                      foreach ($sales->result() as $isi) {
                        // echo "<option value='$isi->id_karyawan_dealer'>$isi->nama_lengkap</option>";
                        if ($isi->id_karyawan_dealer == $row->id_karyawan_dealer) {
                          $selected='selected';
                        }else{
                          $selected='';
                        }
                        echo "
                        <option value='$isi->id_karyawan_dealer' $selected>$isi->nama_lengkap</option>;
                        ";
                      }
                      ?>
                      }
                      ?>  
                    </select>
                  </div>   
                </div>   
                
                <button class="btn btn-block btn-primary btn-flat" disabled> DATA KENDARAAN </button> <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Type Motor</label>
                  <div class="col-sm-4">                    
                    <select class="form-control select2" name="id_tipe_kendaraan" onchange="getWarna()" id="id_tipe_kendaraan">
                      <option value="<?php echo $row->id_tipe_kendaraan ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_tipe_kendaraan","id_tipe_kendaraan",$row->id_tipe_kendaraan)->row();                                 
                        if(isset($dt_cust)){
                          echo "$dt_cust->id_tipe_kendaraan | $dt_cust->tipe_ahm";
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_tipe = $this->m_admin->kondisiCond("ms_tipe_kendaraan","id_tipe_kendaraan != '$row->id_tipe_kendaraan'");                                                
                      foreach($dt_tipe->result() as $val) {
                        echo "
                        <option value='$val->id_tipe_kendaraan'>$val->id_tipe_kendaraan | $val->tipe_ahm</option>;
                        ";
                      }
                      ?>
                    </select>                                                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="hidden" id="warna_mode">                    
                    <select class="form-control select2" name="id_warna" id="id_warna">
                        
                        <option value=''></option>;
                       <?php 
                      foreach($dt_warna->result() as $val) {
                        if ($val->id_warna == $row->id_warna) {
                          $selected='selected';
                        }else{
                          $selected='';
                        }
                        echo "
                        <option value='$val->id_warna' $selected>$val->id_warna | $val->warna</option>;
                        ";
                      }
                      ?>
                    </select>                                                     
                  </div>
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi Warna Motor</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Deskripsi Warna Motor" value="<?php echo $row->deskripsi_warna ?>" name="deskripsi_warna">                    
                  </div>                  
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi Motor MKT</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Deskripsi Motor MKT" value="<?php echo $row->deskripsi_mkt ?>" name="deskripsi_mkt">                    
                  </div>                  
                </div>               
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to update all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="detail"){
      $row = $dt_guest_book->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/guest_book">
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
            <form class="form-horizontal" action="dealer/guest_book/update" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <button class="btn btn-block btn-warning btn-flat" disabled> DATA CUSTOMER </button> <br>
                <div class="form-group">
                  <input type="hidden" id="id_guest_book" name="id" value="<?php echo $row->id_guest_book ?>">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID List Appointment</label>
                  <div class="col-sm-4">
                   <input type="text" name="" readonly value="<?= $row->id_list_appointment ?> " class="form-control" >
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" value="<?php echo $row->no_hp ?>" readonly placeholder="No HP" id="no_hp" name="no_hp">                                                                           
                  </div>
                </div>                
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" value="<?php echo $row->nama_konsumen ?>" readonly placeholder="Nama Konsumen" id="nama_konsumen" name="nama_konsumen">                    
                  </div>                  
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Alamat" value="<?php echo $row->alamat2 ?>" name="alamat" id="alamat" disabled>                    
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Customer</label>
                  <div class="col-sm-4">
                    <?php $dt_cust    = $this->m_admin->getByID("ms_jenis_customer","id_jenis_customer",$row->id_jenis_customer)->row()->jenis_customer;       ?>
                    <input type="text" name="" readonly class="form-control" value="<?= $dt_cust ?>">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Rencana Pembayaran</label>
                  <div class="col-sm-4">
                    <input type="text" name="" class="form-control" value="<?= $row->rencana_bayar ?>" disabled>
                  </div>
                </div>
                <button class="btn btn-block btn-primary btn-flat" disabled> DATA KENDARAAN </button> <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Type Motor</label>
                  <div class="col-sm-4">   
                    <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_tipe_kendaraan","id_tipe_kendaraan",$row->id_tipe_kendaraan)->row();                                 
                        if(isset($dt_cust)){
                          $kendaraan= "$dt_cust->id_tipe_kendaraan | $dt_cust->tipe_ahm";
                        }else{
                          $kendaraan='';
                        }
                        ?>

                  <input type="text" name="" class="form-control" value="<?= $kendaraan ?>" disabled>           
                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_warna","id_warna",$row->id_warna)->row();                                 
                        if(isset($dt_cust)){
                          $wrn= "$dt_cust->id_warna | $dt_cust->warna";
                        }else{
                          $wrn="";
                        }
                        ?>              
                      <input type="" name="" class="form-control" readonly value="<?= $wrn ?>">                                                  
                  </div>
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi Warna Motor</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Deskripsi Warna Motor" value="<?php echo $row->deskripsi_warna ?>" name="deskripsi_warna" disabled>                    
                  </div>                  
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi Motor MKT</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Deskripsi Motor MKT" value="<?php echo $row->deskripsi_mkt ?>" name="deskripsi_mkt" disabled>                    
                  </div>                  
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
      <div class="box-header with-border">
        <h3 class="box-title">
          <?php if (isset($mode)){ 
            $history=1;
            ?>
                   <a href="dealer/guest_book">
            <button class="btn bg-maroon btn-flat margin">View Data</button>
          </a>  
          <?php }else{ $history=0; ?>
            <a href="dealer/guest_book/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
            <a href="dealer/guest_book?mode=history">
            <!--<button class="btn bg-blue btn-flat margin">History</button>-->
            
          </a>   
          </a>   
          <?php } ?>
                   
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
        <table id="example3" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>    
              <th>Nama Konsumen</th>
              <th>Alamat</th>
              <th>No HP</th> 
              <th>Sales</th> 
              <?php if ($history==0): ?>
                <th>Action</th>              
              <?php endif ?>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
               foreach($dt_guest_book->result() as $row) {

                $id_customer = get_data('ms_karyawan_dealer','id_karyawan_dealer',$row->id_karyawan_dealer,'nama_lengkap');
                echo "                  
                <tr>
                  <td>$no</td>  
                  <td>
                    $row->nama_konsumen
                  </td>
                  <td>$row->alamat</td>
                  <td>$row->no_telp</td>
                  <td>$id_customer</td>
                  ";
                  ?>               
                              
                  <?php if ($history==0): ?>
                  <td width='8%'> 
                                 <a href='dealer/guest_book/edit?id=<?php echo $row->id_guest_book ?>'>
                    <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"edit"); ?> class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button>                
                  </a>   
                                            
                </td><?php endif ?>
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
function take_idlist(){
  var id_list_appointment = $("#id_list_appointment").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/guest_book/take_idlist')?>",
      type:"POST",
      data:"id_list_appointment="+id_list_appointment,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");                    
          $("#no_hp").val(data[0]);                                                    
          $("#nama_konsumen").val(data[1]);                                                    
          $("#alamat").val(data[2]);                                                             
         // $("#id_tipe_kendaraan").val(data[3]);     
          // $("#id_tipe_kendaraan").select2().select2('val',data[3]);                                          
          $('#id_tipe_kendaraan').select2().val(data[3]).trigger('change');              
          $('#id_warna').select2().val(data[4]).trigger('change');  
          getWarna(data[4]);            
      } 
  })
}

function tes(){
  //var id_sales = document.getElementById("id_sales").value;   
  alert("id_sales");
}

function take_appointment(){
  var id_sales = document.getElementById("id_sales").value;   
  $.ajax({
      url: "<?php echo site_url('dealer/guest_book/take_appointment')?>",
      type:"POST",
      data:"id_sales="+id_sales,            
      cache:false,
      success:function(msg){                          
          $("#id_list_appointment").html(msg);
          //alert(id_sales);
      } 
  })
}

function kirim_data(){    
  $("#tampil_data").show();  
  var id_guest_book = document.getElementById("id_guest_book").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_guest_book="+id_guest_book;
     xhr.open("POST", "dealer/guest_book/t_data", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_data").innerHTML = xhr.responseText;
                getDatePicker();
                getWarna('<?= isset($row->id_warna)?$row->id_warna:null; ?>');
            }else{
                alert('There was a problem with the request.');
            }
        }
    }   
}
function tes(){
  alert("ok");
}
function simpan_data(){
  var id_guest_book     = document.getElementById("id_guest_book").value;  
  var tgl_fu            = document.getElementById("tanggal19").value;     
  var next_fu           = document.getElementById("tanggal_today").value;     
  var status_fu         = document.getElementById("status_fu").value;     
  var hasil_fu          = document.getElementById("hasil_fu").value;     
  var last_next_fu      = document.getElementById("last_next_fu").value; 

  var today = new Date();
  var birthDate = new Date($('.tgl_fu').val());
  var age = today.getFullYear() - birthDate.getFullYear();
  var m = today.getMonth() - birthDate.getMonth();
  if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }
     

  //alert(id_guest_book);
  if (id_guest_book == "" || hasil_fu == "") {    
    alert("Isikan data dengan lengkap...!");
    kosong();
    return false;
  }else if(age < today){
    alert('Tanggal Tidak boleh lebih kecil dari tanggal sebelumnya')
  }else{
    $.ajax({
      url : "<?php echo site_url('dealer/guest_book/save_data')?>",
      type:"POST",
      data:"id_guest_book="+id_guest_book+"&tgl_fu="+tgl_fu+"&next_fu="+next_fu+"&hasil_fu="+hasil_fu+"&status_fu="+status_fu,
      cache:false,
      success:function(msg){            
        data=msg.split("|");
        if(data[0]=="nihil"){
          kirim_data();
          kosong();                              
        }else{
          alert(data[0]);
          kosong();                      
        }                
      }
    })    
  }
}

function kosong(args){
  $("#tanggal19").val("");  
  $("#tanggal20").val("");  
  $("#status_fu").val("");  
  $("#hasil_fu").val("");  
}
function hapus_data(a){ 
    var id_guest_book_detail  = a;       
    $.ajax({
        url : "<?php echo site_url('dealer/guest_book/delete_data')?>",
        type:"POST",
        data:"id_guest_book_detail="+id_guest_book_detail,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data();
            }
        }
    })
}

function getWarna(id_warna=null)
  { 
      //var nama_type = $(".modal_edit_detailkendaraan #kode_type").select2().find(":selected").data("nama_type");
      var id_tipe_kendaraan = $("#id_tipe_kendaraan").val();
      $.ajax({
               beforeSend: function() { $('#loading-status').show(); },
               url:"<?php echo site_url('dealer/guest_book/getWarna');?>",
               type:"POST",
               data:"id_tipe_kendaraan="+id_tipe_kendaraan
                  +"&id_warna="+id_warna,
                  /* +"&keterangan="+keterangan
                  +"&tgl_pinjaman="+tgl_pinjaman,
               */
               cache:false,
               success:function(html){
                  $('#loading-status').hide();
                  $('#id_warna').html(html);
                  $("#warna_mode").val("ada");

                  // take_harga();
               },
               statusCode: {
            500: function() {
              $('#loading-status').hide();
              swal("Something Wen't Wrong");
            }
          }
          });
  }
  function getWarna2(){
    var mode = $("#warna_mode").val();
    if(mode == ''){
      getWarna();
    }else{
      return false;
    }
  }
  function getDatePicker()
  {
  $('.tanggal').datepicker({
          format:"yyyy-mm-dd",
          autoclose:true            
      }); 
  }

</script>