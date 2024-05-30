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
          <a href="dealer/claim">
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
            <form class="form-horizontal" action="dealer/claim/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label" >Nama Program</label>
                  <div class="col-sm-10">
                  		<select name="id_proposal" id="id_proposal_dealer" class="form-control select2" onchange="showProposal()">
                  			<option>--Choose--</option>
                  			<?php $proposal =$this->db->query("SELECT * FROM tr_proposal_dealer");
                  			foreach ($proposal->result() as $pr) {
                  				echo "<option data-tgl_mulai='$pr->tgl_mulai' data-tgl_selesai='$pr->tgl_selesai' data-tema='$pr->tema_program' value='$pr->id_proposal'>$pr->nama_program</option>";
                  			}
                  			 ?>
                  		</select>
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tema Program</label>
                  <div class="col-sm-10">
                    <input type="text" required class="form-control" placeholder="Tema Program" name="tema_program" id="tema_program" readonly>                    
                  </div>
                </div>                
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Mulai</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="Tanggal Mulai" name="tgl_mulai" id="tgl_mulai" readonly>                    
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Selesai</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="Tanggal Selesai" name="tgl_selesai" id="tgl_selesai" readonly>                    
                  </div>                  
                </div>                                                  
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Upload LPJ</label>
                  <div class="col-sm-10">
                    <input type="file" class="form-control" accept=".xls,.xlsx,.pdf,.jpg,.jpeg,.png,.doc,.docx" name="file_lpj">                    
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
          <a href="dealer/claim/add">
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
              <th>File LPJ</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_claim->result() as $row) {        
            $file='';
            if($row->file_lpj != ''){
              $file = "<a target='_blank' href='assets/panel/files/$row->file_lpj' class='btn btn-flat btn-primary btn-xs'>Lihat File</a>"; 
            }
            if($row->status_claim == ''){              
              $status = "";
            }elseif($row->status_claim == 'approved'){
              $status = "<span class='label label-success'>Approve by MD</span>";            
            }elseif($row->status_claim == 'rejected'){
              $status = "<span class='label label-danger'>Reject by MD</span>";
            }
            if($row->status_claim != 'approved'){
              echo "
              <tr>
                <td>$no</td>
                <td>$row->nama_program</td>
                <td>$row->tema_program</td>
                <td>$row->tgl_mulai</td>
                <td>$row->tgl_selesai</td>     
                <td>
                  $file
                </td>     
                <td>$status</td>
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
    }
    ?>
  </section>
</div>
<script type="text/javascript">
function auto(){
  var tgl_js=document.getElementById("tgl").value; 
  $.ajax({
      url : "<?php echo site_url('dealer/claim/cari_id')?>",
      type:"POST",
      data:"tgl="+tgl_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_claim").val(data[0]);                
        $("#id_customer").val(data[1]);                
      }        
  })
}
function take_sales(){
  var id_karyawan_dealer = $("#id_karyawan_dealer").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/claim/take_sales')?>",
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
  var tgl_mulai = $("#id_proposal_dealer").select2().find(":selected").data("tgl_mulai");
  var tgl_selesai = $("#id_proposal_dealer").select2().find(":selected").data("tgl_selesai");
  var tema_program = $("#id_proposal_dealer").select2().find(":selected").data("tema");
  $('#tgl_mulai').val(tgl_mulai);
  $('#tgl_selesai').val(tgl_selesai);
  $('#tema_program').val(tema_program);
}
</script>