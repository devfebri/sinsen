<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_po_logistik extends CI_Controller {

    public function index(){
        $data = [];
        $data['po_logistik'] = $this->db
        ->select('nrfs.dokumen_nrfs_id')
        ->select('"" as request_id')
        ->select('"" as no_po_ahm')
        ->select('nrfs.no_mesin')
        ->select('nrfs.no_rangka')
        ->select('nrfs.type_code')
        ->select('tk.deskripsi_ahm')
        ->select('nrfs.status')
        ->select('nrfs.deskripsi_warna')
        ->from('tr_dokumen_nrfs as nrfs')
        ->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = nrfs.type_code', 'left')
		->where('nrfs.dokumen_nrfs_id', $this->input->get('dokumen_nrfs_id'))
		->get()->row_array();

		$data['parts'] = $this->db
		->select('nrfs_part.id_part')
		->select('p.nama_part')
		->select('p.harga_md_dealer as harga')
		->select('nrfs_part.qty_part')
		->select('(nrfs_part.qty_part * p.harga_md_dealer) as amount')
		->select('"" as no_faktur_md')
		->from('tr_dokumen_nrfs_part as nrfs_part')
		->join('ms_part as p', 'p.id_part = nrfs_part.id_part')
		->where('nrfs_part.dokumen_nrfs_id', $this->input->get('dokumen_nrfs_id'))
		->get()->result_array();

        $this->load->view('iframe/md/h3/h3_md_po_logistik', $data);
    }

}