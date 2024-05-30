<?php 
function mata_uang($a){
    	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
	return number_format($a, 0, ',', '.');
} 
$tabel_jenis_bayar = $gc=='ya'?'tr_sales_order_gc_jenis_bayar':'tr_sales_order_jenis_bayar';
$tabel_jenis_bayar_detail = $gc=='ya'?'tr_sales_order_gc_jenis_bayar_detail':'$tabel_jenis_bayar_detail';
$show_id_so = $gc=='ya'?'id_sales_order_gc':'id_sales_order';
$cekJenisBayar = $this->db->query("SELECT * FROM $tabel_jenis_bayar WHERE $show_id_so = '$id_sales_order'")->row(); 
if($jenis_bayar=='Transfer'){ ?>
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<th>Bank Penerima</th>
				<th>No Rekening</th>
				<th>Tanggal Transfer</th>
				<th>Nilai</th>
			</thead>
			<tbody>
			<?php 
			$ambil = $this->db->query("SELECT * FROM $tabel_jenis_bayar_detail WHERE id_jenis_bayar = '$cekJenisBayar->id_jenis_bayar'");
			foreach ($ambil->result() as $isi) {
				echo "
				<tr>
					<td>$isi->bank_konsumen</td>
					<td>$isi->no_rek_tujuan</td>					
					<td>$isi->tgl_transfer</td>
					<td>$isi->nilai</td>
				</tr>
				";
			}
			?>
			</tbody>
		</table>	
<?php 
}elseif($jenis_bayar=='Cek/Giro'){
?>
	<table class="table table-bordered table-condensed table-striped">
		<thead>
			<th>Bank Konsumen</th>
			<th>No Rekening Tujuan</th>
			<th>No Cek / Giro</th>
			<th>Tanggal Cek / Giro</th>
			<th>Nilai</th>
		</thead>
		<tbody>
		<?php 
		$ambil = $this->db->query("SELECT * FROM $tabel_jenis_bayar_detail WHERE id_jenis_bayar = '$cekJenisBayar->id_jenis_bayar'");
		foreach ($ambil->result() as $isi) {
			echo "
			<tr>
				<td>$isi->bank_konsumen</td>
				<td>$isi->no_rek_tujuan</td>
				<td>$isi->no_cek_giro</td>
				<td>$isi->tgl_cek_giro</td>
				<td>$isi->nilai</td>
			</tr>
			";
		}
		?>
		</tbody>
	</table>
<?php } ?>