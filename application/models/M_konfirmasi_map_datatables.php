<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_konfirmasi_map_datatables extends CI_Model
{

    var $table = "tr_pengajuan_bbn_detail";
    var $column_order = array('','tr_pengajuan_bbn_detail.tgl_mohon_samsat','tr_pengajuan_bbn_detail.id_generate','tr_kirim_biro.no_tanda_terima');
    var $column_search = array('tr_pengajuan_bbn_detail.tgl_mohon_samsat','tr_pengajuan_bbn_detail.id_generate','tr_kirim_biro.no_tanda_terima');
    var $order = array('tr_pengajuan_bbn_detail.tgl_mohon_samsat' => 'desc'); 

   
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

     function _get_datatables_query()
    {   
     
        $this->db->select('tr_pengajuan_bbn_detail.tgl_mohon_samsat, tr_pengajuan_bbn_detail.id_generate ,no_tanda_terima , tgl_terima , count(no_mesin) as jumlah');
        $this->db->from('tr_pengajuan_bbn_detail');
        $this->db->join('tr_kirim_biro', 'tr_pengajuan_bbn_detail.id_generate = tr_kirim_biro.id_generate');
        $this->db->where('tr_pengajuan_bbn_detail.id_generate IS NOT NULL');
        $this->db->where("no_tanda_terima != ''");
        $this->db->group_by('tgl_mohon_samsat');
        $this->db->group_by('tr_pengajuan_bbn_detail.id_generate');
        $this->db->group_by('no_tanda_terima');
        $this->db->group_by('tgl_terima');
        $this->db->order_by('tgl_mohon_samsat', 'desc');
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
        $this->db->select('tr_pengajuan_bbn_detail.tgl_mohon_samsat, tr_pengajuan_bbn_detail.id_generate ,no_tanda_terima ');
        $this->db->from('tr_pengajuan_bbn_detail');
        $this->db->join('tr_kirim_biro', 'tr_pengajuan_bbn_detail.id_generate = tr_kirim_biro.id_generate');
        $this->db->where('tr_pengajuan_bbn_detail.id_generate IS NOT NULL');
        $this->db->where("no_tanda_terima != ''");
        $this->db->group_by('tgl_mohon_samsat');
        $this->db->group_by('tr_pengajuan_bbn_detail.id_generate');
        $this->db->group_by('no_tanda_terima');
        $this->db->group_by('tgl_terima');
        return $this->db->count_all_results();
    }


}

