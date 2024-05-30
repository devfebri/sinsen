<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_laporan_penerimaan_barang extends CI_Controller {

    public function index(){
        $data = [];
        $this->db
        ->select('pb.*')
        ->select('date_format(pb.tanggal_penerimaan, "%d/%m/%Y") as tanggal_penerimaan')
        ->select('date_format(pb.tgl_surat_jalan_ekspedisi, "%d/%m/%Y") as tgl_surat_jalan_ekspedisi')
        ->select('date_format(pb.created_at, "%d-%m-%Y") as created_at')
		->select('e.nama_ekspedisi as vendor_name')
		->select('o.id as id_ongkos_angkut_part')
        ->from('tr_h3_md_penerimaan_barang as pb')
		->join('ms_h3_md_ekspedisi as e', 'e.id = pb.id_vendor')
		->join('ms_h3_md_ongkos_angkut_part as o', 'o.id_vendor = pb.id_vendor')
		->where('pb.no_surat_jalan_ekspedisi', $this->input->get('no_surat_jalan_ekspedisi'))
        ->limit(1);
        $data['header'] = $this->db->get()->row_array();

        $this->load->view('iframe/md/h3/h3_md_laporan_penerimaan_barang', $data);
    }

}