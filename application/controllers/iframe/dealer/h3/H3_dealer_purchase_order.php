<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_dealer_purchase_order extends CI_Controller {

    public function index(){
        $data = [];
		$data['purchase_order'] = $this->db
		->select('po.*')
		->select('d.kode_dealer_md')
		->select('d.nama_dealer')
		->select('date_format(po.batas_waktu, "%d/%m/%Y") as batas_waktu')
		->select('date_format(po.tanggal_order, "%b") as periode')
		->select('date_format(po.tanggal_order, "%d-%m-%Y") as tanggal_order')
		->select('ifnull(po.tanggal_selesai, "-") as tanggal_selesai')
		->select('dealer_terdekat.nama_dealer as nama_dealer_terdekat')
		->from('tr_h3_dealer_purchase_order as po')
		->join('ms_dealer as dealer_terdekat', 'po.order_to = dealer_terdekat.id_dealer', 'left')
		->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
		->where('po.po_id', $this->input->get('po_id'))
		->get()->row_array();

		$data['part_group'] = $this->db->distinct()->select('kelompok_part')->from('ms_part')->get()->result();
		$data['dealer'] = $this->db->from('ms_dealer')->where('id_dealer', $data['purchase_order']['id_dealer'])->get()->row();

		$data['parts'] = $this->db
		->select('pop.*')
		->select('p.nama_part')
		->from('tr_h3_dealer_purchase_order_parts as pop')
		->join('ms_part as p', 'p.id_part = pop.id_part')
		->where('pop.po_id', $this->input->get('po_id'))
		->get()->result();

        $this->load->view('iframe/dealer/h3/h3_dealer_purchase_order', $data);
    }

}