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
          <a href="dealer/faktur_permohonan_stnk">
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
            <form class="form-horizontal" action="dealer/penerimaan_unit/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                  <div class="col-sm-3">
                    <input type="text" required class="form-control"  id="tanggal"  placeholder="Start Date" name="id_penerimaan_unit">
                  </div>
                  <div class="col-sm-3">
                    <button class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Generate</button>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
                  <div class="col-sm-3">
                    <input type="text" required class="form-control" id="tanggal2"  placeholder="End Date" name="no_antrian">                    
                  </div>
                </div>                
              </div>
            </form>
          </div>
        </div>
        <button class="btn btn-primary btn-block btn-flat" disabled>Kelengkapan Dokumen</button>
        <table id="example" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>No Mesin</th>              
              <th>No Rangka</th>              
              <th>Nama Konsuen</th>
              <th>Alamat</th>
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
    </div><!-- /.box -->
    <?php 
    }elseif($set=="reject"){
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/faktur_permohonan_stnk">
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
            <form class="form-horizontal" action="dealer/faktur_permohonan_stnk/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Spk</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="No SPK" name="nama_konsumen">                    
                  </div>                                    
                </div>                
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                  <div class="col-sm-10">
                    <input type="text" required class="form-control" placeholder="Nama Konsumen" name="nama_konsumen">                    
                  </div>                  
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-10">
                    <input type="text" required class="form-control" placeholder="Nama Konsumen" name="nama_konsumen">                    
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="Warna" name="nama_konsumen">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Motor</label>
                  <div class="col-sm-4">                    
                    <input type="text" required class="form-control" placeholder="Tipe Motor" name="nama_konsumen">                    
                  </div>
                  
                </div>
                <div class="form-group">                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Alasan Reject</label>
                  <div class="col-sm-10">
                    <input type="text" required class="form-control" placeholder="Alasan Reject" name="nama_konsumen">                    
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
    }elseif($set=="view"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/faktur_permohonan_stnk/add">
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>              
              <th>No BASTD</th>              
              <th>Tanggal BASTD</th>              
              <th>Start Date</th>              
              <th>End Date</th>
              <th>Qty Pengajuan</th>              
              <th>Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          //$no=1; 
          //foreach($dt_faktur_permohonan_stnk->result() as $row) {     
            //$s = $this->db->query("SELECT * FROM ms_vendor WHERE id_vendor = '$row->ekspedisi'")->row();          
            echo "
            <tr>
              <td>1</td>
              <td>212POS01</td>              
              <td>12-01-2018</td>
              <td>01-01-2018</td>
              <td>31-01-2018</td>                            
              <td>192</td>                                                        
              <td>                                
                <a href='dealer/faktur_permohonan_stnk/konfirmasi?id=19'>
                  <button class='btn btn-flat btn-xs btn-success'><i class='fa fa-check'></i> Konfirmasi Penerimaan</button>
                </a>
                <a href='dealer/faktur_permohonan_stnk/print?id=19'>
                  <button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-print'></i> Print BASTD</button>
                </a>
              </td>
            </tr>
            ";
          //$no++;
          //}
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
      url : "<?php echo site_url('dealer/faktur_permohonan_stnk/cari_id')?>",
      type:"POST",
      data:"tgl="+tgl_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_faktur_permohonan_stnk").val(data[0]);                
        $("#id_customer").val(data[1]);                
      }        
  })
}
function take_sales(){
  var id_karyawan_dealer = $("#id_karyawan_dealer").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/faktur_permohonan_stnk/take_sales')?>",
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