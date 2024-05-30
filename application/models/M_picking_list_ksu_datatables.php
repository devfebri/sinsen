<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_picking_list_ksu_datatables extends CI_Model
{
    var $table = "tr_surat_jalan_ksu_pl";
    var $column_order = array('','tr_surat_jalan_ksu_pl.no_pl_ksu','tr_surat_jalan_ksu_pl.tgl_pl_ksu','tr_picking_list.no_picking_list','tr_picking_list.tgl_pl','tr_picking_list.no_do','ms_dealer.nama_dealer'); //field yang ada di table user
    var $column_search =  array('tr_surat_jalan_ksu_pl.no_pl_ksu','tr_surat_jalan_ksu_pl.tgl_pl_ksu','tr_picking_list.no_picking_list','tr_picking_list.tgl_pl','tr_picking_list.no_do','ms_dealer.nama_dealer'); //field yang diizin untuk pencarian 
    var $order = array('tr_surat_jalan_ksu_pl.created_at' => 'desc'); 

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {   
        $this->db->select('tr_surat_jalan_ksu_pl.no_pl_ksu,tr_surat_jalan_ksu_pl.tgl_pl_ksu,tr_picking_list.no_picking_list,tr_picking_list.tgl_pl,tr_picking_list.no_do,ms_dealer.nama_dealer');
        $this->db->from('tr_surat_jalan_ksu_pl');
        $this->db->join('tr_picking_list', 'tr_surat_jalan_ksu_pl.no_do = tr_picking_list.no_do');
        $this->db->join('tr_do_po', 'tr_picking_list.no_do = tr_do_po.no_do');
        $this->db->join('ms_dealer', 'tr_do_po.id_dealer = ms_dealer.id_dealer');

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
        $this->db->select('tr_surat_jalan_ksu_pl.no_pl_ksu,tr_surat_jalan_ksu_pl.tgl_pl_ksu,tr_picking_list.no_picking_list,tr_picking_list.tgl_pl,tr_picking_list.no_do,ms_dealer.nama_dealer');
        $this->db->from('tr_surat_jalan_ksu_pl');
        $this->db->join('tr_picking_list', 'tr_surat_jalan_ksu_pl.no_do = tr_picking_list.no_do');
        $this->db->join('tr_do_po', 'tr_picking_list.no_do = tr_do_po.no_do');
        $this->db->join('ms_dealer', 'tr_do_po.id_dealer = ms_dealer.id_dealer');
        return $this->db->count_all_results();

    }

}

