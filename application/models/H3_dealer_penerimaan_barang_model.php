<?php

class h3_dealer_penerimaan_barang_model extends Honda_Model {

	protected $table = 'tr_h3_dealer_penerimaan_barang';
	
	public function __construct(){
		parent::__construct();

		$this->load->model('m_admin');
	}

	public function insert($data){
		$data['created_by'] = $this->session->userdata('id_user');
		$data['id_dealer'] = $this->m_admin->cari_dealer();

		parent::insert($data);
	}

    public function generateID()
	{
		$po_type = $this->input->post('po_type');
		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		$thbln     = date('ym');
		$id_dealer = $this->m_admin->cari_dealer();
		$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
		
		$query  = $this->db
		->from($this->table)
		->where('id_dealer', $this->m_admin->cari_dealer())
		->where("LEFT(tanggal,7)='{$th_bln}'")
		->order_by('id_penerimaan_barang', 'desc')
		->limit(1)
		->get();

	   		if ($query->num_rows() > 0) {
				$row        = $query->row();
				$id_penerimaan_barang = substr($row->id_penerimaan_barang, -4);
				$new_kode   = 'PB/' . $dealer->kode_dealer_md.'/'.$thbln.'/'.sprintf("%'.04d",$id_penerimaan_barang+1);
	   		}else{
				$new_kode   = 'PB/' . $dealer->kode_dealer_md.'/'.$thbln.'/0001';
	   		}
   		return strtoupper($new_kode);
	}
}
?>