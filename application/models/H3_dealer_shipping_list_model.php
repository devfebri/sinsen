<?php

class h3_dealer_shipping_list_model extends Honda_Model {

	protected $table = 'tr_h3_dealer_shipping_list';
	
	public function __construct(){
		parent::__construct();

		$this->load->model('m_admin');
	}

    public function generateShippingListNumber($kode = null)
	{
		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		$thbln     = date('ym');
		$id_dealer = $this->m_admin->cari_dealer();
		
		if($kode == null){
			$dealer    = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
			$kode_dealer = $dealer->kode_dealer_md;
		}else{
			$kode_dealer = $kode;
		}

		$get_data  = $this->db->from($this->table)->order_by('created_at', 'DESC')->limit(1)->get();
	   		if ($get_data->num_rows()>0) {
				$row        = $get_data->row();
				$id_shipping_list = substr($row->id_shipping_list, -4);
				$new_kode   = 'SHP/'. $kode_dealer .'/'.$thbln.'/'.sprintf("%'.04d",$id_shipping_list+1);
	   		}else{
				$new_kode   = 'SHP/'. $kode_dealer .'/'.$thbln.'/0001';
	   		}
   		return strtoupper($new_kode);
	}
}
?>