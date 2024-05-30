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
          <a href="dealer/report">
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
            <form class="form-horizontal" action="dealer/report/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Program</label>
                  <div class="col-sm-5">
                    <select class="form-control select2 id_proposal" name="id_proposal" id="id_proposal" onchange="showProposal()">
                    	<option>--Choose--</option>
                    	<?php foreach ($dt_proposal->result() as $pr): ?>
                    		<option data-tgl_mulai="<?php echo $pr->tgl_mulai ?>" data-tgl_selesai="<?php echo $pr->tgl_selesai ?>" value="<?php echo $pr->id_proposal ?>"><?php echo $pr->nama_program ?></option>
                    	<?php endforeach ?>
                    </select>                                        	
                  </div>                  
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Mulai</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="Tanggal Mulai" name="nama_konsumen" id="tgl_mulai" readonly>                    
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Selesai</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="Tanggal Selesai" name="nama_konsumen" id="tgl_selesai" readonly>                    
                  </div>                  
                </div>                                                  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Jumlah (Orang)</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Jumlah (Orang)" name="jml_orang">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Biaya</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Total Biaya yang Dikeluarkan" name="total_biaya">                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Deskripsi" name="deskripsi">                    
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Foto 1</label>
                  <div class="col-sm-4">
                    <input type="file" class="form-control" placeholder="Deskripsi" name="foto1">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Foto 2</label>
                  <div class="col-sm-4">
                    <input type="file" class="form-control" placeholder="Deskripsi" name="foto2">                    
                  </div>                  
                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Foto 3</label>
                  <div class="col-sm-4">
                    <input type="file" class="form-control" placeholder="Deskripsi" name="foto3">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Foto 4</label>
                  <div class="col-sm-4">
                    <input type="file" class="form-control" placeholder="Deskripsi" name="foto4">                    
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
          <a href="dealer/report/add">
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
              <th>Nama Program</th>              
              <th>Tema Program</th>              
              <th>Tanggal Mulai</th>              
              <th>Tanggal Selesai</th>
              <th>Jenis Proposal</th>
              <th>Total Biaya</th>              
              <th>Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_report->result() as $row) {          
            echo "
            <tr>
              <td>$no</td>
              <td>$row->nama_program</td>
              <td>$row->tema_program</td>
              <td>$row->tgl_mulai</td>
              <td>$row->tgl_selesai</td>                            
              <td>$row->jenis_proposal</td>              
              <td>$row->total_biaya</td>                            
              <td>                
                <a href='dealer/report/detail?id=$row->id_report_proposal' class='btn btn-flat btn-xs btn-warning'><i class='fa fa-eye'></i>
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
    elseif($set=="detail"){
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/report">
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
            <form class="form-horizontal" action="dealer/report/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Program</label>
                  <div class="col-sm-5">
                   <input type="" name="" class="form-control" value="<?php echo $dt_report->nama_program ?>" readonly>
                  </div>                  
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Mulai</label>
                  <div class="col-sm-4">
                   <input type="" name="" class="form-control" value="<?php echo $dt_report->tgl_mulai ?>" readonly>                   
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Selesai</label>
                  <div class="col-sm-4">
                   <input type="" name="" class="form-control" value="<?php echo $dt_report->tgl_selesai ?>" readonly>
                  </div>                  
                </div>                                                  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Jumlah (Orang)</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Jumlah (Orang)" name="jml_orang" value="<?php echo $dt_report->jml_orang ?>" readonly>                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Biaya</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Total Biaya yang Dikeluarkan" name="total_biaya" value="<?php echo $dt_report->total_biaya ?>" readonly>                    
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Deskripsi" name="deskripsi" value="<?php echo $dt_report->deskripsi ?>" readonly>                    
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>
                 <div class="col-sm-10"><?php $detail_report=$this->db->query("SELECT * FROM tr_report_proposal_attachment where id_report_proposal='$dt_report->id_report_proposal'") ?>                
                 	<?php foreach ($detail_report->result() as $rpt): ?>
                 		 <div class="col-sm-4"><img src="<?php echo base_url('assets/panel/images/report/'.$rpt->filename) ?>" class="img-thumbnail img-fluid"><br><br></div>  
                 	<?php endforeach ?></div>
                </div>                
               
              </div><!-- /.box-body -->
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <?php } ?>
  </section>
</div>
<script type="text/javascript">
function auto(){
 // var tgl_js=document.getElementById("tgl").value; 
  $.ajax({
      url : "<?php echo site_url('dealer/report/cari_id')?>",
      type:"POST",
    //  data:"tgl="+tgl_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_report").val(data[0]);                
        $("#id_customer").val(data[1]);                
      }        
  })
}
function take_sales(){
  var id_karyawan_dealer = $("#id_karyawan_dealer").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/report/take_sales')?>",
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
function showProposal()
{
  var tgl_mulai = $("#id_proposal").select2().find(":selected").data("tgl_mulai");
  var tgl_selesai = $("#id_proposal").select2().find(":selected").data("tgl_selesai");
  $('#tgl_mulai').val(tgl_mulai);
  $('#tgl_selesai').val(tgl_selesai);
}
</script>