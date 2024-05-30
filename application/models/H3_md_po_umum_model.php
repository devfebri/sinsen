<?php

class H3_md_po_umum_model extends Honda_Model{

    protected $table = 'tr_h3_md_po_umum';

    public function insert($data){
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');
		$data['status'] = 'Open';
		
		log_message('info', "Membuat PO Umum dengan nomor {$data['id_purchase_order']}");
		
        parent::insert($data);
    }

    public function generate_nomor(){
		$th = date('Y');
		$bln = date('m');
		$th_bln = date('Y-m');
		$thbln = date('ym');

		$query = $this->db
        ->select('id_purchase_order')
		->from($this->table)
		->where("LEFT(created_at, 7)='{$th_bln}'")
		->order_by('created_at', 'DESC')
		->limit(1)
		->get();

		if ($query->num_rows()>0) {
			$row = $query->row();
			$id_purchase_order = substr($row->id_purchase_order, 0, 5);
			$id_purchase_order = sprintf("%'.05d",$id_purchase_order+1);
			$id = "{$id_purchase_order}/PU/{$bln}/{$th}";
		}else{
			$id = "00001/PU/{$bln}/{$th}";
		}

   		return strtoupper($id);
    }
}
