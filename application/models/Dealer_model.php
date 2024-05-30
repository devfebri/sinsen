<?php

class dealer_model extends Honda_Model {

    protected $table = 'ms_dealer';

    public function __construct(){
        $this->load->model('m_admin');
        $this->load->library('Mcarbon');
    }

    public function insert($data){
        $data['created_at'] = Mcarbon::now()->toDateTimeString();
        $data['created_by'] = $this->session->userdata('id_user');
        parent::insert($data);
    }

    public function getCurrentUserDealer(){
        $id_dealer = $this->m_admin->cari_dealer();
        return $this->db->select('*')
        ->where('id_dealer', $id_dealer)
        ->get($this->table)
        ->row();
    }

    public function dealer_lain(){
        return $this->db->select('*')
        ->where('id_dealer !=', $this->m_admin->cari_dealer())
        ->get($this->table)
        ->result();
    }

    public function dealer_terdekat(){
        return $this->db->select('d.*')
        ->where('dt.id_dealer', $this->m_admin->cari_dealer())
        ->from('ms_h3_dealer_terdekat as dt')
        ->join('ms_dealer as d', 'd.id_dealer = dt.id_dealer_terdekat')
        ->get()
        ->result();
    }

    public function get_dealer_stock_part($id_dealer, $id_part, $sql = false){
        $this->db
        ->select('sum(ds.stock) as stock')
        ->from('ms_h3_dealer_stock as ds');
        
        if($sql){
            $this->db->where("ds.id_dealer = '{$id_dealer}'");
            $this->db->where("ds.id_part = '{$id_part}'");
            return $this->db->get_compiled_select();
        }else{
            $this->db->where('ds.id_dealer', $id_part);
            $this->db->where('ds.id_part', $id_part);
            $data = $this->db->get()->row_array();

            return $data != null ? $data['stock'] : 0;
        }
    }

    public function exist_by_kode_dealer($kode_dealer){
        $dealer = $this->find($kode_dealer, 'kode_dealer_md');

        if($dealer == null){
            $this->form_validation->set_message('exist_by_kode_dealer_callable', 'Dealer tidak ditemukan');
        }

        return $dealer != null;
    }
}