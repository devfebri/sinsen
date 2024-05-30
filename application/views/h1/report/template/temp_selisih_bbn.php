<?php 
//$bln = sprintf("%'.02d",$bulan);
$no = $tahun;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=SelisihBBN_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");

?>
AS OF  <?php echo date('d-m-Y') ?>
<table border="1">   	
 	<tr>
 		<td align="center">Kode Dealer</td> 		 		 		 		 		
 		<td align="center">Nama Dealer</td> 		 		 		 		 		
 		<td align="center">Carry Over 2019</td> 		 		 		 		 		
 		<td align="center">SSU</td> 		 		 		 		 		
 		<td align="center">BBN IN</td> 		 		 		 		 		
 		<td align="center"></td> 		 		 		 		 		 		
 	</tr>
 	<?php 
 	$t_isi=0;$t_bbn=0;$t_sisa=0;
 	$sql = $this->db->query("SELECT *,count(tr_sales_order.no_mesin) AS jum FROM tr_sales_order INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
 	 	WHERE LEFT(tr_sales_order.tgl_cetak_invoice,4) = '$tahun' GROUP BY ms_dealer.id_dealer ORDER BY ms_dealer.nama_dealer ASC");
 	foreach ($sql->result() as $isi) {
 		$cek = $this->db->query("SELECT count(tr_pengajuan_bbn_detail.no_mesin) AS jum FROM tr_pengajuan_bbn_detail INNER JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn_detail.no_bastd = tr_pengajuan_bbn.no_bastd 
 			WHERE LEFT(tr_pengajuan_bbn_detail.tgl_mohon_samsat,4) = '$tahun' 
 			AND tr_pengajuan_bbn.id_dealer = '$isi->id_dealer'");
 		$bbn = ($cek->num_rows() > 0) ? $cek->row()->jum : 0 ;

		$retail = $this->m_admin->get_penjualan_inv('tahun', $tahun, null, $isi->id_dealer);     
 		$cek_carry = $this->m_admin->getByID("tr_carry_over","id_dealer",$isi->id_dealer);
 		$carry = ($cek_carry->num_rows() > 0) ? $cek_carry->row()->angka : 0 ;

 		$hasil = $bbn - ($retail+$carry);


 		echo "
 		<tr>
 			<td>$isi->kode_dealer_md</td>
 			<td>$isi->nama_dealer</td>
 			<td>".$carry."</td>
 			<td>".$retail."</td>
 			<td>".$bbn."</td>
 			<td>".$hasil."</td>
 		</tr>
 		";
 		$t_sisa += $hasil;
 		$t_bbn += $bbn;
 		$t_isi += $retail;
 	}
 	?> 
 	<tr>
 		<td colspan="2">Total</td>
 		<td><?php echo $t_isi ?></td>
 		<td><?php echo $t_bbn ?></td>
 		<td><?php echo $t_sisa ?></td>
 	</tr>
</table>
