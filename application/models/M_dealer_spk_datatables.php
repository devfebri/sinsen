<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_dealer_spk_datatables extends CI_Model
{
    var $table = "tr_spk";
    var $column_order = array('','tr_spk.no_spk','tr_spk.nama_konsumen','tr_prospek.id_prospek','tr_spk.alamat','tr_spk.status_spk','tr_spk.id_customer','tr_spk.no_spk_int','tr_spk.id_tipe_kendaraan','tr_spk.id_tipe_kendaraan','tr_prospek.nama_konsumen','ms_warna.warna','ms_tipe_kendaraan.tipe_ahm','ms_warna.id_warna'); //field yang ada di table user
    var $column_search = array('tr_spk.no_spk','tr_spk.nama_konsumen','tr_prospek.id_prospek','tr_spk.alamat','tr_spk.status_spk','tr_spk.id_customer','tr_spk.no_spk_int','tr_spk.id_tipe_kendaraan','tr_spk.id_tipe_kendaraan','tr_prospek.nama_konsumen','ms_warna.warna','ms_tipe_kendaraan.tipe_ahm','ms_warna.id_warna'); //field yang diizin untuk pencarian 
    var $order = array('tr_spk.created_at' => 'desc'); 
   
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query($id_dealer)
    {   
        $spk_status = array('approved','booking','rejected');
        $this->db->select('tr_spk.no_spk,tr_spk.nama_konsumen,tr_spk.no_ktp,tr_spk.alamat,tr_spk.status_spk,tr_spk.id_customer,tr_spk.no_spk_int,tr_spk.id_tipe_kendaraan,tr_prospek.nama_konsumen,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm,ms_warna.id_warna,tr_spk.id_dealer,tr_prospek.id_prospek');
        $this->db->from('tr_spk');
        $this->db->join('tr_prospek', 'tr_spk.id_customer = tr_prospek.id_customer');
        $this->db->join('ms_warna', 'tr_spk.id_warna = ms_warna.id_warna');
        $this->db->join('ms_tipe_kendaraan', 'tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan');
        $this->db->where('tr_prospek.id_dealer', $id_dealer);
        $this->db->where("tr_spk.expired is null and tr_spk.no_spk not in (select no_spk FROM tr_sales_order WHERE no_spk=tr_spk.no_spk) ");
        $this->db->where_in('tr_spk.status_spk' ,$spk_status);

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
        $spk_status = array('approved','booking','rejected');
        $this->db->select('tr_spk.no_spk,tr_spk.nama_konsumen,tr_spk.no_ktp,tr_spk.alamat,tr_spk.status_spk,tr_spk.id_dealer');
        $this->db->from('tr_spk');
        $this->db->join('tr_prospek', 'tr_spk.id_customer = tr_prospek.id_customer');
        $this->db->where('tr_prospek.id_dealer', $id_dealer);
        $this->db->where("tr_spk.expired is null and tr_spk.no_spk not in (select no_spk FROM tr_sales_order WHERE no_spk=tr_spk.no_spk) ");
        $this->db->where_in('tr_spk.status_spk' ,$spk_status);
        return $this->db->count_all_results();

    }

}

