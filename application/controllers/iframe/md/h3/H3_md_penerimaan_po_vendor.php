<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_penerimaan_po_vendor extends CI_Controller {

    public function index(){
		$this->load->model('H3_md_lokasi_rak_model', 'lokasi_rak');

        $data = [];
        $data['penerimaan_po_vendor'] = $this->db
		->select('ppv.id_penerimaan_po_vendor')
		->select('ppv.surat_jalan_ekspedisi')
		->select('ppv.tgl_surat_jalan_ekspedisi')
		->select('ppv.id_po_vendor')
		->select('ppv.no_plat')
		->select('ppv.nama_driver')
		->select('ppv.tanggal')
		->select('ekspedisi.id as id_ekspedisi')
		->select('ekspedisi.nama_ekspedisi')
		->select('ppv.type_mobil')
		->select('ppv.harga_ongkos_angkut_part')
		->select('ppv.jenis_ongkos_angkut_part')
		->select('ppv.per_satuan_ongkos_angkut_part')
		->select('ppv.berat_truk')
		->select('ppv.status')
		->from('tr_h3_md_penerimaan_po_vendor as ppv')
		->join('ms_h3_md_ekspedisi as ekspedisi', 'ekspedisi.id = ppv.id_ekspedisi')
		->where('ppv.id_penerimaan_po_vendor', $this->input->get('id_penerimaan_po_vendor'))
		->get()->row();

		$parts = $this->db
		->select('ppvp.*')
		->select('p.nama_part')
		->select('lokasi.kode_lokasi_rak')
        ->select("(lokasi.kapasitas - lokasi.kapasitas_terpakai) as kapasitas_tersedia")
		->from('tr_h3_md_penerimaan_po_vendor_parts as ppvp')
		->join('ms_part as p', 'p.id_part = ppvp.id_part')
		->join('ms_h3_md_lokasi_rak as lokasi', 'lokasi.id = ppvp.id_lokasi_rak', 'left')
		->where('ppvp.id_penerimaan_po_vendor', $this->input->get('id_penerimaan_po_vendor'))
		->get()->result_array();

		$parts = array_map(function($part){
			$lokasi = $this->lokasi_rak->suggest_lokasi($part['id_part'], $part['qty_order'], false, $part['id_lokasi_rak']);
			if($lokasi != null){
				$part['kapasitas_tersedia'] = intval($lokasi['kapasitas_tersedia']);
				$part['setting_per_part'] = $lokasi['setting_per_part'] == 1;
			}
			return $part;
		}, $parts);

        $data['parts'] = $parts;

        $this->load->view('iframe/md/h3/h3_md_penerimaan_po_vendor', $data);
    }

}