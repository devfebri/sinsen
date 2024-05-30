<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");

	echo "
	<table>
		<tr>
			<td>D</td>
			<td>Seller ID</td>
			<td>Buyer ID</td>
			<td>Buyer Seasonal Account Flag</td>
			<td>Invoice No.</td>
			<td>Invoice Remark </td>
			<td>Invoice Date </td>
			<td>Expiry Date </td>
			<td>Due Date </td>
			<td>Amount Currency </td>
			<td>Invoice Amount </td>
			<td>VAT Type </td>
			<td>VAT Percentage </td>
			<td>VAT Amount Currency</td>
			<td>VAT Amount </td>
			<td>Total Invoice Amount Currency </td>
			<td>Total Invoice Amount </td>
			<td>Immediate Flag </td>
			<td>Early Payment Discount Flag </td>
			<td>Seller Account Description </td>
			<td>Buyer Account Description </td>
			<td>Charge Instruction </td>
			<td>Debit Charge </td>
			<td>Type</td>
		</tr>	
	";

// $tgl_faktur = '2023-10-01';

if($tgl_faktur!=''){
	$get_data 	= $this->db->query("
		select a.no_faktur , a.tgl_faktur , a.tgl_cair, a.tgl_overdue , a.total_bayar , b.id_dealer , c.kode_dealer_md , c.nama_dealer, c.head_office 
		from tr_invoice_dealer a 
		join tr_do_po b on a.no_do =b.no_do 
		join ms_dealer c on c.id_dealer = b.id_dealer 
		where a.tgl_faktur ='$tgl_faktur' and bank like 'Danamon%'
	");

	foreach ($get_data->result() as $isi) {				
		// $inv = $this->db->query("SELECT * FROM tr_invoice_dealer WHERE no_do = '$isi->no_do'")->row();
		if($isi->tgl_faktur != "0000-00-00"){
			$tgl_faktur = date("Ymd", strtotime($isi->tgl_faktur));
			$top 	= $isi->top_unit;
			$tgl_due_date = date("Ymd", strtotime($isi->tgl_overdue));
			// $tgl_due_date = date("Ymd", strtotime("+".$top." days", strtotime($tgl_faktur))); //operasi penjumlahan tanggal sebanyak 6 hari                    
		}else{
			$tgl_due_date = "";
		}
		
		// $isi_tgl_cair = date("Ymd", strtotime($isi->tgl_cair));
		// $isi_tgl_tempo = $tgl2;
		$description= $isi->nama_dealer." ". $isi->no_faktur;

		// $get_inv = $this->m_admin->get_detail_inv_dealer_dpp($isi->no_do,$isi->id_item); // bisa disederhanakan di top, bunga, dan df
		// $qty_do         = $get_inv['detail'][$isi->id_item]['qty_do'];
		// $harga         = $get_inv['detail'][$isi->id_item]['harga'];
		// $diskon_top    = $get_inv['detail'][$isi->id_item]['diskon_top']/$qty_do;
		// $subtotal      = $get_inv['detail'][$isi->id_item]['subtotal'];
		// $ppn           = $get_inv['detail'][$isi->id_item]['ppn']/$qty_do;
		// $diskon_satuan = $get_inv['detail'][$isi->id_item]['diskon_satuan'];
		// $rumus_baru = ($harga-($diskon_top+$diskon_satuan))+$ppn;

		echo "<tr>
			<td>D</td>
			<td>SINSEN01</td>
			<td>&nbsp;$isi->head_office</td>
			<td>N</td>
			<td>$isi->no_faktur</td>
			<td></td>
			<td>$tgl_faktur</td>
			<td>$tgl_due_date</td>
			<td>$tgl_due_date</td>
			<td>IDR</td>
			<td>$isi->total_bayar</td>
			<td>A</td>
			<td></td>
			<td>IDR</td>
			<td>0</td>
			<td>IDR</td>
			<td>$isi->total_bayar</td>
			<td>N</td>
			<td></td>
			<td>$description</td>
			<td>$description</td>
			<td></td>
			<td>S</td>
			<td>B</td>
		</tr>";	
	}	  
}

echo  "</table>";

?>      

