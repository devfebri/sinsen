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
          <a href="dealer/laporan_intransit">
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>                            
              <th>ID Item</th>              
              <th>Tipe Kendaraan</th>                          
              <th>Warna</th>
              <th>No Mesin</th>             
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          $cek = $this->db->query("SELECT ms_item.id_item,ms_item.id_tipe_kendaraan,ms_item.id_warna,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_surat_jalan_detail.no_mesin
              FROM tr_surat_jalan_detail INNER JOIN ms_item ON tr_surat_jalan_detail.id_item=ms_item.id_item 
              INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
              INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
              WHERE tr_surat_jalan_detail.no_surat_jalan = '$id'");
          foreach($cek->result() as $row) {     
            echo "
            <tr>
              <td>$no</td>              
              <td>$row->id_item</td>
              <td>$row->id_tipe_kendaraan | $row->tipe_ahm</td>                            
              <td>$row->id_warna | $row->warna</td>                            
              <td>$row->no_mesin</td>              
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
              <th width="5%">No</th>              
              <th>No Surat Jalan</th>              
              <th>Tgl Surat Jalan</th>
              <th>No DO</th>              
              <th>Tgl DO</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_int->result() as $row) {     
            //$s = $this->db->query("SELECT * FROM ms_vendor WHERE id_vendor = '$row->ekspedisi'")->row();          
            echo "
            <tr>
              <td>$no</td>
              <td>
                <a href='dealer/laporan_intransit/detail?id=$row->no_surat_jalan'>
                  $row->no_surat_jalan
                </a>
              </td>              
              <td>$row->tgl_surat</td>
              <td>$row->no_do</td>
              <td>$row->tgl_do</td>              
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