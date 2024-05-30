<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_rekap_tagihan_datatables extends CI_Model
{

    var $table = "tr_rekap_tagihan";
    var $column_order = array('','tr_rekap_tagihan.id_rekap_tagihan','tr_rekap_tagihan.tgl_rekap','ms_vendor.vendor_name','tr_rekap_tagihan.tgl_awal'); //field yang ada di table user
    var $column_search = array('tr_rekap_tagihan.id_rekap_tagihan','tr_rekap_tagihan.tgl_rekap',' ms_vendor.vendor_name','tr_rekap_tagihan.tgl_awal'); //field yang diizin untuk pencarian 
    var $order = array('tr_rekap_tagihan.tgl_awal' => 'desc'); 
   
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    private function _get_datatables_query()
    {   
        $this->db->select('
        sum(tr_invoice_penerimaan.total) as total,
        tr_rekap_tagihan.id_rekap_tagihan,
        tr_rekap_tagihan.tgl_rekap,
        ms_vendor.vendor_name,
        tr_rekap_tagihan.tgl_awal,
        tr_rekap_tagihan.tgl_akhir')            
        ->from('tr_rekap_tagihan')
        ->join('ms_vendor ', 'tr_rekap_tagihan.id_vendor = ms_vendor.id_vendor')
        ->join('tr_rekap_tagihan_detail ', 'tr_rekap_tagihan.id_rekap_tagihan = tr_rekap_tagihan_detail.id_rekap_tagihan')
        ->join('tr_penerimaan_unit ', 'tr_penerimaan_unit.id_penerimaan_unit = tr_rekap_tagihan_detail.id_penerimaan_unit')
        ->join('tr_invoice_penerimaan ', 'tr_invoice_penerimaan.no_penerimaan = tr_penerimaan_unit.id_penerimaan_unit')
        ->group_by("tr_rekap_tagihan.id_rekap_tagihan");

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
        $this->_get_datatables_query();
        return $this->db->count_all_results();
    }

}

