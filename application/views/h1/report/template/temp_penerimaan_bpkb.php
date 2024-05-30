<?php 
$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=PenerimaanBPKB_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">Kode Dealer</td>
    <td align="center">Nama Dealer</td>
    <td align="center">No Mesin</td>    
    <td align="center">No Rangka</td>    
    <td align="center">Desc Tipe Cust</td>    
    <td align="center">Warna</td>    
    <td align="center">Tahun Produksi</td>    
    <td align="center">Tgl Terima BPKB</td>    
    <td align="center">Nama Customer</td>    
    <td align="center">No HP</td>    
    <td align="center">No BPKB</td>    
    <td align="center">No Polisi</td>    
    <td align="center">Alamat</td>    
 		<td align="center">No Faktur AHM</td>    
 	</tr>
 	<?php  	 	
  $sql = $this->db->query("SELECT ms_dealer.kode_dealer_md,ms_dealer.nama_dealer,tr_scan_barcode.no_mesin,tr_scan_barcode.no_rangka,
    ms_tipe_kendaraan.tipe_customer, ms_warna.warna, tr_fkb.tahun_produksi,tr_penyerahan_bpkb.tgl_serah_terima,tr_spk.nama_konsumen,tr_spk.no_hp,tr_entry_stnk.no_bpkb,
    tr_faktur_stnk_detail.no_bastd,tr_spk.alamat,tr_entry_stnk.no_pol
    FROM tr_penyerahan_bpkb INNER JOIN tr_penyerahan_bpkb_detail ON tr_penyerahan_bpkb.no_serah_bpkb = tr_penyerahan_bpkb_detail.no_serah_bpkb
    LEFT JOIN ms_dealer ON tr_penyerahan_bpkb.id_dealer  = ms_dealer.id_dealer
    LEFT JOIN tr_scan_barcode ON tr_penyerahan_bpkb_detail.no_mesin = tr_scan_barcode.no_mesin
    LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
    LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
    LEFT JOIN tr_fkb ON tr_scan_barcode.no_mesin = tr_fkb.no_mesin_spasi
    LEFT JOIN tr_sales_order ON tr_penyerahan_bpkb_detail.no_mesin = tr_sales_order.no_mesin
    LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
    LEFT JOIN tr_entry_stnk ON tr_penyerahan_bpkb_detail.no_mesin = tr_entry_stnk.no_mesin
    LEFT JOIN tr_faktur_stnk_detail ON tr_penyerahan_bpkb_detail.no_mesin = tr_faktur_stnk_detail.no_mesin
    WHERE tr_penyerahan_bpkb.tgl_serah_terima BETWEEN '$tgl1' AND '$tgl2'");
  foreach ($sql->result() as $isi) {
    echo "
    <tr>
      <td>$isi->kode_dealer_md</td>
      <td>$isi->nama_dealer</td>
      <td>$isi->no_mesin</td>
      <td>$isi->no_rangka</td>
      <td>$isi->tipe_customer</td>
      <td>$isi->warna</td>
      <td>$isi->tahun_produksi</td>
      <td>$isi->tgl_serah_terima</td>
      <td>$isi->nama_konsumen</td>
      <td>$isi->no_hp</td>
      <td>$isi->no_bpkb</td>
      <td>$isi->no_pol</td>
      <td>$isi->alamat</td>
      <td>$isi->no_bastd</td>
    </tr>
    ";
  }
 	?>
</table>
 