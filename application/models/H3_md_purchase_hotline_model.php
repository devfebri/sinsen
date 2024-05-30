<?php

class h3_md_purchase_hotline_model extends Honda_Model{

    protected $table = 'tr_h3_md_purchase_hotline';

    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
        $this->load->model('dealer_model', 'dealer');
    }

    public function insert($data){
		$data['created_by'] = $this->session->userdata('id_user');
		$data['tanggal_po'] = date('Y-m-d', time());
		$data['status'] = 'Open';
        parent::insert($data);
    }

    public function generateID(){
        $jenis_po = $this->input->post('jenis_po');
		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		$thbln     = date('ym');
		
		$query = $this->db->select('*')
						->from($this->table)
						->where("LEFT(tanggal_po, 7)='{$th_bln}'")
						->order_by('created_at', 'DESC')
						->limit(1)
						->get();

		if ($query->num_rows()>0) {
			$row        = $query->row();
			$id_purchase_hotline = substr($row->id_purchase_hotline, 0, 5);
			$id_purchase_hotline = sprintf("%'.05d",$id_purchase_hotline+1);
			$id   = "{$id_purchase_hotline}/HTL-E20/{$bln}/{$th}";
		}else{
			$id   = "00001/HTL-E20/{$bln}/{$th}";
		}

   		return strtoupper($id);
    }
}
