<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_stok_d extends CI_Model {

    var $table = 'stok_dealer';
    var $column_order = array(null,'id_item'); //set column field database for datatable orderable
    var $column_search = array('id_item'); //set column field database for datatable searchable
    var $order = array('id_item','id_dealer' => 'asc'); // default order

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {

        $this->db->from($this->table);
        // $id_dealer = $this->m_admin->cari_dealer();
        // $this->db->query("SELECT DISTINCT(tr_scan_barcode.id_item),COUNT(tr_penerimaan_unit_dealer_detail.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
        //         INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer
        //         WHERE tr_penerimaan_unit_dealer.id_dealer = '$id_dealer'
        //         GROUP BY tr_scan_barcode.id_item
        //         ORDER BY tr_scan_barcode.id_item");

        $i = 0;

        foreach ($this->column_search as $item) // loop column
        {
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
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

}
