<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_dealer_prospek_datatables extends CI_Model
{
    var $table = "tr_prospek";
    var $column_order = array('','a.id_prospek','a.id_customer','a.nama_konsumen','b.nama_lengkap','a.no_hp','a.status_prospek'); //field yang ada di table user
    var $column_search = array('a.id_prospek','a.id_customer','a.nama_konsumen','b.nama_lengkap','a.no_hp','a.status_prospek'); //field yang diizin untuk pencarian 
    var $order = array('a.created_at' => 'desc'); 
   
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query($id_dealer)
    {   

        $spk_status = array('approved','booking','rejected');
        $this->db->select('a.id_prospek, a.id_customer ,a.nama_konsumen , b.nama_lengkap , a.no_hp , a.status_prospek ');
        $this->db->from('tr_prospek a');
        $this->db->join('ms_karyawan_dealer b', 'a.id_karyawan_dealer = b.id_karyawan_dealer');
        $this->db->where("a.id_dealer = '$id_dealer' AND id_customer not in (SELECT id_customer FROM tr_spk 
        JOIN tr_sales_order ON tr_sales_order.no_spk=tr_spk.no_spk
        WHERE tr_sales_order.status_delivery IS NOT NULL AND id_customer=a.id_customer
        )");
        
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


    function get_datatables($id_dealer)
    {
        $this->_get_datatables_query($id_dealer);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($id_dealer)
    {
        $this->_get_datatables_query($id_dealer);
        $query = $this->db->get();
        return $query->num_rows();

    }

    public function count_all($id_dealer)
    {
     
        $num = array($id_dealer);
        $spk_status = array('approved','booking','rejected');
        $this->db->select('a.id_prospek, a.id_customer ,a.nama_konsumen , b.nama_lengkap , a.no_hp , a.status_prospek ');
        $this->db->from('tr_prospek a');
        $this->db->join('ms_karyawan_dealer b', 'a.id_karyawan_dealer = b.id_karyawan_dealer');
        $this->db->where("a.id_dealer = '$id_dealer' AND id_customer not in (SELECT id_customer FROM tr_spk 
        JOIN tr_sales_order ON tr_sales_order.no_spk=tr_spk.no_spk
        WHERE tr_sales_order.status_delivery IS NOT NULL AND id_customer=a.id_customer
        )");
                
        return $this->db->count_all_results();

    }

}

