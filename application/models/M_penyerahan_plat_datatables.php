<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_penyerahan_plat_datatables extends CI_Model
{
    var $table = "tr_penyerahan_plat";
    var $column_order = array('','tr_penyerahan_plat.no_serah_plat ','tr_penyerahan_plat.tgl_serah_terima','tr_penyerahan_plat.status_plat','ms_dealer.nama_dealer','ms_dealer.alamat'); //field yang ada di table user
    var $column_search = array('tr_penyerahan_plat.no_serah_plat ','tr_penyerahan_plat.tgl_serah_terima','tr_penyerahan_plat.status_plat','ms_dealer.nama_dealer','ms_dealer.alamat'); //field yang diizin untuk pencarian 
    var $order = array('tr_penyerahan_plat.created_at' => 'desc'); 
   
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {   

         $this->db->select('tr_penyerahan_plat.no_serah_plat,tr_penyerahan_plat.tgl_serah_terima,tr_penyerahan_plat.status_plat,ms_dealer.nama_dealer,ms_dealer.alamat');
        $this->db->from('tr_penyerahan_plat');
        $this->db->join('ms_dealer', 'tr_penyerahan_plat.id_dealer = ms_dealer.id_dealer');
        $this->db->order_by('tr_penyerahan_plat.no_serah_plat DESC');
        
        
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
        $this->db->select('tr_penyerahan_plat.no_serah_plat,tr_penyerahan_plat.tgl_serah_terima,tr_penyerahan_plat.status_plat,ms_dealer.nama_dealer,ms_dealer.alamat');
        $this->db->from('tr_penyerahan_plat');
        $this->db->join('ms_dealer', 'tr_penyerahan_plat.id_dealer = ms_dealer.id_dealer');
        $this->db->order_by('tr_penyerahan_plat.no_serah_plat DESC');
        return $this->db->count_all_results();

    }

}

