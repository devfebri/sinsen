	<?php 
		function mata_uang($a){
    	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
		    return number_format($a, 0, ',', '.');
		} ?>

<?php 
	$tabel_jenis_bayar = $gc=='ya'?'tr_sales_order_gc_jenis_bayar':'tr_sales_order_jenis_bayar';
	$tabel_jenis_bayar_detail = $gc=='ya'?'tr_sales_order_gc_jenis_bayar_detail':'tr_sales_order_jenis_bayar_detail';
	$show_id_so = $gc=='ya'?'id_sales_order_gc':'id_sales_order';
	if ($jenis_bayar=='Transfer') { ?>
	<?php 
		$cekJenisBayar = $this->db->query("SELECT * FROM $tabel_jenis_bayar WHERE $show_id_so = '$id_sales_order'");
	if ($cekJenisBayar->num_rows()==0) { ?>
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<th>Bank Penerima</th>
				<th>No Rekening</th>
				<th>Tanggal Transfer</th>
				<th>Nilai</th>
				<th>Aksi</th>
			</thead>
			<tbody>
				<?php 
					$login_id       = $this->session->userdata('id_user');	
					$getDetailJenis = $this->db->query("SELECT * FROM $tabel_jenis_bayar_detail WHERE status='new' AND created_by='$login_id'"); 
				if ($getDetailJenis->num_rows()>0) {
					foreach ($getDetailJenis->result() as $key => $val) {
					$id_dealer = $this->m_admin->cari_dealer();
						$rek = $this->db->query("SELECT * FROM ms_norek_dealer_detail
												INNER JOIN ms_norek_dealer on ms_norek_dealer_detail.id_norek_dealer=ms_norek_dealer.id_norek_dealer
												INNER JOIN ms_bank on ms_norek_dealer_detail.id_bank=ms_bank.id_bank
												WHERE id_dealer='$id_dealer' AND id_norek_dealer_detail='$val->no_rek_tujuan' ");
						if ($rek->num_rows()>0) {
							$bank = $rek->row()->bank;
							$no_rek = $rek->row()->no_rek;
						}else{
							$bank='';
							$no_rek='';
						}
								 ?>
					<tr>
						<td><?php echo $bank?></td>
						<td><?php echo $no_rek?></td>
						<td><?php echo $val->tgl_transfer?></td>
						<td align='right'><?php echo mata_uang($val->nilai)?></td>
						<td>
            <button title="Hapus Data"
                class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
                onClick="hapus_data_jenis('<?php echo $val->id; ?>')"></button>
          	</td>

					</tr>
				<?php
					}
				}
					?>
			</tbody>
			<tfoot>
				<tr>
					<td>
						<?php 
						$id_dealer = $this->m_admin->cari_dealer();
						$rek = $this->db->query("SELECT * FROM ms_norek_dealer_detail
												INNER JOIN ms_norek_dealer on ms_norek_dealer_detail.id_norek_dealer=ms_norek_dealer.id_norek_dealer
												INNER JOIN ms_bank on ms_norek_dealer_detail.id_bank=ms_bank.id_bank
												WHERE id_dealer='$id_dealer'
						") ?>
						<select class="form-control select2" name="no_rek_tujuan" id="no_rek_tujuan" onchange="getRek()">
							<?php if ($rek->num_rows()>0): ?>
								<option >- Choose -</option>
								<?php foreach ($rek->result() as $key => $res): ?>
									<option value="<?php echo $res->id_norek_dealer_detail?>" data-norek="<?php echo $res->no_rek?>"><?php echo $res->bank?> | <?php echo $res->no_rek?> | <?php echo $res->nama_rek?></option>
								<?php endforeach ?>
							<?php endif ?>
						</select>
					</td>
					<td><input type="text" name="no_rek_dealer" class="form-control" id="no_rek_dealer" disabled></td>
					<td><input type="text" name="tgl_transfer" id="tgl_transfer" class="form-control tanggal"></td>
					<td><input type="text" name="nilai" id="nilai" class="form-control"></td>
					<td><button class="btn btn-primary btn-sm btn-flat" type="button" onclick="saveJenisBayar()"><i class="fa fa-plus" ></i></button></td>
				</tr>
			</tfoot>
		</table>
	<?php }else{ ?>
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<th>Bank Penerima</th>
				<th>No Rekening</th>
				<th>Tanggal Transfer</th>
				<th>Nilai</th>
			</thead>
			<tbody>
				<?php 
					$login_id       = $this->session->userdata('id_user');	
					$getJenisBayar = $this->db->query("SELECT * FROM $tabel_jenis_bayar WHERE $show_id_so='$id_sales_order'")->row()->id_jenis_bayar;
					$getDetailJenis = $this->db->query("SELECT * FROM $tabel_jenis_bayar_detail WHERE id_jenis_bayar='$getJenisBayar'"); 
				if ($getDetailJenis->num_rows()>0) {
					foreach ($getDetailJenis->result() as $key => $val) {
					$id_dealer = $this->m_admin->cari_dealer();
						$rek = $this->db->query("SELECT * FROM ms_norek_dealer_detail
												INNER JOIN ms_norek_dealer on ms_norek_dealer_detail.id_norek_dealer=ms_norek_dealer.id_norek_dealer
												INNER JOIN ms_bank on ms_norek_dealer_detail.id_bank=ms_bank.id_bank
												WHERE id_dealer='$id_dealer' AND id_norek_dealer_detail='$val->no_rek_tujuan' ");
						if ($rek->num_rows()>0) {
							$bank = $rek->row()->bank;
							$no_rek = $rek->row()->no_rek;
						}else{
							$bank='';
							$no_rek='';
						}
								 ?>
					<tr>
						<td><?php echo $bank?></td>
						<td><?php echo $no_rek?></td>
						<td><?php echo $val->tgl_transfer?></td>
						<td align='right'><?php echo mata_uang($val->nilai)?></td>

					</tr>
				<?php
					}
				}
					?>
			</tbody>
		</table>
	
	<?php } ?>




<?php // }elseif ($jenis_bayar=='Cash On Hand Collection' OR $jenis_bayar=='Cek/Giro') { ?>
<?php }elseif ($jenis_bayar=='Cek/Giro') { ?>
	<table class="table table-bordered table-condensed table-striped">
		<thead>
			<th>Bank Konsumen</th>
			<th>No Rekening Tujuan</th>
			<th>No Cek / Giro</th>
			<th>Tanggal Cek / Giro</th>
			<th>Nilai</th>
			<th>Aksi</th>
		</thead>
		<tbody>
				<?php 
					$login_id       = $this->session->userdata('id_user');	
					$getDetailJenis = $this->db->query("SELECT * FROM $tabel_jenis_bayar_detail WHERE status='new' AND created_by='$login_id'"); 
				if ($getDetailJenis->num_rows()>0) {
					foreach ($getDetailJenis->result() as $key => $val) {
					$id_dealer = $this->m_admin->cari_dealer();
						$rek = $this->db->query("SELECT * FROM ms_norek_dealer_detail
												INNER JOIN ms_norek_dealer on ms_norek_dealer_detail.id_norek_dealer=ms_norek_dealer.id_norek_dealer
												INNER JOIN ms_bank on ms_norek_dealer_detail.id_bank=ms_bank.id_bank
												WHERE id_dealer='$id_dealer' AND id_norek_dealer_detail='$val->no_rek_tujuan' ");
						if ($rek->num_rows()>0) {
							$bank = $rek->row()->bank;
							$no_rek = $rek->row()->no_rek;
						}else{
							$bank='';
							$no_rek='';
						}
								 ?>
					<tr>
						<td><?php echo  $val->bank_konsumen ?></td>
						<td><?php echo $bank?> | <?php echo $no_rek?></td>
						<td><?php echo $val->no_cek_giro ?></td>
						<td><?php echo $val->tgl_cek_giro ?></td>
						<td align='right'><?php echo mata_uang($val->nilai)?></td>
						<td>
            <button title="Hapus Data"
                class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
                onClick="hapus_data_jenis('<?php echo $val->id; ?>')"></button>
          	</td>

					</tr>
				<?php
					}
				}
					?>
			</tbody>
		<tfoot>
			<tr>
				<td><input type="text" name="bank_konsumen" id="bank_konsumen" class="form-control"></td>
				<td>
					<?php 
					$id_dealer = $this->m_admin->cari_dealer();
					$rek = $this->db->query("SELECT * FROM ms_norek_dealer_detail
											INNER JOIN ms_norek_dealer on ms_norek_dealer_detail.id_norek_dealer=ms_norek_dealer.id_norek_dealer
											INNER JOIN ms_bank on ms_norek_dealer_detail.id_bank=ms_bank.id_bank
											WHERE id_dealer='$id_dealer'
					") ?>
					<select class="form-control select2" name="no_rek_tujuan" id="no_rek_tujuan" onchange="getRek()">
						<?php if ($rek->num_rows()>0): ?>
							<option >- Choose -</option>
							<?php foreach ($rek->result() as $key => $res): ?>
								<option value="<?php echo $res->id_norek_dealer_detail?>" data-norek="<?php echo $res->no_rek?>"><?php echo $res->bank?> | <?php echo $res->no_rek?> | <?php echo $res->nama_rek?></option>
							<?php endforeach ?>
						<?php endif ?>
					</select>
				</td>	
				<td><input type="text" name="no_cek_giro" id="no_cek_giro" class="form-control"></td>
				<td><input type="text" name="tgl_cek_giro" id="tgl_cek_giro" class="form-control tanggal"></td>
				<td><input type="text" name="nilai" id="nilai" class="form-control"></td>
				<td><button class="btn btn-primary btn-sm btn-flat" type="button" onclick="saveJenisBayarGiro()"><i class="fa fa-plus" ></i></button></td>
			</tr>
		</tfoot>
	</table>
<?php } ?>


<script type="text/javascript">
	 function saveJenisBayar()
	  {
	    var value={no_rek_tujuan:$('#no_rek_tujuan').val(),
	               tgl_transfer:$('#tgl_transfer').val(),
	               nilai:$('#nilai').val(),
	               gc:'<?= $gc ?>'
	        }
	    $.ajax({
	         beforeSend: function() { $('#loading-status').show(); },
	         url:"<?php echo site_url('dealer/sales_order/saveJenisBayar')?>",
	         type:"POST",
	         data:value,
	         cache:false,
	         success:function(html){
	            $('#loading-status').hide();
	            getJenisBayar();
	         },
	         statusCode: {
	      500: function() {
	        $('#loading-status').hide();
	        alert("Something Wen't Wrong");
	      }
	    }
	    });
	  }

	  function saveJenisBayarGiro()
	  {
	    var value={no_rek_tujuan:$('#no_rek_tujuan').val(),
	               bank_konsumen:$('#bank_konsumen').val(),
	               no_cek_giro:$('#no_cek_giro').val(),
	               tgl_cek_giro:$('#tgl_cek_giro').val(),
	               nilai:$('#nilai').val(),
	               gc:'<?= $gc ?>'
	        }
	    $.ajax({
	         beforeSend: function() { $('#loading-status').show(); },
	         url:"<?php echo site_url('dealer/sales_order/saveJenisBayarGiro')?>",
	         type:"POST",
	         data:value,
	         cache:false,
	         success:function(html){
	            $('#loading-status').hide();
	            getJenisBayar();
	         },
	         statusCode: {
	      500: function() {
	        $('#loading-status').hide();
	        alert("Something Wen't Wrong");
	      }
	    }
	    });
	  }

	  function getRek()
		{
		  var no_rek_dealer = $("#no_rek_tujuan").select2().find(":selected").data("norek");
		  $('#no_rek_dealer').val(no_rek_dealer);
		}		
</script>
<script type="text/javascript">
function hapus_data_jenis(a){ 
    var id  = a;       
    $.ajax({
        url : "<?php echo site_url('dealer/sales_order/delete_jenis_bayar')?>",
        type:"POST",
        data:"id="+id+"&gc=<?= $gc ?>",
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              getJenisBayar();
            }
        }
    })
}
function tes(){
	alert("ok");
}
</script>
