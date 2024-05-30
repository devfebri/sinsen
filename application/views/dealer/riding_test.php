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
<body onload="take_guest()">
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
          <a href="dealer/riding_test">
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
            <form class="form-horizontal" action="dealer/riding_test/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Guest Book</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_guest_book" id="id_guest_book" onchange="take_guest()">                      
                      <option value="">- choose -</option>
                      <?php 
                       $id_dealer = $this->m_admin->cari_dealer();
                      $guest = $this->m_admin->getByID("tr_guest_book", "id_dealer", $id_dealer);
                      foreach ($guest->result() as $isi) {
                        $ko = $this->db->query("SELECT * FROM tr_prospek WHERE id_list_appointment='$isi->id_list_appointment' AND id_dealer='$id_dealer'")->row();

                        // $this->m_admin->getByID("tr_prospek","id_list_appointment",$isi->id_list_appointment)->row();
                        echo "<option value='$isi->id_guest_book'>$isi->id_guest_book | $ko->nama_konsumen</option>";
                      }
                      ?>
                    </select>
                  </div>             
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Motor Riding Test</label>
                  <div class="col-sm-4">                    
                    <select class="form-control select2" name="id_tipe_kendaraan" required>
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
                </div>         
                <div class="form-group">
                       
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Customer</label>
                  <div class="col-sm-4">
                    <input type="text"  id="id_customer" readonly class="form-control" placeholder="ID Customer" name="id_customer">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Motor yg Disukai</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="motor_disukai" required>
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_tipe->result() as $val) {
                        echo "
                        <option value='$val->tipe_ahm'>$val->id_tipe_kendaraan | $val->tipe_ahm</option>;
                        ";
                      }
                      ?>
                    </select>   
                    <!--
                    <input type="text" required class="form-control" placeholder="Tipe Motor yg Disukai" name="">                  -->  
                  </div>
                </div>       
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                  <div class="col-sm-4">
                    <input type="text" readonly id="nama_konsumen" class="form-control" placeholder="Nama Konsumen" name="nama_konsumen">                    
                  </div>              
                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna Motor Yang Disukai</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_warna">
                      <option value="">- choose -</option>
                      <?php 
                      $list = $this->m_admin->getSortCond("ms_warna","warna","ASC");
                      foreach ($list->result() as $isi) {
                        echo "<option value='$isi->id_warna'>$isi->id_warna | $isi->warna</option>";
                      }
                      ?>  
                    </select>
                  </div> 


                </div>

                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-4">
                    <input type="text" readonly id="alamat" class="form-control" placeholder="Alamat Konsumen" name="alamat">                    
                  </div>    
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Riding Test</label>
                  <div class="col-sm-2">
                    <input type="text" required class="form-control" id="tanggal" placeholder="Tanggal" name="tgl_riding" value="<?php echo date('Y-m-d') ?>">                    
                  </div>              
                </div>

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>
                  <div class="col-sm-4">
                    <input type="text" readonly id="no_hp" class="form-control" placeholder="No HP" name="no_hp">                    
                  </div>                                   
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Saran/Kritik</label>
                  <div class="col-sm-10">
                    <input type="text" required class="form-control" placeholder="Saran/Kritik" name="saran">                    
                  </div>                  
                </div>
              </div>
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="button" onClick="cancel_tr()" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="detail"){
      $row = $dt_riding_test->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/riding_test">
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
            <form class="form-horizontal" action="dealer/riding_test/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Guest Book</label>
                  <div class="col-sm-4">
                    <input type="text" readonly id="id_guest_book" value="<?php echo $row->id_guest_book ?>" class="form-control" placeholder="ID Gest Book" name="id_guest_book">
                  </div>             
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Motor Riding Test</label>
                  <div class="col-sm-4">                    
                    <?php 
                    $se = $this->m_admin->getByID("ms_tipe_kendaraan","id_tipe_kendaraan",$row->id_tipe_kendaraan)->row();
                    ?>
                    <input type="text" value="<?php echo $se->tipe_ahm ?>" readonly class="form-control" placeholder="Tipe Motor" name="tgl_riding">                                               
                  </div>  
                </div>         
                <div class="form-group">
                       
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Customer</label>
                  <div class="col-sm-4">
                   <input type="text" readonly id="id_customer" readonly class="form-control" placeholder="ID Customer" name="id_customer">               
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Motor yg Disukai</label>
                  <div class="col-sm-4">
                     <?php 
                    $se = $this->m_admin->getByID("ms_tipe_kendaraan","id_tipe_kendaraan",$row->id_tipe_kendaraan)->row();
                    ?>
                    <input type="text" value="<?php echo $se->tipe_ahm ?>" readonly class="form-control" placeholder="Tipe Motor" name="tgl_riding">     
                    <!--
                    <input type="text" required class="form-control" placeholder="Tipe Motor yg Disukai" name="">                  -->  
                  </div>
                </div>       
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                  <div class="col-sm-4">
                    <input type="text" readonly id="nama_konsumen" class="form-control" placeholder="Nama Konsumen" name="nama_konsumen">   
                  </div>              
                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna Motor Yang Disukai</label>
                  <div class="col-sm-4">
                   <?php 
                    $se = $this->m_admin->getByID("ms_warna","id_warna",$row->id_warna)->row();
                    if(isset($se->warna)) $warna = $se->warna;
                      else $warna = "";
                    ?>
                    <input type="text" value="<?php echo $warna ?>" readonly class="form-control" placeholder="Warna" name="warna">  
                  </div> 


                </div>

                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-4">
                     <input type="text" readonly id="alamat" class="form-control" placeholder="Alamat Konsumen" name="alamat">                          
                  </div>    
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Riding Test</label>
                  <div class="col-sm-2">
                    <input type="text" value="<?php echo $row->tgl_riding ?>" readonly class="form-control" id="tanggal" placeholder="Tanggal" name="tgl_riding">                    
                  </div>              
                </div>

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>
                  <div class="col-sm-4">
                    <input type="text" readonly id="no_hp" class="form-control" placeholder="No HP" name="no_hp">                       
                  </div>                                   
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Saran/Kritik</label>
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $row->saran ?>" readonly class="form-control" placeholder="Saran/Kritik" name="saran">                
                  </div>                  
                </div>
              </div>
              
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
          <a href="dealer/riding_test/add">
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
        <form action="dealer/riding_test" method="POST">
          <div class="form-group">
                      
                  <div class="col-sm-3">
                    Start Date:
                    <input type="text"  id="tanggal" readonly class="form-control" placeholder="Start Date" name="start_date" class="" value="<?php echo $start_date ?>">                    
                  </div>
                  <div class="col-sm-3">
                    End Date:
                    <input type="text"  id="tanggal2" readonly class="form-control" placeholder="End Date" name="end_date"  class="" value="<?php echo $end_date ?>">                    
                  </div>
                  <div class="col-sm-1"><br>
                    <button type="submit" class="btn btn-primary">Filter</button>
                  </div>
      </div><br><br><br>
        </form>
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>              
              <th>Nama Konsumen</th>              
              <th>Tipe Motor</th>
              <th>Warna</th>              
              <th>Tanggal</th>
              <th>Tipe Motor yg Disukai</th>              
              <th>Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_riding_test->result() as $row) {
            // $dt_p = $this->m_admin->getByID("tr_prospek","id_list_appointment",$row->id_list_appointment)->row();

            $id_dealer = $this->m_admin->cari_dealer();
            $dt_p = $this->db->query("SELECT * FROM tr_prospek WHERE id_list_appointment='$row->id_list_appointment' AND id_dealer='$id_dealer'");
            $nama_konsumen = "";
            if($dt_p->num_rows() > 0){
              $nama_konsumen = $dt_p->row()->nama_konsumen;
            }
                echo "
                  <tr class='even pointer'>
                    <td>$no</td>                                                 
                    <td>$nama_konsumen</td>
                    <td>$row->tipe_ahm</td>
                    <td>$row->warna</td>
                    <td>$row->tgl_riding</td>
                    <td>$row->motor_disukai</td>
                    <td width='11%'>                    
                      <a href='dealer/riding_test/detail?id=$row->id_riding_test'>
                        <button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-eye'></i> Detail</button>
                      </a>
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



<script type="text/javascript">
function take_guest(){
  var id_guest_book = $("#id_guest_book").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/riding_test/take_guest')?>",
      type:"POST",
      data:"id_guest_book="+id_guest_book,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");                    
          $("#id_customer").val(data[0]);                                                    
          $("#nama_konsumen").val(data[1]);                                                    
          $("#alamat").val(data[2]);                                                    
          $("#no_hp").val(data[3]);                                                    
      } 
  })
}


</script>