<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Stock Level_Dealer_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h4><b>Data Part Insight : Stock Level_Dealer <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</b></h4>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>Nama Dealer</b></td>
		<td align="center"><b>Jumlah Stock</b></td>
 		<td align="center"><b>Pendapatan 6 bulan Terakhir</b></td>
		<td align="center"><b>Stock Level</b></td>
 	</tr>
	 <?php 
	if($sl_penjualan_dealer->num_rows()>0){
		foreach ($sl_penjualan_dealer->result() as $row) { 
			$filter_dealer = '';
            $filter_dealer2 ='' ;
            if ($id_dealer!='all') {
                 $filter_dealer = "and a.id_dealer='$row->id_dealer'";
                 $filter_dealer2 = "WHERE id_dealer='$row->id_dealer'";
            }

            $sl_dealer = $this->db->query(" SELECT a.id_dealer,SUM(a.stok_akhir) as stok_akhir, b.tgl
            FROM ms_h3_dealer_transaksi_stok as a
            JOIN (
            SELECT max(created_at) as tgl, SUM(stok_akhir) as stok_akhir, id_part 
            FROM ms_h3_dealer_transaksi_stok 
            -- $filter_dealer2
			WHERE id_dealer='$row->id_dealer'
            GROUP BY id_part
            ) as b on b.id_part = a.id_part 
            WHERE a.created_at = b.tgl and a.id_dealer='$row->id_dealer'
            GROUP BY a.id_dealer")->row_array();
		if($row->ytd == 0){
			$stock_level = '-';
		}else{
			$stock_level = $sl_dealer['stok_akhir']/$row->ytd;
			$stock_level = number_format($stock_level,2);
		}
		

		echo "
 			<tr>
				<td>$row->nama_dealer</td>";?>
				<td><?php echo $sl_dealer['stok_akhir'] ?></td>
				<td><?php echo number_format($row->ytd,2,",",".") ?></td>
				<td><?php echo number_format($stock_level,2,",",".")?></td>
 			</tr>
		<?php 
 		}
	}else{
		echo "<td colspan='4' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>