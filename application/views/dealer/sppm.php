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
		if($set=="insert"){
		?>

		<div class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">
					<a href="dealer/sppm">
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
						<form class="form-horizontal" action="dealer/sppm/save" method="post" enctype="multipart/form-data">
							<div class="box-body">    
								<div class="form-group">									
									<label for="inputEmail3" class="col-sm-2 control-label">Tgl Surat</label>
									<div class="col-sm-3">
										<input type="text" required class="form-control" id="tanggal" value="<?php echo gmdate("Y-m-d", time()+60*60*7); ?>" placeholder="Tgl Surat" name="tgl_surat">
									</div>                  
								</div>
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label">No DO</label>
									<div class="col-sm-4">
										<select class="form-control select2" name="no_do" id="no_do" required onchange="getNoPO();kirim_data_sppm();">
											<option value="">- choose -</option>
											<?php 
											foreach($dt_do->result() as $val) {
												$do = $this->db->get_where('tr_do_po',['no_do'=>$val->no_do])->row();
												/*
												$cek_sppm=$this->db->query("sum(qty_ambil) AS ambil FROM tr_sppm 
													LEFT JOIN tr_sppm_detail on tr_sppm.no_surat_sppm=tr_sppm_detail.no_surat_sppm
													WHERE tr_sppm.no_do='$val->no_do'
													")->row()->ambil;*/
												
											/* 
											$cek_do = $this->db->query("SELECT sum(qty_do) as do FROM tr_do_po_detail WHERE no_do='$val->no_do'")->row()->do;
											$cek_sj = $this->db->query("SELECT count(no_mesin) as sj FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan 
												INNER JOIN tr_picking_list ON tr_surat_jalan.no_picking_list = tr_picking_list.no_picking_list
												WHERE tr_picking_list.no_do = '$val->no_do' AND tr_surat_jalan_detail.ceklist = 'ya'")->row()->sj;
											*/

											$cek_do = $val->do;
											$cek_sj = $val->sj;

											if ($cek_do > $cek_sj) {
												echo "
												<option value='$val->no_do' data-no_po='$val->no_po' >$do->tgl_do | $val->no_do</option>;
												";
											}

											}
											?>
										</select>
									</div>                  
									<div class="col-sm-1">                  
										<button onclick="kirim_data_sppm()" type="button" class="btn btn-flat btn-primary btn-sm">Generate</button>
									</div>
									<label for="inputEmail3" class="col-sm-2 control-label">Tgl Do</label>
									<div class="col-sm-3">
										<input type="text" onkeypress="return nihil(event)" autocomplete="off" required class="form-control" id="tgl_do" placeholder="Tgl Do" name="tgl_do">
									</div>                  
								</div>
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label">No PO</label>
									<div class="col-sm-3">
										<input type="text" readonly required class="form-control" id="no_po" placeholder="No PO" name="no_po" >
									</div>                  
								</div>
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label">No. Polisi</label>
									<div class="col-sm-2">
										<!--<input type="text" name="no_pol"  id="no_pol" class="form-control" placeholder="No. Polisi"> -->
										<select class="form-control select2" name="no_pol" id="no_plat" onchange="getDriver()" required>
											<option value="">- Choose -</option>
											<?php $id_dealer 			= $this->m_admin->cari_dealer(); ?>
											<?php $no_pol=$this->db->query("SELECT * FROM ms_plat_dealer where id_dealer='$id_dealer' and active =1") ?>
											<?php foreach ($no_pol->result() as $pl): ?>
												<option value="<?php echo $pl->id_master_plat ?>" data-driver="<?php echo $pl->driver ?>"><?php echo $pl->no_plat ?></option>
											<?php endforeach ?>
										</select>
									</div>
									<div class="col-sm-3"></div>

									<label for="inputEmail3" class="col-sm-2 control-label">Driver</label>
									<div class="col-sm-3">
										<input type="text" required class="form-control" placeholder="Driver" name="driver" id="driver">
									</div>                  
								</div>


								<button class="btn btn-primary btn-block btn-flat" disabled>Detail Kendaraan</button>
								<br>                
								<span id="tampil_sppm"></span>
								<br>
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
									<div class="col-sm-10">
										<input type="text" required class="form-control"  placeholder="Keterangan" name="ket">
									</div>                  
								</div>               

							</div>
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
		</div><!-- /.box -->
		</form>

		
	 
		<?php
		}elseif($set=="view"){
		?>

		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">
					<a href="dealer/sppm/add">
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
				<table id="datatable" class="table table-bordered table-hover">
					<thead>
						<tr>
							<!--th width="1%"><input type="checkbox" id="check-all"></th-->              
							<th width="5%">No</th>
							<th>No Surat</th>              
							<th>Tgl Surat</th>              
							<th>No PO</th>
							<th>No DO</th>
							<th>Tgl DO</th>
							<th>Total Qty Pengambilan</th>              
							<th>No. Polisi</th>  
							<th>Driver</th>            
							<th>Aksi</th>                          
						</tr>
					</thead>
					<tbody>  
					</tbody>
				</table>
			</div><!-- /.box-body -->
		</div><!-- /.box -->


		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>


		<script type="text/javascript">
		  $(document).ready(function(e){
		    $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
		    {
		        return {
		            "iStart": oSettings._iDisplayStart,
		            "iEnd": oSettings.fnDisplayEnd(),
		            "iLength": oSettings._iDisplayLength,
		            "iTotal": oSettings.fnRecordsTotal(),
		            "iFilteredTotal": oSettings.fnRecordsDisplay(),
		            "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
		            "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
		        };
		    };

		    var base_url = "<?php echo base_url();?>/"; // You can use full url here but I prefer like this
		    $('#datatable').DataTable({
		       "pageLength" : 10,
		       "serverSide": true,
		       "ordering": true, // Set true agar bisa di sorting
		        "processing": true,
		        "language": {
		          processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
		          searchPlaceholder: "Pencarian..."
		        },

		       "order": [[1, "desc" ]],
		       "rowCallback": function (row, data, iDisplayIndex) {
		            var info = this.fnPagingInfo();
		            var page = info.iPage;
		            var length = info.iLength;
		            var index = page * length + (iDisplayIndex + 1);
		            $('td:eq(0)', row).html(index);
		        },
		       "ajax":{
		                url :  base_url+'dealer/sppm/getData',
		                type : 'POST'
		              },
		    }); // End of DataTable


		  }); 

		</script>

		<?php
		}
		?>
	</section>
</div>



<script type="text/javascript">
function auto(){
  var tgl = 1;
  $.ajax({
      url : "<?php echo site_url('dealer/sppm/cari_id')?>",
      type:"POST",
      data:"tgl="+tgl,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#no_surat_sppm").val(data[0]);        
      }        
  })
}
function kirim_data_sppm(){    
	$("#tampil_sppm").show();
	cari_lain();
	var no_do = document.getElementById("no_do").value;  

	var xhr;
	if (window.XMLHttpRequest) { // Mozilla, Safari, ...
		xhr = new XMLHttpRequest();
	}else if (window.ActiveXObject) { // IE 8 and older
		xhr = new ActiveXObject("Microsoft.XMLHTTP");
	} 
	 //var data = "no_pl="+birthday1_js;          
		var data = "no_do="+no_do;
		 xhr.open("POST", "dealer/sppm/t_sppm", true); 
		 xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
		 xhr.send(data);
		 xhr.onreadystatechange = display_data;
		 function display_data() {
				if (xhr.readyState == 4) {
						if (xhr.status == 200) {       
								document.getElementById("tampil_sppm").innerHTML = xhr.responseText;
						}else{
								alert('There was a problem with the request.');
						}
				}
		} 
}
function cari_lain(){
	var no_do  = $("#no_do").val();                         
	$.ajax({
			url: "<?php echo site_url('dealer/sppm/cari_lain')?>",
			type:"POST",
			data:"no_do="+no_do,
			cache:false,
			success:function(msg){                
					data=msg.split("|");
					if(data[0]=="ok"){          
						$("#tgl_do").val(data[1]);                
					}else{
						alert(data[0]);
					}
			} 
	})
}

function getDriver()
{
  var driver = $("#no_plat").select2().find(":selected").data("driver");
  $('#driver').val(driver);
}
function getNoPO()
{
  var no_po = $("#no_do").select2().find(":selected").data("no_po");
  $('#no_po').val(no_po);
}
</script>