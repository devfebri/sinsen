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
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
    if($set=="detail"){          
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/ksu_hilang">
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
            <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">     
              <div class="box-body">              
                <div class="form-group">
                  <input type="hidden" name="id_penerimaan_unit_dealer" value="<?php echo $id_penerimaan_unit_dealer ?>">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Penerimaan Unit</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" value="<?php echo $id_penerimaan_unit_dealer ?>" readonly placeholder="No Surat Jalan" name="no_surat_jalan">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Penerimaan</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control" value="<?php echo $sj->tgl_penerimaan ?>" readonly placeholder="Tgl Surat Jalan" name="tgl_surat">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $sj->no_surat_jalan ?>" readonly placeholder="No DO" name="no_do">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $sj->tgl_surat_jalan ?>" readonly placeholder="Tgl DO" name="tgl_do">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Driver</label>
                  <div class="col-sm-4">
                    <?php $driver=$this->db->query("SELECT * FROM tr_sppm WHERE no_surat_sppm='$sj->no_surat_sppm' ");
                      if ($driver->num_rows() >0) {
                        $driver= $driver->row()->driver;
                      }else{
                        $driver='';
                      }

                     ?>
                    <input type="text" class="form-control" value="<?php echo $driver ?>" readonly placeholder="" name="no_do">
                </div>                
              </div>            
          </div>
        </div>
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>Kode KSU</th>              
              <th>Aksesoris</th>                            
              <th>Qty MD</th>
              <th>Qty Terima</th>              
            </tr>
          </thead>
          <tbody>            
            <?php 
            $no = 1;
            $v_ksu = $this->db->query("SELECT * FROM tr_penerimaan_ksu_dealer INNER JOIN ms_ksu ON tr_penerimaan_ksu_dealer.id_ksu=ms_ksu.id_ksu 
                WHERE tr_penerimaan_ksu_dealer.id_penerimaan_unit_dealer = '$id_penerimaan_unit_dealer' AND tr_penerimaan_ksu_dealer.qty_md > tr_penerimaan_ksu_dealer.qty_terima");
            foreach ($v_ksu->result() as $row) {                                          
              echo "
              <tr>
                <td>$no</td>
                <td>$id_penerimaan_unit_dealer</td>                
                <td>$row->ksu</td>                                
                <td>$row->qty_md</td>                                                
                <td>$row->qty_terima</td>                                                
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
    }elseif($set=="terima"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/ksu_hilang">
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
            <form class="form-horizontal" action="dealer/ksu_hilang/save_ksu" method="post" enctype="multipart/form-data">     
              <div class="box-body">              
                <div class="form-group">
                  <input type="hidden" name="id_penerimaan_unit_dealer" value="<?php echo $id_penerimaan_unit_dealer ?>">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Penerimaan Unit</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" value="<?php echo $id_penerimaan_unit_dealer ?>" readonly placeholder="No Surat Jalan" name="no_surat_jalan">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Penerimaan</label>
                  <div class="col-sm-4">
                    <input type="text"  class="form-control" value="<?php echo $sj->tgl_penerimaan ?>" readonly placeholder="Tgl Surat Jalan" name="tgl_surat">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $sj->no_surat_jalan ?>" readonly placeholder="No DO" name="no_do">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $sj->tgl_surat_jalan ?>" readonly placeholder="Tgl DO" name="tgl_do">                    
                  </div>
                </div>                
              </div>            
          </div>
        </div>
        <table id="" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>Kode KSU</th>              
              <th>Aksesoris</th>              
              <th>Qty Kekurangan</th>
              <th width="10%">Qty Terima</th>              
            </tr>
          </thead>
          <tbody>            
            <?php 
            $no = 1;      
            foreach ($v_ksu->result() as $row) {                                          
              $sisa = $row->qty_md - $row->qty_terima;
              echo "
              <tr>
                <td>$no</td>
                <td>$row->id_ksu</td>                
                <td>$row->ksu</td>                
                <td>$sisa</td>                                                
                <td>
                  <input type='hidden' name='id_pu[]' value='$id_penerimaan_unit_dealer'>
                  <input type='hidden' name='id_ksu_d[]' value='$row->id_penerimaan_ksu_dealer'>
                  <input type='hidden' name='id_ksu[]' value='$row->id_ksu'>
                  <input type='hidden' name='no_sj[]' value='$sj->no_surat_jalan'>
                  <input type='hidden' name='id_item[]' value='$row->id_item'>                  
                  <input type='hidden' name='qty_md[]' value='$sisa'>
                  <input type='text' value='$sisa' name='qty_terima[]' class='form-control isi' onkeypress=\"return number_only(event)\">
                </td>                                                
              </tr>
              ";
              $no++;
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
    }elseif($set=="view"){
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
              <th>No Penerimaan KSU</th>              
              <th>No Surat Jalan</th>            
              <th>Tgl Surat Jalan</th>
              <th>Qty Penerimaan</th>
              <th>Qty Hilang</th> 
              <th>Action</th>             
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_ksu->result() as $row) {     
            $s = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer WHERE id_penerimaan_unit_dealer = '$row->ID'")->row();          
            $jum = $this->db->query("SELECT SUM(qty_md) AS qty_md,SUM(qty_terima) AS qty_terima FROM tr_penerimaan_ksu_dealer
              WHERE id_penerimaan_unit_dealer = '$row->ID'");
            if($jum->num_rows() > 0){
              $rt = $jum->row();
              $sum_md = $rt->qty_md;
              $sum_terima = $rt->qty_terima;
              $sum_hilang = $sum_md - $sum_terima;
            }else{
              $sum_md = "0";
              $sum_terima = "0";
              $sum_hilang = "0";
            }
            echo "
            <tr>
              <td>$no</td>              
              <td>$s->id_penerimaan_unit_dealer</td>
              <td>$s->no_surat_jalan</td>              
              <td>$s->tgl_surat_jalan</td>
              <td>$sum_terima</td>
              <td>$sum_hilang</td>              
              <td>                                
                <a href='dealer/ksu_hilang/detail?id=$s->id_penerimaan_unit_dealer'>
                  <button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-eye'></i> Detail</button>
                </a>
                <a href='dealer/ksu_hilang/cetak?id=$s->id_penerimaan_unit_dealer'>
                  <button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-print'></i> Cetak</button>
                </a>
                <a href='dealer/ksu_hilang/terima?id=$s->id_penerimaan_unit_dealer'>
                  <button class='btn btn-flat btn-xs btn-success'><i class='fa fa-check'></i> Terima Barang</button>
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
    }
    ?>
  </section>
</div>



<script type="text/javascript">
function auto(){
  var tgl_js=document.getElementById("tgl").value; 
  $.ajax({
      url : "<?php echo site_url('dealer/monitoring_outstanding_ksu/cari_id')?>",
      type:"POST",
      data:"tgl="+tgl_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_monitoring_outstanding_ksu").val(data[0]);                
        $("#id_customer").val(data[1]);                
      }        
  })
}
function take_sales(){
  var id_karyawan_dealer = $("#id_karyawan_dealer").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/monitoring_outstanding_ksu/take_sales')?>",
      type:"POST",
      data:"id_karyawan_dealer="+id_karyawan_dealer,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");          
          //$("#no_polisi").html(msg);                                                    
          $("#kode_sales").val(data[0]);                                                    
          $("#nama_sales").val(data[1]);                                                    
      } 
  })
}


</script>