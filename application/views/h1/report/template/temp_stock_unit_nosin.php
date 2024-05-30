<?php
$no = date("dmyhis");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=stock_unit_nomesin".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">
  <tr>
    <td align="center">No Mesin</td>
    <td align="center">Kode Item</td>
    <td align="center">Deskripsi Tipe</td>
    <td align="center">Tahun Produksi</td>
    <td align="center">FIFO</td>
    <td align="center">Lokasi</td>
    <td align="center">Slot</td>
    <td align="center">Status Lokasi</td>
		<td align="center">Status Sale</td> 
    <td align="center">No DO</td>
    <td align="center">Kd dealer</td>
    <td align="center">Nama Dealer</td>
    <td align="center">Tgl Enduser</td>
    <td align="center">Jenis Penjualan</td>
    <td align="center">Nama Leasing</td>
    <td align="center">Tgl SSU</td>
 	</tr>    	                    
 	<?php 
 	foreach ($sql->result() as $row) {
 		$tgl_ssu="";$finco="";$jenis_beli="";
 		$cek_ssu = $this->db->query("SELECT LEFT(tr_sales_order.tgl_create_ssu,10) AS tgl,tr_spk.jenis_beli,ms_finance_company.finance_company 
 			FROM tr_sales_order LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
 			LEFT JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company 
 			WHERE tr_sales_order.no_mesin = '$row->no_mesin'");
 		if($cek_ssu->num_rows() > 0){
 			if(isset($cek_ssu->row()->tgl)){
 				$tgl_ssu = $cek_ssu->row()->tgl;
 			}
 			$finco = $cek_ssu->row()->finance_company;
 			$jenis_beli = $cek_ssu->row()->jenis_beli;
 		}

 		$cek_ssu2 = $this->db->query("SELECT LEFT(tr_sales_order_gc.tgl_create_ssu,10) AS tgl,tr_spk_gc.jenis_beli,ms_finance_company.finance_company 
 			FROM tr_sales_order_gc INNER JOIN tr_sales_order_gc_nosin ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc 
 			LEFT JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc
 			LEFT JOIN ms_finance_company ON tr_spk_gc.id_finance_company = ms_finance_company.id_finance_company
 			WHERE tr_sales_order_gc_nosin.no_mesin = '$row->no_mesin'");
 		if($cek_ssu2->num_rows() > 0){
 			if(isset($cek_ssu2->row()->tgl)){
 				$tgl_ssu = $cek_ssu2->row()->tgl;
 			}
 			$finco = $cek_ssu2->row()->finance_company;
 			$jenis_beli = $cek_ssu2->row()->jenis_beli;
 		}


 		echo "
 			<tr>
		    <td>$row->no_mesin</td>
		    <td>$row->id_item</td>
		    <td>$row->deskripsi_samsat</td>
		    <td>$row->tahun_produksi</td>
		    <td>$row->fifo</td>
		    <td>$row->lokasi</td>
		    <td>$row->slot</td>
		    <td>$row->status</td>
		    <td>$row->tipe</td>
		    <td>$row->no_do</td>
		    <td>$row->kode_dealer_md</td>
		    <td>$row->nama_dealer</td>
		    <td></td>
		    <td>$jenis_beli</td>
		    <td>$finco</td>
		    <td>$tgl_ssu</td>
		  </tr>

 		";
 	}
 	?> 	
</table>


