<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_picking_list extends CI_Model
{
    var $table = "tr_picking_list";
    var $column_order = array('','no_picking_list','tgl_pl','no_do','nama_dealer'); //field yang ada di table user
    var $column_search = array('no_picking_list','tr_do_po.no_do','nama_dealer'); //field yang diizin untuk pencarian 
    var $order = array('tr_picking_list.no_picking_list' => 'desc'); // default order 

    function __construct()
    {

        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query(){
        $this->db->select('tr_picking_list.no_picking_list , tr_picking_list.no_do , tr_picking_list.tgl_pl , tr_picking_list.status , ms_dealer.id_dealer,ms_dealer.kode_dealer_md,ms_dealer.nama_dealer');
        $this->db->from('tr_picking_list');
        $this->db->join('tr_do_po', 'tr_picking_list.no_do = tr_do_po.no_do');
        $this->db->join('ms_dealer', 'ms_dealer.id_dealer = tr_do_po.id_dealer');

        $i = 0;
        // loop column
        foreach ($this->column_search as $item) {
            if($_POST['search']['value']) // if datatable send POST for search
            {

                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

	    // $this->db->where('send_dealer','1');		

        if(isset($_POST['order'])) // here order processing
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
        $this->db->select('select tr_picking_list.no_picking_list');
        $this->db->from('tr_picking_list');
        $this->db->join('tr_do_po', 'tr_picking_list.no_do = tr_do_po.no_do');
        $this->db->join('ms_dealer', 'ms_dealer.id_dealer = tr_do_po.id_dealer');
        return $this->db->count_all_results();
    }
}
