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
          <a href="dealer/retur_bastd_d">
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
            <form class="form-horizontal" action="dealer/retur_bastd_d" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Retur</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="No Retur" name="nama_konsumen">                                        
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Retur</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="Tanggal Retur" name="nama_konsumen">                    
                  </div>
                </div>                
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">No BASTD</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="No BASTD" name="nama_konsumen">                    
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="Nama Dealer" name="nama_konsumen">                    
                  </div>                  
                </div>                                  
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Dealer</label>
                  <div class="col-sm-10">
                    <input type="text" required class="form-control" placeholder="Alamat Dealer" name="nama_konsumen">                    
                  </div>                                                    
                </div>
                <table id="" class="table table-bordered table-hover">
                  <thead>
                    <tr>                      
                      <th>No Mesin</th>              
                      <th>No Rangka</th>              
                      <th>Nama Konsumen</th>              
                      <th>Tipe</th>              
                      <th>Warna</th>              
                      <th>Tahun</th>
                      <th>Alasan Retur</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody> 
                  </tbody>
                </table>
              </div><!-- /.box-body -->
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
    }elseif($set=="view"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <!--a href="dealer/retur_bastd_d/add">
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
          <?php 
          $get_kurang = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn_detail.no_bastd=tr_pengajuan_bbn.no_bastd WHERE id_dealer=$id_dealer AND tr_pengajuan_bbn_detail.reject='ya' ");
          if ($get_kurang->num_rows()>0) {
            $pesan = 'Ada data yang kurang : </br>';
            foreach ($get_kurang->result() as $rs) {
                $pesan .="- No. Mesin : $rs->no_mesin, Kekurangan : $rs->kekurangan </br>";
              }
          }

          ?>
        <?php if (isset($pesan)): ?>
          <div class="alert alert-danger alert-dismissable">
            <strong><?= $pesan ?></strong>
            <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>  
            </button>
        </div>
        <?php endif ?>
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>No BASTD</th>              
              <th>Tanggal BASTD</th>              
              <th>Tanggal Reject MD</th>              
             <?php /* <th>Total Reject</th>              
              <th>Action</th>              */?>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_bastd->result() as $row) {           
            $tgl_reject = explode(' ',$row->updated_at);
            $tgl_reject = $tgl_reject[0];
            $total = $this->db->query("SELECT count(no_bastd) as count from tr_pengajuan_bbn_detail where no_bastd='$row->no_bastd'")->row()->count;
            echo "
            <tr>
              <td>$no</td>
              <td>$row->no_bastd</td>
              <td>$row->tgl_bastd</td>
              <td>$tgl_reject</td>";
          /*    <td>$total</td>                                          
               "<td>                
                <a href='dealer/retur_bastd_d/konfirmasi?id=19'>
                  <button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-check'></i> Konfirmasi Pelayanan</button>                
                </a>                
              </td>" */ echo "  </tr> ";
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
      url : "<?php echo site_url('dealer/retur_bastd_d/cari_id')?>",
      type:"POST",
      data:"tgl="+tgl_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_retur_bastd").val(data[0]);                
        $("#id_customer").val(data[1]);                
      }        
  })
}
function take_sales(){
  var id_karyawan_dealer = $("#id_karyawan_dealer").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/retur_bastd_d/take_sales')?>",
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