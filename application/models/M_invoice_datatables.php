<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_invoice_datatables extends CI_Model
{
    var $table = "tr_invoice";
    var $column_order = array('','tr_invoice.no_faktur','tr_invoice.tgl_faktur','tr_invoice.tgl_pokok','tr_invoice.tgl_ppn','tr_invoice.tgl_pph','tr_invoice.no_sl','tr_invoice.no_sipb','ms_tipe_kendaraan.tipe_ahm','ms_warna.warna','tr_invoice.qty','tr_invoice.pph','tr_invoice.disc_quo','tr_invoice.disc_type','tr_invoice.disc_other'); 
    var $column_search = array('tr_invoice.no_faktur','tr_invoice.tgl_faktur','tr_invoice.tgl_pokok','tr_invoice.tgl_ppn','tr_invoice.tgl_pph','tr_invoice.no_sl','tr_invoice.no_sipb','ms_tipe_kendaraan.tipe_ahm','ms_warna.warna','tr_invoice.qty','tr_invoice.pph','tr_invoice.disc_quo','tr_invoice.disc_type','tr_invoice.disc_other'); 
    var $order = array('tr_invoice.no_faktur' => 'desc'); // default order 
   
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {   
        // $tgl_faktur = array('%2022');
        $this->db->select('tr_invoice.no_faktur,tr_invoice.tgl_faktur,tr_invoice.tgl_pokok,tr_invoice.tgl_ppn,tr_invoice.tgl_pph,tr_invoice.no_sl,tr_invoice.no_sipb,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_invoice.qty,tr_invoice.pph,tr_invoice.disc_quo,tr_invoice.disc_type,tr_invoice.disc_other');
        $this->db->from('tr_invoice');
        $this->db->join('ms_tipe_kendaraan','tr_invoice.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan','left');
        $this->db->join('ms_warna', 'tr_invoice.id_warna = ms_warna.id_warna','left');
        // $this->db->where_in('tr_invoice.tgl_faktur');
        // $this->db->like('tr_invoice.tgl_faktur',$tanggal_faktur);
        $this->db->order_by('tr_invoice.no_faktur DESC');
        
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
        //    $tgl_faktur = array('2022');
           $this->db->select('tr_invoice.no_faktur,tr_invoice.tgl_faktur,tr_invoice.tgl_pokok,tr_invoice.tgl_ppn,tr_invoice.tgl_pph,tr_invoice.no_sl,tr_invoice.no_sipb,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_invoice.qty,tr_invoice.pph,tr_invoice.disc_quo,tr_invoice.disc_type,tr_invoice.disc_other');
           $this->db->from('tr_invoice');
           $this->db->join('ms_tipe_kendaraan', 'tr_invoice.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan','left');
           $this->db->join('ms_warna', 'tr_invoice.id_warna = ms_warna.id_warna','left');
        //    $this->db->where_in('tr_invoice.tgl_faktur');
        //    $this->db->like('tr_invoice.tgl_faktur','2022');
           $this->db->order_by('tr_invoice.no_faktur DESC');
        return $this->db->count_all_results();

    }

}

