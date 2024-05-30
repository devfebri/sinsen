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
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Monitoring</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
    if($set=="detail"){
      $row = $dt_guest_book->row();
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/m_guest_book">
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
                <div class="form-group">
                  <input type="hidden" name="id" value="<?php echo $row->id_guest_book ?>">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID List Appointment</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->id_list_appointment ?>" readonly placeholder="ID List Appointment" id="no_hp" name="no_hp">                                                                           
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
                    <input type="text" readonly class="form-control" placeholder="Alamat" value="<?php echo $row->alamat ?>" name="alamat" id="alamat">                    
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Type Motor</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" value="<?php echo $row->tipe_ahm ?>" readonly placeholder="Tipe AHM" id="no_hp" name="no_hp">                                                                           
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->warna ?>" readonly placeholder="Warna" id="no_hp" name="no_hp">                                                                           
                  </div>
                </div>
               <!--  <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" readonly placeholder="Alamat" value="<?php echo $row->alamat2 ?>" name="alamat2">                    
                  </div>                  
                </div> -->
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi Warna Motor</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" readonly placeholder="Deskripsi Warna Motor" value="<?php echo $row->deskripsi_warna ?>" name="deskripsi_warna">                    
                  </div>                  
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi Motor MKT</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" readonly placeholder="Deskripsi Motor MKT" value="<?php echo $row->deskripsi_mkt ?>" name="deskripsi_mkt">                    
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Rencana Pembayaran</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->rencana_bayar ?>" readonly placeholder="Rencana Bayar" id="no_hp" name="no_hp">                                                                           
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Customer</label>
                  <div class="col-sm-4">
                    <?php 
                    $jenis = $this->m_admin->getByID("ms_jenis_customer","id_jenis_customer",$row->id_jenis_customer)->row();
                    ?>
                    <input type="text" class="form-control" value="<?php echo $jenis->jenis_customer ?>" readonly placeholder="Jenis Customer" id="no_hp" name="no_hp">                                                                           
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-4">                    
                    <?php 
                    $status = $this->db->query("SELECT * FROM tr_guest_book_detail WHERE id_guest_book = '$row->id_guest_book' ORDER BY id_guest_book_detail DESC LIMIT 0,1");
                    if($status->num_rows() > 0){
                      $h = $status->row();
                      $status_ = $h->status_fu;                      
                    }else{
                      $status_ = "";                      
                    }

                    $status = $this->db->query("SELECT * FROM tr_guest_book_detail WHERE id_guest_book = '$row->id_guest_book' ORDER BY id_guest_book_detail ASC LIMIT 0,1");
                    if($status->num_rows() > 0){
                      $h = $status->row();                      
                      $tgl_fu_1 = $h->tgl_fu;
                      $hasil_fu_1 = $h->hasil_fu;
                    }else{                      
                      $tgl_fu_1 = "";
                      $hasil_fu_1 = "";
                    }

                    $status = $this->db->query("SELECT * FROM tr_guest_book_detail WHERE id_guest_book = '$row->id_guest_book' ORDER BY id_guest_book_detail ASC LIMIT 1,1");
                    if($status->num_rows() > 0){
                      $h = $status->row();                      
                      $tgl_fu_2 = $h->tgl_fu;
                      $hasil_fu_2 = $h->hasil_fu;
                    }else{                      
                      $tgl_fu_2 = "";
                      $hasil_fu_2 = "";
                    }
                    
                    $status = $this->db->query("SELECT * FROM tr_guest_book_detail WHERE id_guest_book = '$row->id_guest_book' ORDER BY id_guest_book_detail ASC LIMIT 2,1");
                    if($status->num_rows() > 0){
                      $h = $status->row();                      
                      $tgl_fu_3 = $h->tgl_fu;
                      $hasil_fu_3 = $h->hasil_fu;
                    }else{                      
                      $tgl_fu_3 = "";
                      $hasil_fu_3 = "";
                    }
                    
                    ?>
                    <input type="text" class="form-control" value="<?php echo $status_ ?>" readonly placeholder="Status" id="no_hp" name="no_hp">                                                                           
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Follow Up Pertama</label>
                  <div class="col-sm-4">
                    <input type="text" id="tanggal4" class="form-control" readonly placeholder="Tgl Follow Up Pertama" value="<?php echo $tgl_fu_1 ?>" name="tgl_fu_1">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Hasil Follow Up Pertama</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly placeholder="Hasil Follow Up Pertama" value="<?php echo $hasil_fu_1 ?>" name="hasil_fu_1">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Follow Up Kedua</label>
                  <div class="col-sm-4">
                    <input type="text" id="tanggal5" class="form-control" readonly placeholder="Tgl Follow Up Kedua" value="<?php echo $tgl_fu_2 ?>" name="tgl_fu_2">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Hasil Follow Up Kedua</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly placeholder="Hasil Follow Up Kedua" name="hasil_fu_2" value="<?php echo $hasil_fu_2 ?>">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Follow Up Ketiga</label>
                  <div class="col-sm-4">
                    <input type="text" id="tanggal6" readonly class="form-control" placeholder="Tgl Follow Up Ketiga" name="tgl_fu_3" value="<?php echo $tgl_fu_3 ?>">                                        
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Hasil Follow Up Ketiga</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly placeholder="Hasil Follow Up Ketiga" name="hasil_fu_3" value="<?php echo $hasil_fu_3 ?>">                    
                  </div>
                </div>                
              </div><!-- /.box-body -->              
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    
    <?php 
    }elseif($set=="insert"){
    ?>

   

    <?php
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <!--a href="dealer/monitoring_outstanding_ksu/list_ksu">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-list"></i> List KSU</button>
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
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>                            
              <th>ID Customer</th>              
              <th>Nama Konsumen</th>            
              <th>Alamat Konsumen</th>              
              <th>Tipe Motor</th> 
              <th>Warna</th>
              <th>Rencana Pembayaran</th>
              <th>Tgl Terakhir Follow Up</th>            
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
           foreach($dt_guest_book->result() as $row) {   
            $det = $this->db->query("SELECT * FROM tr_guest_book_detail WHERE id_guest_book ='$row->id_guest_book' order by id_guest_book_detail DESC limit 0,1")->row();         
            echo "                  
            <tr>
              <td>$no</td>                             
              <td>$row->id_customer</td>
              <td>
                <a href='dealer/m_guest_book/detail?id=$row->id_guest_book'>
                  $row->nama_konsumen
                </a>
              </td>
              <td>$row->alamat</td>
              <td>$row->tipe_ahm</td>
              <td>$row->warna</td>
              <td>$row->rencana_bayar</td>
              <td>$det->tgl_fu</td>             
            </td>
          </tr>";          
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