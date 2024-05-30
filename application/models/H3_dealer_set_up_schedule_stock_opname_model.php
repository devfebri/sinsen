<?php

class h3_dealer_set_up_schedule_stock_opname_model extends Honda_Model{

    protected $table = 'ms_set_up_schedule_stock_opname';

    public function __construct(){
        parent::__construct();
        $this->load->model('dealer_model', 'dealer');
        $this->load->model('m_admin');
    }

    public function insert($data){
        $data['id_dealer'] = $this->m_admin->cari_dealer();
        $data['created_by'] = $this->session->userdata('id_user');

        parent::insert($data);
    }

    public function generateID()
    {
        $th        = date('Y');
        $tahun_short = date('y');
        $bln       = date('m');
        $th_bln    = date('Y-m');
        $thbln     = date('ym');
        $dealer    = $this->dealer->getCurrentUserDealer();
        $id_dealer = $this->m_admin->cari_dealer();

        $query  = $this->db
        ->from($this->table)
        ->limit(1)
        ->order_by('id', 'desc')
        ->where('id_dealer',$id_dealer)
        ->get();

        if ($query->num_rows() > 0) {
            $row        = $query->row();
            $id_schedule = substr($row->id_schedule, -5);
            $suffix = sprintf("%'.05d", $id_schedule+1);
            $new_kode   = "{$dealer->kode_dealer_md}/{$tahun_short}/{$bln}/SCHED/{$suffix}";
        } else {
            $new_kode   = "{$dealer->kode_dealer_md}/{$tahun_short}/{$bln}/SCHED/00001";
        }
        return strtoupper($new_kode);
    }

    public function getNotification()
    {
        $dealer = $this->m_admin->cari_dealer(); 
        $query = $this->db->query("SELECT DATE_FORMAT(date_opname, '%d-%m-%Y') as date_opname,  DATE_FORMAT(date_opname_end, '%d-%m-%Y') as date_opname_end
        FROM ms_set_up_schedule_stock_opname 
        WHERE DATE_FORMAT(NOW(),'%Y-%m-%d') =  date_opname - INTERVAL reminder_days DAY AND id_dealer = $dealer");
        return $query;
    }
}
