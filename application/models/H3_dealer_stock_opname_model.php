<?php

class h3_dealer_stock_opname_model extends Honda_Model
{
    protected $table = 'tr_h3_dealer_stock_opname';
    var $column_order = array(null,'id_schedule','id_stock_opname','jenis_schedule','date_opname','date_opname_end',null); //field yang ada di table user
    var $column_search = array('id_schedule','jenis_schedule','date_opname','date_opname_end'); //field yang diizin untuk pencarian 
    var $order = array('created_at' => 'desc'); // default order 

    public function __construct(){
        parent::__construct();

        $this->load->model('dealer_model', 'dealer');
    }
 
    public function generateID()
    {
        $th        = date('Y');
        $bln       = date('m');
        $th_bln    = date('Y-m');
        $thbln     = date('ym');
        $dealer    = $this->dealer->getCurrentUserDealer();
        $id_dealer = $this->m_admin->cari_dealer();
        
        $get_data  = $this->db->query("SELECT * FROM $this->table WHERE id_dealer='$id_dealer' ORDER BY created_at DESC LIMIT 0,1");
            
        if ($get_data->num_rows()>0) {
            $row        = $get_data->row();
            $id_stock_opname = substr($row->id_stock_opname, -3);
            $new_kode   = $dealer->kode_dealer_md.'/OPNM-'.sprintf("%'.03d", $id_stock_opname+1);
        } else {
            $new_kode   = $dealer->kode_dealer_md.'/OPNM-'.'001';
        }
        return strtoupper($new_kode);
    }

    private function _get_datatables_query()
    {
        $this->db->select('sso.id_schedule,sso.date_opname,sso.date_opname_end,sso.jenis_schedule,sso.created_at');
        $this->db->from('ms_set_up_schedule_stock_opname as sso');
        // $this->db->join('tr_h3_dealer_stock_opname as so','sso.id_schedule=so.id_schedule','left');
        $this->db->where('sso.id_dealer',$this->m_admin->cari_dealer());
        $i = 0;
        
        foreach ($this->column_search as $item) // looping awal
        {
            if($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {
                if($i===0) // looping awal
                {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($this->column_search) - 1 == $i) 
                    $this->db->group_end(); 
            }
            $i++;
        }

        if(isset($_POST['order'])) 
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->_get_datatables_query();
        return $this->db->count_all_results();
    }

}
