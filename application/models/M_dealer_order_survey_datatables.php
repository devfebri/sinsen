<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_dealer_order_survey_datatables extends CI_Model
{
    // no, no order survey, no spk, nama konsumen, leasing, alamat, tipe, warna, no.ktp, action
    // 'ms_finance_company.id_finance_company','tr_spk.id_tipe_kendaraan',,,'ms_warna.id_warna',
    var $table          = "tr_order_survey";
    var $column_order   = array('','tr_order_survey.no_order_survey','tr_spk.no_spk','tr_spk.nama_konsumen','ms_finance_company.finance_company','tr_spk.alamat','ms_tipe_kendaraan.tipe_ahm','ms_warna.warna','tr_spk.no_ktp','tr_order_survey.no_spk'); //field yang ada di table user
    var $column_search  = array('tr_order_survey.no_order_survey','tr_spk.no_spk','tr_spk.alamat','ms_finance_company.finance_company','ms_finance_company.id_finance_company','tr_spk.id_tipe_kendaraan','ms_warna.warna','ms_tipe_kendaraan.tipe_ahm','ms_warna.id_warna','tr_spk.no_ktp','tr_spk.nama_konsumen'); //field yang diizin untuk pencarian 
    var $order          = array('tr_order_survey.no_order_survey' => 'desc'); 
   
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query($id_dealer)
    {   

        // $this->db->select('tr_order_survey.no_order_survey, tr_spk.no_spk , tr_spk.nama_konsumen, ms_finance_company.finance_company, tr_spk.alamat, ms_tipe_kendaraan.deskripsi_ahm, ms_warna.warna, tr_spk.no_ktp','tr_spk.id_customer','tr_order_survey.id_customer','tr_spk.id_tipe_kendaraan','tr_spk.id_warna','tr_spk.id_finance_company','ms_warna.id_warna');
        // $this->db->select('*');
        $this->db->select('tr_spk.no_spk,tr_spk.nama_konsumen,tr_spk.no_ktp,tr_spk.alamat,tr_spk.status_spk,tr_spk.id_customer,tr_spk.id_tipe_kendaraan,tr_spk.id_dealer,tr_spk.id_warna,tr_order_survey.no_order_survey,tr_spk.id_finance_company,ms_finance_company.finance_company, ms_warna.warna,ms_tipe_kendaraan.tipe_ahm');
        $this->db->from('tr_order_survey');
        // $this->db->join('tr_spk', 'tr_order_survey.no_spk = tr_spk.no_spk');
        $this->db->join("tr_spk", "tr_order_survey.no_spk = tr_spk.no_spk and tr_spk.id_dealer = '$id_dealer'");
        $this->db->join('ms_finance_company', 'tr_spk.id_finance_company = ms_finance_company.id_finance_company');
        $this->db->join('ms_warna', 'tr_spk.id_warna = ms_warna.id_warna');
        $this->db->join('ms_tipe_kendaraan', 'tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan');
        $this->db->where('tr_order_survey.id_dealer', $id_dealer);
        $this->db->where("tr_spk.status_spk not in ('close','canceled','rejected','paid')");

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
        $this->db->select('tr_order_survey.no_order_survey','tr_order_survey.id_finance_company','tr_spk.no_spk','tr_spk.nama_konsumen','tr_spk.id_finance_company','tr_spk.id_customer','ms_finance_company.finance_company','tr_order_survey.no_spk');
        $this->db->from('tr_order_survey');
        $this->db->join("tr_spk", "tr_order_survey.no_spk = tr_spk.no_spk and tr_spk.id_dealer = '$id_dealer'");
        $this->db->join('ms_finance_company', 'tr_spk.id_finance_company = ms_finance_company.id_finance_company');
        $this->db->join('ms_warna', 'tr_spk.id_warna = ms_warna.id_warna');
        $this->db->join('ms_tipe_kendaraan', 'tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan');
        $this->db->where('tr_order_survey.id_dealer', $id_dealer);
        $this->db->where("tr_spk.status_spk not in ('close','canceled','rejected','paid')");
        return $this->db->count_all_results();
    }

}

