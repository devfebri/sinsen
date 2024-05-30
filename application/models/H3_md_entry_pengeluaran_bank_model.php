<?php

class H3_md_entry_pengeluaran_bank_model extends Honda_Model{

    protected $table = 'tr_h3_md_entry_pengeluaran_bank';

    public function insert($data){
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');
		$data['status'] = 'Open';
		
		log_message('info', "Membuat Entry Pengeluaran Bank dengan nomor {$data['id_entry_pengeluaran_bank']}");
		
        parent::insert($data);
    }

    public function generate_nomor(){
		$th = date('Y');
		$bln = date('m');
		$th_bln = date('Y-m');
		$thbln = date('ym');

		$query = $this->db
        ->select('id_entry_pengeluaran_bank')
		->from($this->table)
		->where("LEFT(created_at, 7)='{$th_bln}'")
		->order_by('created_at', 'DESC')
		->limit(1)
		->get();

		if ($query->num_rows()>0) {
			$row = $query->row();
			$id_entry_pengeluaran_bank = substr($row->id_entry_pengeluaran_bank, 0, 5);
			$id_entry_pengeluaran_bank = sprintf("%'.05d",$id_entry_pengeluaran_bank+1);
			$id = "{$id_entry_pengeluaran_bank}/EPB/{$bln}/{$th}";
		}else{
			$id = "00001/EPB/{$bln}/{$th}";
		}

   		return strtoupper($id);
    }
}
