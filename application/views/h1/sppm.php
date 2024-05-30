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
									<label for="inputEmail3" class="col-sm-2 control-label">No Surat</label>
									<div class="col-sm-4">
										<input type="text" required class="form-control" id="no_surat_sppm" readonly placeholder="No Surat" name="no_surat">
									</div>                                    
									<div class="col-sm-1">                  
									</div>
									<label for="inputEmail3" class="col-sm-2 control-label">Tgl Surat</label>
									<div class="col-sm-3">
										<input type="text" required class="form-control" id="tanggal" value="<?php echo gmdate("Y-m-d", time()+60*60*7); ?>" placeholder="Tgl Surat" name="tgl_surat">
									</div>                  
								</div>
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label">No DO</label>
									<div class="col-sm-4">
										<select class="form-control select2" name="no_do" id="no_do">
											<option value="">- choose -</option>
											<?php 
											foreach($dt_do->result() as $val) {
												echo "
												<option value='$val->no_do'>$val->no_do</option>;
												";
											}
											?>
										</select>
									</div>                  
									<div class="col-sm-1">                  
										<button onclick="kirim_data_sppm()" type="button" class="btn btn-flat btn-primary btn-sm">Generate</button>
									</div>
									<label for="inputEmail3" class="col-sm-2 control-label">Tgl Do</label>
									<div class="col-sm-3">
										<input type="text" readonly required class="form-control" id="tgl_do" placeholder="Tgl Do" name="tgl_do">
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
						<button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
				<table id="example" class="table table-bordered table-hover">
					<thead>
						<tr>
							<!--th width="1%"><input type="checkbox" id="check-all"></th-->              
							<th width="5%">No</th>
							<th>No Surat</th>              
							<th>Tgl Surat</th>              
							<th>No DO</th>
							<th>Tgl DO</th>
							<th>Total Qty Pengambilan</th>              
							<th>Aksi</th>                          
						</tr>
					</thead>
					<tbody>            
					<?php 
					$no=1; 
					foreach($dt_sppm->result() as $row) {     
						$print = $this->m_admin->set_tombol($id_menu,$group,'print');
						$s = $this->db->query("SELECT SUM(qty_ambil) AS total FROM tr_sppm_detail WHERE no_surat_sppm = '$row->no_surat_sppm'")->row();          
						echo "
						<tr>
							<td>$no</td>
							<td>$row->no_surat_sppm</td>              
							<td>$row->tgl_surat</td>              
							<td>$row->no_do</td>
							<td>$row->tgl_do</td>
							<td>$s->total</td>                                          
							<td>                                
								<a href='dealer/sppm/cetak?id=$row->no_do'>
									<button $print class='btn btn-flat btn-xs btn-primary'><i class='fa fa-print'></i> Cetak Surat</button>
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
</script>