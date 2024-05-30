<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H1_sipb_rec extends CI_Model {
    var $table = 'tr_sipb'; //nama tabel dari database
    var $column_order = array(
        'no_sipb',
        'tgl_sipb',
        'tgl_spes',
        'id_tipe_kendaraan',
        'id_warna',
        'jumlah',
        'q_flag',
        'no_po_md',
        'dealer_qq',
        'nama_dealer',
        'provinsi'
    ); //field yang ada di table user
    var $column_search =array(
        'no_sipb',
        'tgl_sipb',
        'tgl_spes',
        'id_tipe_kendaraan',
        'id_warna',
        'jumlah',
        'harga',
        'disc',
        'q_flag',
        'no_po_md',
        'dealer_qq',
        'amount',
        'ppn',
        'pph',
        'nama_dealer',
        'provinsi'
    ); //field yang diizin untuk pencarian 

    var $order = array('tgl_sipb' => 'desc'); // default order 

    function __construct(){
    
        parent::__construct();
        $this->load->database();
    
    }

    private function get_sipb()
    {
        $this->db->from($this->table);
        $search = $this->input->post('search');
        $i = 0;

        foreach ($this->column_search as $item) // looping awal
        {
            if ($search['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {

                if ($i === 0) // looping awal
                {
                    $this->db->group_start();
                    $this->db->like($item, $search['value']);
                } else {
                    $this->db->or_like($item, $search['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    function get_datatables()
    {
        $le = $this->input->post('length');
        $st = $this->input->post('start');
        $this->get_sipb();
        if ($le != -1)
            $this->db->limit($le, $st);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->get_sipb();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
}
 
