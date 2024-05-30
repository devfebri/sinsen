<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_claim_md_internal_payment_datatables extends CI_Model
{
    var $table = "tr_claim_dealer";
    var $column_order = array('','tr_claim_dealer.id_program_md','tr_sales_program.judul_kegiatan','tr_sales_program.periode_awal','tr_sales_program.periode_akhir','tr_sales_order.id_dealer'); //field yang ada di table user
    var $column_search = array('tr_claim_dealer.id_program_md','tr_sales_program.judul_kegiatan','tr_sales_program.periode_awal','tr_sales_program.periode_akhir', 'tr_sales_order.id_dealer'); //field yang diizin untuk pencarian 
    var $order = array('tr_claim_dealer.id_program_md' => 'desc'); 
   
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    private function _get_datatables_query()
    {   

        $get_year = date('Y');
        $string_date = $get_year."-m-30";
        $get_today =  date($string_date);

        $get_past_month = date('Y-m-01', strtotime(' - 10 months'));
        $this->db->select('tr_claim_dealer.id_program_md, tr_sales_program.judul_kegiatan , tr_sales_program.periode_awal, tr_sales_program.periode_akhir, tr_sales_order.id_dealer, 
        sum(Case When tr_claim_dealer.Status = "approved"  Then 1 Else 0 End) AS status_approved,
        sum(Case When tr_claim_dealer.Status = "rejected" Then 1 Else 0 End) AS status_reject,
        sum(case when tr_claim_dealer.status=  "ajukan" or tr_claim_dealer.status ="" then 1 else 0 end) as jumlahnya_pendding,
        sum((case when tr_spk .jenis_beli = "Cash"  and tr_claim_dealer.Status = "approved"  then (tr_sales_program_tipe.ahm_cash)                                             WHEN tr_spk.jenis_beli = "Kredit" and tr_claim_dealer.Status = "approved"  THEN   (tr_sales_program_tipe.ahm_kredit) end)) as kontribusi_ahm , 
        sum((case when tr_spk .jenis_beli = "Cash"  and tr_claim_dealer.Status = "approved"  then (tr_sales_program_tipe.md_cash + tr_sales_program_tipe.add_md_cash )         WHEN tr_spk.jenis_beli = "Kredit" and tr_claim_dealer.Status = "approved"  THEN   (tr_sales_program_tipe.md_kredit + tr_sales_program_tipe.add_md_kredit) end)) as kontribusi_md, 
        sum((case when tr_spk .jenis_beli = "Cash"  and tr_claim_dealer.Status = "approved"  then (tr_sales_program_tipe.dealer_cash +tr_sales_program_tipe.add_dealer_cash)   WHEN tr_spk.jenis_beli = "Kredit" and tr_claim_dealer.Status = "approved"  THEN   (tr_sales_program_tipe.dealer_kredit + tr_sales_program_tipe.add_dealer_kredit) end)) as kontribusi_dealer')        
        ->from('tr_claim_dealer ')
        ->join('tr_claim_sales_program_detail ', 'tr_claim_sales_program_detail.id_claim_dealer = tr_claim_dealer.id_claim','left')
        ->join('ms_dealer ', 'tr_claim_dealer.id_dealer = ms_dealer.id_dealer')
        ->join('tr_sales_program', 'tr_sales_program.id_program_md = tr_claim_dealer.id_program_md')
        ->join('tr_sales_order', 'tr_sales_order.id_sales_order =tr_claim_dealer.id_sales_order')
        ->join('tr_spk ', 'tr_spk.no_spk = tr_sales_order.no_spk')
        ->join('tr_sales_program_tipe', 'tr_sales_program_tipe.id_program_md = tr_claim_dealer.id_program_md and tr_sales_program_tipe.id_tipe_kendaraan = tr_spk.id_tipe_kendaraan')
        ->join('ms_group_dealer_detail ', 'ms_dealer.id_dealer = ms_group_dealer_detail.id_dealer')
        ->join('ms_group_dealer ', 'ms_group_dealer.id_group_dealer = ms_group_dealer_detail.id_group_dealer')
        ->join('tr_claim_sales_program ', 'tr_claim_sales_program.id_program_md = tr_claim_dealer.id_program_md and  tr_claim_sales_program.id_claim_sp = tr_claim_sales_program_detail.id_claim_sp ','left')
        ->where("tr_sales_program.periode_awal  BETWEEN  '$get_past_month'  AND '$get_today'")
        ->group_by('tr_claim_dealer.id_program_md','ms_dealer.id_dealer');

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




    private function _get_datatables_query_history()
    {   
        $this->db->select('tr_claim_dealer.id_program_md, tr_sales_program.judul_kegiatan , tr_sales_program.periode_awal, tr_sales_program.periode_akhir, tr_sales_order.id_dealer, 
        sum(Case When tr_claim_dealer.Status = "approved"  Then 1 Else 0 End) AS status_approved,
        sum(Case When tr_claim_dealer.Status = "rejected" Then 1 Else 0 End) AS status_reject,
        sum(case when tr_claim_dealer.status=  "ajukan" or tr_claim_dealer.status ="" then 1 else 0 end) as jumlahnya_pendding,
        sum((case when tr_spk .jenis_beli = "Cash"  and tr_claim_dealer.Status = "approved"  then (tr_sales_program_tipe.ahm_cash)                                             WHEN tr_spk.jenis_beli = "Kredit" and tr_claim_dealer.Status = "approved"  THEN   (tr_sales_program_tipe.ahm_kredit) end)) as kontribusi_ahm , 
        sum((case when tr_spk .jenis_beli = "Cash"  and tr_claim_dealer.Status = "approved"  then (tr_sales_program_tipe.md_cash + tr_sales_program_tipe.add_md_cash )         WHEN tr_spk.jenis_beli = "Kredit" and tr_claim_dealer.Status = "approved"  THEN   (tr_sales_program_tipe.md_kredit + tr_sales_program_tipe.add_md_kredit) end)) as kontribusi_md, 
        sum((case when tr_spk .jenis_beli = "Cash"  and tr_claim_dealer.Status = "approved"  then (tr_sales_program_tipe.dealer_cash +tr_sales_program_tipe.add_dealer_cash)   WHEN tr_spk.jenis_beli = "Kredit" and tr_claim_dealer.Status = "approved"  THEN   (tr_sales_program_tipe.dealer_kredit + tr_sales_program_tipe.add_dealer_kredit) end)) as kontribusi_dealer')        
        ->from('tr_claim_dealer ')
        ->join('tr_claim_sales_program_detail ', 'tr_claim_sales_program_detail.id_claim_dealer = tr_claim_dealer.id_claim','left')
        ->join('ms_dealer ', 'tr_claim_dealer.id_dealer = ms_dealer.id_dealer')
        ->join('tr_sales_program', 'tr_sales_program.id_program_md = tr_claim_dealer.id_program_md')
        ->join('tr_sales_order', 'tr_sales_order.id_sales_order =tr_claim_dealer.id_sales_order')
        ->join('tr_spk ', 'tr_spk.no_spk = tr_sales_order.no_spk')
        ->join('tr_sales_program_tipe', 'tr_sales_program_tipe.id_program_md = tr_claim_dealer.id_program_md and tr_sales_program_tipe.id_tipe_kendaraan = tr_spk.id_tipe_kendaraan')
        ->join('ms_group_dealer_detail ', 'ms_dealer.id_dealer = ms_group_dealer_detail.id_dealer')
        ->join('ms_group_dealer ', 'ms_group_dealer.id_group_dealer = ms_group_dealer_detail.id_group_dealer')
        ->join('tr_claim_sales_program ', 'tr_claim_sales_program.id_program_md = tr_claim_dealer.id_program_md and  tr_claim_sales_program.id_claim_sp = tr_claim_sales_program_detail.id_claim_sp ','left')
        // ->where('tr_claim_sales_program.status', 'close')
        // ->where('tr_claim_sales_program.status is NULL', NULL, FALSE)
        ->group_by('tr_claim_dealer.id_program_md','ms_dealer.id_dealer');
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




    
    function get_datatables_history()
    {
        $this->_get_datatables_query_history();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    
    function count_filtered_history()
    {
        $this->_get_datatables_query_history();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_history()
    {
        $this->_get_datatables_query_history();
        return $this->db->count_all_results();
    }

}

