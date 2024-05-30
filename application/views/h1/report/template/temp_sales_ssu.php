<?php 
//$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Sales_SSU.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">Kode Dealer</td>
 		<td align="center">Nama Dealer</td>
    <td align="center">Tgl Entry</td>
 		<td align="center">Tgl Invoice Dealer</td>
    <td align="center">Tgl Mohon Samsat</td>         
    <td align="center">Kode Tipe</td>        
    <td align="center">Kode Item</td>         
    <td align="center">Deskripsi Motor AHM</td>            
    <td align="center">Deskripsi Motor Customer</td>            
    <td align="center">Kode Warna</td>            
    <td align="center">Deskripsi Warna</td>            
    <td align="center">No Mesin</td>            
    <td align="center">No Rangka</td>            
    <td align="center">Tahun Produksi</td>            
    <td align="center">Jenis Kendaraan</td>            
    <td align="center">Segment</td>            
    <td align="center">Jenis Pembayaran</td>            
    <td align="center">Fincoy</td>            
    <td align="center">DP Gross</td>            
    <td align="center">DP</td>            
    <td align="center">Tenor</td>            
    <td align="center">Angsuran</td>            
    <td align="center">No Faktur STNK</td>            
    <td align="center">Nama Customer</td>            
    <td align="center">Tipe Customer</td>            
    <td align="center">Alamat</td>            
    <td align="center">Kelurahan</td>            
    <td align="center">Nama Kelurahan</td>            
    <td align="center">Kecamatan</td>            
    <td align="center">Nama Kecamatan</td>            
    <td align="center">Kota</td>            
    <td align="center">Kodepos</td>            
    <td align="center">Kode Provinsi</td>            
    <td align="center">No KTP</td>            
    <td align="center">No HP</td>            
    <td align="center">No Telp</td>            
    <td align="center">Pekerjaan</td>            
    <td align="center">RO</td>            
    <td align="center">ID FLP</td>            
    <td align="center">Nama</td>            
    <td align="center">Tgl Entry MD</td>            
 	</tr>
 	<?php  	
 	$no=1;
  $where = "";
  if($id_dealer!='') $where = "AND tr_sales_order.id_dealer = '$id_dealer'";
  $sql = $this->db->query("SELECT DISTINCT(tr_ssu_detail.no_mesin),tr_sales_order.id_dealer,tr_sales_order.tgl_cetak_invoice,
    tr_scan_barcode.tipe_motor,tr_scan_barcode.id_item,ms_tipe_kendaraan.deskripsi_ahm,ms_tipe_kendaraan.tipe_customer,ms_warna.id_warna,ms_warna.warna,
    ms_warna.warna_samsat,tr_scan_barcode.no_rangka,ms_kategori.kategori,ms_segment.segment,tr_spk.jenis_beli,ms_finance_company.finance_company,
    tr_spk.uang_muka,tr_spk.dp_stor,tr_spk.tenor,tr_spk.angsuran,tr_spk.nama_konsumen,tr_spk.id_kelurahan,ms_kelurahan.kelurahan,tr_spk.id_kecamatan,ms_kecamatan.kecamatan,
    tr_spk.id_kabupaten,tr_spk.id_provinsi,tr_spk.alamat,tr_spk.kodepos,tr_spk.no_ktp,tr_spk.no_hp,tr_spk.no_telp,ms_pekerjaan.pekerjaan,
    tr_prospek.id_flp_md,ms_karyawan_dealer.nama_lengkap,tr_scan_barcode.tgl_penerimaan
    FROM tr_ssu_detail 
    INNER JOIN tr_scan_barcode ON tr_ssu_detail.no_mesin = tr_scan_barcode.no_mesin
    LEFT JOIN tr_sales_order ON tr_scan_barcode.no_mesin = tr_sales_order.no_mesin    
    LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
    LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
    LEFT JOIN ms_kategori ON ms_tipe_kendaraan.id_kategori = ms_kategori.id_kategori
    LEFT JOIN ms_segment ON ms_tipe_kendaraan.id_segment = ms_segment.id_segment
    LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
    LEFT JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
    LEFT JOIN ms_kelurahan ON tr_spk.id_kelurahan = ms_kelurahan.id_kelurahan
    LEFT JOIN ms_kecamatan ON tr_spk.id_kecamatan = ms_kecamatan.id_kecamatan
    LEFT JOIN ms_pekerjaan ON tr_spk.pekerjaan = ms_pekerjaan.id_pekerjaan
    LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
    LEFT JOIN ms_karyawan_dealer ON tr_prospek.id_flp_md = ms_karyawan_dealer.id_flp_md
    WHERE (tr_sales_order.tgl_cetak_invoice BETWEEN '$tgl1' AND '$tgl2' AND tr_scan_barcode.tgl_penerimaan BETWEEN '$tgl3' AND '$tgl4')
    $where");
  foreach ($sql->result() as $row) {
    $sql2 = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer);
    $kode_dealer_md = ($sql2->num_rows() > 0) ? $sql2->row()->kode_dealer_md : "" ;
    $nama_dealer = ($sql2->num_rows() > 0) ? $sql2->row()->nama_dealer : "" ;

    $sql3 = $this->m_admin->getByID("tr_pengajuan_bbn_detail","no_mesin",$row->no_mesin);
    $tgl_mohon_samsat = ($sql3->num_rows() > 0) ? $sql3->row()->tgl_mohon_samsat : "" ;
    $no_faktur = ($sql3->num_rows() > 0) ? $sql3->row()->no_bastd : "" ;

    $sql4 = $this->m_admin->getByID("tr_fkb","no_mesin_spasi",$row->no_mesin);
    $tahun_produksi = ($sql4->num_rows() > 0) ? $sql4->row()->tahun_produksi : "" ;
    echo "
    <tr>
      <td>$kode_dealer_md</td>      
      <td>$nama_dealer</td>      
      <td>$row->tgl_cetak_invoice</td>      
      <td>$tgl_mohon_samsat</td>      
      <td>$row->tipe_motor</td>      
      <td>$row->id_item</td>      
      <td>$row->deskripsi_ahm</td>      
      <td>$row->tipe_customer</td>      
      <td>$row->id_warna</td>      
      <td>$row->warna_samsat</td>      
      <td>$row->no_mesin</td>      
      <td>$row->no_rangka</td>      
      <td>$tahun_produksi</td>      
      <td>$row->kategori</td>      
      <td>$row->segment</td>      
      <td>$row->jenis_beli</td>      
      <td>$row->finance_company</td>      
      <td>$row->uang_muka</td>      
      <td>$row->dp_stor</td>      
      <td>$row->tenor</td>      
      <td>$row->angsuran</td>      
      <td>$no_faktur</td>      
      <td>$row->nama_konsumen</td>      
      <td>I</td>      
      <td>$row->alamat</td>      
      <td>$row->id_kelurahan</td>      
      <td>$row->kelurahan</td>      
      <td>$row->id_kecamatan</td>      
      <td>$row->kecamatan</td>      
      <td>$row->id_kabupaten</td>      
      <td>$row->kodepos</td>      
      <td>$row->id_provinsi</td>      
      <td>$row->no_ktp</td>      
      <td>$row->no_hp</td>      
      <td>$row->no_telp</td>      
      <td>$row->pekerjaan</td>      
      <td></td>      
      <td>$row->id_flp_md</td>            
      <td>$row->nama_lengkap</td>            
      <td>$row->tgl_penerimaan</td>            
    </tr>
    ";

  // $where2 = "";
  // if($id_dealer!='') $where2 = "AND tr_sales_order_gc.id_dealer = '$id_dealer'";
  // $sq = $this->db->query("SELECT DISTINCT(tr_ssu_detail.no_mesin),tr_sales_order_gc.id_dealer,tr_sales_order_gc.tgl_cetak_invoice,
  //   tr_scan_barcode.tipe_motor,tr_scan_barcode.id_item,ms_tipe_kendaraan.deskripsi_ahm,ms_tipe_kendaraan.tipe_customer,ms_warna.id_warna,ms_warna.warna,
  //   ms_warna.warna_samsat,tr_scan_barcode.no_rangka,ms_kategori.kategori,ms_segment.segment,tr_spk_gc.jenis_beli,ms_finance_company.finance_company
  //   FROM tr_ssu_detail     
  //   INNER JOIN tr_sales_order_gc_nosin ON tr_ssu_detail.no_mesin = tr_sales_order_gc_nosin.no_mesin
  //   INNER JOIN tr_sales_order_gc ON tr_sales_order_gc_nosin.id_sales_order_gc = tr_sales_order_gc.id_sales_order_gc
  //   INNER JOIN tr_scan_barcode ON tr_ssu_detail.no_mesin = tr_scan_barcode.no_mesin    
  //   LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
  //   LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
  //   LEFT JOIN ms_kategori ON ms_tipe_kendaraan.id_kategori = ms_kategori.id_kategori
  //   LEFT JOIN ms_segment ON ms_tipe_kendaraan.id_segment = ms_segment.id_segment    
  //   LEFT JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc
  //   LEFT JOIN ms_finance_company ON tr_spk_gc.id_finance_company = ms_finance_company.id_finance_company
  //   WHERE (tr_sales_order_gc.tgl_cetak_invoice BETWEEN '$tgl1' AND '$tgl2' AND tr_scan_barcode.tgl_penerimaan BETWEEN '$tgl3' AND '$tgl4')
  //   $where2");
  // foreach ($sq->result() as $row) {
  //   $sql2 = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer);
  //   $kode_dealer_md = ($sql2->num_rows() > 0) ? $sql2->row()->kode_dealer_md : "" ;
  //   $nama_dealer = ($sql2->num_rows() > 0) ? $sql2->row()->nama_dealer : "" ;

  //   $sql3 = $this->m_admin->getByID("tr_pengajuan_bbn_detail","no_mesin",$row->no_mesin);
  //   $tgl_mohon_samsat = ($sql3->num_rows() > 0) ? $sql3->row()->tgl_mohon_samsat : "" ;
  //   $no_faktur = ($sql3->num_rows() > 0) ? $sql3->row()->no_bastd : "" ;

  //   $sql4 = $this->m_admin->getByID("tr_fkb","no_mesin_spasi",$row->no_mesin);
  //   $tahun_produksi = ($sql4->num_rows() > 0) ? $sql4->row()->tahun_produksi : "" ;
  //   echo "
  //   <tr>
  //     <td>$no</td>
  //     <td>$kode_dealer_md</td>      
  //     <td>$nama_dealer</td>      
  //     <td>$row->tgl_cetak_invoice</td>      
  //     <td>$tgl_mohon_samsat</td>      
  //     <td>$row->tipe_motor</td>      
  //     <td>$row->id_item</td>      
  //     <td>$row->deskripsi_ahm</td>      
  //     <td>$row->tipe_customer</td>      
  //     <td>$row->id_warna</td>      
  //     <td>$row->warna_samsat</td>      
  //     <td>$row->no_mesin</td>      
  //     <td>$row->no_rangka</td>      
  //     <td>$tahun_produksi</td>      
  //     <td>$row->kategori</td>      
  //     <td>$row->segment</td>      
  //     <td>$row->jenis_beli</td>      
  //     <td>$row->finance_company</td>      
  //     <td>$row->uang_muka</td>      
  //     <td>$row->dp_stor</td>      
  //     <td>$row->tenor</td>      
  //     <td>$row->angsuran</td>      
  //     <td>$no_faktur</td>      
  //     <td>$row->nama_konsumen</td>      
  //     <td>I</td>      
  //     <td>$row->alamat</td>      
  //     <td>$row->id_kelurahan</td>      
  //     <td>$row->kelurahan</td>      
  //     <td>$row->id_kecamatan</td>      
  //     <td>$row->kecamatan</td>      
  //     <td>$row->id_kabupaten</td>      
  //     <td>$row->kodepos</td>      
  //     <td>$row->id_provinsi</td>      
  //     <td>$row->no_ktp</td>      
  //     <td>$row->no_hp</td>      
  //     <td>$row->no_telp</td>      
  //     <td>$row->pekerjaan</td>      
  //     <td></td>      
  //     <td>$row->id_flp_md</td>            
  //     <td>$row->nama_lengkap</td>            
  //     <td>$row->tgl_penerimaan</td>
  //   </tr>
  //   ";
    $no++;
  }
 	?>
</table>
 