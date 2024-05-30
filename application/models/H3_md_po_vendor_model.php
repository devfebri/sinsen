<?php

class h3_md_po_vendor_model extends Honda_Model{

    protected $table = 'tr_h3_md_po_vendor';

    public function __construct(){
		parent::__construct();
    }

    public function insert($data){
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');
		$data['status'] = 'Open';

        parent::insert($data);
	}
	
	public function update($data, $condition){
		$data['updated_at'] = date('Y-m-d H:i:s', time());
		$data['updated_by'] = $this->session->userdata('id_user');

		if(isset($data['status']) && $data['status'] == 'Closed'){
			$this->create_ap($condition['id_po_vendor']);
		}

        parent::update($data, $condition);
	}
	
	public function create_ap($id_po_vendor){
		$this->load->model('H3_md_ap_part_model', 'ap_part');

		$ap_part = $this->db
		->select('pov.id_po_vendor as referensi')
		->select('pov.tanggal as tanggal_transaksi')
		->select('DATE_ADD(pov.tanggal, INTERVAL 30 DAY) as tanggal_jatuh_tempo')
		->select('"purchase_order_vendor" as jenis_transaksi')
		->select('pov.id_vendor as nama_vendor')
		->select('pov.total as total_bayar')
		->from("{$this->table} as pov")
		->where('pov.id_po_vendor', $id_po_vendor)
		->get()->row_array();

		$this->ap_part->insert($ap_part);
	}

    public function generateID(){
		$th = date('Y');
		$bln = date('m');
		$th_bln = date('Y-m');
		$thbln = date('ym');

		$query = $this->db->select('*')
		->from($this->table)
		->where("LEFT(tanggal, 7)='{$th_bln}'")
		->order_by('created_at', 'DESC')
		->limit(1)
		->get();

		if ($query->num_rows()>0) {
			$row = $query->row();
			$id_po_vendor = substr($row->id_po_vendor, 0, 5);
			$id_po_vendor = sprintf("%'.05d",$id_po_vendor+1);
			$id = "{$id_po_vendor}/POV/{$bln}/{$th}";
		}else{
			$id = "00001/POV/{$bln}/{$th}";
		}

   		return strtoupper($id);
    }
}