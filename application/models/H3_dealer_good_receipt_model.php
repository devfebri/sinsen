<?php

class h3_dealer_good_receipt_model extends Honda_Model {

	protected $table = 'tr_h3_dealer_good_receipt';
	
	public function __construct(){
		parent::__construct();

		$this->load->model('m_admin');
	}

	public function insert($data){
		$data['created_at'] = date('Y-m-d H:i:s');
		$data['created_by'] = $this->session->userdata('id_user');

		parent::insert($data);
	}

    public function generateGoodReceipt(){
		$th = date('Y');
		$bln = date('m');
		$th_bln = date('Y-m');
		$thbln = date('ym');
		$id_dealer = $this->m_admin->cari_dealer();
		$dealer = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();

		$data = $this->db
		->select('id_good_receipt')
		->from($this->table)
		->where('id_dealer', $id_dealer)
		->where("LEFT(created_at, 7) = '$th_bln'", null, false)
		->order_by('created_at', 'desc')
		->order_by('id_good_receipt', 'desc')
		->limit(1)
		->get();

		if ($data->num_rows()>0) {
			$row = $data->row();
			$id_good_receipt = substr($row->id_good_receipt, -4);
			$id_good_receipt = sprintf("%'.04d",$id_good_receipt + 1);
			$new_kode   = 'GR/' . $dealer->kode_dealer_md . '/' . $thbln . '/' . $id_good_receipt; 
		}else{
			$new_kode   = 'GR/' . $dealer->kode_dealer_md . '/' . $thbln . '/0001';
		}

   		return strtoupper($new_kode);
	}
}
?>