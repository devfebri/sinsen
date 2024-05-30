<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_users extends CI_Model
{
    var $table = "tr_prospek";
    var $column_order = array('','ms_user.username','ms_user.username_sc','','','ms_user_group.user_group',''); //field yang ada di table user
    var $column_search = array('ms_user.username','ms_user.username_sc','ms_user_group.user_group'); //field yang diizin untuk pencarian 
    var $order = array('ms_user.id_user' => 'desc'); 
   
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {   

        $this->db->select("ms_user.id_user, ms_user.username , ms_user.username_sc , ms_user.jenis_user , ms_user.id_karyawan_dealer,ms_user_group.user_group,(case when ms_user.jenis_user='Dealer' then (select nama_lengkap from ms_karyawan_dealer where id_karyawan_dealer=ms_user.id_karyawan_dealer) 
        when ms_user.jenis_user ='Main Dealer' then (select nama_lengkap from ms_karyawan where id_karyawan=ms_user.id_karyawan_dealer) 
        else '-' end 
        ) as nama_lengkap, ms_user.status");
        $this->db->from('ms_user');
        $this->db->join('ms_user_group', 'ms_user.id_user_group = ms_user_group.id_user_group','left');
        $this->db->where("ms_user.jenis_user != 'Super Admin' AND ms_user.jenis_user != 'Admin'");
        
        $i = 0;

        foreach ($this->column_search as $item) {
            if($_POST['search']['value']) 
            {
                if($i===0)
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
        $this->db->select('ms_user.username , ms_user.username_sc , ms_user.jenis_user , ms_user.id_karyawan_dealer,ms_user_group.user_group');
        $this->db->from('ms_user');
        $this->db->join('ms_user_group', 'ms_user.id_user_group = ms_user_group.id_user_group','left');
        $this->db->where("ms_user.jenis_user != 'Super Admin' AND ms_user.jenis_user != 'Admin'");        
        return $this->db->count_all_results();
    }

}

