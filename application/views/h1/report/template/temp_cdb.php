<?php 
$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=CDB_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">No Mesin</td>
    <td align="center">Deskripsi MOtor Customer</td>
    <td align="center">Deskripsi Warna</td>    
    <td align="center">Jenis Customer</td>    
    <td align="center">Jenis Kelamin</td>    
    <td align="center">Tgl Lahir</td>    
    <td align="center">Nama Customer</td>    
    <td align="center">Alamat</td>    
    <td align="center">Kelurahan</td>    
    <td align="center">Nama Kelurahan</td>    
    <td align="center">Kecamatan</td>    
    <td align="center">Nama Kecamatan</td>        
    <td align="center">Kota</td>        
    <td align="center">Kodepos</td>        
    <td align="center">Kode Provinsi</td>        
    <td align="center">Agama</td>        
    <td align="center">Pengeluaran</td>        
    <td align="center">Pekerjaan</td>        
    <td align="center">Pendidikan</td>        
    <td align="center">Penanggungjawab</td>        
    <td align="center">No HP</td>        
    <td align="center">No Telp</td>        
    <td align="center">Bersedia Dihubungi</td>        
    <td align="center">Merk Motor Sekarang</td>        
    <td align="center">Digunakan Untuk</td>        
    <td align="center">Yang Menggunakan Motor</td>        
    <td align="center">Hobi</td>        
    <td align="center">ID FLP</td>        
    <td align="center">Nama FLP Dealer</td>        
    <td align="center">Kode Dealer</td>        
    <td align="center">Nama Dealer</td>        
 	</tr>
 	<?php  	 	    
  $sql = $this->db->query("SELECT tr_sales_order.no_mesin, ms_tipe_kendaraan.tipe_customer,ms_warna.warna,tr_prospek.jenis_kelamin,
    tr_spk.nama_konsumen,tr_spk.tgl_lahir,tr_spk.alamat,tr_spk.id_kelurahan,ms_kelurahan.kelurahan,tr_spk.id_kecamatan,ms_kecamatan.kecamatan,
    tr_spk.id_kabupaten,tr_spk.id_provinsi,tr_spk.kodepos,ms_agama.agama,ms_pengeluaran_bulan.pengeluaran,ms_pendidikan.pendidikan,ms_pekerjaan.pekerjaan,
    tr_spk.nama_penjamin,tr_spk.no_hp,tr_spk.no_telp,tr_cdb.sedia_hub,tr_spk.tipe_customer,ms_tipe_kendaraan.tipe_ahm,ms_digunakan.digunakan,
    tr_cdb.menggunakan,ms_hobi.hobi,tr_prospek.id_flp_md,ms_karyawan_dealer.nama_lengkap,ms_dealer.kode_dealer_md,ms_dealer.nama_dealer
    FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
    LEFT JOIN tr_cdb ON tr_spk.no_spk = tr_cdb.no_spk
    LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
    LEFT JOIN ms_dealer ON tr_spk.id_dealer = ms_dealer.id_dealer    
    LEFT JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
    LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
    LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
    LEFT JOIN ms_kelurahan ON tr_spk.id_kelurahan = ms_kelurahan.id_kelurahan
    LEFT JOIN ms_kecamatan ON tr_spk.id_kecamatan = ms_kecamatan.id_kecamatan
    LEFT JOIN ms_pengeluaran_bulan ON tr_spk.pengeluaran_bulan = ms_pengeluaran_bulan.pengeluaran
    LEFT JOIN ms_agama ON tr_cdb.agama = ms_agama.id_agama
    LEFT JOIN ms_pendidikan ON tr_cdb.pendidikan = ms_pendidikan.id_pendidikan
    LEFT JOIN ms_pekerjaan ON tr_spk.pekerjaan = ms_pekerjaan.id_pekerjaan
    LEFT JOIN ms_jenis_sebelumnya ON tr_cdb.jenis_sebelumnya = ms_jenis_sebelumnya.jenis_sebelumnya     
    LEFT JOIN ms_digunakan ON tr_cdb.digunakan = ms_digunakan.id_digunakan
    LEFT JOIN ms_hobi ON tr_cdb.hobi = ms_hobi.id_hobi
    LEFT JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer    
    WHERE LEFT(tr_cdb.created_at,7) BETWEEN '$tgl1' AND '$tgl2'");  
  foreach ($sql->result() as $isi) {    
    if($status=='on'){
      $cek = $this->m_admin->getByID("tr_cdb_generate_detail","no_mesin",$isi->no_mesin);
      if($cek->num_rows() > 0){
        $jalan = 1;
      }else{
        $jalan = 0;
      }
    }else{
      $jalan = 1;
    }
    if($jalan == 1){
      echo "
      <tr>
        <td>$isi->no_mesin</td>                
        <td>$isi->tipe_customer</td>                
        <td>$isi->warna</td>                
        <td>$isi->tipe_customer</td>                
        <td>$isi->jenis_kelamin</td>                
        <td>$isi->tgl_lahir</td>                
        <td>$isi->nama_konsumen</td>                
        <td>$isi->alamat</td>                
        <td>$isi->id_kelurahan</td>                
        <td>$isi->kelurahan</td>                
        <td>$isi->id_kecamatan</td>                
        <td>$isi->kecamatan</td>                
        <td>$isi->id_kabupaten</td>                
        <td>$isi->kodepos</td>                
        <td>$isi->id_provinsi</td>                
        <td>$isi->agama</td>                
        <td>$isi->pengeluaran</td>                
        <td>$isi->pekerjaan</td>                
        <td>$isi->pendidikan</td>                
        <td>$isi->nama_penjamin</td>                
        <td>$isi->no_hp</td>                
        <td>$isi->no_telp</td>                
        <td>$isi->sedia_hub</td>                
        <td>$isi->tipe_ahm</td>                
        <td>$isi->digunakan</td>                
        <td>$isi->menggunakan</td>                
        <td>$isi->hobi</td>                
        <td>$isi->id_flp_md</td>                
        <td>$isi->nama_lengkap</td>                
        <td>$isi->kode_dealer_md</td>                
        <td>$isi->nama_dealer</td>                
      </tr>
      ";
    }
  }
 	?>
</table>
 