<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=" . $nama_file);
header("Pragma: no-cache");
header("Expires: 0");
$get_data = $this->db->query("SELECT * FROM tr_claim_kpb_generate_detail
	JOIN tr_claim_kpb_generate ON tr_claim_kpb_generate_detail.no_generate=tr_claim_kpb_generate.no_generate AND tr_claim_kpb_generate.tahun=tr_claim_kpb_generate_detail.tahun
	JOIN tr_claim_kpb ON tr_claim_kpb_generate_detail.id_claim_kpb=tr_claim_kpb.id_claim_kpb
	WHERE tr_claim_kpb_generate_detail.no_generate='$no_generate' AND tr_claim_kpb_generate.tahun='$tahun'
");
foreach ($get_data->result() as $rs) {
	$no_kpb = $rs->no_kpb;
	if ($rs->no_kpb == '' or $rs->no_kpb == NULL) {
		$no_kpb = 0;
	}
	$dealer = $this->db->get_where('ms_dealer', ['id_dealer' => $rs->id_dealer])->row();
	$kode_ahass='';

	if($dealer->kode_dealer_md =='TP-P001'){
		$kode_ahass ='07443';
	}else if($dealer->kode_dealer_md =='13726.1'){
		$kode_ahass ='13726';
	}else if($dealer->kode_dealer_md =='05621.1'){
		$kode_ahass ='18133';
	}else if($dealer->kode_dealer_md =='01118.1'){
		$kode_ahass ='01118';
	}else{
		$kode_ahass = $dealer->kode_dealer_md;
	}

	echo 'E20;' . $kode_ahass . ';' . $rs->no_mesin . ';' . $no_kpb . ';' . date('dmY', strtotime($rs->tgl_beli_smh)) . ';' . $rs->service_ke . ';' . $rs->km_service . ';' . date('dmY', strtotime($rs->tgl_service)) . ';' . $rs->no_surat_claim . ';';
	if ($get_data->num_rows() > 1) {
		echo "\r\n";
	}
}
