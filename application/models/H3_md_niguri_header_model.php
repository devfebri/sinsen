<?php

class H3_md_niguri_header_model extends Honda_Model{

    protected $table = 'tr_h3_md_niguri_header';

    public function __construct(){
        parent::__construct();
        $this->load->library('Mcarbon');
    }

    public function insert($data){
        $data['status'] = 'Open';
        $data['created_at'] = date('Y-m-d H:i:s', time());
        $data['created_by'] = $this->session->userdata('id_user');
        
        parent::insert($data);
    }

    public function niguri_exists($now, $type_niguri){
        return $this->db
		->select('nh.id')
		->from('tr_h3_md_niguri_header as nh')
		->where("LEFT(nh.tanggal_generate, 7) = '{$now->format('Y-m')}'", null, false)
		->where('nh.type_niguri', $type_niguri)
		->get()->row_array();
    }

    public function create_header($now, $type_niguri){
        $data = [
			'type_niguri' => $type_niguri,
			'tanggal_generate' => $now->toDateString(),
			'bulan' => $now->format('m'),
			'tahun' => $now->format('Y'),
		];

		$this->insert($data);

		return $this->db->insert_id();
    }

    public function qty_suggest($id_part, $jenis_po, $tanggal_order = null){
        if($tanggal_order != null){
            $bulan = Mcarbon::parse($tanggal_order)->format('m');
            $tahun = Mcarbon::parse($tanggal_order)->format('Y');
        }else{
            $bulan = Mcarbon::now()->format('m');
            $tahun = Mcarbon::now()->format('Y');
        }

        $data = $this->db
        ->select('n.id_part')
        ->select('ROUND(n.qty_suggest) as qty_suggest')
        ->select('ROUND(n.fix_order_n) as fix_order_n', false)
        ->select('ROUND(n.fix_order_n_1) as fix_order_n_1', false)
        ->select('ROUND(n.fix_order_n_2) as fix_order_n_2', false)
        ->select('ROUND(n.fix_order_n_3) as fix_order_n_3', false)
        ->select('ROUND(n.fix_order_n_4) as fix_order_n_4', false)
        ->select('ROUND(n.fix_order_n_5) as fix_order_n_5', false)
        ->from('tr_h3_md_niguri as n')
        ->join('tr_h3_md_niguri_header as nh', 'nh.id = id_niguri_header')
        ->where('n.id_part', $id_part)
        ->where('nh.bulan', $bulan)
        ->where('nh.tahun', $tahun)
        ->where('nh.type_niguri', $jenis_po)
        ->limit(1)
        ->get()->row_array();

        return $data;
    }
}
