<table class="table table-bordered table-stripped" id="example2">
	<thead>
		<th>Nama Konsumen</th>
	  <th>Alamat Konsumen</th>
	  <th>No Mesin</th>
	  <th>No Rangka</th>
	  <th>No Faktur AHM</th>
	  <th>Tipe</th>
	  <th>Warna</th>
	  <th>Tahun</th>
	  <th>Harga BBN</th>
	  <th>Tgl Jual</th>
	  <th>Tgl Mohon Samsat</th>
	  <th>Kesalahan Disengaja</th>
	</thead>
	<tbody>
	<?php 
	$no=1;$isi=0;
	foreach ($detail->result() as $dt) {
		if ($dt->sengaja <> null or $dt->sengaja=='') {
			$sengaja = '';
		}else{
			$sengaja="<i class='glyphicon glyphicon-ok'></i>";
		}
	 	echo "
	 	<tr>	 	
		 	<td>$dt->nama_konsumen</td>
		 	<td>$dt->alamat</td>
		 	<td>$dt->no_mesin</td>
		 	<td>$dt->no_rangka</td>
		 	<td>$dt->no_faktur</td>
		 	<td>$dt->tipe_ahm</td>
		 	<td>$dt->warna</td>
		 	<td>$dt->tahun</td>
		 	<td>$dt->biaya_bbn</td>
		 	<td>$dt->tgl_jual</td>
		 	<td>$dt->tgl_mohon_samsat</td>
		 	<td>$sengaja</td>
	 	</tr>
	 	";	 	
	 	$isi++;
	 }
 
		/* sepertinya tidak kepakai karena sudah pakai tabel tr_bantuan_bbn_luar
		$sql = $this->db->query("SELECT tr_bantuan_bbn.*, ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,ms_warna.warna_samsat FROM tr_bantuan_bbn				
				INNER JOIN ms_tipe_kendaraan ON tr_bantuan_bbn.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
				LEFT JOIN ms_warna ON tr_bantuan_bbn.id_warna = ms_warna.id_warna 						
			 	WHERE tr_bantuan_bbn.status = 'approved' AND tr_bantuan_bbn.tgl_samsat = '$tanggal'");

		foreach ($sql->result() as $row) {				  
			$isi++;
			$nosin_spasi = substr_replace($row->no_mesin," ", 5, -strlen($row->no_mesin));
			$nosin_strip = substr_replace($row->no_mesin,"-", 5, -strlen($row->no_mesin));
			//$nosin_strip = "1212-1212";
		  $re = $this->m_admin->getByID("tr_fkb","no_mesin",$nosin_spasi)->row();
		  $lu = $this->m_admin->getByID("ms_kelurahan","id_kelurahan",$row->id_kelurahan)->row();
		  //$tanggal = date("d/m/Y", strtotime($re->tgl_upload));
		  $tgl_jual = $row->tgl_samsat;
		  $wil = $this->db->query("SELECT ms_kabupaten.* FROM ms_kabupaten INNER JOIN ms_kecamatan ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
				INNER JOIN ms_kelurahan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan 
				WHERE ms_kelurahan.id_kelurahan = '$lu->id_kelurahan'")->row();
			$cek_biaya_bbn_md_bj = $this->db->query("SELECT * FROM ms_bbn_biro WHERE id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND tahun_produksi = '$row->tahun_produksi'")->row();

			$tipe_customer = "Customer Umum";
			if ($tipe_customer =='Customer Umum') {
				if(isset($cek_biaya_bbn_md_bj->biaya_bbn)){
					$biaya_bbn_md_bj = $cek_biaya_bbn_md_bj->biaya_bbn;
				}else{
					$biaya_bbn_md_bj = 0;
				}				
			}elseif ($tipe_customer == 'Instansi') {
				if(isset($cek_biaya_bbn_md_bj->biaya_instansi)){
					$biaya_bbn_md_bj = $cek_biaya_bbn_md_bj->biaya_instansi;
				}else{
					$biaya_bbn_md_bj = 0;
				}				
			}

			$wil = $this->db->query("SELECT ms_kabupaten.* FROM ms_kabupaten INNER JOIN ms_kecamatan ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
				INNER JOIN ms_kelurahan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan 
				WHERE ms_kelurahan.id_kelurahan = '$lu->id_kelurahan'")->row();
			$cek_biaya_bbn_md_bj = $this->db->query("SELECT * FROM ms_bbn_biro WHERE id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND tahun_produksi = '$row->tahun_produksi'");
			if($cek_biaya_bbn_md_bj->num_rows() > 0){
				$gt = $cek_biaya_bbn_md_bj->row();
				$tipe_customer = "Customer Umum";
				if ($tipe_customer =='Customer Umum') {
					$biaya_bbn_md_bj = $gt->biaya_bbn;
				}elseif ($tipe_customer == 'Instansi') {
					$biaya_bbn_md_bj = $gt->biaya_instansi;
				}
			}else{
				$biaya_bbn_md_bj = 0;		
			}

			echo "
			 	<tr>
			 	<td>$row->nama_konsumen</td>
			 	<td>$row->alamat</td>
			 	<td>$row->no_mesin</td>
			 	<td>$row->no_rangka</td>
			 	<td>$row->no_faktur</td>
			 	<td>$row->tipe_ahm</td>
			 	<td>$row->warna_samsat</td>
			 	<td>$row->tahun_produksi</td>
			 	<td>$row->biaya_bbn</td>
			 	<td>$row->tgl_faktur</td>
			 	<td>$row->tgl_samsat</td>
			 	<td></td>
			 	</tr>
			 	";									
		}
		*/
	 ?>

	 <input type="hidden" name="isi" id="isi_generate" value="<?php echo $isi ?>">

	</tbody>
</table>